<script setup>
import { loadStripe } from "@stripe/stripe-js";
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { usePage } from "@inertiajs/vue3";
import StaffLayout from "@/Layouts/StaffLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useDark } from "@vueuse/core";

const props = defineProps({
    api_key: {
        type: String,
        required: true,
    },
    client_secret: {
        type: String,
        required: true,
    },
});
// For some strange reason async/await doesn't work here only promise chaining
const stripe = loadStripe(props.api_key);
console.log("Stripe loaded", stripe);
stripe.then((stripe) => {
    const checkout = stripe.initEmbeddedCheckout({
        clientSecret: props.client_secret,
    });
    console.log("Checkout loaded", checkout);
    checkout.then((checkout) => {
        checkout.mount("#checkout");
    });
});

const isLogged = computed(() => {
    return !!usePage().props.auth.user;
});
const isAdmin = computed(() => !usePage().props.auth.user.role);

// Change the theme to light, because the stripe checkout is in light mode
const isDark = useDark();
isDark.value = false;
</script>

<template>
    <component
        :is="isLogged ? (isAdmin ? AdminLayout : StaffLayout) : GuestLayout"
        :showNavBar="false"
    >
        <div id="checkout" class="rounded-xl overflow-hidden" />
    </component>
</template>
