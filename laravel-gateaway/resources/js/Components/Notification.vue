<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
    <div class="my-3 2xl:my-4">
        <div
            v-if="page.props.flash?.error"
            class="text-sm rounded bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900 dark:text-red-300"
            role="alert"
        >
            <p class="font-bold">Error</p>
            <p>{{ page.props.flash.error }}</p>
        </div>
        <div
            v-if="page.props.flash?.success"
            class="text-sm rounded bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900 dark:text-green-300"
            role="alert"
        >
            <p class="font-bold">Success</p>
            <p>{{ page.props.flash.success }}</p>
        </div>
    </div>
</template>
