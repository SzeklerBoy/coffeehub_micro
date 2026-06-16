<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";
import ShowStaff from "@/Components/Desks/ShowStaff.vue";

const { t } = useI18n();

defineProps({
    rooms: Array,
});
</script>

<template>
    <AdminButton :href="route('desks.create')" class="w-40 ml-auto mb-4">
        {{ t("desksPage.newConfig") }}
    </AdminButton>
    <ShowStaff :rooms="rooms" />
</template>
