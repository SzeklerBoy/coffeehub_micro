<!-- src/Components/Desks/DeskSidebarControls.vue -->
<script setup>
import { useI18n } from "vue-i18n";
import NavDesksIcon from "@/Components/Icons/NavIcons/NavDesksIcon.vue";
import ClearIcon from "@/Components/Icons/ClearIcon.vue";
import AdminButton from "@/Components/AdminButton.vue";

const { t } = useI18n();
</script>

<template>
    <div
        class="ml-3 mt-5 border-2 border-black dark:bg-coffee-darker w-full rounded-lg p-2"
    >
        <ul class="flex flex-col items-center">
            <button
                @click="$emit('add-desk')"
                class="flex justify-evenly items-center w-full hover:bg-coffee-light-3 dark:hover:bg-coffee-dark-2 rounded-lg p-2"
            >
                <NavDesksIcon class="h-16 w-10 text-black dark:text-white" />
                <span class="text-black dark:text-white">
                    {{ t("desksPage.addDesk") }}
                </span>
            </button>
            <button
                @click="$emit('clear-canvas')"
                class="flex justify-evenly items-center w-full hover:bg-coffee-light-3 dark:hover:bg-coffee-dark-2 rounded-lg p-2"
            >
                <ClearIcon class="h-16 w-10 text-black dark:text-white" />
                <span class="text-black dark:text-white">
                    {{ t("desksPage.clearCanvas") }}
                </span>
            </button>
            <AdminButton class="mt-2" @click="$emit('save')">
                {{ t("desksPage.save") }}
            </AdminButton>
        </ul>
    </div>
</template>
