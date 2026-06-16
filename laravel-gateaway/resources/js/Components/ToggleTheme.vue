<script setup>
import { useDark, useToggle } from "@vueuse/core";
import { useI18n } from "vue-i18n";
const isDark = useDark();
const toggle = useToggle(isDark);
const { t } = useI18n();
</script>

<template>
    <button
        @click="toggle()"
        class="p-2 rounded bg-coffee-dark-2 dark:bg-coffee-light-3 text-coffee-light-1 dark:text-coffee-dark-1"
    >
        {{ isDark ? t("util.lightMode") : t("util.darkMode") }}
    </button>
</template>
