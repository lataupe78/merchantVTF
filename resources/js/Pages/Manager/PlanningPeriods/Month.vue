<script setup> 
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";
import PlanningPeriodTable from "@/Components/Plannings/PlanningPeriodTable.vue";
import { toFormat } from "@/helpers/dates.js";

const props = defineProps({
    totals_by_day: [Array, Object],

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

const actionLinks = [
    {
        label: "plannings Dashboard",
        url: route("manager.plannings.dashboard"),
        color : 'orange',
    },
    {
        label: "Calendar Month",
        url: route("manager.plannings.calendar.index"),
    },
    {
        label: "plannings.monthly",
        url: route("manager.plannings.period.month"),
        color : 'green',
    },
    {
        label: "plannings.weekly",
        url: route("manager.plannings.period.week"),
        color : 'green',
    },
    {
        label: "plannings.daily",
        url: route("manager.plannings.period.day"),
        color : 'green',
    },
];
</script>

<template>
    <ManagerLayout
        :title="
            __('plannings.monthly') +
            ' ' +
            toFormat(dates?.current, 'MMMM yyyy')
        "
        :action-links="actionLinks"
    >
        <v-container>
            <!-- NAV -->
            <NavPeriod
                :prev="{
                    url: route('manager.plannings.period.month', {
                        month: toFormat(dates?.prev, 'yyyy-MM'),
                    }),
                    label: toFormat(dates?.prev, 'MMMM yyyy'),
                }"
                :next="{
                    url: route('manager.plannings.period.month', {
                        month: toFormat(dates?.next, 'yyyy-MM'),
                    }),
                    label: toFormat(dates?.next, 'MMMM yyyy'),
                }"
            />

 

            <PreDebug title="dates">
                {{ dates }}
            </PreDebug>

            <PlanningPeriodTable :totals="totals_by_day" />

            <pre>{{ totals_by_day }}</pre>
        </v-container>
    </ManagerLayout>
</template>
