<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminButton from "@/Components/AdminButton.vue";
import { useI18n } from "vue-i18n";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    item: Object,
    categories: Array,
});

const { t, locale } = useI18n();

const form = useForm({
    _method: "PATCH",
    category: props.item.category || "",
    name: props.item.name || "",
    description: props.item.description || "",
    ETAinMinutes: props.item.ETAinMinutes || "",
    quantity: props.item.quantity || "",
    price: props.item.price || "",
    image: null,
    locale: locale.value,
});

const categorySearch = ref("");
const filteredCategories = computed(() =>
    props.categories.filter((cat) =>
        cat.toLowerCase().includes(categorySearch.value.toLowerCase()),
    ),
);

const imageFile = ref(null);
const selectCategory = (cat) => {
    form.category = cat;
    categorySearch.value = cat;
    showSuggestions.value = false;
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        imageFile.value = file;
    }
};

const showSuggestions = ref(false);
const submit = () => {
    if (imageFile.value) {
        form.image = imageFile.value;
    } else {
        delete form.image;
    }

    // eslint-disable-next-line no-undef
    form.post(route("menu.update", props.item), {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
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
                {{ t("menuPage.editItem.title") }}
            </h4>
        </div>
        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="px-5 py-3 bg-white border mt-4 text-sm rounded-lg dark:bg-coffee-dark-3 dark:border-none"
        >
            <div
                class="flex flex-col sm:flex-row gap-4 text-black dark:text-white md:gap-10"
            >
                <!-- Image Upload -->
                <div class="flex-none sm:w-60">
                    <label class="block mb-1 font-medium">
                        {{ t("menuPage.table.image") }}:</label
                    >
                    <input
                        type="file"
                        @change="handleFileChange"
                        accept="image/*"
                    />
                    <label class="block text-sm mb-1 mt-5">
                        {{ t("menuPage.editItem.oldImage") }}
                    </label>
                    <img
                        :src="
                            item.image_path ??
                            `https://placehold.co/250x250?text=${item.name[0]}`
                        "
                        class="border rounded-xl aspect-square object-cover"
                        alt="Menu item image"
                    />
                    <span
                        v-if="form.errors.image"
                        class="text-red-500 text-sm"
                        >{{ form.errors.image }}</span
                    >
                </div>

                <!-- Text Inputs -->
                <div class="flex flex-col flex-grow gap-5">
                    <div>
                        <label class="block font-medium mb-1">
                            {{ t("menuPage.table.name") }}:
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Espresso"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
                            required
                        />
                        <span
                            v-if="form.errors.name"
                            class="text-red-500 text-sm"
                            >{{ form.errors.name }}</span
                        >
                    </div>

                    <!-- Category Autocomplete -->
                    <div class="relative">
                        <label class="block mb-1 font-medium">
                            {{ t("menuPage.table.category") }}:</label
                        >
                        <input
                            v-model="form.category"
                            @input="showSuggestions = true"
                            @blur="
                                setTimeout(() => (showSuggestions = false), 150)
                            "
                            type="text"
                            placeholder="Search or type category"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
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
                        <span
                            v-if="form.errors.category"
                            class="text-red-500 text-sm"
                        >
                            {{ form.errors.category }}
                        </span>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">
                            {{ t("menuPage.table.description") }}:</label
                        >
                        <textarea
                            v-model="form.description"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
                            rows="3"
                            placeholder="A concentrated form of coffee..."
                        ></textarea>
                        <span
                            v-if="form.errors.description"
                            class="text-red-500 text-sm"
                            >{{ form.errors.description }}</span
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">
                            {{ t("menuPage.table.eta") }}:</label
                        >
                        <input
                            v-model="form.ETAinMinutes"
                            type="number"
                            min="0"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
                            required
                        />
                        <span
                            v-if="form.errors.ETAinMinutes"
                            class="text-red-500 text-sm"
                            >{{ form.errors.ETAinMinutes }}</span
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">
                            {{ t("menuPage.table.quantity") }}:</label
                        >
                        <input
                            v-model="form.quantity"
                            type="number"
                            min="0"
                            step="0.01"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
                            required
                        />
                        <span
                            v-if="form.errors.quantity"
                            class="text-red-500 text-sm"
                            >{{ form.errors.quantity }}</span
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-medium"
                            >{{ t("menuPage.table.price") }}:</label
                        >
                        <input
                            v-model="form.price"
                            type="number"
                            min="0"
                            step="0.01"
                            class="w-1/2 border dark:text-black rounded px-3 py-2"
                            required
                        />
                        <span
                            v-if="form.errors.price"
                            class="text-red-500 text-sm"
                            >{{ form.errors.price }}</span
                        >
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-3">
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
