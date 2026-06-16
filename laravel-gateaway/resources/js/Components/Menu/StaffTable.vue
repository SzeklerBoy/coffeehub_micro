<script setup>
import ItemCard from "@/Components/ItemCard.vue";
import CursorPagination from "@/Components/CursorPagination.vue";
import CustomImage from "@/Components/CustomImage.vue";
import ItemInCartAction from "@/Components/ItemCardActions/ItemInCartAction.vue";
import DescriptionList from "@/Components/DescriptionList.vue";
import { useI18n } from "vue-i18n";
import { ref } from "vue";

defineProps({
    menuItems: Object,
});

const { t } = useI18n();
const itemCards = ref(null);
</script>

<template>
    <div id="items" class="mx-auto grid">
        <ul
            class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6"
        >
            <ItemCard
                v-for="item in menuItems"
                :key="item.id"
                :item="item"
                ref="itemCards"
            >
                <template #icon>
                    <CustomImage :src="item.image_path" :alt="item.name" />
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
            </ItemCard>
        </ul>
        <CursorPagination :pagination="menuItems" />
    </div>
</template>
