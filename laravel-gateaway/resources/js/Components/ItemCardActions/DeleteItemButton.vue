<script setup>
import DeleteItemIcon from "@/Components/Icons/DeleteItemIcon.vue";

defineProps({
    paidQuantity: {
        type: Number,
        required: true,
    },
    status: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <button
        v-if="status === 'ordered' && paidQuantity === 0"
        class="w-10 text-red-600 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1 dark:text-red-400"
    >
        <DeleteItemIcon />
    </button>
</template>
