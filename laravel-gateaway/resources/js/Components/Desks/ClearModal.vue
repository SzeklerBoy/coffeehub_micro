<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";
import DangerButton from "@/Components/DangerButton.vue";

const { t } = useI18n();
defineProps(["isOpen"]);
defineEmits(["confirm", "close"]);
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-white dark:bg-coffee-darker p-6 rounded-lg shadow-lg w-96 text-center"
        >
            <h3 class="text-lg text-black dark:text-white font-bold mb-4">
                {{ t("desksPage.clearModal.title") }}
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                {{ t("desksPage.clearModal.description") }}
            </p>
            <div class="flex justify-center">
                <DangerButton
                    @click="$emit('confirm')"
                    class="bg-red-500 text-white px-4 py-2 rounded mr-2"
                >
                    {{ t("desksPage.clearModal.confirm") }}
                </DangerButton>
                <AdminButton @click="$emit('close')">
                    {{ t("desksPage.clearModal.cancel") }}
                </AdminButton>
            </div>
        </div>
    </div>
</template>
