<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
});
defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-coffee-dark-3 overflow-hidden shadow sm:rounded-lg"
    >
        <div class="p-3 mr-4 rounded-full" v-bind="$attrs">
            <slot name="icon" />
        </div>
        <div>
            <p
                class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
                {{ title }}
            </p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                <slot name="body" />
            </p>
        </div>
    </div>
</template>
