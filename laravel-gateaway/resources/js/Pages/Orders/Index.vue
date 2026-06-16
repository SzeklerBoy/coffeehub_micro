<script setup>
import ItemCard from "@/Components/ItemCard.vue";
import OrderIcon from "@/Components/Icons/Orders/OrderIcon.vue";
import DetailsIcon from "@/Components/Icons/Orders/DetailsIcon.vue";
import DescriptionList from "@/Components/DescriptionList.vue";
import OrderStatus from "@/Components/Orders/OrderStatus.vue";
import StaffLayout from "@/Layouts/StaffLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Notification from "@/Components/Notification.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps({
    orders: {
        type: Object,
        required: true,
    },
});

const isAdmin = computed(() => !usePage().props.auth.user.role);
</script>

<template>
    <component :is="isAdmin ? AdminLayout : StaffLayout">
        <Notification />
        <div class="flex gap-3 pb-4 flex-wrap justify-between">
            <div class="flex space-x-3">
                <!--                      TODO: add a back button when necessary-->
                <h3
                    class="text-lg content-center font-semibold text-gray-600 dark:text-gray-300"
                >
                    {{ t("ordersPage.title") }}
                </h3>
                <!--      TODO: add filtering and searching here-->
            </div>
        </div>

        <div id="items" class="mx-auto grid">
            <ul
                class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6"
            >
                <ItemCard
                    v-for="order in orders.data"
                    :key="order.uuid"
                    :item="order"
                    :title="t('itemCard.order') + ' #' + order.id"
                >
                    <template #icon>
                        <OrderIcon />
                    </template>
                    <template #header-actions>
                        <a
                            :href="'/orders/' + order.uuid"
                            class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1"
                        >
                            <DetailsIcon />
                        </a>
                    </template>
                    <template #body>
                        <DescriptionList :title="t('itemCard.status') + ':'">
                            <template #text>
                                <OrderStatus :status="order.status" />
                            </template>
                        </DescriptionList>
                        <DescriptionList>
                            <template #title>
                                {{
                                    order.desk_id
                                        ? t("itemCard.desk")
                                        : t("itemCard.group")
                                }}:
                            </template>
                            <template #text>
                                {{
                                    order.desk_id
                                        ? order.desk_id
                                        : order.group_id
                                }}
                            </template>
                        </DescriptionList>
                        <DescriptionList
                            :title="t('itemCard.timeToPrep') + ':'"
                            :text="
                                order.totalPrepTime + ' ' + t('itemCard.min')
                            "
                        />
                        <DescriptionList
                            :title="t('itemCard.description') + ':'"
                            :text="order.description"
                        />
                    </template>
                </ItemCard>
            </ul>
        </div>
    </component>
</template>
