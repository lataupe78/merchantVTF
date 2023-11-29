<script setup>
import { ref, watch, computed, nextTick, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import Calendar from "@/Components/Calendar/CalendarMain.vue";
import CalendarRow from "@/Components/Calendar/CalendarRow.vue";
// import WorkingRangeSelector from "@/Components/Calendar/WorkingRangeSelector.vue";
import WorkingRangeList from "@/Components/Calendar/WorkingRangeList.vue";

const props = defineProps({
    sale_point: { type: Object, required: true },

    working_ranges: { type: [Array, Object], required: true },

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

    options: {
        type: [Array, Object],
        required: true,
        default() {
            return {
                sale_points: [],
                sale_points: [],
            };
        },
    },
});

const prevNavLink = {
    url: route("manager.plannings.sale_points.index", {
        sale_point: props.sale_point,
        month: toFormat(props.dates?.prev, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.prev, "MMMM yyyy"),
};
const nextNavLink = {
    url: route("manager.plannings.sale_points.index", {
        sale_point: props.sale_point,
        month: toFormat(props.dates?.next, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.next, "MMMM yyyy"),
};

const ranges = ref(props.working_ranges);

const selectedDates = ref([]);

const hasSelectedDates = computed(() => selectedDates.value.length > 0);

const toggleColumn = (event, column) => {
    // let inputEl = document.getElementById(event.target.id)
    let columnChecked = event.target.checked;

    let inputsfromColumn = document.querySelectorAll(
        `input[type=checkbox][id$="-col-${column}"]`
    );
    console.log({
        "COLUMN CHECKED": columnChecked,
        column,
        inputsfromColumn,
    });

    inputsfromColumn.forEach((el) => {
        let value = el.value;

        console.log("looking for " + value);
        if (columnChecked === true) {
            console.log("adding element", value);
            // add if not present
            if (selectedDates.value.includes(value) != true) {
                selectedDates.value.push(value);
            }
        } else {
            // remove if present

            let index = selectedDates.value.indexOf(value);
            console.log("removing element", { value, index });
            if (index !== -1) {
                selectedDates.value.splice(index, 1);
            }
        }
    });

    console.log("toggleColumn", {
        columnChecked,

        column,
        inputsfromColumn,
    });
};

const toggleRow = (event, row) => {
    let rowChecked = event.target.checked;

    let inputsfromRow = document.querySelectorAll(
        `input[type=checkbox][id^="cbo-row-${row}"]`
    );
    console.log({
        "ROW CHECKED": rowChecked,
        row,
        inputsfromRow,
    });

    inputsfromRow.forEach((el) => {
        let value = el.value;

        if (rowChecked === true) {
            console.log("adding element", value);
            // add if not present
            if (selectedDates.value.includes(value) != true) {
                selectedDates.value.push(value);
            }
        } else {
            // remove if present

            let index = selectedDates.value.indexOf(value);
            console.log("removing element", { value, index });
            if (index !== -1) {
                selectedDates.value.splice(index, 1);
            }
        }
    });
};

const actionLinks = [
    {
        label: "plannings.workers",
        url: route("manager.plannings.workers.index"),
    },
    {
        label: "plannings.sale_points",
        url: route("manager.plannings.sale_points.index"),
    },
];
</script>

<template>
    <ManagerLayout
        :title="
            __('plannings sale_point') +
            ' ' +
            toFormat(dates?.current, 'MMMM yyyy')
        "
        :action-links="actionLinks"
    >
        <v-container>
            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />

            <v-row>
                <v-col>
                    <PreDebug title="dates">{{ dates }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="ranges">{{ ranges }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="selectedDates">{{
                        selectedDates
                    }}</PreDebug>
                </v-col>
            </v-row>

            <!-- <WorkingRangeSelector
                v-if="hasSelectedDates"
                :dates="selectedDates"
                :sale_point="sale_point"
                :sale_points="options.sale_points"
            /> -->

            <Calendar
                :ranges="ranges"
                :dates="dates"
                :showInfo="true"
                :showDates="false"
                :selectedDates="selectedDates"
            >
                <template #calendarHeaderTitle="{}">
                    {{ dates.current }}
                </template>

                <template #calendarHeaderDay="{ date, column }">
                    <!-- {{ date.label }}
                    <v-divider /> -->
                    {{ date.labelLong[0] }}
                    <v-checkbox @change="toggleColumn($event, column)" />
                </template>

                <template #calendarRow="{ range, indexWeek, info, showInfo }">
                    <!-- <PreDebug>{{ range }}</PreDebug> -->
                    <CalendarRow
                        :currentRange="range"
                        :indexRow="indexWeek"
                        :showInfo="showInfo"
                        :showCellLabel="true"
                    >
                        <template v-slot:info="{ index, range }">
                            <div class="text-body-1 text-wrap mb-3">
                                {{
                                    `Sem. ${range[0].year}-${range[0].weekNumber}`
                                }}
                            </div>
                            <span
                                class="text-body-2 text-wrap text-wrap text-truncate text-capitalize"
                                :title="sale_point?.name"
                            >
                                {{ sale_point.name }}

                                <v-checkbox
                                    @change="toggleRow($event, index)"
                                />
                            </span>
                        </template>

                        <template v-slot:cell="{ date, row, column }">
                            <div class="d-flex flex-column w-100 align-center">
                                <!-- <div>selected : {{ date.selected }}</div> -->

                                <WorkingRangeList
                                    v-for="range in date?.ranges"
                                    :range="range"
                                >
                                    <template v-slot:title="{ range }">
                                        {{
                                            range?.worker?.name ||
                                            range?.worker_id
                                        }}
                                    </template>
                                </WorkingRangeList>
                            </div>
                            <v-checkbox
                                :id="`cbo-row-${row}-col-${column}`"
                                v-model="selectedDates"
                                :value="date.labelData"
                            >
                            </v-checkbox>
                            <!-- <PreDebug>{{ date?.ranges }}</PreDebug> -->
                        </template>
                    </CalendarRow>
                </template>
            </Calendar>
        </v-container>
    </ManagerLayout>
</template>
