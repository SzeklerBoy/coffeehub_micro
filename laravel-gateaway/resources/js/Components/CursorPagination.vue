<!-- CursorPagination.vue
props: pagination: Object
* This component is used to display pagination buttons for cursor-based pagination.
* The entity list is passed as a prop to the component.
* It uses the prev_page_url and next_page_url properties of the pagination object
to determine if the buttons should be displayed.
-->

<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";

defineProps({
    pagination: Object,
});

const { t } = useI18n();
</script>

<template>
    <div
        class="min-h-12 py-1 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3"
    >
        <AdminButton
            v-if="pagination.prev_page_url"
            class="float-left"
            :href="pagination.prev_page_url"
        >
            {{ t("util.previousPage") }}
        </AdminButton>
        <AdminButton
            v-if="pagination.next_page_url"
            class="float-right"
            :href="pagination.next_page_url"
        >
            {{ t("util.nextPage") }}
        </AdminButton>
    </div>
</template>
