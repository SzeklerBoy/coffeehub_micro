<script setup>
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

defineProps({
    orders: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <table class="w-full whitespace-no-wrap">
        <thead>
            <tr
                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-2"
            >
                <th class="px-4 py-3">
                    {{ t("reportsPage.table.order") }}
                </th>
                <th class="px-4 py-3">
                    {{ t("reportsPage.table.time") }}
                </th>
                <th class="px-4 py-3">
                    {{ t("reportsPage.table.status") }}
                </th>
                <th class="px-4 py-4">
                    {{ t("reportsPage.table.desk_group") }}
                </th>
                <th class="px-4 py-3">
                    {{ t("reportsPage.table.description") }}
                </th>
            </tr>
        </thead>
        <tbody
            class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-1"
        >
            <template v-for="order in orders.data" :key="order.id">
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-sm font-bold">
                        <div v-text="order.id"></div>
                    </td>
                    <td class="px-4 py-3">
                        <div
                            v-text="
                                new Date(order.ordered_at).toLocaleDateString(
                                    locale,
                                    {
                                        year: 'numeric',
                                        month: '2-digit',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                    },
                                )
                            "
                        ></div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div v-text="order.status"></div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div v-text="order.desk_id"></div>
                        <div v-text="order.group_id"></div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div v-text="order.description"></div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>
