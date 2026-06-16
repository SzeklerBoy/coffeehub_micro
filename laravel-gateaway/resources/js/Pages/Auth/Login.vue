<script setup>
import AppLogo from "@/Components/AppLogo.vue";
import { Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useI18n } from "vue-i18n";
import InputError from "@/Components/InputError.vue";
import AdminButton from "@/Components/AdminButton.vue";
import AuthSessionStatus from "@/Components/AuthSessionStatus.vue";
import Checkbox from "@/Components/Checkbox.vue";
import NavBar from "@/Components/NavBar.vue";

const { t } = useI18n();

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    // eslint-disable-next-line no-undef
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-coffee-light-2 dark:bg-coffee-dark-3"
    >
        <Link href="/">
            <AppLogo class="h-20"></AppLogo>
        </Link>
        <NavBar />
        <div
            class="w-full sm:max-w-md mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-coffee-dark-1"
        >
            <div v-if="status">
                <AuthSessionStatus :status="status" />
            </div>
            <form @submit.prevent="submit">
                <!-- Email -->
                <div>
                    <InputLabel for="email" :value="t('loginPage.email')" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        class="block mt-1 w-full text-black"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div>
                    <InputLabel
                        for="password"
                        :value="t('loginPage.password')"
                        class="mt-2"
                    />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        class="block mt-1 w-full text-black"
                        type="password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Remember Me -->
                <div class="mt-4 block">
                    <label class="flex items-center">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                        />
                        <span
                            class="ms-2 text-sm text-gray-600 dark:text-coffee-light-1"
                        >
                            {{ t("loginPage.rememberMe") }}
                        </span>
                    </label>
                </div>

                <!-- Forget Password -->

                <div class="flex items-center justify-end mt-4">
                    <div class="block">
                        <Link
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:text-gray-400 dark:hover:text-gray-200"
                            :href="route('password.request')"
                        >
                            <!--REWRITE THE ROUTE TO PASSWORD.REQUEST -->
                            {{ t("loginPage.forgotPassword") }}
                        </Link>
                    </div>

                    <!-- Login Button -->
                    <AdminButton type="submit" class="ms-3">
                        {{ t("loginPage.login") }}
                    </AdminButton>
                </div>
            </form>
        </div>
    </div>
</template>
