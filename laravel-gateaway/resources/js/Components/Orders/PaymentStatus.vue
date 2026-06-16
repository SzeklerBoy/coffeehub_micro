<script setup>
import { computed } from "vue";

const props = defineProps({
    paid: {
        type: Boolean,
        required: true,
    },
});
const text = computed(() => {
    return props.paid ? "Paid" : "Not paid";
});
</script>

<template>
    <span
        class="px-2 py-1 ml-2 font-semibold leading-tight bg-green-100 rounded-full"
        :class="{
            'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100': !paid,
            'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100':
                paid,
        }"
    >
        {{ text }}
    </span>
</template>
