<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import StaffLayout from "@/Layouts/StaffLayout.vue";
import ShowAdmin from "@/Components/Desks/ShowAdmin.vue";
import ShowStaff from "@/Components/Desks/ShowStaff.vue";

defineProps({
    desksProp: Object,
    rooms: Array,
});

const isAdmin = computed(() => !usePage().props.auth.user.role);
</script>

<template>
    <component :is="isAdmin ? AdminLayout : StaffLayout">
        <component :is="isAdmin ? ShowAdmin : ShowStaff" :rooms="rooms" />
    </component>
</template>
