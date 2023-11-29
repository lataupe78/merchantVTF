<script setup>
import { computed } from "vue";
const props = defineProps({
    value: {
        type: Boolean,
        required: true,
    },
    labels: {
        type: Array,
        default() {
            return ["off", "on"]; // 0, 1
        },
    },
    classes: {
        type: Array,
        default() {
            return ["error", "success"]; // 0, 1
        },
    },
});

const pos = computed(() => {
    return props.value ? 1 : 0;
});
</script>
<template>
    <v-chip :color="classes[`${pos}`]">{{ labels[`${pos}`] }}</v-chip>
</template>

<style></style>
