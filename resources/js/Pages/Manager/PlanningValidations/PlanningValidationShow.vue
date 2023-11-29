<script setup>
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import { Link } from "@inertiajs/vue3";
import { dateLabel, toFormat } from "@/helpers/dates.js";

import NavPeriod from "@/Components/Navs/NavPeriod.vue";

//  import PlanningValidationDay from "@/Components/Plannings/Validation/Day.vue";
import PlanningValidationDay from "@/Components/Plannings/Validation/DayValidation.vue";

defineProps({
    dates: [Object, String],
    days: [Array, Object], // punchings  by worker
    ranges: [Array, Object],
    // punchings: [Array, Object],
});

const actionLinks = [
    {
        label: "plannings Dashboard",
        icon: "mdi-chart-line",
        url: route("manager.plannings.dashboard"),
    },
    {
        label: "Validation Index",
        icon: "mdi-chart-line",
        url: route("manager.plannings.validation.index"),
        color : 'orange',
    },
];
</script>
<template>
    <ManagerLayout
        :title="
            __('schedule_validation_day', {
                day: dateLabel(dates?.current, 'DATE_MED_WITH_WEEKDAY'),
            })
        "
        :action-links="actionLinks"
    >
        <v-container>
            <v-row>
                <v-col>
                    <PreDebug title="dates" :show="true">{{ dates }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="ranges" :show="true">{{
                        ranges
                    }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="days" :show="true">{{ days }}</PreDebug>
                </v-col>
            </v-row>

            <NavPeriod
                :prev="{
                    route: route('manager.plannings.validation.show', {
                        day: toFormat(dates?.prev, 'yyyy-MM-dd'),
                    }),
                    label: toFormat(dates?.prev, 'DDDD'),
                }"
                :next="{
                    route: route('manager.plannings.validation.show', {
                        day: toFormat(dates?.next, 'yyyy-MM-dd'),
                    }),
                    label: toFormat(dates?.next, 'DDDD'),
                }"
            />

            <template v-if="ranges.length < 1">
                <v-alert type="error">
                    Il n'y a pas d'horaires prévu ce jour
                </v-alert>
            </template>

            <template v-for="(day, dayIndex) in days" :key="`day-${dayIndex}`">
                <pre>{{ dayIndex }}</pre>
                <!-- <pre class="text-danger">{{day}}</pre> -->

                <div class="row mb-5">
                    <PlanningValidationDay
                        v-for="(user, userIndex) in day"
                        :key="`day-${dayIndex}-worker-${userIndex}`"
                        :user="user"
                        :dayIndex="dayIndex"
                        :userIndex="userIndex"
                    />
                </div>
            </template>

            <v-row>
                <v-col>
                    <PreDebug title="users">{{ users }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="ranges">{{ ranges }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="punchings">{{ punchings }}</PreDebug>
                </v-col>
            </v-row>
        </v-container>
    </ManagerLayout>
</template>
