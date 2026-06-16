<script setup>
import Notification from "@/Components/Notification.vue";
import { computed, ref, watch } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PaymentStatus from "@/Components/Orders/PaymentStatus.vue";
import OrderStatus from "@/Components/Orders/OrderStatus.vue";
import AdminButton from "@/Components/AdminButton.vue";
import ItemCard from "@/Components/ItemCard.vue";
import CustomImage from "@/Components/CustomImage.vue";
import DeleteItemButton from "@/Components/ItemCardActions/DeleteItemButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { useForm } from "@inertiajs/vue3";
import DescriptionList from "@/Components/DescriptionList.vue";
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

const showDeleteModal = ref(false);
const idOfItemToDelete = ref(null);

const form = useForm({});

async function deleteItem() {
    showDeleteModal.value = false;
    /* eslint-disable no-undef */
    form.delete(
        route("orders.menuitem.destroy", [
            props.order.uuid,
            idOfItemToDelete.value,
        ]),
    );
    /* eslint-enable no-undef */
}

watch(locale, () => {
    reFetchMenuItem();
});

async function reFetchMenuItem() {
    const response = await axios.get(
        // eslint-disable-next-line no-undef
        route("api.orders.menuItems", props.order.uuid),
    );
    menuItems.value = response.data;
}

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
</script>

<template>
    <GuestLayout>
        <Notification />
        <div
            class="overflow-hidden bg-white border rounded-lg border-gray-300 dark:bg-coffee-dark-3"
        >
            <div class="px-4 lg:px-6">
                <div class="flex justify-between items-center">
                    <h2
                        class="my-4 text-xl font-semibold text-gray-600 dark:text-gray-300"
                    >
                        {{ t("ordersPage.showGuest.title") }}:
                    </h2>
                </div>
                <h3
                    class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200"
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
                                    {{ t("itemCard.table") }}:
                                </span>
                                <span class="ml-2"> {{ order.table_id }} </span>
                            </template>
                        </div>
                        <div>
                            <span class="font-bold"
                                >{{ t("itemCard.totalPrice") }}:</span
                            >
                            <span class="ml-2"
                                >{{ totalPrice }} {{ t("itemCard.lei") }}</span
                            >
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
                                >{{ t("itemCard.willServe") }}:</span
                            >
                            <span class="ml-2">
                                {{ formatDate(willBeCompletedAt) }}
                            </span>
                        </div>
                    </div>
                </h3>
                <div class="my-4">
                    <OrderStatus :status="order.status" />
                    <PaymentStatus :paid="isPaid" />
                </div>
                <div class="flex gap-x-3 float-right p-4 mb-3">
                    <!--                    TODO: translation for status????-->
                    <AdminButton
                        v-if="
                            isPaid ||
                            ['served', 'completed'].includes(order.status)
                        "
                        :href="route('orders.create')"
                    >
                        {{ t("ordersPage.showGuest.backToMenu") }}
                    </AdminButton>
                    <AdminButton
                        v-if="!isPaid"
                        :href="route('orders.checkout.all', order)"
                    >
                        {{ t("ordersPage.showGuest.payOrder") }}
                    </AdminButton>
                </div>
            </div>
        </div>
        <div class="py-5">
            <h3
                class="my-2 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
                {{ t("ordersPage.showGuest.orderedItems") }}:
            </h3>
            <ul
                class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6"
            >
                <ItemCard v-for="item in menuItems" :key="item.id" :item="item">
                    <template #icon>
                        <CustomImage
                            :src="item.image_path || ''"
                            :alt="item.name"
                        />
                    </template>
                    <template #header-actions>
                        <DeleteItemButton
                            :status="order.status"
                            :paid-quantity="item.pivot.paid"
                            @click="
                                idOfItemToDelete = item.id;
                                showDeleteModal = true;
                            "
                        />
                    </template>
                    <template #body>
                        <DescriptionList
                            :title="t('itemCard.pricePerItem') + ':'"
                            :text="item.price + ' ' + t('itemCard.lei')"
                        />
                        <DescriptionList
                            :title="t('itemCard.quantity') + ':'"
                            :text="item.pivot.quantity"
                        />
                        <DescriptionList
                            :title="t('itemCard.totalPrice') + ':'"
                            :text="
                                item.price * item.pivot.quantity +
                                ' ' +
                                t('itemCard.lei')
                            "
                        />
                    </template>
                </ItemCard>
            </ul>
        </div>
    </GuestLayout>
    <Modal v-model="showDeleteModal" max-width="md">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                {{ t("ordersPage.showGuest.deleteModalText") }}
            </h2>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="showDeleteModal = false">
                    {{ t("modal.cancel") }}
                </SecondaryButton>
                <DangerButton class="ms-3" @click="deleteItem()">
                    {{ t("modal.delete") }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
