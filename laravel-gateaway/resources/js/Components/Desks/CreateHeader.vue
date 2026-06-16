<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps({
    room: {
        type: Object,
        required: false,
    },
});
</script>

<template>
    <div class="flex items-center gap-x-4 mb-4" v-if="room">
        <h1 class="text-black dark:text-white text-xl">
            {{ t("desksPage.currentSize") }}: {{ room.width }} x
            {{ room.length }} {{ t("desksPage.meter") }}
        </h1>
        <AdminButton @click="$emit('changeSize')">
            {{ t("desksPage.roomModal.changeSize") }}
        </AdminButton>
    </div>
</template>
