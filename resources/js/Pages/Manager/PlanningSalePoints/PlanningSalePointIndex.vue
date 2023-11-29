<script setup>
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import Calendar from "@/Components/Calendar/CalendarMain.vue";
import CalendarRow from "@/Components/Calendar/CalendarRow.vue";
import WorkingRangeList from "@/Components/Calendar/WorkingRangeList.vue";

const props = defineProps({
    sale_points: {
        type: [Array, Object],
        default() {
            return [];
        },
    },
    // les ranges sont triées par date croissante et groupée par sale_point
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

const actionLinks = [
    {
        label: "plannings.dashboard",
        url: route("manager.plannings.dashboard"),
        color: "orange",
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

const workingRangesForSalePointAtDate = (worker, labelData) => {
    let workingRangeForSalePoint = props.working_ranges?.[worker?.id];

    if (
        workingRangeForSalePoint == undefined ||
        workingRangeForSalePoint.length < 1
    ) {
        return [];
    }

    let workingRanges = props.working_ranges[worker.id].filter((range) => {
        return range?.current_date == labelData;
    });
    return workingRanges;
};
</script>
<template>
    <ManagerLayout
        :title="
            __('plannings.sale_points_workers') +
            ' ' +
            toFormat(dates?.current, 'MMMM yyyy')
        "
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" class="mb-16">
                <p>On affiche le planning de chaque point de vente.</p>
                <p>
                    La validation des horaires se fait à la semaine ( autre
                    controller).
                </p>
            </v-alert>

            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />

            <PreDebug title="sale_points">{{ sale_points }} </PreDebug>

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

                <template
                    #calendarRow="{
                        range,
                        currentDates,
                        indexWeek,
                        info,
                        showInfo,
                    }"
                >
                    <template
                        v-for="(sale_point, salePointIndex) in sale_points"
                        :key="`row-${indexWeek}-${salePointIndex}`"
                    >
                        <CalendarRow
                            :currentRange="range"
                            :indexRow="indexWeek"
                            :showInfo="showInfo"
                            :showCellLabel="true"
                        >
                            <template v-slot:info="{}">
                                <div class="d-flex flex-column flex-wrap w-100">
                                    <p>{{ sale_point?.name }}</p>

                                    <v-btn
                                        color="primary"
                                        :to="
                                            route(
                                                'manager.plannings.sale_points.show',
                                                {
                                                    sale_point: sale_point,
                                                }
                                            )
                                        "
                                    >
                                    </v-btn>
                                </div>
                            </template>

                            <template v-slot:cell="{ date, row, column }">
                                <div
                                    class="d-flex flex-column w-100 align-center"
                                >
                                    <WorkingRangeList
                                        v-for="range in workingRangesForSalePointAtDate(
                                            sale_point,
                                            date?.labelData
                                        )"
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
                            </template>
                        </CalendarRow>
                    </template>
                </template>
            </Calendar>
        </v-container>
    </ManagerLayout>
</template>
