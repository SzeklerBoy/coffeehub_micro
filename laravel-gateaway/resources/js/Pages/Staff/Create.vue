<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const form = useForm({
    name: "",
    email: "",
    phone: "",
    role: "",
    is_active: true,
    password: "",
    password_confirmation: "",
});

const errors = ref({});

const submitForm = () => {
    //eslint-disable-next-line no-undef
    form.post(route("profile.store"), {
        onError: (error) => {
            errors.value = error;
            console.log(error);
        },
        onSuccess: () => {
            form.reset();
            errors.value = {};
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            <h4 class="text-black dark:text-white text-2xl font-bold">
                {{ t("staffPage.create") }}
            </h4>
            <AdminButton :href="route('profile.index')" class="mr-4">
                {{ t("util.back") }}
            </AdminButton>
        </div>

        <form
            @submit.prevent="submitForm"
            class="flex flex-col gap-3 px-5 py-3 bg-white text-black dark:text-white shadow rounded-lg dark:bg-coffee-dark-3"
        >
            <label
                for="name"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.name") }}
            </label>
            <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.name" class="text-red-600 text-sm mt-2">
                {{ errors.name[0] }}
            </p>

            <label
                for="email"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.email") }}
            </label>
            <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.email" class="text-red-600 text-sm mt-2">
                {{ errors.email[0] }}
            </p>

            <label
                for="phone"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.phone") }}
            </label>
            <input
                id="phone"
                v-model="form.phone"
                type="text"
                required
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.phone" class="text-red-600 text-sm mt-2">
                {{ errors.phone[0] }}
            </p>

            <label
                for="role"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.role") }}
            </label>
            <input
                id="role"
                v-model="form.role"
                type="text"
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.role" class="text-red-600 text-sm mt-2">
                {{ errors.role[0] }}
            </p>
            <label
                for="password"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.password") }}
            </label>
            <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.password" class="text-red-600 text-sm mt-2">
                {{ errors.password }}
            </p>
            <label
                for="password_confirmation"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.confirmPassword") }}
            </label>
            <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p
                v-if="errors.password_confirmation"
                class="text-red-600 text-sm mt-2"
            >
                {{ errors.password_confirmation }}
            </p>

            <label
                for="is_active"
                class="block mt-4 text-sm font-medium dark:text-gray-300"
            >
                {{ t("staffPage.editPage.isActive") }}
            </label>
            <input
                id="is_active"
                v-model="form.is_active"
                type="checkbox"
                class="mt-1"
            />
            <p v-if="errors.is_active" class="text-red-600 text-sm mt-2">
                {{ errors.is_active[0] }}
            </p>

            <div class="flex items-center gap-4 mt-4">
                <AdminButton type="submit">{{ t("util.save") }}</AdminButton>
            </div>
        </form>
    </AdminLayout>
</template>
