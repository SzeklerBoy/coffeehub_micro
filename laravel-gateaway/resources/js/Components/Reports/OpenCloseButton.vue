<script setup>
import CloseUpIcon from "@/Components/Icons/Reports/CloseUpIcon.vue";
import OpenDownIcon from "@/Components/Icons/Reports/OpenDownIcon.vue";
import AdminButton from "@/Components/AdminButton.vue";

const visible = defineModel("visible", {
    type: Boolean,
    default: false,
});

defineEmits(["opening"]);
</script>

<template>
    <AdminButton
        @click="
            () => {
                !visible ? $emit('opening') : '';
                visible = !visible;
            }
        "
    >
        <component :is="visible ? CloseUpIcon : OpenDownIcon" />
    </AdminButton>
</template>
