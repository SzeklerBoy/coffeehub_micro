<script setup>
import { useI18n } from "vue-i18n";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const { t } = useI18n();

const props = defineProps({
    visible: Boolean,
    position: {
        type: Object,
        required: true,
    },
    menuOptions: {
        type: Array,
        required: true,
    },
    deskCode: {
        type: [String, null],
        default: null,
    },
    groupId: {
        type: [Number, null],
        default: null,
    },
    deskId: {
        type: [Number, null],
        default: null,
    },
});

const emit = defineEmits([
    "editSeats",
    "placeOrder",
    "viewOrder",
    "generateCode",
    "clearCode",
    "deleteGroup",
]);

const handle = (action) => {
    emit(action);
};

const placeOrderHref = computed(() => {
    if (props.menuOptions.includes("placeOrder")) {
        if (props.groupId) {
            // eslint-disable-next-line no-undef
            return route("orders.create", { group: props.groupId });
        }
        if (props.deskId) {
            // eslint-disable-next-line no-undef
            return route("orders.create", { desk: props.deskId });
        }
    }
    return null;
});

const viewOrderHref = computed(() => {
    if (props.menuOptions.includes("viewOrder")) {
        if (props.groupId) {
            // eslint-disable-next-line no-undef
            return route("groups.orders.index", { group: props.groupId });
        }

        if (props.deskId) {
            // eslint-disable-next-line no-undef
            return route("desks.orders.index", { desk: props.deskId });
        }
    }
    return null;
});
</script>

<template>
    <div
        v-if="props.visible"
        class="absolute bg-white shadow-md rounded z-10 flex flex-col"
        :style="{
            top: `${props.position.y}px`,
            left: `${props.position.x}px`,
        }"
        @click.stop
    >
        <div
            v-if="deskCode"
            class="px-4 py-2 text-sm text-gray-600 border-b border-gray-200 bg-gray-50"
        >
            {{ t("desksPage.deskCode") }}: <strong>{{ deskCode }}</strong>
        </div>
        <button
            v-if="menuOptions.includes('editSeats')"
            @click="handle('editSeats')"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.editSeats") }}
        </button>
        <Link
            v-if="placeOrderHref"
            :href="placeOrderHref"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.placeOrder") }}
        </Link>

        <Link
            v-if="viewOrderHref"
            :href="viewOrderHref"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.viewOrder") }}
        </Link>

        <button
            v-if="deskCode && menuOptions.includes('generateCode')"
            @click="handle('clearCode')"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.clearCode") }}
        </button>
        <button
            v-if="menuOptions.includes('generateCode') && !deskCode"
            @click="handle('generateCode')"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.generateCode") }}
        </button>
        <button
            v-if="menuOptions.includes('deleteGroup')"
            @click="handle('deleteGroup')"
            class="w-full text-black px-4 py-2 hover:bg-gray-200 text-left"
        >
            {{ t("desksPage.deleteGroup") }}
        </button>
    </div>
</template>
