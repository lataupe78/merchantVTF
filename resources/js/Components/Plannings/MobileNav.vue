<script setup>
const props = defineProps({
    start: String,
    end: String,
    dates: {
        type: Object,
        default() {
            return {
                current: null,
                date_start: null,
                date_end: null,
            };
        },
    },
});
</script>

<template>
    <v-row>
        <div></div>
    </v-row>
</template>
