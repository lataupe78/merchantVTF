<script setup>
import { ref, watch, computed, nextTick, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import Calendar from "@/Components/Calendar/CalendarMain.vue";
import CalendarRow from "@/Components/Calendar/CalendarRow.vue";
import WorkingRangeSelector from "@/Components/Calendar/WorkingRangeSelector.vue";
import WorkingRangeDisplay from "@/Components/Calendar/WorkingRangeDisplay.vue";

const props = defineProps({
    worker: { type: Object, required: true },

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
                workers: [],
                sale_points: [],
            };
        },
    },
});

const prevNavLink = {
    url: route("manager.plannings.calendar.show", {
        worker: props.worker,
        month: toFormat(props.dates?.prev, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.prev, "MMMM yyyy"),
};
const nextNavLink = {
    url: route("manager.plannings.calendar.show", {
        worker: props.worker,
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
</script>

<template>
    <ManagerLayout
        :title="__('plannings') + ' ' + toFormat(dates?.current, 'MMMM yyyy')"
    >
        <v-container>
            <v-alert type="info" variant="tonal" class="my-10">
                <h3>
                    2023-11-21 : Vuetify Calendar n'étant pas finalisé on
                    continue avec mon CalendarComponent
                </h3>
            </v-alert>
            <v-alert type="success" variant="tonal" class="my-10">
                <h3 class="my-8">
                    2023-11-22 : ça fonctionne pas mal apparement, on arrive à
                    creér des ranges !!!
                </h3>
                <h3 class="my-8">
                    Next Step : migrer la logique de gestion de selection des
                    ranges dans le CalendarMain :
                </h3>
                <h2 class="my-8">[sauvegarder avant]</h2>
                <p>
                    Et oui actuellemnent ç'est dur de parser les selectedDates
                    car on se base sur le labelData ( ç'est plus simple / il y a
                    eu un problème alors que je passais le date entier j'ai
                    oublié :( )
                </p>
                <h4>
                    Il faut intégrer une limite ( outrepassable bien sûr ) un
                    seul horaire par jour
                </h4>
            </v-alert>

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

            <WorkingRangeSelector
                v-if="hasSelectedDates"
                :dates="selectedDates"
                :worker="worker"
                :sale_points="options.sale_points"
            />

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
                    >
                        <template v-slot:info="{ index, range }">
                            <div class="text-body-1 text-wrap mb-3">
                                {{
                                    `Sem. ${range[0].year}-${range[0].weekNumber}`
                                }}
                            </div>
                            <span
                                class="text-body-2 text-wrap text-wrap text-truncate text-capitalize"
                                :title="worker?.name"
                            >
                                {{ worker.name }}

                                <v-checkbox
                                    @change="toggleRow($event, index)"
                                />
                            </span>
                        </template>

                        <template v-slot:cell="{ date, row, column }">
                            <div class="d-flex flex-column w-100 align-center">
                                <!-- <div>selected : {{ date.selected }}</div> -->
                                <div
                                    class="d-flex justify-space-around align-center"
                                >
                                    <WorkingRangeDisplay
                                        v-for="range in date?.ranges"
                                        :range="range"
                                    />
                                </div>
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
