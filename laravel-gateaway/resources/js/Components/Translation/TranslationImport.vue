<script setup>
import { ref } from "vue";
import AdminButton from "@/Components/AdminButton.vue";
import OpenModalIcon from "@/Components/Icons/OpenModalIcon.vue";
import CloseModalIcon from "@/Components/Icons/CloseModalIcon.vue";
import { useI18n } from "vue-i18n";
import axios from "axios";

const { t } = useI18n();

const isImportClosed = ref(true);
const imageFile = ref(null);

const handleFileUpload = (e) => {
    imageFile.value = e.target.files[0];
};

const showSuccessMessage = ref(false);
const showErrorMessage = ref(false);

const submitImport = async () => {
    if (!imageFile.value) {
        return alert(t("translationPage.import.noFile"));
    }

    const formData = new FormData();
    formData.append("file", imageFile.value);

    try {
        // eslint-disable-next-line no-undef
        const res = await axios.post(route("translations.import"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        if (res.status === 200) {
            showSuccessMessage.value = true;
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 3000);
        }
    } catch (err) {
        console.error("Import error:", err.response?.data || err.message);
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
                {{ t("translationPage.import.title") }}
            </h1>
            <AdminButton @click="isImportClosed = !isImportClosed">
                <component
                    :is="isImportClosed ? OpenModalIcon : CloseModalIcon"
                    class="w-5 h-5"
                />
            </AdminButton>
        </div>
        <div v-if="!isImportClosed" class="px-4 sm:px-6">
            <p class="mt-1">{{ t("translationPage.import.description") }}</p>
            <label class="block mb-4 mt-4 font-medium">
                {{ t("translationPage.import.selectFile") }}:
            </label>
            <input
                type="file"
                accept=".xliff,.xliff2"
                class="input-select"
                @change="handleFileUpload"
            />
            <AdminButton class="mb-4 float-end" @click="submitImport">
                {{ t("translationPage.import.import") }}
            </AdminButton>
        </div>

        <div
            v-if="showSuccessMessage"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50"
        >
            {{ t("translationPage.import.importSuccess") }}
        </div>

        <div
            v-if="showErrorMessage"
            class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50"
        >
            {{ t("translationPage.import.importError") }}
        </div>
    </div>
</template>
