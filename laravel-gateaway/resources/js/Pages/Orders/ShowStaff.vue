<script setup>
import StaffLayout from "@/Layouts/StaffLayout.vue";
import { computed, ref, watch } from "vue";
import Notification from "@/Components/Notification.vue";
import PaymentStatus from "@/Components/Orders/PaymentStatus.vue";
import OrderStatus from "@/Components/Orders/OrderStatus.vue";
import DescriptionList from "@/Components/DescriptionList.vue";
import AdminButton from "@/Components/AdminButton.vue";
import ItemCard from "@/Components/ItemCard.vue";
import CustomImage from "@/Components/CustomImage.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Modal from "@/Components/Modal.vue";
import DeleteItemButton from "@/Components/ItemCardActions/DeleteItemButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { useI18n } from "vue-i18n";
import axios from "axios";

const { t, locale } = useI18n();

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    willBeCompletedAt: {
        type: String,
        required: true,
    },
});
const menuItems = ref([...props.order.menu_items]);
const isPaid = computed(() => {
    return !menuItems.value.some(
        (item) => item.pivot.quantity > item.pivot.paid,
    );
});
const totalPrice = computed(() => {
    return menuItems.value.reduce((total, item) => {
        return total + item.price * item.pivot.quantity;
    }, 0);
});
const formUpdateOrder = useForm({
    status: props.order.status,
});
const formPaySelection = useForm({
    items: [],
});
const formDeletion = useForm({});

menuItems.value.forEach((item) => {
    formPaySelection.items.push({
        menu_item_id: item.id,
        quantity: 0,
    });
});

const statusSelect = ref(null);
const enableStatusChange = ref(true);
const enablePaySelection = ref(true);
const showDeleteOrderModal = ref(false);
const showDeleteItemModal = ref(false);
const idOfItemToDelete = ref(null); // to store the id of the item to delete

function formatDate(dateInput) {
    // if the date is today return the time else return the date
    const date = new Date(dateInput);
    const today = new Date();
    if (
        date.getFullYear() === today.getFullYear() &&
        date.getMonth() === today.getMonth() &&
        date.getDay() === today.getDay()
    ) {
        return date.toLocaleTimeString(locale.value, {
            hour: "2-digit",
            minute: "2-digit",
        });
    }
    return date.toLocaleDateString(locale.value, {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });
}

function changeStatus() {
    const selectedValue = statusSelect.value.value;
    if (selectedValue === props.order.status) {
        return;
    }
    enableStatusChange.value = false;
    formUpdateOrder.status = selectedValue;
    /* eslint-disable no-undef */
    formUpdateOrder.put(route("orders.update", props.order.uuid), {
        onFinish: () => {
            enableStatusChange.value = true;
        },
    });
    /* eslint-enable no-undef */
}

function paySelection() {
    enablePaySelection.value = false;
    /* eslint-disable no-undef */
    formPaySelection.post(route("orders.checkout", props.order.uuid), {
        onFinish: () => {
            enablePaySelection.value = true;
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
}

function deleteOrder() {
    showDeleteOrderModal.value = false;
    /* eslint-disable no-undef */
    formDeletion.delete(route("orders.destroy", props.order.uuid));
    /* eslint-enable no-undef */
}

async function deleteItem() {
    showDeleteItemModal.value = false;
    /* eslint-disable no-undef */
    formDeletion.delete(
        route("orders.menuitem.destroy", [
            props.order.uuid,
            idOfItemToDelete.value,
        ]),
    );
    /* eslint-enable no-undef */
}

const isAdmin = computed(() => !usePage().props.auth.user.role);

watch(locale, () => {
    reFetchMenuItem();
});

async function reFetchMenuItem() {
    const response = await axios.get(
        // eslint-disable-next-line no-undef
        route("api.orders.menuItems", props.order.uuid),
    );
    menuItems.value = response.data;
    console.log(menuItems.value);
}
</script>

<template>
    <component :is="isAdmin ? AdminLayout : StaffLayout">
        <Notification />
        <div class="flex gap-3 pb-4 flex-wrap justify-between">
            <div class="flex space-x-3">
                <!--TODO: add a back button when necessary-->
                <h3
                    class="text-lg content-center font-semibold text-gray-600 dark:text-gray-300"
                >
                    {{ t("itemCard.order") }} #{{ order.id }}
                </h3>
            </div>
            <div class="flex space-x-3 md:space-x-5 items-center">
                <AdminButton
                    class="ml-4"
                    @click.prevent="showDeleteOrderModal = true"
                >
                    {{ t("ordersPage.showStaff.deleteOrder") }}
                </AdminButton>
            </div>
        </div>
        <div class="flex flex-col">
            <div
                class="overflow-hidden bg-white border border-gray-300 rounded-lg dark:border-coffee-dark-3 dark:bg-coffee-dark-3"
            >
                <div
                    class="px-4 py-5 sm:px-6 text-lg font-medium leading-6 text-gray-900 dark:text-gray-200"
                >
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <template v-if="order.desk_id">
                                <span class="font-bold">
                                    {{ t("itemCard.desk") }}:
                                </span>
                                <span class="ml-2"> {{ order.desk_id }} </span>
                            </template>
                            <template v-else>
                                <span class="font-bold">
                                    {{ t("itemCard.group") }}:
                                </span>
                                <span class="ml-2"> {{ order.group_id }} </span>
                            </template>
                        </div>
                        <div>
                            <span class="font-bold">
                                {{ t("itemCard.description") }}:
                            </span>
                            <span class="ml-2"> {{ order.description }} </span>
                        </div>
                        <div>
                            <span class="font-bold">
                                {{ t("ordersPage.showStaff.orderedAt") }}:
                            </span>
                            <span class="ml-2">
                                {{ formatDate(order.created_at) }}
                            </span>
                        </div>
                        <div>
                            <span class="font-bold"
                                >{{ t("itemCard.totalPrice") }}:</span
                            >
                            <span class="ml-2"
                                >{{ totalPrice }} {{ t("itemCard.lei") }}</span
                            >
                        </div>
                        <template
                            v-if="['ordered', 'pending'].includes(order.status)"
                        >
                            <div>
                                <span class="font-bold"
                                    >{{ t("itemCard.timeToPrep") }}:</span
                                >
                                <span class="ml-2"
                                    >{{ order.totalPrepTime }}
                                    {{ t("itemCard.min") }}</span
                                >
                            </div>
                            <div>
                                <span class="font-bold"
                                    >{{ t("itemCard.willServe") }}:</span
                                >
                                <span class="ml-2">
                                    {{ formatDate(willBeCompletedAt) }}
                                </span>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4 mb-4 text-base">
                        <OrderStatus :status="order.status" />
                        <PaymentStatus :paid="isPaid" />
                    </div>
                    <div class="mt-4 mb-4">
                        <select
                            class="h-10 font-semibold text-gray-700 bg-gray-200 border-0 rounded-md dark:hover:bg-coffee-dark-0 dark:bg-coffee-dark-1 dark:text-gray-200 focus:outline-none focus:ring-coffee dark:focus:ring-coffee-lighter"
                            name="status"
                            ref="statusSelect"
                        >
                            <option
                                value="pending"
                                :selected="order.status === 'pending'"
                            >
                                {{ t("orderStatus.pending") }}
                            </option>
                            <option
                                value="cancelled"
                                :selected="order.status === 'cancelled'"
                            >
                                {{ t("orderStatus.cancelled") }}
                            </option>

                            <option
                                value="served"
                                :selected="order.status === 'served'"
                            >
                                {{ t("orderStatus.served") }}
                            </option>
                            <option
                                value="completed"
                                :selected="order.status === 'completed'"
                            >
                                {{ t("orderStatus.completed") }}
                            </option>
                        </select>
                        <AdminButton
                            class="ml-2"
                            @click.prevent="changeStatus()"
                            :disabled="!enableStatusChange"
                        >
                            {{ t("ordersPage.showStaff.updateStatus") }}
                        </AdminButton>
                    </div>
                </div>
            </div>
            <div class="py-5">
                <div
                    v-if="!isPaid"
                    class="float-right flex justify-end gap-3 mt-3"
                >
                    <AdminButton :href="route('orders.checkout.all', order)">
                        {{ t("ordersPage.showStaff.payAll") }}
                    </AdminButton>
                    <AdminButton @click="paySelection()">
                        {{ t("ordersPage.showStaff.paySelection") }}
                    </AdminButton>
                </div>
                <h3
                    class="my-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
                >
                    {{ t("ordersPage.showStaff.orderedItems") }}
                </h3>
                <ul
                    class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6"
                >
                    <ItemCard
                        v-for="(item, index) in menuItems"
                        :key="item.id"
                        :item="item"
                    >
                        <template #icon>
                            <CustomImage
                                :src="item.image_path"
                                class="w-10 h-10"
                            />
                        </template>
                        <template #header-actions>
                            <DeleteItemButton
                                :status="order.status"
                                :paid-quantity="item.pivot.paid"
                                @click="
                                    idOfItemToDelete = item.id;
                                    showDeleteItemModal = true;
                                "
                            />
                        </template>
                        <template #body>
                            <DescriptionList
                                :title="t('itemCard.pricePerUnit')"
                                :text="item.price + ' ' + t('itemCard.lei')"
                            />
                            <DescriptionList
                                title="Total price"
                                :text="
                                    item.price * item.pivot.quantity +
                                    ' ' +
                                    t('itemCard.lei')
                                "
                            />
                            <DescriptionList
                                :title="t('ordersPage.showStaff.paidPerAll')"
                            >
                                <template #text>
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight rounded-full"
                                        :class="
                                            item.pivot.paid <
                                            item.pivot.quantity
                                                ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100'
                                                : 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100'
                                        "
                                    >
                                        {{ item.pivot.paid }} /
                                        {{ item.pivot.quantity }}
                                    </span>
                                </template>
                            </DescriptionList>
                            <DescriptionList
                                :title="t('ordersPage.showStaff.pay')"
                            >
                                <template #text>
                                    <span
                                        v-if="
                                            item.pivot.paid >=
                                            item.pivot.quantity
                                        "
                                    >
                                        {{ t("ordersPage.showStaff.allPaid") }}
                                    </span>
                                    <span v-else>
                                        <input
                                            type="number"
                                            v-model="
                                                formPaySelection.items[index]
                                                    .quantity
                                            "
                                            min="0"
                                            :max="
                                                item.pivot.quantity -
                                                item.pivot.paid
                                            "
                                            class="w-16 text-center border-gray-300 rounded-md dark:bg-coffee-dark-2 dark:text-gray-200"
                                        />
                                    </span>
                                </template>
                            </DescriptionList>
                        </template>
                    </ItemCard>
                </ul>
            </div>
        </div>
    </component>
    <Modal v-model="showDeleteOrderModal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                {{ t("ordersPage.showStaff.deleteOrderModalText") }}
            </h2>

            <div class="mt-6 flex justify-end">
                <AdminButton
                    class="mr-2"
                    @click.prevent="showDeleteOrderModal = false"
                >
                    {{ t("modal.cancel") }}
                </AdminButton>
                <AdminButton
                    class="bg-red-500 hover:bg-red-700"
                    @click.prevent="deleteOrder()"
                >
                    {{ t("modal.delete") }}
                </AdminButton>
            </div>
        </div>
    </Modal>
    <Modal v-model="showDeleteItemModal" max-width="md">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                {{ t("ordersPage.showStaff.deleteItemModalText") }}
            </h2>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="showDeleteItemModal = false">
                    {{ t("modal.cancel") }}
                </SecondaryButton>
                <DangerButton class="ms-3" @click="deleteItem()">
                    {{ t("modal.delete") }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
