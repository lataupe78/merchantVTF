<script setup>
import { computed } from "vue";

const emit = defineEmits(["sort"]);

const props = defineProps({
    field: {
        String,
        default: "",
    },
    params: {
        type: Object,
        default: {
            field: null,
            direction: null,
        },
    },
    label: {
        type: String,
        default: "",
    },
});

const toggleSort = () => {
    console.log({ action: "SORT", field: props.field, params: props.params });
    // debugger;
    if (props.field != "") {
        emit("sort", props.field);
    }
};

const isSorted = computed(() => {
    return props.field === props.params.sort_by;
});

const getDirectionIcon = computed(() => {
    if (props.field != props.params.sort_by) {
        return "▼";
    }
    if (props.params.dir == null) {
        return "▼";
    }
    return props.params.dir == "asc" ? "▲" : "▼";
});
</script>
<template>
    <button
        @click="toggleSort"
        role="button"
        class="d-flex w-100 pa-1 mb-2"
        style="cursor: pointer"
    >
        <div class="d-flex w-100 align-items-center fw-bold">
            <div class="text-wrap me-2">
                <slot>{{ label || __(field) }}</slot>
            </div>
            <div
                :class="{
                    'text-muted': !isSorted,
                    'text-info': !!isSorted,
                }"
                class="ms-auto"
                v-text="getDirectionIcon"
            ></div>
        </div>
    </button>
</template>
