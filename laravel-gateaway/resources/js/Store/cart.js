import { defineStore } from "pinia";

const CART_STORAGE_KEY = "__cart";

export const useCartStore = defineStore("cart", {
    state: () => ({
        contents: JSON.parse(sessionStorage.getItem(CART_STORAGE_KEY)) ?? {},
    }),
    getters: {
        count: (state) => {
            return Object.keys(state.contents).reduce((acc, id) => {
                return acc + state.contents[id].quantity;
            }, 0);
        },
        total: (state) => {
            return Object.keys(state.contents).reduce((acc, id) => {
                const product = state.contents[id];
                return acc + product.quantity * product.price;
            }, 0);
        },
    },
    actions: {
        add(productId, name, price, quantity) {
            if (quantity <= 0) {
                // Remove the product from the cart if the quantity is 0
                delete this.contents[productId];
                return;
            }
            this.contents[productId] = {
                productId,
                name,
                price,
                quantity,
            };
        },
        product(id) {
            return this.contents[id] ?? undefined;
        },
        remove(id) {
            delete this.contents[id];
        },
        saveToSession() {
            sessionStorage.setItem(
                CART_STORAGE_KEY,
                JSON.stringify(this.contents),
            );
        },
        clear() {
            this.contents = {};
            sessionStorage.removeItem(CART_STORAGE_KEY);
        },
    },
});
