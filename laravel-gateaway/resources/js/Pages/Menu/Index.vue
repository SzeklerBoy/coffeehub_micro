<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";
import SearchIcon from "@/Components/Icons/SearchIcon.vue";
import AdminButton from "@/Components/AdminButton.vue";
import { watch, onMounted, ref, computed } from "vue";
import AdminTable from "@/Components/Menu/AdminTable.vue";
import XIcon from "@/Components/Icons/XIcon.vue";
import { router, usePage } from "@inertiajs/vue3";
import StaffTable from "@/Components/Menu/StaffTable.vue";
import axios from "axios";

const { t, locale } = useI18n();
const search = ref("");
const menuItems = ref([]);

const clearSearch = () => {
    search.value = "";
    router.get(
        // eslint-disable-next-line no-undef
        route("menu.index"),
        {
            search: "",
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const submitSearch = () => {
    fetchMenuItems();
};

const fetchMenuItems = async () => {
    const response = await axios.get(`/api/menu`, {
        params: {
            locale: locale.value,
            search: search.value,
        },
    });
    menuItems.value = response.data;
};

onMounted(() => {
    fetchMenuItems();
});

watch(locale, () => {
    fetchMenuItems();
});

const isAdmin = computed(() => !usePage().props.auth.user.role);
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            <!-- Left: Title -->
            <h4 class="text-black dark:text-white text-2xl font-bold">
                {{ t("menuPage.title") }}
            </h4>

            <!-- Right: Search input and buttons -->
            <div class="flex items-center gap-2">
                <div class="relative focus-within:text-coffee-500">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-2 dark:text-gray-300"
                    >
                        <SearchIcon class="w-4 h-4" />
                    </div>
                    <input
                        v-model="search"
                        @keyup.enter="submitSearch"
                        class="h-10 pl-8 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md focus:ring-coffee focus:shadow-outline-coffee"
                        type="search"
                        :placeholder="t('menuPage.searchItem')"
                        aria-label="Search"
                    />
                </div>

                <AdminButton @click="submitSearch" class="h-10">
                    {{ t("menuPage.search") }}
                </AdminButton>

                <template v-if="search">
                    <AdminButton
                        @click="clearSearch"
                        class="hidden sm:block h-10"
                    >
                        {{ t("util.clear") }}
                    </AdminButton>
                    <AdminButton
                        @click="clearSearch"
                        class="sm:hidden !px-2 h-10 flex items-center justify-center"
                    >
                        <XIcon class="w-4 h-4" />
                    </AdminButton>
                </template>

                <!-- Add button for admin role (userRole is null) -->
                <template v-if="isAdmin">
                    <AdminButton
                        :href="route('menu.create', { locale: locale })"
                    >
                        {{ t("menuPage.addItem") }}
                    </AdminButton>
                </template>
            </div>
        </div>
        <!-- Table -->
        <template v-if="menuItems.length > 0 && isAdmin">
            <AdminTable class="mt-4" :menu-items="menuItems" />
        </template>
        <template v-else-if="menuItems.length > 0 && !isAdmin">
            <StaffTable class="mt-4" :menu-items="menuItems" />
        </template>
        <template v-else>
            <div class="mt-4 text-center">
                <p class="text-black dark:text-white text-xl">
                    {{ t("menuPage.noItems") }}
                </p>
            </div>
        </template>

        <!-- CSV functions -->
        <div class="flex justify-end mt-4" v-if="isAdmin">
            <AdminButton :href="route('menu.export')">
                {{ t("menuPage.exportCSV") }}
            </AdminButton>
            <AdminButton class="ml-4" :href="route('menu.import')">
                {{ t("menuPage.importCSV") }}
            </AdminButton>
        </div>
    </AdminLayout>
</template>
