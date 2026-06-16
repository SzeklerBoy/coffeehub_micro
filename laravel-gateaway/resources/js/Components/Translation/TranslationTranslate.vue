<script setup>
import { ref } from "vue";
import AdminButton from "@/Components/AdminButton.vue";
import OpenModalIcon from "@/Components/Icons/OpenModalIcon.vue";
import CloseModalIcon from "@/Components/Icons/CloseModalIcon.vue";
import { useI18n } from "vue-i18n";

defineProps({
    languages: Array,
    targetLanguageDict: Array,
});

const { t } = useI18n();

const isTranslateClosed = ref(true);
const selectedLanguage = ref(null);
const targetLanguage = ref(null);

const sendToTranslate = () => {
    console.log(
        "Send to translate",
        selectedLanguage.value,
        targetLanguage.value,
    );
    //TODO: Add actual implementation here
};
</script>

<template>
    <div
        class="overflow-hidden mb-4 bg-white shadow sm:rounded-lg text-black dark:text-gray-200 dark:bg-coffee-dark-3"
    >
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h1 class="text-xl text-black dark:text-white">
                {{ t("translationPage.translation.title") }}
            </h1>
            <AdminButton @click="isTranslateClosed = !isTranslateClosed">
                <component
                    :is="isTranslateClosed ? OpenModalIcon : CloseModalIcon"
                    class="w-5 h-5"
                />
            </AdminButton>
        </div>
        <div v-if="!isTranslateClosed" class="px-4 sm:px-6">
            <p class="mt-1">
                {{ t("translationPage.translation.description") }}
            </p>
            <div class="mt-2">
                <label
                    class="block font-medium text-gray-700 dark:text-gray-300"
                >
                    {{ t("translationPage.translation.selectLanguage") }}:
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

                <AdminButton class="mb-4 float-end" @click="sendToTranslate">
                    {{ t("translationPage.translation.sendToTranslate") }}
                </AdminButton>
            </div>
        </div>
    </div>
</template>
