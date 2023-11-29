<script setup>
const props = defineProps({
    items: {
        type: Array,
        default() {
            return [];
        },
    },
});
</script>
<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-btn
                variant="tonal"
                color="primary"
                v-bind="props"
                icon="mdi-dots-vertical"
            />
        </template>
        <v-list>
            <v-list-item
                v-for="(item, itemIndex) in items"
                :key="`list-item-${itemIndex}`"
                :title="item?.label"
                :to="item?.url"
            >
                <template v-slot:prepend v-if="item?.icon">
                    <v-icon :icon="item.icon" />
                </template>
            </v-list-item>
            <slot />
        </v-list>
    </v-menu>
</template>
