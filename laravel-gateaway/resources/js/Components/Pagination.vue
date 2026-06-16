<!--
A robust pagination component paired with functions from
Utils/pagination.js.
The component doesn't execute any logic on its own.
It just emits the needed activity, so the parent component has total
control on what to do with it.
This way it can be used in any context, with any kind of data.
The next and prev props are only needed to determine if the buttons
are visible or not. Other than that, whether you reload the page or only
a part of it is up to the parent component.
-->

<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";

defineEmits(["loadNext", "loadPrev"]);

defineProps({
    next: {
        type: String,
        required: false,
    },
    prev: {
        type: String,
        required: false,
    },
});

const { t } = useI18n();
</script>

<template>
    <div
        class="min-h-12 py-1 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3"
    >
        <AdminButton v-if="prev" class="float-left" @click="$emit('loadPrev')">
            {{ t("util.previousPage") }}
        </AdminButton>
        <AdminButton v-if="next" class="float-right" @click="$emit('loadNext')">
            {{ t("util.nextPage") }}
        </AdminButton>
    </div>
</template>
