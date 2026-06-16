<script setup>
defineProps({
    title: {
        type: String,
        default: "",
    },
    text: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
        <div class="flex justify-between gap-x-4 py-3">
            <dt class="text-gray-500 dark:text-coffee-light-3">
                {{ title }}
                <slot name="title" />
            </dt>
            <dd class="flex items-start gap-x-2">
                <div
                    class="font-medium text-gray-900 text-right dark:text-coffee-light-3"
                >
                    {{ text }}
                    <slot name="text" />
                </div>
            </dd>
        </div>
    </dl>
</template>
