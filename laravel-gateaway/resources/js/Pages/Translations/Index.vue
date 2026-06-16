<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TranslationExport from "@/Components/Translation/TranslationExport.vue";
//import TranslationTranslate from "@/Components/Translation/TranslationTranslate.vue";
import TranslationImport from "@/Components/Translation/TranslationImport.vue";
import { useI18n } from "vue-i18n";

defineProps({ languages: Array });

const { t } = useI18n();

const targetLanguageDict = [
    { code: "en", name: "English" },
    { code: "fr", name: "French" },
    { code: "hu", name: "Hungarian" },
    { code: "de", name: "German" },
    { code: "ro", name: "Romanian" },
];
</script>

<template>
    <AdminLayout>
        <h4 class="text-black dark:text-white text-2xl font-bold mb-4">
            {{ t("translationPage.title") }}
        </h4>

        <TranslationExport
            :languages="languages"
            :targetLanguageDict="targetLanguageDict"
        />
        <!--
        <TranslationTranslate
            :languages="languages"
            :targetLanguageDict="targetLanguageDict"
        />
        -->
        <TranslationImport />
    </AdminLayout>
</template>
