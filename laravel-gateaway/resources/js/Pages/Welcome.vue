<script setup>
import { useI18n } from "vue-i18n";
import { useDark } from "@vueuse/core";
import ToggleTheme from "@/Components/ToggleTheme.vue";
import AppLogo from "@/Components/AppLogo.vue";
import { Link } from "@inertiajs/vue3";
import AdminButton from "@/Components/AdminButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import CoffeeBean from "@/Components/CoffeeBean.vue";
import Checkbox from "@/Components/Checkbox.vue";
import AuthSessionStatus from "@/Components/AuthSessionStatus.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import AdminListItem from "@/Components/AdminListItem.vue";
const { t } = useI18n();

const isDark = useDark();
</script>

<template>
    <div class="h-dvh flex flex-col items-center pt-3">
        <Link href="/test2">
            <AppLogo class="h-14" />
        </Link>
        <h1 class="ml-3 text-black">{{ t("test") }}</h1>
        <span class="dark:text-white">
            {{ isDark ? "Dark" : "Light" }} Mode
        </span>
        <ToggleTheme />
        <AdminButton> Egy sima gomb </AdminButton>
        <AdminButton href="/test2"> Egy link gomb </AdminButton>
        <SecondaryButton> Egy másodlagos gomb </SecondaryButton>
        <SecondaryButton href="/test2">
            Egy másodlagos link gomb
        </SecondaryButton>
        <PrimaryButton> Egy sima gomb </PrimaryButton>
        <TextInput />
        <DangerButton> Egy veszélyes gomb </DangerButton>
        <CoffeeBean class="h-14" />
        <Checkbox name="name" label="Name"> Lehet szoveg irni bele </Checkbox>
        <AuthSessionStatus status="status" />
        <ResponsiveNavLink to="/blade" active> Blade </ResponsiveNavLink>
        <AdminListItem href="/blade">
            <template v-slot:icon>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path
                        d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22"
                    />
                </svg>
            </template>
            van benne szoveg
        </AdminListItem>
    </div>
</template>
