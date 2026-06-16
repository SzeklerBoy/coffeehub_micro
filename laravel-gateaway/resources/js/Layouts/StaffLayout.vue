<!-- General wrapper component for staff regarding functionalities.
   Provide the nav bar for both desktop and responsive view -->

<script setup>
import { Link } from "@inertiajs/vue3";
import AppLogo from "@/Components/AppLogo.vue";
import { useI18n } from "vue-i18n";
import NavMenuIcon from "@/Components/Icons/NavIcons/NavMenuIcon.vue";
import NavAdminHomeIcon from "@/Components/Icons/NavIcons/NavAdminHomeIcon.vue";
import AdminListItem from "@/Components/AdminListItem.vue";
import NavDesksIcon from "@/Components/Icons/NavIcons/NavDesksIcon.vue";
import NavOrdersIcon from "@/Components/Icons/NavIcons/NavOrdersIcon.vue";
import Header from "@/Components/Header.vue";
import { onMounted, watch } from "vue";
import axios from "axios";

const { t, locale } = useI18n();

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
        <aside
            class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-coffee-darker md:block flex-shrink-0 drop-shadow"
        >
            <div class="pb-4 text-gray-500 dark:text-gray-400">
                <div class="h-16 shrink-0 flex items-center justify-center">
                    <Link :href="route('dashboard')">
                        <AppLogo class="inline-block h-12 w-auto" />
                        <span class="text-xs align-bottom uppercase">
                            Staff
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
                        :active="route().current('desks.index')"
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
                        :active="route().current('menu.index')"
                    >
                        <template v-slot:icon>
                            <NavMenuIcon />
                        </template>
                        <p class="dark:text-coffee-light-1">
                            {{ t("navBar.menu") }}
                        </p>
                    </AdminListItem>
                    <AdminListItem
                        :href="route('orders.index')"
                        :active="route().current('orders.index')"
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

        <div class="flex flex-col flex-1 w-full">
            <!-- Header -->
            <Header />
            <main class="overflow-y-auto">
                <div class="container p-4 mx-auto grid md:p-6 2xl:p-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
