<script setup>
const props = defineProps({
    maxWidth: {
        type: String,
        default: "2xl",
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
});
const maxWidth = {
    sm: "sm:max-w-sm",
    md: "sm:max-w-md",
    lg: "sm:max-w-lg",
    xl: "sm:max-w-xl",
    "2xl": "sm:max-w-2xl",
}[props.maxWidth];
</script>
<template>
    <div
        v-show="modelValue"
        class="fixed inset-0 top-40 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    >
        <div
            class="fixed inset-0 transform transition-all"
            @click="$emit('update:modelValue', false)"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
            class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto dark:bg-coffee-dark-3 dark:text-white"
            :class="maxWidth"
        >
            <slot />
        </div>
    </div>
</template>
