<script setup>
defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <img
        :src="src"
        :alt="alt"
        class="object-cover w-full h-full"
        loading="lazy"
    />
</template>
