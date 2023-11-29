<script setup>
const props = defineProps({
    range: Object,
    show: {
        type: Object,
        default() {
            return {
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
    <v-list
        class="range_hours-container w-100 mb-2"
        :variant="range?.validated_at !== null ? 'tonal' : 'flat'"
    >
        <v-list-item-title
            v-if="$slots.title"
            class="text-center text-uppercase text-secondary"
        >
            <slot name="title" :range="range"></slot>
        </v-list-item-title>
        <!-- PLANNED -->
        <v-list-item class="px-2">
            <div class="text-no-wrap">
                {{ range.starts_at?.time }}
                -
                {{ range.ends_at?.time }}
            </div>
        </v-list-item>
        <!-- COMPLETED -->
        <v-list-item class="px-2"
            v-if="show.completed && hasPunching(range)"
            base-color="blue"
        >
            {{ range.started_at?.time }}
            -
            {{ range.ended_at?.time }}
        </v-list-item>

        <v-list-item class="px-2"
            v-if="show.validation && range.validated_started_at !== null"
            base-color="green"
        >
            {{ range.validated_started_at?.time }}
            -
            {{ range.validated_ended_at?.time }}
        </v-list-item>
    </v-list>
</template>
