<script setup>
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import Calendar from "@/Components/Calendar/CalendarMain.vue";
import CalendarRow from "@/Components/Calendar/CalendarRow.vue";
import WorkingRangeList from "@/Components/Calendar/WorkingRangeList.vue";

const props = defineProps({
    workers: {
        type: [Array, Object],
        default() {
            return [];
        },
    },
    // les ranges sont triées par date croissante et groupée par worker
    // on garde le nom working_ranges, il ne se sera pas traité par useCalendar.rangeDates ( ajout des range au jour )
    working_ranges: {
        type: [Array, Object],
        default() {
            return [];
        },
    },
    filters: Object,

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
});

const prevNavLink = {
    url: route("manager.plannings.workers.index", {
        worker: props.worker,
        month: toFormat(props.dates?.prev, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.prev, "MMMM yyyy"),
};
const nextNavLink = {
    url: route("manager.plannings.workers.index", {
        worker: props.worker,
        month: toFormat(props.dates?.next, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.next, "MMMM yyyy"),
};

const mergedDateRangeWithWorkerWorkingRanges = (
    currentDatesRange = [],
    worker
) => {
    let workingRangeForWorker = props.working_ranges?.[worker?.id];
    // debugger;
    if (
        workingRangeForWorker == undefined ||
        workingRangeForWorker.length < 1
    ) {
        return [];
    }
    let workingRangeWorker = props.working_ranges[worker.id].filter((range) => {
        return currentDatesRange.includes(range?.current_date);
    });

    return workingRangeWorker;
};

const workingRangesForWorkerAtDate = (worker, labelData) => {
    let workingRangeForWorker = props.working_ranges?.[worker?.id];

    if (
        workingRangeForWorker == undefined ||
        workingRangeForWorker.length < 1
    ) {
        return [];
    }

    let workingRanges = props.working_ranges[worker.id].filter((range) => {
        return range?.current_date == labelData;
    });
    return workingRanges;
};

const actionLinks = [
    {
        label: "plannings.dashboard",
        url: route("manager.plannings.dashboard"),
        color : 'orange',
    },
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
            __('plannings.workers_index') +
            ' ' +
            toFormat(dates?.current, 'MMMM yyyy')
        "
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" class="mb-16">
                <p>On affiche le planning mensuel des employés.</p>
                <p>
                    La validation des horaires se fait à la semaine ( autre
                    controller).
                </p>
                <p>
                    ç'est l'occasion de tester si le CalendarComponent est bien
                    modulable
                </p>
            </v-alert>

            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />

            <PreDebug title="workers">{{ workers }} </PreDebug>

            <v-row>
                <v-col>
                    <PreDebug :show="true" title="working_ranges">{{
                        working_ranges
                    }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug :show="true" title="dates">{{ dates }}</PreDebug>
                </v-col>
            </v-row>

            <Calendar
                :dates="dates"
                :showInfo="true"
                :showDates="false"
                :selectedDates="[]"
            >
                <template #calendarHeaderTitle="{}">
                    {{ toFormat(dates?.current, "MMMM yyyy") }}
                </template>

                <template #calendarHeaderDay="{ date, column }">
                    {{ date.labelLong[0] }}
                    <v-divider />
                    {{ date.label }}
                </template>

                <template
                    #calendarRow="{
                        range,
                        currentDates,
                        indexWeek,
                        info,
                        showInfo,
                    }"
                >
                    <CalendarRow
                        :currentRange="range"
                        :indexRow="indexWeek"
                        :showInfo="showInfo"
                    >
                        <template v-slot:info="{}"> </template>

                        <template v-slot:cell="{ date, row, column }">
                            <div class="block w-100 text-center">
                                <div class="text-uppercase text-h5">
                                    {{ date.labelLong[0] }}
                                </div>
                                <div class="block my-2 text-info">
                                    {{ date.label }}
                                </div>
                            </div>
                        </template>
                    </CalendarRow>

                    <template
                        v-for="(worker, workerIndex) in workers"
                        :key="`row-${indexWeek}-${workerIndex}`"
                    >
                        <!-- <tr>
                            <td colspan="8">
                                <v-row>
                                    <v-col>
                                        <PreDebug
                                            :show="true"
                                            title="dates range"
                                            >{{ range }}
                                        </PreDebug>
                                    </v-col>
                                    <v-col>
                                        <PreDebug
                                            :show="true"
                                            title="currentDates range"
                                            >{{ currentDates }}
                                        </PreDebug>
                                    </v-col>
                                    <v-col>
                                        <PreDebug :show="true" title="worker"
                                            >{{ worker }}
                                        </PreDebug>
                                    </v-col>
                                    <v-col>
                                        <PreDebug
                                            :show="true"
                                            title="working_ranges for worker"
                                            >{{ working_ranges[worker?.id] }}
                                        </PreDebug>
                                    </v-col>
                                    <v-col>
                                        <PreDebug
                                            :show="true"
                                            title="mergedDateRange With WorkerWorkingRanges"
                                        >
                                            {{
                                                mergedDateRangeWithWorkerWorkingRanges(
                                                    range,
                                                    worker
                                                )
                                            }}
                                        </PreDebug>
                                    </v-col>
                                </v-row>
                            </td>
                        </tr> -->

                        <CalendarRow
                            :currentRange="range"
                            :indexRow="indexWeek"
                            :showInfo="showInfo"
                            :showCellLabel="true"
                        >
                            <template v-slot:info="{}">
                                <div style="max-width: 8rem">
                                    <div
                                        class="text-h6 text-wrap text-truncate text-capitalize mb-1"
                                    >
                                        <p>{{ worker?.name }}</p>
                                    </div>
                                </div>
                                <v-btn
                                    color="primary"
                                    :to="
                                        route(
                                            'manager.plannings.workers.show',
                                            {
                                                worker: worker,
                                            }
                                        )
                                    "
                                >
                                    {{ __("show calendar") }}
                                </v-btn>
                            </template>

                            <template v-slot:cell="{ date, row, column }">
                                <div
                                    class="d-flex flex-column w-100 align-center"
                                >
                                    <WorkingRangeList
                                        v-for="range in workingRangesForWorkerAtDate(
                                            worker,
                                            date?.labelData
                                        )"
                                        :range="range"
                                        :show="{
                                            completed: true,
                                            validation: true,
                                        }"
                                    >
                                        <template v-slot:title="{ range }">
                                            {{
                                                range.sale_point?.name ||
                                                range.sale_point_id
                                            }}
                                        </template>
                                    </WorkingRangeList>
                                </div>
                            </template>
                        </CalendarRow>
                    </template>
                </template>
            </Calendar>
        </v-container>
    </ManagerLayout>
</template>
