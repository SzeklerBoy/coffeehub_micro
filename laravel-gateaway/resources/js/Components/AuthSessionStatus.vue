<script setup>
defineProps({
    status: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="font-medium text-sm text-green-600">
        {{ status }}
    </div>
</template>
