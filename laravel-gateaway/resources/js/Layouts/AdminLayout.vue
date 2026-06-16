<script setup>
import AppLogo from "@/Components/AppLogo.vue";
import { Link } from "@inertiajs/vue3";
import AdminListItem from "@/Components/AdminListItem.vue";
import { useI18n } from "vue-i18n";
import NavOrdersIcon from "@/Components/Icons/NavIcons/NavOrdersIcon.vue";
import NavReportsIcon from "@/Components/Icons/NavIcons/NavReportsIcon.vue";
import NavProfileIcon from "@/Components/Icons/NavIcons/NavProfileIcon.vue";
import NavMenuIcon from "@/Components/Icons/NavIcons/NavMenuIcon.vue";
import NavDesksIcon from "@/Components/Icons/NavIcons/NavDesksIcon.vue";
import NavAdminHomeIcon from "@/Components/Icons/NavIcons/NavAdminHomeIcon.vue";
import Header from "@/Components/Header.vue";
import NavTranslationsIcon from "@/Components/Icons/NavIcons/NavTranslationsIcon.vue";
import { onMounted, watch } from "vue";
import axios from "axios";

const { t, locale } = useI18n();

defineProps({
    title: String,
    showBackButton: Boolean,
    backUrl: String,
});

// Watch for locale changes and update the server
watch(locale, (newLocal) => {
    // eslint-disable-next-line no-undef
    axios.post(route("api.setLocale"), { locale: newLocal });
});

onMounted(() => {
    // Set the locale on the server when the component is mounted
    // eslint-disable-next-line no-undef
    axios.post(route("api.setLocale"), { locale: locale.value });
});
</script>

<template>
    <div class="flex h-dvh bg-coffee-light-1 dark:bg-coffee-dark-1">
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-coffee-darker md:block flex-shrink-0 drop-shadow"
        >
            <div class="pb-4 text-gray-500 dark:text-gray-400">
                <div class="h-16 shrink-0 flex items-center justify-center">
                    <Link :href="route('dashboard')">
                        <AppLogo class="inline-block h-12 w-auto" />
                        <span class="text-xs align-bottom uppercase">
                            Admin
                        </span>
                    </Link>
                </div>
                <ul class="mt-6">
                    <AdminListItem
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        <template v-slot:icon>
                            <NavAdminHomeIcon />
                        </template>
                        <p class="dark:text-white">
                            {{ t("navBar.dashboard") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('desks.index')"
                        :active="route().current('desks*')"
                    >
                        <template v-slot:icon>
                            <NavDesksIcon />
                        </template>
                        <p class="dark:text-white">
                            {{ t("navBar.desks") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('menu.index')"
                        :active="route().current('menu*')"
                    >
                        <template v-slot:icon>
                            <NavMenuIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.menu") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('translations.index')"
                        :active="route().current('translations*')"
                    >
                        <template v-slot:icon>
                            <NavTranslationsIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.translation") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('profile.index')"
                        :active="route().current('profile*')"
                    >
                        <template v-slot:icon>
                            <NavProfileIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.profile") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('reports.index')"
                        :active="route().current('reports*')"
                    >
                        <template v-slot:icon>
                            <NavReportsIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.reports") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('orders.index')"
                        :active="route().current('orders*')"
                    >
                        <template v-slot:icon>
                            <NavOrdersIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.orders") }}
                        </p>
                    </AdminListItem>
                </ul>
            </div>
        </aside>

        <!-- Header -->
        <div class="flex flex-col flex-1 w-full">
            <Header />
            <main class="overflow-y-auto">
                <div class="container p-4 mx-auto grid md:p-6 2xl:p-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
