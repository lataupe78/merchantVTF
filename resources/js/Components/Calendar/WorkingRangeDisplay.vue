<script setup>
const props = defineProps({
    range: Object,
    show: {
        type: Object,
        default() {
            return {
                title : true,
                validation: false,
                completed: false,
            };
        },
    },
});

const hasPunching = (range) => {
    return range.started_at?.time !== null && range.ended_at?.time !== null;
};
</script>
<template>
    <div
        class="range_hours-container w-100"
        :title="range.title"
        :class="{
            'is-validated': range?.validated_at !== null,
        }"
    >
        <div class="range_hours-title text-wrap bg-info pa-2 mb-2">
            <slot name="title" :range="range"></slot>
            
        </div>
        <!-- PLANNED -->
        <div class="range_hours-slot mb-1">
            {{ range.starts_at?.time }}
            -
            {{ range.ends_at?.time }}
        </div>
        <!-- COMPLETED -->
        <template v-if="show.completed">
            <div
                class="range_hours-slot mb-1 text-primary"
                v-if="hasPunching(range)"
            >
                {{ range.started_at?.time }}
                -
                {{ range.ended_at?.time }}
            </div>
        </template>

        <!-- VALIDATION -->
        <template v-if="show.validation">
            <div
                class="range_hours-slot mb-1 --validated text-success"
                v-if="range.validated_started_at !== null"
            >
                {{ range.validated_started_at?.time }}
                -
                {{ range.validated_ended_at?.time }}
            </div>
        </template>
    </div>
</template>
