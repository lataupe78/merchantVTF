<script setup>
const props = defineProps({
    prev: {
        type: Object,
        validator: function (obj) {
            return obj.label && obj.url && obj.url.length;
        },
        default() {
            return {
                label: "",
                url: "",
            };
        },
    },
    next: {
        type: Object,
        validator: function (obj) {
            return obj.label && obj.url && obj.url.length;
        },
        default() {
            return {
                label: "",
                url: "",
            };
        },
    },
});
</script>
<template>
    <nav class="d-flex mb-5">
        <template v-if="prev?.url">
            <v-btn
                size="large"
                color="secondary"
                variant="tonal"
                :to="prev.url"
                :preserve-scroll="true"
                prepend-icon="mdi-arrow-left-bold"
            >
                <span class="text-wrap">
                    {{ prev.label }}
                </span>
            </v-btn>
        </template>
        <v-spacer></v-spacer>
        <template v-if="next?.url">
            <v-btn
                size="large"
                color="secondary"
                variant="tonal"
                :to="next.url"
                :preserve-scroll="true"
                append-icon="mdi-arrow-right-bold"
            >
                <span class="text-wrap">
                    {{ next.label }}
                </span>
            </v-btn>
        </template>
    </nav>
</template>
