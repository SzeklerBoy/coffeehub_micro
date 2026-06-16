<script setup>
import { Link } from "@inertiajs/vue3";
import AppLogo from "@/Components/AppLogo.vue";
import NavBar from "@/Components/NavBar.vue";
import { onMounted, watch } from "vue";
import { useI18n } from "vue-i18n";
import axios from "axios";

const { locale } = useI18n();

// Watch for locale changes and update the server
watch(locale, (newLocal) => {
    // eslint-disable-next-line no-undef
    axios.post(route("api.setLocale"), { locale: newLocal });
});

const showNavBar = defineModel("showNavBar", {
    default: true,
});

onMounted(() => {
    // Set the locale on the server when the component is mounted
    // eslint-disable-next-line no-undef
    axios.post(route("api.setLocale"), { locale: locale.value });
});
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-coffee-light-2 dark:bg-coffee-dark-3"
    >
        <div class="flex flex-col items-center">
            <Link :href="route('home')">
                <AppLogo class="h-14" />
            </Link>
            <NavBar v-show="showNavBar" />
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:rounded-lg dark:bg-black"
        >
            <slot />
        </div>
    </div>
</template>
