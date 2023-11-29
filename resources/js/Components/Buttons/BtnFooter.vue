<script setup>
const props = defineProps({
    link: Object,
    required: true,
});
</script>
<template>
    <v-btn
        class="mx-auto pa-5"
        :color="link?.color ?? 'transparent'"
        :class="link?.textColor ?? 'text-white'"
        stacked
        :to="link?.url"
        :flat="true"
        variant="tonal"
    >
        <template v-slot:prepend>
            <v-icon
                :icon="link.icon"
                variant="tonal"
                color="indigo-darken-4"
                size="small"
                class="mt-0"
            />
        </template>

        <small class="text-center text-title text-uppercase">
            {{ __(link?.label) }}
        </small>
    </v-btn>
</template>
