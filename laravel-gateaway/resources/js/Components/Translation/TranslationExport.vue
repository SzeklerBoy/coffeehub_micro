<script setup>
import { ref } from "vue";
import AdminButton from "@/Components/AdminButton.vue";
import OpenModalIcon from "@/Components/Icons/OpenModalIcon.vue";
import CloseModalIcon from "@/Components/Icons/CloseModalIcon.vue";
import { useI18n } from "vue-i18n";
import axios from "axios";

defineProps({
    languages: Array,
    targetLanguageDict: Array,
});

const { t } = useI18n();

const isExportClosed = ref(true);
const selectedLanguage = ref(null);
const targetLanguage = ref(null);
const showErrorMessage = ref(false);

const submitExport = async () => {
    try {
        const response = await axios.post(
            // eslint-disable-next-line no-undef
            route("translations.export"),
            {
                sourceLanguage: selectedLanguage.value,
                targetLanguage: targetLanguage.value,
            },
            {
                responseType: "blob",
            },
        );

        const blob = new Blob([response.data], {
            type: response.headers["content-type"],
        });
        const fileUrl = URL.createObjectURL(blob);
        const fileLink = document.createElement("a");

        fileLink.href = fileUrl;
        fileLink.setAttribute(
            "download",
            `translations_${selectedLanguage.value}.xliff`,
        );
        document.body.appendChild(fileLink);
        fileLink.click();
    } catch (error) {
        console.error(
            "Error exporting translations:",
            error.response?.data || error.message,
        );
        showErrorMessage.value = true;
        setTimeout(() => {
            showErrorMessage.value = false;
        }, 3000);
    }
};
</script>

<template>
    <div
        class="overflow-hidden mb-4 bg-white shadow sm:rounded-lg text-black dark:text-gray-200 dark:bg-coffee-dark-3"
    >
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h1 class="text-xl text-black dark:text-white">
                {{ t("translationPage.export.title") }}
            </h1>
            <AdminButton @click="isExportClosed = !isExportClosed">
                <component
                    :is="isExportClosed ? OpenModalIcon : CloseModalIcon"
                    class="w-5 h-5"
                />
            </AdminButton>
        </div>
        <div v-if="!isExportClosed" class="px-4 sm:px-6">
            <p class="mt-1">{{ t("translationPage.export.description") }}</p>
            <div class="mt-2">
                <label
                    class="block font-medium text-gray-700 dark:text-gray-300"
                >
                    {{ t("translationPage.export.selectLanguage") }}:
                </label>
                <select
                    v-model="selectedLanguage"
                    class="mt-1 mb-4 block w-1/5 rounded-md border-gray-300 shadow-sm focus:border-coffee-500 focus:ring-coffee-500 dark:bg-coffee-dark-3 dark:text-gray-200"
                >
                    <option
                        v-for="language in languages"
                        :key="language.code"
                        :value="language.code"
                    >
                        {{ language.name }}
                    </option>
                </select>

                <label
                    class="block font-medium text-gray-700 dark:text-gray-300"
                >
                    {{ t("translationPage.translation.targetLanguage") }}:
                </label>
                <select
                    v-model="targetLanguage"
                    class="mt-1 mb-4 block w-1/5 rounded-md border-gray-300 shadow-sm focus:border-coffee-500 focus:ring-coffee-500 dark:bg-coffee-dark-3 dark:text-gray-200"
                >
                    <option
                        v-for="language in targetLanguageDict"
                        :key="language.code"
                        :value="language.code"
                    >
                        {{ language.name }}
                    </option>
                </select>

                <AdminButton class="mb-4 float-end" @click="submitExport">
                    {{ t("translationPage.export.export") }}
                </AdminButton>
            </div>
        </div>

        <div
            v-if="showErrorMessage"
            class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50"
        >
            {{ t("translationPage.export.importError") }}
        </div>
    </div>
</template>
