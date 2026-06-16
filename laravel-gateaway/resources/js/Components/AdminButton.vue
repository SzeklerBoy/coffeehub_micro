<!--
  Primary Button for form submit and for router buttons.
  Props: href (optional)
  Note: Might be submit type, but to maintain spa conventions
  preventDefault event should be used.
 -->
<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: false,
    },
});

defineEmits(["click"]);

const componentTag = computed(() => (props.href ? Link : "button"));

const baseClass =
    "px-4 py-2 text-sm h-min self-center font-medium" +
    " leading-5 text-white transition-colors duration-150 bg-coffee " +
    "border border-transparent rounded-lg " +
    "active:bg-coffee-lighter hover:bg-coffee-lighter";
</script>

<template>
    <component
        :is="componentTag"
        :href="href"
        :class="baseClass"
        @click="$emit('click', $event)"
    >
        <slot />
    </component>
</template>
