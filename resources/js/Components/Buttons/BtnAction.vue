<script setup>
const props = defineProps({
    link: Object,
    required: true,
});
</script>
<template>
    <v-btn
        class="mx-1"
        :color="link?.color || 'white'"
        selected-class="text-blue font-weight-bold"
        size="small"
        :to="link?.url"
        :flat="true"
        height="64px"
        variant="tonal"
        is_active
    >
        <template v-slot:default>
            <div
                class="text-uppercase text-center text-wrap"
                style="max-width: 15rem"
            >
                {{ __(link?.label || "action") }}
            </div>
        </template>
    </v-btn>
</template>
