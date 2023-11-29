<script setup>
import { computed } from "vue";
const emit = defineEmits(["edit"]);
const props = defineProps({
    date: Object,
    worker: Object,
});

const hasWorkingRange = computed(() => {
    return props.date?.working_ranges?.length > 0;
});

const workingRanges = computed(() => {
    if (!!hasWorkingRange.value === false) {
        return [];
    }

    return (props.date?.working_ranges ?? []).filter(
        (range) => range.worker_id === props.worker.id
    );
});

const hasPunching = (range) => {
    return range.started_at?.time !== null && range.ended_at?.time !== null;
};
</script>
<template>
    <td
        class="day border calendar-cell text-center"
        :class="{
            'is-active': date.isActive == true,
        }"
    >

    

        <template v-if="hasWorkingRange === true">
            <template v-for="(range, index) in workingRanges" :key="index">
                <div
                    class="range_hours-container w-100"
                    :title="range.title"
                    :class="{
                        'is-validated': range?.validated_at !== null,
                    }"
                >
                    <div class="range_hours-title text-wrap mb-2">
                        {{ range.sale_point?.name }}
                    </div>
                    <div class="range_hours-slot mb-1">
                        {{ range.starts_at?.time }}
                        -
                        {{ range.ends_at?.time }}
                    </div>
                    <div
                        class="range_hours-slot mb-1 text-primary"
                        v-if="hasPunching(range)"
                    >
                        {{ range.started_at?.time }}
                        -
                        {{ range.ended_at?.time }}
                    </div>
                    <div
                        class="range_hours-slot mb-1 --validated text-success"
                        v-if="range.validated_started_at !== null"
                    >
                        {{ range.validated_started_at?.time }}
                        -
                        {{ range.validated_ended_at?.time }}
                    </div>

                    <v-btn
                        icon="mdi-pencil"
                        @click="emit('edit', { range, worker: worker })"
                    />
                </div>
            </template>
        </template>

        <!-- <pre>{{  date }}</pre> -->
        <template v-if="props.date?.isActive === true">
            <v-btn
                icon="mdi-plus"
                color="cyan"
                size="w-small" 
                @click="
                    emit('edit', {
                        range: {
                            current_date: date?.labelData,
                        },
                        worker: worker,
                    })
                "
            />
        </template>

        <!-- <pre>
hasWR: {{ hasWorkingRange }} - isActive : {{ date?.isActive }}</pre
        > -->
        <!-- <PreDebug>{{ hasWorkingRange }}</PreDebug>
        <PreDebug>{{ workingRanges }}</PreDebug>
        <PreDebug>{{ worker }}</PreDebug> -->
        <!-- <PreDebug>{{ date }}</PreDebug> -->
    </td>
</template>
