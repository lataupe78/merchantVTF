<script setup>
import { ref, watch, computed, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import PlanningFilters from "./_FiltersPlanning.vue";

import useCalendar from "@/composables/useCalendar.js";



import SchedulerEvent from "@/Components/Plannings/SchedulerEvent.vue";



import CalendarHeader from "@/Components/Calendar/CalendarHeader.vue";
import CalendarCell from "@/Components/Calendar/CalendarCell.vue";
import CalendarDialog from "@/Components/Calendar/CalendarDialog.vue";

const props = defineProps({
    sale_points: { type: [Array, Object], required: true },

    workers: { type: [Array, Object], required: true },

    working_ranges: { type: [Array, Object], required: true },

    filters: { type: [Array, Object] },

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

const { rangeDates, weekCount, currentRange } = useCalendar(props, "month");

const prevNavLink = {
    url: route("manager.plannings.calendar.index", {
        month: toFormat(props.dates?.prev, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.prev, "MMMM yyyy"),
};
const nextNavLink = {
    url: route("manager.plannings.calendar.index", {
        month: toFormat(props.dates?.next, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.next, "MMMM yyyy"),
};

const selectedRange = ref(null);
const selectedWorker = ref(null);
const openDialog = ref(false);

const openModal = ({ range, worker }) => {
    console.log({ range });
    selectedRange.value = range;
    selectedWorker.value = worker;
    nextTick().then(() => {
        openDialog.value = true;
    });
};

const resetCalendarDialog = () => {
    openDialog.value = false;
    selectedRange.value = {};
    selectedWorker.value = {};
};
const sellerCellWidth = "160px";

const params = ref(props?.filters ?? []);

watch(
    params,
    (newParams) => {
        console.log("watching params", newParams);

        router.get(route("manager.plannings.calendar.index"), newParams, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            onFinish: (visit) => {
                // processing.value = false;
            },
            onError(error) {
                console.error(error);
                // debugger;
            },
        });
    },
    {
        deep: true,
        immediate: false,
    }
);

const updateCurrentFilters = (filters) => {
    console.log("updateCurrentFilters", { filters: filters });
    params.value = { ...params.value, ...filters };
};

const filteredWorkers = computed(() => {
    // urlParams.forEach((value, key) => {
    //     if (props.allowedFilters.includes(key) == false || value == "") {
    //         keysForDel.push(key);
    //     }
    // });

    if (params.value?.workers_ids && params.value.workers_ids?.length > 0) {
        return props.workers.filter((worker) => {
            return params.value.workers_ids.includes(worker.id);
        });
    }
    return props.workers;
});

const actionLinks = [
    {
        url: route("manager.plannings.dashboard", {}),
        label: "plannings.dashboard",
        color: "orange",
    },
    {
        url: route("manager.plannings.workers.index", {}),
        label: "calendar.workers",
    },
    {
        url: route("manager.plannings.sale_points.index", {}),
        label: "calendar.sale_points",
    },
    {
        url: route("manager.plannings.calendar.index", {}),
        label: "calendar",
    },
];
</script>

<template>
    <ManagerLayout
        :title="__('plannings') + ' ' + toFormat(dates?.current, 'MMMM yyyy')"
        :action-links="actionLinks"
    >
        <v-container>
            <v-row>
                <v-col>
                    <PreDebug>filteredWorkers {{ filteredWorkers }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug>selectedRange {{ selectedRange }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug>selectedWorker {{ selectedWorker }}</PreDebug>
                </v-col>
                <v-col>
                    <PreDebug>openDialog {{ openDialog }}</PreDebug>
                </v-col>
            </v-row>

            <CalendarDialog
                :worker="selectedWorker"
                :range="selectedRange"
                :options="{
                    sale_points: sale_points,
                    workers: workers,
                }"
                :open.sync="openDialog"
                @close-modal="resetCalendarDialog"
            />

            <v-alert color="blue-accent-2"  class="my-10">
                <h3>ça marche :)</h3>
                <p>
                    J'ai ajouté des filtres ( par workers ) pour alléger les
                    requêtes lors du dev
                </p>
                <hr class="my-8" />
                <h3>Pour info</h3>
                <p>
                    La logique des Working Ranges affichées se situe dans
                    <strong>Manager\PlanningController @index</strong>
                </p>

                <p>
                    Depuis CalendarDialog on appelle les methodes @store et
                    @update de <strong>Manager\WorkingRangeController </strong>,
                    voir la validation depuis les FormRequests
                    <strong
                        >StoreWorkingRangeRequest et
                        UpdateWorkingRangeRequest</strong
                    >
                </p>

                <hr class="my-8" />

                <p><strong>2023-11-26 :</strong></p>
                <ul>
                    <li>
                        il faut généraliser à l'utlisation de CalendarRow et des
                        slots associés (
                        <strong>infos , cell</strong> ->
                        <i> check better names</i> ) lors de l'affichage d'un
                        Calendar.
                    </li>
                    <li>
                        Et bien utiliser form reactive car le useForm de Inertia
                        ne gère pas les nested keys que j'utilise notamment pour
                        les champs date / time. -> voir le Component
                        ValidationDayForm
                    </li>
                </ul>

                <hr class="my-8" />

                <p><strong>2023-11-30 :</strong></p>
                <p>Implémentation de Scheduler recurrent Events</p>

                <p>A tester pour créer les occurrences :</p>

                <ul>
                    <li>
                        https://jkbrzt.github.io/rrule/ ( version light de
                        rschedule )
                    </li>

                    <li>https://gitlab.com/john.carroll.p/rschedule</li>

                    <li>
                        https://codesandbox.io/p/sandbox/rschedule-starter-pxezu?file=%2Ftsconfig.json
                    </li>
                </ul>
            </v-alert>

            <PlanningFiltersำำำ
                :filters="filters"
                :options="{ sale_points, workers }"
                @filters-updated="updateCurrentFilters"
            />

            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />


            <SchedulerEvent />


            <v-card :title="__('plannings')" class="mb-5">
                <template v-if="workers.length < 1">
                    <v-card-text>
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </v-card-text>
                </template>
                <template v-else>
                    <template
                        v-for="(week, indexWeek) of weekCount"
                        :key="`week-${indexWeek}`"
                    >
                        <!-- WEEK_TABLE -->
                        <v-table
                            class="table-calendar mb-5"
                            density="comfortable"
                        >
                            <CalendarHeader
                                :rangeDates="rangeDates[indexWeek * 7]"
                                :currentRange="currentRange(indexWeek * 7)"
                            />

                            <tbody>
                                <tr
                                    v-for="(
                                        worker, indexWorker
                                    ) in filteredWorkers"
                                    :key="`week-${indexWeek}-worker-${indexWorker}`"
                                >
                                    <!-- SELLER -->
                                    <th
                                        scope="row"
                                        class="col-info border text-truncate"
                                        :style="{
                                            width: sellerCellWidth,
                                            maxWidth: sellerCellWidth,
                                        }"
                                    >
                                        <span
                                            class="h5 text-wrap text-wrap text-truncate text-capitalize"
                                            :title="worker?.name"
                                        >
                                            {{ worker?.name }}
                                        </span>
                                    </th>

                                    <!-- DAYS -->

                                    <CalendarCell
                                        v-for="date in currentRange(
                                            indexWeek * 7
                                        )"
                                        :key="`week-${indexWeek}-day-${date?.labelData}`"
                                        :date="date"
                                        :worker="worker"
                                        @edit="openModal"
                                    />
                                </tr>
                            </tbody>
                        </v-table>
                    </template>
                </template>
            </v-card>

            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />
        </v-container>

        <!-- DEBUG -->
        <v-row>
            <v-col>
                <PreDebug title="workers">{{ workers }}</PreDebug>
            </v-col>
            <v-col>
                <PreDebug title="sale_points">{{ sale_points }}</PreDebug>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <PreDebug title="weekCount">{{ weekCount }}</PreDebug>
            </v-col>
            <v-col>
                <PreDebug title="working_ranges">{{ working_ranges }}</PreDebug>
            </v-col>
            <v-col>
                <PreDebug title="dates">{{ dates }}</PreDebug>
            </v-col>
        </v-row>
    </ManagerLayout>
</template>

<style>
.calendar-cell.is-active {
    border: 2px solid rgb(var(--v-theme-primary));
}
</style>
