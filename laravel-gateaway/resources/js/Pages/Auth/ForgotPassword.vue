<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";
import AppLogo from "@/Components/AppLogo.vue";
import ToggleTheme from "@/Components/ToggleTheme.vue";

const { t } = useI18n();

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    // eslint-disable-next-line no-undef
    form.post(route("password.email"));
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-coffee-light-2 dark:bg-coffee-dark-3"
    >
        <Link href="/">
            <AppLogo class="h-20"></AppLogo>
        </Link>
        <ToggleTheme />
        <div
            class="w-full sm:max-w-md mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-coffee-dark-1"
        >
            <Head title="Forgot Password" />

            <div class="mb-4 text-sm text-gray-600 dark:text-coffee-light-1">
                {{ t("forgotPasswordPage.text") }}
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel
                        for="email"
                        :value="t('forgotPasswordPage.email')"
                    />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <AdminButton :href="route('login')">
                        {{ t("forgotPasswordPage.back") }}
                    </AdminButton>
                    <AdminButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ t("forgotPasswordPage.send") }}
                    </AdminButton>
                </div>
            </form>
        </div>
    </div>
</template>
