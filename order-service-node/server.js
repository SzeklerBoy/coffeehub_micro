const express = require('express');
const { Sequelize, DataTypes } = require('sequelize');
const cors = require('cors');
const { Op } = require('sequelize');

const sequelize = new Sequelize('cafe_orders_db', 'order_user', 'order_password', {
    host: process.env.DB_HOST || '127.0.0.1',
    port: process.env.DB_PORT || 3309,
    dialect: 'mysql',
    logging: false, 
});

const Order = sequelize.define('Order', {
    uuid: {
        type: DataTypes.UUID,
        defaultValue: DataTypes.UUIDV4,
    },
    desk_id: { type: DataTypes.BIGINT, allowNull: true },
    group_id: { type: DataTypes.BIGINT, allowNull: true },
    ordered_at: {
        type: DataTypes.DATE,
        allowNull: false,
        defaultValue: Sequelize.NOW
    },
    completed_at: { type: DataTypes.DATE, allowNull: true },
    waiter_id: { type: DataTypes.BIGINT, allowNull: true },
    description: { type: DataTypes.TEXT, allowNull: true },
    status: {
        type: DataTypes.ENUM('ordered', 'pending', 'served', 'completed', 'cancelled'),
        allowNull: false,
        defaultValue: 'ordered'
    },
    totalPrepTime: {
        type: DataTypes.DOUBLE,
        allowNull: false,
        defaultValue: 0
    }
}, {
    tableName: 'orders',
    timestamps: true,
});

const OrderItem = sequelize.define('OrderItem', {
    menu_item_id: { type: DataTypes.BIGINT, allowNull: false },
    unit_price: {
        type: DataTypes.DOUBLE,
        allowNull: false,
        defaultValue: 0,
    },
    prep_time: {
        type: DataTypes.DOUBLE,
        allowNull: false,
        defaultValue: 0,
    },
    quantity: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 1
    },
    paid: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 0
    }
}, {
    tableName: 'order_items',
    timestamps: true,
});

Order.hasMany(OrderItem, { foreignKey: 'order_id', as: 'items', onDelete: 'CASCADE' });
OrderItem.belongsTo(Order, { foreignKey: 'order_id' });

const app = express();
app.use(cors());
app.use(express.json());

function includeItems() {
    return [{ model: OrderItem, as: 'items' }];
}

function buildPagination(result, page, limit, basePath) {
    const total = result.count;
    const lastPage = Math.max(Math.ceil(total / limit), 1);

    return {
        data: result.rows,
        current_page: page,
        per_page: limit,
        total,
        last_page: lastPage,
        next_page_url: page < lastPage ? `${basePath}?page=${page + 1}&limit=${limit}` : null,
        prev_page_url: page > 1 ? `${basePath}?page=${page - 1}&limit=${limit}` : null,
    };
}

app.get('/api/orders', async (req, res) => {
    try {
        const page = Math.max(parseInt(req.query.page || '1', 10), 1);
        const limit = Math.max(parseInt(req.query.limit || '10', 10), 1);
        const offset = (page - 1) * limit;
        const where = {};

        if (req.query.search) {
            where.description = { [Op.like]: `%${req.query.search}%` };
        }

        if (req.query.status) {
            where.status = req.query.status;
        }

        if (req.query.desk_id) {
            where.desk_id = parseInt(req.query.desk_id, 10);
        }

        if (req.query.group_id) {
            where.group_id = parseInt(req.query.group_id, 10);
        }

        const sortField = req.query.sort_field || 'id';
        const sortDirection = req.query.sort_direction || 'ASC';

        const orders = await Order.findAndCountAll({
            where,
            include: includeItems(),
            order: [[sortField, sortDirection]],
            limit,
            offset,
            distinct: true,
        });

        res.json(buildPagination(orders, page, limit, '/api/orders'));
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.get('/api/orders/all', async (req, res) => {
    try {
        const orders = await Order.findAll({
            include: includeItems(),
            order: [['ordered_at', 'DESC']],
        });

        res.json({ data: orders });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.get('/api/orders/:uuid', async (req, res) => {
    try {
        const order = await Order.findOne({
            where: { uuid: req.params.uuid },
            include: includeItems(),
        });

        if (!order) {
            return res.status(404).json({ error: 'Order not found' });
        }

        res.json(order);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.get('/api/orders/:uuid/eta', async (req, res) => {
    try {
        const order = await Order.findOne({
            where: { uuid: req.params.uuid },
        });

        if (!order) {
            return res.status(404).json({ error: 'Order not found' });
        }

        const waiters = Math.max(parseInt(req.query.waiters || '1', 10), 1);
        const minutesToComplete = await Order.sum('totalPrepTime', {
            where: {
                status: 'ordered',
                ordered_at: {
                    [Op.lte]: order.ordered_at,
                },
            },
        });

        const eta = new Date(order.ordered_at);
        eta.setMinutes(eta.getMinutes() + minutesToComplete / waiters);

        res.json({ willBeCompletedAt: eta.toISOString() });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/api/orders', async (req, res) => {
    const t = await sequelize.transaction();
    
    try {
        const payload = req.body;

        const newOrder = await Order.create({
            uuid: payload.uuid || undefined,
            desk_id: payload.desk_id,
            group_id: payload.group_id,
            waiter_id: payload.waiter_id,
            description: payload.description,
            totalPrepTime: payload.totalPrepTime || 0,
            status: payload.status || 'ordered',
        }, { transaction: t });

        if (payload.items && payload.items.length > 0) {
            const itemsToCreate = payload.items.map(item => ({
                order_id: newOrder.id,
                menu_item_id: item.menu_item_id,
                unit_price: item.unit_price || 0,
                prep_time: item.prep_time || 0,
                quantity: item.quantity,
                paid: 0
            }));

            await OrderItem.bulkCreate(itemsToCreate, { transaction: t });
        }

        await t.commit();

        const createdOrder = await Order.findByPk(newOrder.id, {
            include: includeItems()
        });
        res.status(201).json(createdOrder);

    } catch (error) {
        await t.rollback();
        res.status(400).json({ error: error.message });
    }
});

app.put('/api/orders/:uuid/status', async (req, res) => {
    try {
        const order = await Order.findOne({ where: { uuid: req.params.uuid }, include: includeItems() });

        if (!order) {
            return res.status(404).json({ error: 'Order not found' });
        }

        order.status = req.body.status;
        if (req.body.status === 'completed' || req.body.status === 'cancelled') {
            order.completed_at = new Date();
        }

        await order.save();

        res.json(order);
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

app.delete('/api/orders/:uuid', async (req, res) => {
    try {
        const order = await Order.findOne({ where: { uuid: req.params.uuid }, include: includeItems() });

        if (!order) {
            return res.status(404).json({ error: 'Order not found' });
        }

        await order.destroy();
        res.json({ deleted: true, order });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.delete('/api/orders/:uuid/items/:itemId', async (req, res) => {
    const t = await sequelize.transaction();

    try {
        const order = await Order.findOne({
            where: { uuid: req.params.uuid },
            include: includeItems(),
            transaction: t,
            lock: t.LOCK.UPDATE,
        });

        if (!order) {
            await t.rollback();
            return res.status(404).json({ error: 'Order not found' });
        }

        if (order.status !== 'ordered') {
            await t.rollback();
            return res.status(422).json({ error: 'Order preparation already started.' });
        }

        const item = await OrderItem.findOne({
            where: { order_id: order.id, menu_item_id: req.params.itemId },
            transaction: t,
            lock: t.LOCK.UPDATE,
        });

        if (!item) {
            await t.rollback();
            return res.status(404).json({ error: 'Order item not found' });
        }

        await item.destroy({ transaction: t });
        await Order.update({
            totalPrepTime: Math.max((order.totalPrepTime || 0) - ((item.prep_time || 0) * (item.quantity || 0)), 0),
        }, {
            where: { id: order.id },
            transaction: t,
        });

        const remainingItems = await OrderItem.count({
            where: { order_id: order.id },
            transaction: t,
        });

        if (remainingItems === 0) {
            await order.destroy({ transaction: t });
            await t.commit();
            return res.json({ deleted: true });
        }

        const refreshedOrder = await Order.findByPk(order.id, {
            include: includeItems(),
            transaction: t,
        });

        await t.commit();
        res.json({ deleted: false, order: refreshedOrder });
    } catch (error) {
        await t.rollback();
        res.status(500).json({ error: error.message });
    }
});

app.patch('/api/orders/:uuid/items/:itemId/paid', async (req, res) => {
    try {
        const order = await Order.findOne({ where: { uuid: req.params.uuid } });

        if (!order) {
            return res.status(404).json({ error: 'Order not found' });
        }

        const item = await OrderItem.findOne({
            where: { order_id: order.id, menu_item_id: req.params.itemId },
        });

        if (!item) {
            return res.status(404).json({ error: 'Order item not found' });
        }

        const quantity = Math.max(parseInt(req.body.quantity || '0', 10), 0);
        item.paid = Math.min(item.quantity, item.paid + quantity);
        await item.save();

        res.json({ updated: true, item });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.get('/api/reports/most-sold', async (req, res) => {
    try {
        const items = await OrderItem.findAll({
            attributes: [
                'menu_item_id',
                [sequelize.fn('SUM', sequelize.col('quantity')), 'quantity'],
            ],
            group: ['menu_item_id'],
            order: [[sequelize.literal('quantity'), 'DESC']],
            limit: 5,
            raw: true,
        });

        res.json(items);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

const PORT = 8003;

sequelize.sync({ alter: true }).then(() => {
    console.log("MySQL Database synced successfully.");
    app.listen(PORT, () => {
        console.log(`Order Service is running on http://127.0.0.1:${PORT}`);
    });
}).catch(err => {
    console.error("Failed to sync database:", err);
});