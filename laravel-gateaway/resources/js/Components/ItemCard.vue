<script setup>
import { computed, ref } from "vue";
import AdminButton from "@/Components/AdminButton.vue";

const props = defineProps({
    canPopup: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: () => ({}),
    },
    title: {
        type: String,
        default: "",
    },
});
const showPopup = ref(false);
const title = computed(
    () =>
        props.title ||
        props.item.name ||
        props.item.translation_with_locale?.name,
);
const showFooterActions = ref(false);

function changeShowFooterActions() {
    showFooterActions.value = !showFooterActions.value;
}

defineExpose({
    changeShowFooterActions,
});
</script>

<template>
    <li
        @click="changeShowFooterActions()"
        class="flex flex-col list-none overflow-hidden rounded-xl border border-gray-300 dark:text-gray-800"
    >
        <div
            class="flex items-center gap-x-3 text-coffee border-b border-gray-900/5 bg-coffee-light-3 p-2.5 dark:bg-coffee-dark-3 dark:text-coffee-light-3"
        >
            <div
                id="icon"
                class="h-10 w-10 text-gray-800 p-1 border rounded-md overflow-hidden bg-coffee-light-1 dark:bg-coffee-dark-2 dark:text-gray-200"
                @click.stop="showPopup = true"
                :class="{
                    'cursor-pointer': canPopup,
                }"
            >
                <slot name="icon" />
            </div>
            <div
                id="popupContainer"
                v-if="canPopup"
                v-show="showPopup"
                v-cloak
                class="fixed inset-0 top-24 overflow-y-auto px-4 py-6 sm:px-0 z-50"
            >
                <div
                    id="popupBackground"
                    v-show="showPopup"
                    class="fixed inset-0 transform transition-all"
                    @click.stop="showPopup = false"
                >
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div
                    id="popup"
                    v-show="showPopup"
                    class="mb-6 bg-white cursor-auto rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-fit sm:mx-auto dark:bg-coffee-dark-3 dark:text-white"
                >
                    <div class="p-6 flex flex-col gap-6">
                        <div class="text-lg font-medium leading-6 truncate">
                            {{ title }}
                        </div>
                        <div
                            class="max-h-96 max-w-96 aspect-square text-gray-800 p-1 border rounded-md overflow-hidden bg-coffee-light-1 dark:bg-coffee-dark-2 dark:text-gray-200"
                        >
                            <slot name="icon" />
                        </div>
                        <div class="flex justify-end">
                            <AdminButton @click.stop="showPopup = false">
                                Close
                            </AdminButton>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-lg font-medium leading-6 truncate">
                {{ title }}
            </div>

            <div class="flex gap-1 ml-auto">
                <slot name="header-actions" />
            </div>
        </div>
        <div
            class="h-full overflow-hidden bg-white dark:bg-coffee-dark-0 dark:text-coffee-light-3"
        >
            <div>
                <div class="h-full flex flex-col justify-between">
                    <div class="py-2">
                        <slot name="body" />
                    </div>
                </div>
            </div>
        </div>
        <div v-show="showFooterActions">
            <slot name="footer-actions" />
        </div>
    </li>
</template>
