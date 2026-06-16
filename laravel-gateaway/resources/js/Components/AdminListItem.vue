<script setup>
import { computed, defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: {
        type: Boolean,
        default: false,
    },
    icon: String,
});

const baseClass =
    "inline-flex items-center w-full font-semibold transition-colors duration-150 hover:text-gray-800";
const computedClass = computed(() =>
    props.active ? `${baseClass} text-gray-800` : `${baseClass} text-gray-600`,
);
</script>

<template>
    <li class="relative px-6 py-3">
        <span
            v-if="active"
            class="absolute inset-y-0 left-0 w-1 bg-coffee rounded-tr-lg rounded-br-lg"
            aria-hidden="true"
        >
        </span>
        <Link :class="computedClass" :href="href">
            <span class="w-5 mr-3">
                <slot name="icon" />
            </span>
            <slot />
        </Link>
    </li>
</template>
