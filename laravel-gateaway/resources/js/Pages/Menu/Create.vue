<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";
import { useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AdminButton from "@/Components/AdminButton.vue";
import { computed, ref } from "vue";

const props = defineProps({
    categories: Array,
});

const { t, locale } = useI18n();

const form = useForm({
    image: null,
    category: "",
    name: "",
    description: "",
    ETA: "",
    quantity: "",
    price: "",
    locale: locale.value,
});

const categorySearch = ref("");
const filteredCategories = computed(() =>
    props.categories.filter((cat) =>
        cat.toLowerCase().includes(categorySearch.value.toLowerCase()),
    ),
);

const selectCategory = (cat) => {
    form.category = cat;
    categorySearch.value = cat;
    showSuggestions.value = false;
};

const showSuggestions = ref(false);
const submit = () => {
    // eslint-disable-next-line no-undef
    form.post(route("menu.store"), {
        forceFormData: true,
        params: { locale: locale },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex">
            <AdminButton :href="route('menu.index')" class="mr-4">
                {{ t("util.back") }}
            </AdminButton>
            <h4 class="text-black dark:text-white text-2xl font-bold">
                {{ t("menuPage.create.title") }}
            </h4>
        </div>
        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="flex flex-col gap-3 px-5 py-3 bg-white text-black dark:text-white shadow rounded-lg dark:bg-coffee-dark-3"
        >
            <!-- Image Upload -->
            <div>
                <label class="block mb-1 font-medium">
                    {{ t("menuPage.table.image") }}:</label
                >
                <input
                    type="file"
                    @change="(e) => (form.image = e.target.files[0])"
                    accept="image/*"
                />
                <span v-if="form.errors.image" class="text-red-500 text-sm">{{
                    form.errors.image
                }}</span>
            </div>

            <!-- Category Autocomplete -->
            <div class="relative">
                <label class="block mb-1 font-medium">
                    {{ t("menuPage.table.category") }}:</label
                >
                <input
                    v-model="categorySearch"
                    @input="
                        () => {
                            showSuggestions = true;
                            form.category = categorySearch;
                        }
                    "
                    @blur="setTimeout(() => (showSuggestions = false), 150)"
                    type="text"
                    placeholder="Search or type category"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    required
                />
                <!-- Suggestions dropdown -->
                <ul
                    v-if="showSuggestions && filteredCategories.length"
                    class="absolute z-10 bg-white border w-full rounded shadow dark:bg-coffee-dark-2 dark:text-white max-h-40 overflow-auto"
                >
                    <li
                        v-for="(cat, index) in filteredCategories"
                        :key="index"
                        @mousedown.prevent="selectCategory(cat)"
                        class="px-3 py-2 hover:bg-gray-200 dark:hover:bg-coffee-dark-1 cursor-pointer"
                    >
                        {{ cat }}
                    </li>
                </ul>
                <span v-if="form.errors.category" class="text-red-500 text-sm">
                    {{ form.errors.category }}
                </span>
            </div>

            <!-- Name -->
            <div>
                <label class="block mb-1 font-medium"
                    >{{ t("menuPage.table.name") }}:</label
                >
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Espresso"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    required
                />
                <span v-if="form.errors.name" class="text-red-500 text-sm">{{
                    form.errors.name
                }}</span>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-1 font-medium"
                    >{{ t("menuPage.table.description") }}:</label
                >
                <textarea
                    v-model="form.description"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    rows="3"
                    placeholder="A concentrated form of coffee..."
                ></textarea>
                <span
                    v-if="form.errors.description"
                    class="text-red-500 text-sm"
                    >{{ form.errors.description }}</span
                >
            </div>

            <!-- ETA -->
            <div>
                <label class="block mb-1 font-medium"
                    >{{ t("menuPage.table.eta") }}:</label
                >
                <input
                    v-model="form.ETA"
                    type="number"
                    min="0"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    required
                />
                <span v-if="form.errors.ETA" class="text-red-500 text-sm">{{
                    form.errors.ETA
                }}</span>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block mb-1 font-medium"
                    >{{ t("menuPage.table.quantity") }}:</label
                >
                <input
                    v-model="form.quantity"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    required
                />
                <span
                    v-if="form.errors.quantity"
                    class="text-red-500 text-sm"
                    >{{ form.errors.quantity }}</span
                >
            </div>

            <!-- Price -->
            <div>
                <label class="block mb-1 font-medium"
                    >{{ t("menuPage.table.price") }}:</label
                >
                <input
                    v-model="form.price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full border dark:text-black rounded px-3 py-2"
                    required
                />
                <span v-if="form.errors.price" class="text-red-500 text-sm">{{
                    form.errors.price
                }}</span>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-4">
                <SecondaryButton :href="route('menu.index')">
                    {{ t("util.cancel") }}
                </SecondaryButton>
                <AdminButton type="submit" class="ml-4">
                    {{ t("util.save") }}
                </AdminButton>
            </div>
        </form>
    </AdminLayout>
</template>
