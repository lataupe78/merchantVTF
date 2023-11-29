<script setup>
import { computed, ref, watch } from "vue";
import useCalendar from "@/composables/useCalendar.js";

const props = defineProps({
    ranges: {
        type: [Array, Object],
        required: false,
        default() {
            return [];
        },
    },
    showInfo: Boolean,
    showDates: Boolean,
    dates: {
        type: Object,
        default() {
            return {
                current: null,
                date_start: null,
                date_end: null,
                prev: null,
                next: null,
            };
        },
    },
    selectedDates: {
        type: Array,
        default() {
            return [];
        },
    },
});

const firstRange = computed(() => {
    return currentRange(0) ?? [];
});

const { rangeDates, weekCount, currentRange } = useCalendar(props, 'month');

watch(
    () => props.selectedDates,
    (newDates) => {
        let newDatesLabelData = newDates; //newDates.map((date) => date.labelData);

        console.log({ newDatesLabelData, rangeDates });

        rangeDates.value.map((date, dateIndex) => {
            const dateSelected = newDatesLabelData.includes(date.labelData);

            rangeDates.value[dateIndex].selected = dateSelected;
        });
    },
    { deep: true, immediate: true }
);
</script>
<template>
    <v-table fixed-header class="table-calendar mb-5">
     
        <!-- HEADER -->
        <thead>
            <tr v-if="$slots.calendarHeaderTitle">
                <th
                    v-if="showInfo"
                    class="col-info border text-center"
                    colspan="1"
                    rowspan="2"
                ></th>

                <th colspan="7" class="day border text-center text-h5 text-capitalize">
                    <slot name="calendarHeaderTitle" />
                </th>
            </tr>
            <!-- WEEK_DAYS -->
            <tr v-if="firstRange.length">
                <th
                    class="day border text-center pa-0"
                    v-for="(date, colIndex) in firstRange"
                    :key="`label-long-${date?.labelData}`"
                >
                    <span class="text-subtitle-1 text-uppercase">
                        <slot
                            name="calendarHeaderDay"
                            :date="date"
                            :column="colIndex"
                        />
                    </span>
                </th>
            </tr>
        </thead>

        <tbody>
            <template
                v-for="(week, indexWeek) of weekCount"
                :key="`week-${indexWeek}`"
            >
                <slot
                    name="calendarRow"
                    :range="currentRange(indexWeek * 7)"
                    :currentDates="
                        currentRange(indexWeek * 7).map(
                            (date) => date.labelData
                        )
                    "
                    :indexWeek="indexWeek"
                    :showInfo="showInfo"
                />
            </template>
        </tbody>
    
    </v-table>

    <v-row>
        <v-col>
            <PreDebug title="rangeDates">{{ rangeDates }}</PreDebug>
        </v-col>
        <v-col>
            <PreDebug title="selectedDates">{{ selectedDates }}</PreDebug>
        </v-col>
    </v-row>
</template>
<style>
table.table-calendar {
    table-layout: fixed !important;
}
</style>
