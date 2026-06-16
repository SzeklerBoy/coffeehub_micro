<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";
import AdminButton from "@/Components/AdminButton.vue";
import { ref } from "vue";
import axios from "axios";

const { t } = useI18n();

const props = defineProps({
    users: Array,
});

const showDeleteModal = ref(false);
const userToDelete = ref(null);
const openDeleteModal = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const usersData = ref(props.users);

const fetchUsers = () => {
    console.log(usersData.value);

    axios
        //eslint-disable-next-line no-undef
        .get(route("api.users"))
        .then((response) => {
            usersData.value = response.data;
            console.log(usersData.value);
        })
        .catch((error) => {
            console.error("Failed to fetch users:", error);
        });
};

const confirmDelete = () => {
    if (userToDelete.value) {
        axios
            //eslint-disable-next-line no-undef
            .delete(route("profile.destroy", userToDelete.value.id))
            .then(() => {
                fetchUsers();
            })
            .catch((error) => {
                console.error("Delete failed:", error.response?.data || error);
            });
    }
    closeDeleteModal();
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            <h4 class="text-black dark:text-white text-2xl font-bold">
                {{ t("staffPage.title") }}
            </h4>
            <AdminButton :href="route('profile.create')">
                {{ t("staffPage.create") }}
            </AdminButton>
            <div
                class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-coffee-dark-3"
            >
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="h-11 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
                            >
                                <th class="px-4 py-3">
                                    {{ t("staffPage.editPage.name") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.editPage.email") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.editPage.phone") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.editPage.role") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.status") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.createdAt") }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ t("staffPage.action") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2"
                        >
                            <tr
                                v-for="user in usersData"
                                :key="user.id"
                                class="text-gray-700 dark:text-gray-300"
                            >
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold">
                                                {{ user.name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ user.email }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ user.phone }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ user.role || "(admin)" }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <span
                                        :class="
                                            user.is_active
                                                ? 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100'
                                                : 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100'
                                        "
                                        class="px-2 py-1 font-semibold leading-tight rounded-full"
                                    >
                                        {{
                                            user.is_active
                                                ? t("staffPage.active")
                                                : t("staffPage.inactive")
                                        }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{
                                        new Date(
                                            user.created_at,
                                        ).toLocaleDateString()
                                    }}
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="flex items-center space-x-4 text-sm"
                                    >
                                        <AdminButton
                                            :href="
                                                route(
                                                    'profile.edit-admin',
                                                    user.id,
                                                )
                                            "
                                            >{{
                                                t("staffPage.edit")
                                            }}</AdminButton
                                        >
                                        <AdminButton
                                            :class="{
                                                'opacity-50 cursor-not-allowed':
                                                    !user.role,
                                            }"
                                            :disabled="!user.role"
                                            @click.prevent="
                                                openDeleteModal(user)
                                            "
                                        >
                                            {{ t("util.delete") }}
                                        </AdminButton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div
                class="bg-white dark:bg-coffee-dark-3 p-6 rounded-lg shadow-lg w-full max-w-md"
            >
                <h2
                    class="text-xl font-semibold mb-4 text-black dark:text-white"
                >
                    {{ t("staffPage.confirmDelete.title") }}
                </h2>
                <p class="mb-6 text-gray-700 dark:text-gray-300">
                    {{ t("staffPage.confirmDelete.message") }}
                    <strong>{{ userToDelete?.name }}</strong>
                </p>
                <div class="flex justify-end space-x-2">
                    <button
                        @click="closeDeleteModal"
                        class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white"
                    >
                        {{ t("util.cancel") }}
                    </button>
                    <button
                        @click="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                    >
                        {{ t("util.delete") }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
