<script setup>
import { useCartStore } from "@/Store/cart.js";
import AdminButton from "@/Components/AdminButton.vue";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Input from "@/Components/Input.vue";
import { useForm } from "@inertiajs/vue3";
import { inject } from "vue";
import { usePage } from "@inertiajs/vue3";
import XIcon from "@/Components/Icons/XIcon.vue";

const cartStore = useCartStore();
const showConfirmModal = ref(false);
const showCodeModal = ref(false);
const code = ref("");
const desk = inject("desk");
const group = inject("group");
const form = useForm({
    cart: "",
    code: "",
});

const postRoute = computed(() => {
    /* eslint-disable no-undef */
    return desk
        ? route("desks.orders.store", desk)
        : group
          ? route("groups.orders.store", group)
          : route("orders.store");
    /* eslint-enable no-undef */
});

function submitOrder() {
    showConfirmModal.value = false;

    form.cart = JSON.stringify(cartStore.contents);
    form.code = code.value;
    form.post(postRoute.value, {
        onSuccess: () => {
            const page = usePage();
            const success = page.props.flash?.success;
            const error = page.props.flash?.error;
            //check if success or error is set
            //onSuccess is called even if the response is not 200
            //so a manual check is needed
            if (success) {
                code.value = "";

                // Redirect manually
                const nextUrl = page.props.flash?.next;
                if (nextUrl) {
                    window.location.href = nextUrl;
                }
            } else if (error) {
                console.log("Error:", error);
            }
            cartStore.clear();
        },
    });
}

const handleSubmit = () => {
    // eslint-disable-next-line no-undef
    postRoute.value === route("orders.store")
        ? (showCodeModal.value = true)
        : (showConfirmModal.value = true);
};
</script>

<template>
    <div
        class="w-full sticky left-0 bottom-4 lg:bottom-10 bg-white shadow-lg p-4 rounded-lg mt-6 z-20 text-gray-900 border border-gray-300 dark:bg-coffee-dark-3 dark:text-white"
        v-show="cartStore.count > 0"
    >
        <h2 class="text-xl font-bold mb-4">Cart Summary</h2>
        <ul
            class="max-h-52 overflow-y-auto"
            v-for="(item, id) in cartStore.contents"
            :key="id"
        >
            <li class="flex justify-between mb-2">
                <span class="truncate">
                    {{ item.name + " (" + item.quantity + "x)" }}
                </span>
                <div class="flex-none align-middle">
                    <span class="align-middle">
                        {{ (item.quantity * item.price).toFixed(2) + " lei" }}
                    </span>
                    <button
                        class="ml-1 p-1 align-middle rounded hover:bg-gray-100 dark:hover:bg-gray-900"
                        type="button"
                        title="Remove from cart"
                        @click="
                            cartStore.remove(id);
                            cartStore.saveToSession();
                        "
                    >
                        <XIcon />
                    </button>
                </div>
            </li>
        </ul>
        <div class="flex items-center justify-between mt-4 font-bold">
            <span>Total:</span>
            <span>
                {{ cartStore.total.toFixed(2) + " lei" }}
            </span>
            <AdminButton v-if="true" @click.prevent="handleSubmit">
                Send Order
            </AdminButton>

            <Modal v-model="showConfirmModal" max-width="md">
                <div class="p-6">
                    <h2 class="text-lg font-medium">
                        Are you sure you want to order?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="showConfirmModal = false">
                            Cancel
                        </SecondaryButton>
                        <AdminButton class="ms-3" @click="submitOrder()">
                            Send Order
                        </AdminButton>
                    </div>
                </div>
            </Modal>
            <Modal v-model="showCodeModal" max-width="md">
                <div class="p-6">
                    <h2 class="text-lg font-medium mb-3">
                        Enter the code to order
                    </h2>
                    <Input
                        name="code"
                        id="code-input"
                        type="number"
                        min="0"
                        v-model="code"
                        @keydown.enter="
                            showConfirmModal = true;
                            showCodeModal = false;
                        "
                    >
                        Code:
                    </Input>
                    <div class="flex justify-end mt-5">
                        <AdminButton
                            @click.prevent="
                                showConfirmModal = true;
                                showCodeModal = false;
                            "
                        >
                            Submit
                        </AdminButton>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>
