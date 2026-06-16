<script setup>
import { useI18n } from "vue-i18n";
import { Link } from "@inertiajs/vue3";
import CursorPagination from "@/Components/CursorPagination.vue";
import CustomImage from "@/Components/CustomImage.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";

defineProps({
    menuItems: Object,
});

const { t } = useI18n();
</script>

<template>
    <div
        class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-coffee-dark-3"
    >
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="h-12 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
                    >
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.name") }}
                        </th>
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.category") }}
                        </th>
                        <th class="px-4 py-3">{{ t("menuPage.table.eta") }}</th>
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.description") }}
                        </th>
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.quantity") }}
                        </th>
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.price") }}
                        </th>
                        <th class="px-4 py-3">
                            {{ t("menuPage.table.actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2"
                >
                    <tr
                        v-for="item in menuItems"
                        :key="item.id"
                        class="text-gray-700 dark:text-gray-400"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div
                                    class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                >
                                    <CustomImage
                                        :src="item.image_path"
                                        :alt="item.name"
                                    />
                                    <div
                                        class="absolute inset-0 rounded-full shadow-inner"
                                        aria-hidden="true"
                                    ></div>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ item.name }}</p>
                                    <p
                                        class="text-xs text-gray-600 dark:text-gray-400"
                                    ></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ item.category }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ item.ETAinMinutes }} min
                        </td>
                        <td
                            class="px-4 h-full my-3 text-sm line-clamp-2 overflow-clip align-middle"
                        >
                            {{ item.description }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                :class="[
                                    item.quantity < 10
                                        ? 'bg-red-100 text-red-700'
                                        : item.quantity < 20
                                          ? 'bg-orange-100 text-orange-700'
                                          : 'bg-green-100 text-green-700',
                                    'px-2 py-1 font-semibold leading-tight rounded-full',
                                    item.quantity >= 20
                                        ? 'dark:bg-green-700 dark:text-green-100'
                                        : '',
                                ]"
                            >
                                {{ item.quantity }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ item.price }} lei</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <Link
                                    :href="route('menu.edit', item)"
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-coffee rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                    aria-label="Edit"
                                >
                                    <EditIcon />
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <CursorPagination :pagination="menuItems" />
    </div>
</template>
