<script setup>
import { useI18n } from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import AdminButton from "@/Components/AdminButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    isOpen: Boolean,
    isUpdate: {
        type: Boolean,
        default: false,
    },
    existingRoom: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["close", "roomCreated"]);

const { t } = useI18n();

const form = useForm({
    width: props.existingRoom.width ?? "",
    length: props.existingRoom.length ?? "",
});

const submit = () => {
    const width = parseFloat(form.width);
    const length = parseFloat(form.length);

    if (isNaN(width) || isNaN(length) || width <= 0 || length <= 0) {
        alert(t("desksPage.roomModal.positiveValidationError"));
        return;
    }

    const method = props.isUpdate ? "put" : "post";
    /* eslint-disable no-undef */
    const routeName = props.isUpdate
        ? route("api.rooms.update", { id: props.existingRoom.id })
        : route("api.rooms.store");
    /* eslint-enable no-undef */

    form[method](routeName, {
        onSuccess: () => {
            emit("close");
            emit("roomCreated", {
                width: parseFloat(form.width),
                length: parseFloat(form.length),
            });
            form.reset();
        },
    });
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
                {{ t("desksPage.roomModal.askDimension") }}:
            </h3>

            <div class="flex items-center w-full mb-3 gap-2">
                <InputLabel
                    for="width"
                    :value="t('desksPage.roomModal.width')"
                    class="w-1/3"
                />
                <TextInput
                    id="width"
                    v-model="form.width"
                    class="flex-1 text-black"
                    type="number"
                    min="1"
                    step="0.01"
                />
            </div>

            <div class="flex items-center w-full mb-3 gap-2">
                <InputLabel
                    for="breadth"
                    :value="t('desksPage.roomModal.length')"
                    class="w-1/3"
                />
                <TextInput
                    id="breadth"
                    v-model="form.length"
                    class="flex-1 text-black"
                    type="number"
                    min="1"
                    step="0.01"
                />
            </div>

            <div class="flex justify-center">
                <AdminButton @click="submit" class="mt-3 px-4 py-2">
                    {{ t("desksPage.save") }}
                </AdminButton>
                <DangerButton
                    @click="emit('close')"
                    class="mt-3 px-4 py-2 ml-10"
                >
                    {{ t("desksPage.clearModal.cancel") }}
                </DangerButton>
            </div>
        </div>
    </div>
</template>
