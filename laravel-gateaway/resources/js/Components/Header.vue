<script setup>
import NavThemeMenuIcon from "@/Components/Icons/NavIcons/NavThemeMenuIcon.vue";
import NavSideMenuIcon from "@/Components/Icons/NavIcons/NavSideMenuIcon.vue";
import NavLogoutIcon from "@/Components/Icons/NavIcons/NavLogoutIcon.vue";
import ToggleLocale from "@/Components/ToggleLocale.vue";
import { Link } from "@inertiajs/vue3";
import { vOnClickOutside } from "@vueuse/components";

import { useI18n } from "vue-i18n";

const { t } = useI18n();

import { ref } from "vue";
import { useDark, useToggle } from "@vueuse/core";
import { router } from "@inertiajs/vue3";
import AppLogo from "@/Components/AppLogo.vue";
import NavSettingsIcon from "@/Components/Icons/NavIcons/NavSettingsIcon.vue";
import NavAdminHomeIcon from "@/Components/Icons/NavIcons/NavAdminHomeIcon.vue";
import NavDesksIcon from "@/Components/Icons/NavIcons/NavDesksIcon.vue";
import NavMenuIcon from "@/Components/Icons/NavIcons/NavMenuIcon.vue";
import NavOrdersIcon from "@/Components/Icons/NavIcons/NavOrdersIcon.vue";
import ToggleTheme from "@/Components/ToggleTheme.vue";

const isSideMenuOpen = ref(false);
const isThemeMenuOpen = ref(false);
const isProfileMenuOpen = ref(false);
const isSettingsMenuOpen = ref(false);

const isDark = useDark();
const toggle = useToggle(isDark);
const handleLogout = () => {
    router.post("/logout");
};
</script>

<template>
    <header
        class="sticky top-0 z-10 py-4 bg-white shadow-md dark:bg-coffee-dark-3"
    >
        <div
            class="container flex items-center justify-between h-full px-6 mx-auto text-coffee dark:text-coffee-light-3"
        >
            <!-- Mobile Header elements -->
            <div class="flex justify-between w-full relative">
                <div class="relative">
                    <button
                        class="p-1 mr-5 rounded-md self-start md:hidden focus:outline-none focus:ring-coffee"
                        @click="isSideMenuOpen = !isSideMenuOpen"
                        aria-label="Menu"
                    >
                        <NavSideMenuIcon />
                    </button>

                    <!-- Side Menu (Opened below the button) -->
                    <div
                        v-if="isSideMenuOpen"
                        v-on-click-outside="() => (isSideMenuOpen = false)"
                        class="absolute left-0 top-full mt-2 bg-white dark:text-coffee-dark-1 shadow-lg rounded-md p-3 w-48 z-50"
                    >
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <NavAdminHomeIcon class="w-5 mr-3" responsive />
                                <Link
                                    :href="route('dashboard')"
                                    class="block p-2 rounded-md hover:bg-coffee-light-3"
                                >
                                    {{ t("navBar.dashboard") }}
                                </Link>
                            </li>
                            <li class="flex items-center">
                                <NavDesksIcon class="w-5 mr-3" responsive />
                                <Link
                                    :href="route('desks.index')"
                                    class="block p-2 rounded-md hover:bg-coffee-light-3"
                                >
                                    {{ t("navBar.desks") }}
                                </Link>
                            </li>
                            <li class="flex items-center">
                                <NavMenuIcon class="w-5 mr-3" responsive />
                                <Link
                                    :href="route('menu.index')"
                                    class="block p-2 rounded-md hover:bg-coffee-light-3"
                                >
                                    {{ t("navBar.menu") }}
                                </Link>
                            </li>
                            <li class="flex items-center">
                                <NavOrdersIcon class="w-5 mr-3" responsive />
                                <Link
                                    :href="route('orders.index')"
                                    class="block p-2 rounded-md hover:bg-coffee-light-3"
                                >
                                    {{ t("navBar.orders") }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Center Logo -->
                <Link :href="route('dashboard')">
                    <AppLogo
                        class="h-8 md:hidden absolute left-1/2 transform -translate-x-1/2"
                    />
                </Link>

                <!-- Settings Button -->
                <div class="relative">
                    <button
                        class="md:hidden"
                        @click="isSettingsMenuOpen = !isSettingsMenuOpen"
                        aria-label="Settings"
                    >
                        <NavSettingsIcon />
                    </button>

                    <!-- Settings Dropdown -->
                    <div
                        v-if="isSettingsMenuOpen"
                        v-on-click-outside="() => (isSettingsMenuOpen = false)"
                        class="absolute right-0 top-full mt-2 bg-white shadow-lg rounded-md p-3 w-48 z-50"
                    >
                        <ul>
                            <li class="flex items-center">
                                <ToggleTheme />
                            </li>
                            <li class="mt-2 flex items-center">
                                <ToggleLocale />
                            </li>
                            <li class="flex items-center">
                                <button
                                    class="hover:bg-gray-100 dark:hover:bg-gray-800 p-2 rounded-md"
                                    @click="handleLogout"
                                >
                                    <div
                                        class="flex items-center h-10 dark:text-coffee-dark-3"
                                    >
                                        <NavLogoutIcon />
                                        <span class="ml-2">
                                            {{ t("navBar.logout") }}
                                        </span>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <ul
                class="hidden md:flex items-center ml-auto flex-shrink-0 space-x-6"
            >
                <li class="relative">
                    <ToggleLocale />
                </li>
                <li class="relative">
                    <button
                        class="relative align-middle rounded-md focus:outline-none focus:ring-coffee"
                        @click="isThemeMenuOpen = !isThemeMenuOpen"
                    >
                        <NavThemeMenuIcon />
                    </button>
                    <div
                        v-if="isThemeMenuOpen"
                        v-on-click-outside="() => (isThemeMenuOpen = false)"
                    >
                        <ul
                            class="absolute -right-12 md:right-0 top-8 w-40 p-2 space-y-1 text-gray-800 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-coffee-dark-0"
                        >
                            <button
                                @click="toggle()"
                                class="w-full p-2 rounded bg-coffee-dark-2 dark:bg-coffee-light-3 text-coffee-light-1 dark:text-coffee-dark-1"
                            >
                                {{
                                    isDark
                                        ? t("util.lightMode")
                                        : t("util.darkMode")
                                }}
                            </button>
                        </ul>
                    </div>
                </li>
                <li class="relative">
                    <button
                        class="align-middle rounded-full focus:ring-coffee focus:outline-none"
                        @click="isProfileMenuOpen = !isProfileMenuOpen"
                    >
                        <img
                            class="object-cover w-8 h-8 rounded-full"
                            src="/img/avatar.jpg"
                            alt=""
                            aria-hidden="true"
                        />
                    </button>
                    <div
                        v-if="isProfileMenuOpen"
                        v-on-click-outside="() => (isProfileMenuOpen = false)"
                    >
                        <ul
                            class="absolute -right-12 md:right-0 top-8 p-2 space-y-1 text-gray-800 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-coffee-dark-0"
                        >
                            <button
                                class="hover:bg-gray-100 dark:hover:bg-gray-800 p-2 rounded-md"
                                @click="handleLogout"
                            >
                                <div class="flex items-center h-10">
                                    <NavLogoutIcon />
                                    <span class="ml-2">
                                        {{ t("navBar.logout") }}
                                    </span>
                                </div>
                            </button>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </header>
</template>
