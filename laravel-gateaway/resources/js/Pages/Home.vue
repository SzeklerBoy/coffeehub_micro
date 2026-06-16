<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AdminButton from "@/Components/AdminButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
</script>

<template>
    <GuestLayout>
        <div class="flex flex-col space-y-6">
            <div class="flex flex-col items-center justify-center">
                <h1
                    class="text-2xl text-center font-semibold text-gray-900 dark:text-white mr-5"
                >
                    {{ t("homePage.orderHeading") }}
                </h1>
                <SecondaryButton :href="route('orders.create')">
                    {{ t("homePage.orderButton") }}
                </SecondaryButton>
            </div>
            <div class="flex flex-col items-center justify-center">
                <h1 class="font-semibold text-gray-900 dark:text-white mr-5">
                    {{ t("homePage.loginHeading") }}
                </h1>
                <AdminButton :href="route('login')">
                    {{ t("homePage.loginButton") }}
                </AdminButton>
            </div>
        </div>
    </GuestLayout>
</template>
