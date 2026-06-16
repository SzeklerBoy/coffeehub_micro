<script setup>
import { useI18n } from "vue-i18n";
import { watch } from "vue";

const { locale } = useI18n();

// Load the saved language or default to "en"
const savedLocale = localStorage.getItem("app_locale") || "en";
locale.value = savedLocale;

// Watch for changes and update localStorage
watch(locale, (newLocale) => {
    localStorage.setItem("app_locale", newLocale);
});

// Listen for changes from other tabs
window.addEventListener("storage", (event) => {
    if (event.key === "app_locale") {
        locale.value = event.newValue;
    }
});
</script>

<template>
    <select
        v-model="locale"
        class="text-black dark:text-white dark:bg-coffee-dark-2 rounded"
    >
        <option value="en">English</option>
        <option value="hu">Magyar</option>
        <option value="ro">Română</option>
    </select>
</template>
