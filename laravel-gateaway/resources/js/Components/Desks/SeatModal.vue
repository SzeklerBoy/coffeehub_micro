<script setup>
import { computed, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import TextInput from "@/Components/TextInput.vue";
import AdminButton from "@/Components/AdminButton.vue";

const props = defineProps(["isOpen", "desk"]);
const emit = defineEmits(["close", "updateSeats"]);

const { t } = useI18n();
const nrOfSeats = ref(props.desk?.nrOfSeats || 4); // Use desk data

watch(
    () => props.desk,
    (newDesk) => {
        if (newDesk) {
            nrOfSeats.value = newDesk.nrOfSeats;
        }
    },
    { immediate: true },
);

const nrOfSeatsString = computed({
    get: () => String(nrOfSeats.value), // Convert number to string
    set: (val) => (nrOfSeats.value = Number(val)), // Convert string back to number
});

const saveSeats = () => {
    emit("updateSeats", nrOfSeats.value);
    emit("close");
};
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
    >
        <div
            class="bg-white p-5 rounded-lg shadow-lg w-1/4 flex flex-col items-center"
        >
            <h3 class="text-lg text-black font-bold mb-3">
                {{ t("desksPage.nrOfSeats") }}:
            </h3>
            <TextInput v-model="nrOfSeatsString" class="mb-3 text-black" />
            <input
                type="range"
                min="1"
                max="10"
                v-model="nrOfSeats"
                class="w-1/2"
            />
            <AdminButton
                @click="saveSeats"
                class="mt-3 px-4 py-2 bg-blue-500 text-white rounded"
            >
                {{ t("desksPage.addSeats") }}
            </AdminButton>
        </div>
    </div>
</template>
