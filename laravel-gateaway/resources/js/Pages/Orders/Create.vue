<script setup>
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ItemCard from "@/Components/ItemCard.vue";
import CartSummary from "@/Components/CartSummary.vue";
import { provide } from "vue";
import Notification from "@/Components/Notification.vue";
import CustomImage from "@/Components/CustomImage.vue";
import ItemInCartAction from "@/Components/ItemCardActions/ItemInCartAction.vue";
import DescriptionList from "@/Components/DescriptionList.vue";
import AddToCartAction from "@/Components/ItemCardActions/AddToCartAction.vue";
import { useI18n } from "vue-i18n";
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import StaffLayout from "@/Layouts/StaffLayout.vue";

const { locale, t } = useI18n();

const items = ref([]);
const itemCards = ref(null); // for ItemCard components access

const props = defineProps({
    desk: Number,
    group: Number,
    categories: Array,
});
provide("desk", props.desk);
provide("group", props.group);

async function loadItems() {
    //load items from server
    const response = await axios.get(`/api/menu`, {
        params: { locale: locale.value },
    });
    items.value = response.data;
}

watch(locale, () => {
    loadItems();
});

onMounted(() => {
    loadItems();
});

const isLogged = computed(() => usePage().props.auth.user !== null);
const isAdmin = computed(() => !usePage().props.auth.user?.role);
</script>
<template>
    <component
        :is="isLogged ? (isAdmin ? AdminLayout : StaffLayout) : GuestLayout"
    >
        <!--        TODO::add filtering here-->
        <!--    <div id="filtering"-->
        <!--        class="flex flex-wrap justify-between gap-2 md:gap-4 p-3 mt-2 mb-4 lg:mb-6 bg-gray-100 rounded-xl dark:bg-coffee-dark-3 dark:text-coffee-light-3"-->
        <!--    >-->
        <!--      <button-->
        <!--          v-for="category in categories"-->
        <!--          class="flex-1 md:flex-none text-center py-2 px-4 md:w-auto"-->
        <!--          @click="loadItems(category)"-->
        <!--          :class="{ 'bg-coffee text-white rounded-lg': activeCategory === category }"-->
        <!--      >-->
        <!--        {{ category }}-->
        <!--      </button>-->
        <!--    </div>-->
        <Notification />
        <div id="items" class="mx-auto grid">
            <ul
                class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6"
            >
                <ItemCard
                    v-for="(item, index) in items"
                    :key="item.id"
                    :item="item"
                    canPopup
                    ref="itemCards"
                >
                    <template #icon>
                        <CustomImage
                            :src="item.image_path || ''"
                            :alt="item.name"
                        />
                    </template>
                    <template #header-actions>
                        <ItemInCartAction :item="item" />
                    </template>
                    <template #body>
                        <DescriptionList
                            :title="t('itemCard.description')"
                            :text="item.description"
                        />
                        <DescriptionList
                            title="Price"
                            :text="item.price + ' ' + t('itemCard.lei')"
                        />
                    </template>
                    <template #footer-actions>
                        <AddToCartAction
                            :item="item"
                            @hideCartActions="
                                itemCards[index].changeShowFooterActions()
                            "
                        />
                    </template>
                </ItemCard>
            </ul>
        </div>
        <!-- TODO: pagination -->

        <CartSummary v-if="isLogged" />
    </component>
    <CartSummary v-if="!isLogged" />
</template>
