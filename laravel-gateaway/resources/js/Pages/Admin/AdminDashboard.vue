<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import LogoutIcon from "@/Components/Icons/AdminDashboard/LogoutIcon.vue";
import SalesReportIcon from "@/Components/Icons/AdminDashboard/SalesReportIcon.vue";
import ProfileIcon from "@/Components/Icons/AdminDashboard/ProfileIcon.vue";
import OrdersIcon from "@/Components/Icons/AdminDashboard/OrdersIcon.vue";
import MenuIcon from "@/Components/Icons/AdminDashboard/MenuIcon.vue";
import DesksIcon from "@/Components/Icons/AdminDashboard/DesksIcon.vue";

const { t } = useI18n();

const handleLogout = () => {
    router.post("/logout");
};
</script>

<template>
    <AdminLayout>
        <div
            class="text-gray-700 grid gap-8 my-4 md:grid-cols-2 dark:text-gray-300"
        >
            <Link
                :href="route('desks.index')"
                class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <DesksIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3"
                    >
                        {{ t("adminDashboard.desks.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.desks.description") }}
                    </p>
                </div>
            </Link>
            <Link
                :href="route('menu.index')"
                class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <MenuIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3"
                    >
                        {{ t("adminDashboard.menu.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.menu.description") }}
                    </p>
                </div>
            </Link>
            <Link
                :href="route('orders.index')"
                class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <OrdersIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3"
                    >
                        {{ t("adminDashboard.orders.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.orders.description") }}
                    </p>
                </div>
            </Link>
            <Link
                :href="route('profile.index')"
                class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <ProfileIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3"
                    >
                        {{ t("adminDashboard.staff.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.staff.description") }}
                    </p>
                </div>
            </Link>
            <Link
                :href="route('reports.index')"
                class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <SalesReportIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3"
                    >
                        {{ t("adminDashboard.reports.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.reports.description") }}
                    </p>
                </div>
            </Link>
            <button
                class="flex items-center h-full min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
                @click="handleLogout"
            >
                <LogoutIcon />
                <div class="p-4">
                    <h4
                        class="mb-4 font-semibold text-coffee dark:text-coffee-light-3 text-left"
                    >
                        {{ t("adminDashboard.logOut.title") }}
                    </h4>
                    <p>
                        {{ t("adminDashboard.logOut.description") }}
                    </p>
                </div>
            </button>
        </div>
    </AdminLayout>
</template>
