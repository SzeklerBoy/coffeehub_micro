<script setup>
import RoundedButton from "@/Components/RoundedButton.vue";
import { computed, ref } from "vue";
import { useCartStore } from "@/Store/cart.js";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
});
defineEmits(["hideCartActions"]);

const cartStore = useCartStore();
const stockQuantity = props.item.quantity;
const productInCart = computed(() => cartStore.product(props.item.id));
const quantity = ref(productInCart.value?.quantity || 1);
const bottomLimit = computed(() => (productInCart.value ? 0 : 1));
</script>

<template>
    <li
        id="addToCart"
        v-show="stockQuantity > 0"
        class="p-2.5 cursor-default bg-coffee-light-3 border-t border-gray-900/5 flex justify-evenly dark:bg-coffee-dark-3"
    >
        <RoundedButton
            @click.stop="quantity--"
            :disabled="quantity <= bottomLimit"
        >
            -
        </RoundedButton>
        <span
            class="content-center text-lg font-semibold text-coffee-dark-0 dark:text-coffee-light-1"
            v-text="quantity"
        />
        <RoundedButton
            @click.stop="quantity++"
            :disabled="quantity >= stockQuantity"
        >
            +
        </RoundedButton>
        <RoundedButton
            @click.stop="
                $emit('hideCartActions');
                cartStore.add(item.id, item.name, item.price, quantity);
                cartStore.saveToSession();
            "
            :disabled="stockQuantity <= 0"
        >
            {{
                productInCart
                    ? t("cartActions.updateInCart")
                    : t("cartActions.addToCart")
            }}
        </RoundedButton>
    </li>
</template>
