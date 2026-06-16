<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AdminButton from "@/Components/AdminButton.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";

const props = defineProps({
    user: Array,
});

const { t } = useI18n();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    role: props.user.role,
    is_active: Boolean(props.user.is_active),
});

const errors = ref({});

const submitForm = () => {
    //eslint-disable-next-line no-undef
    form.patch(route("profile.update-admin", props.user.id));
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            <h4 class="text-black dark:text-white text-2xl font-bold">
                {{ t("staffPage.editPage.title") }} : {{ form.name }}
            </h4>
            <AdminButton :href="route('profile.index')" class="mr-4">
                {{ t("util.back") }}
            </AdminButton>
        </div>
        <form
            @submit.prevent="submitForm"
            class="flex flex-col gap-3 px-5 py-3 bg-white text-black dark:text-white shadow rounded-lg dark:bg-coffee-dark-3"
        >
            <input type="hidden" name="user_id" :value="form.user_id" />

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
                autofocus
                autocomplete="name"
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
                autocomplete="username"
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
                autocomplete="phone"
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
                autocomplete="role"
                class="mt-1 block w-1/2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
            />
            <p v-if="errors.role" class="text-red-600 text-sm mt-2">
                {{ errors.role[0] }}
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
