<script setup>
defineProps({
    name: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        default: () => [],
    },
});
const inputValue = defineModel({
    type: String,
    required: true,
});
defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <div>
        <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-300">
                <slot />
            </span>
            <!--           v-bind="$attrs" should be used after every other-->
            <!--            attribute so that everything can be overriden from outside-->
            <input
                :name="name"
                v-model="inputValue"
                type="text"
                class="block w-full mt-1 text-sm rounded dark:border-gray-600 dark:bg-coffee-dark-2 focus:border-coffee focus:outline-none focus:ring-coffee dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                v-bind="$attrs"
            />
        </label>
        <!--        TODO: add error field here-->
    </div>
</template>
