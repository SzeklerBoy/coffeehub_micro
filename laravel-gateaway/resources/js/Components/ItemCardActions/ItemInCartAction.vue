<script setup>
import CartIcon from "@/Components/Icons/CartIcon.vue";
import { computed } from "vue";
import { useCartStore } from "@/Store/cart.js";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const cartStore = useCartStore();
const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
});
const productInCart = computed(() => cartStore.product(props.item.id));
</script>

<template>
    <span
        v-show="item.quantity === 0"
        class="px-2 py-1 font-semibold text-nowrap leading-tight border border-red-500 text-red-700 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100 dark:border-none"
    >
        {{ t("itemCard.outOfStock") }}
    </span>
    <div
        v-show="productInCart"
        class="mr-1 h-10 text-coffee align-middle flex gap-1 justify-center items-center dark:text-coffee-light-3"
    >
        <span
            v-text="productInCart?.quantity + ' x'"
            class="text-nowrap"
        ></span>
        <CartIcon />
    </div>
</template>
