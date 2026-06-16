<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: false,
    },
});

const componentTag = computed(() => (props.href ? Link : "button"));

const baseClass =
    "inline-flex items-center h-min px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-coffee tracking-widest shadow-sm dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:text-white transition ease-in-out duration-150 hover:bg-coffee-light-3 dark:hover:bg-coffee-dark-3";
</script>

<template>
    <component :is="componentTag" :href="href" :class="baseClass">
        <slot />
    </component>
</template>
