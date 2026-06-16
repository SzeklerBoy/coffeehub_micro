<script setup>
import StaffLayout from "@/Layouts/StaffLayout.vue";
import { usePage, WhenVisible } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Notification from "@/Components/Notification.vue";
import { useI18n } from "vue-i18n";
import RevenueIcon from "@/Components/Icons/Reports/RevenueIcon.vue";
import Card from "@/Components/Reports/Card.vue";
import OrdersIcon from "@/Components/Icons/Reports/OrdersIcon.vue";
import ItemsIcon from "@/Components/Icons/Reports/ItemsIcon.vue";
import StaffIcon from "@/Components/Icons/Reports/StaffIcon.vue";
import AdminButton from "@/Components/AdminButton.vue";
import { router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import OrdersTable from "@/Components/Reports/OrdersTable.vue";
import { loadCursor } from "@/Utils/pagination.js";
import OpenCloseButton from "@/Components/Reports/OpenCloseButton.vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const { t } = useI18n();

const props = defineProps({
    ordersNumber: {
        type: Number,
        required: true,
    },
    totalRevenue: {
        type: Number,
        required: true,
    },
    totalMenuItems: {
        type: Number,
        required: true,
    },
    staffNumber: {
        type: Number,
        required: true,
    },
    allOrders: {
        type: Object,
        required: false,
    },
    mostSoldItems: {
        type: Object,
        required: false,
    },
});

const isAdmin = computed(() => !usePage().props.auth.user.role);
const showOrders = ref(false);
const showMSi = ref(false);
const mostSoldItemsChart = ref(null);

watch(mostSoldItemsChart, (newValue) => {
    // when the canvas appears on the screen, populate the chart
    if (newValue) {
        populateChart();
    }
});

function reloadOrders() {
    router.reload({
        only: ["allOrders"],
    });
}

function reloadMsi() {
    router.reload({
        only: ["mostSoldItems"],
    });
}

function populateChart() {
    reloadMsi();
    const obj = props.mostSoldItems ? Object.values(props.mostSoldItems) : [];
    const labels = obj.map((item) => item.name);
    const quantities = obj.map((item) => item.quantity);

    const ctx = mostSoldItemsChart.value.getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: t("reportsPage.canvasLabel"),
                    data: quantities,
                    backgroundColor: "#fffefb",
                    borderColor: "#6a450b",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            indexAxis: "x",
        },
    });
    mostSoldItemsChart.value.scrollIntoView({ behavior: "smooth" });
}
</script>

<template>
    <component :is="isAdmin ? AdminLayout : StaffLayout">
        <Notification />
        <div class="flex gap-3 pb-4 flex-wrap justify-between">
            <div class="flex space-x-3">
                <h3
                    class="text-lg content-center font-semibold text-gray-600 dark:text-gray-300"
                >
                    {{ t("reportsPage.title") }}
                </h3>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                <Card
                    :title="t('reportsPage.totalRevenue')"
                    class="text-green-500 bg-green-100 dark:text-green-100 dark:bg-green-500"
                >
                    <template #icon>
                        <RevenueIcon />
                    </template>
                    <template #body>
                        {{ totalRevenue }} {{ t("itemCard.lei") }}</template
                    >
                </Card>
                <Card
                    :title="t('reportsPage.totalOrders')"
                    class="text-blue-500 bg-blue-100 dark:text-blue-100 dark:bg-blue-500"
                >
                    <template #icon>
                        <OrdersIcon />
                    </template>
                    <template #body>
                        {{ ordersNumber }}
                    </template>
                </Card>
                <Card
                    :title="t('reportsPage.totalItems')"
                    class="text-coffee bg-coffee-light-3 dark:text-coffee dark:bg-coffee-light-3"
                >
                    <template #icon>
                        <ItemsIcon />
                    </template>
                    <template #body>
                        {{ totalMenuItems }}
                    </template>
                </Card>
                <Card
                    :title="t('reportsPage.staffMembers')"
                    class="text-orange-500 bg-orange-100 dark:text-orange-100 dark:bg-orange-500"
                >
                    <template #icon>
                        <StaffIcon />
                    </template>
                    <template #body>
                        {{ staffNumber }}
                    </template>
                </Card>
            </div>
            <div
                class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-coffee-dark-3 dark:text-gray-200"
            >
                <div
                    class="px-4 py-5 sm:px-6 flex justify-between items-center"
                >
                    <h1 class="text-xl text-gray-600 font-bold">
                        {{ t("reportsPage.allTimeOrders") }}
                    </h1>
                    <OpenCloseButton
                        v-model:visible="showOrders"
                        @opening="reloadOrders"
                    />
                </div>
                <div v-show="showOrders" class="px-4 sm:px-6">
                    <WhenVisible data="allOrders">
                        <template #fallback>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                {{ t("reportsPage.loading") }}...
                            </p>
                        </template>
                        <div
                            v-show="allOrders.data.length > 0"
                            class="rounded-lg overflow-hidden border dark:border-coffee-dark-2"
                        >
                            <OrdersTable :orders="allOrders" />
                            <Pagination
                                :next="allOrders.next_cursor"
                                :prev="allOrders.prev_cursor"
                                @loadNext="
                                    loadCursor(
                                        ['allOrders'],
                                        allOrders.next_cursor,
                                    )
                                "
                                @loadPrev="
                                    loadCursor(
                                        ['allOrders'],
                                        allOrders.prev_cursor,
                                    )
                                "
                            />
                        </div>
                        <!--                        TODO: fix export-->
                        <AdminButton
                            class="my-4 float-right"
                            :href="route('reports.export')"
                        >
                            {{ t("reportsPage.exportCSV") }}
                        </AdminButton>
                    </WhenVisible>
                </div>
            </div>
            <div
                class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-coffee-dark-3 dark:text-gray-200"
            >
                <div
                    class="px-4 py-5 sm:px-6 flex justify-between items-center"
                >
                    <h1 class="text-xl text-gray-600 font-bold">
                        {{ t("reportsPage.mostPopularItems") }}
                    </h1>
                    <OpenCloseButton
                        v-model:visible="showMSi"
                        @opening="reloadMsi"
                    />
                </div>
                <div v-show="showMSi" class="px-4 sm:px-6">
                    <WhenVisible data="mostSoldItems">
                        <template #fallback>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                {{ t("reportsPage.loading") }}...
                            </p>
                        </template>
                        <div class="px-4 py-5 sm:px-6">
                            <canvas ref="mostSoldItemsChart" />
                            <div>
                                <div
                                    v-for="item in mostSoldItems"
                                    :key="item.name"
                                    class="flex justify-between"
                                >
                                    <p v-text="item.name" />
                                    <p v-text="item.quantity" />
                                </div>
                            </div>
                        </div>
                    </WhenVisible>
                </div>
            </div>
        </div>
    </component>
</template>
