<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import { DateTime } from "luxon";

import { getDateTime, toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import DiffChip from "@/Components/Chips/DateDiffChip.vue";
import MenuActions from "@/Components/Menus/Actions.vue";

const props = defineProps({
    options: {
        type: [Array, Object],
        default() {
            return {
                workers: [],
                sale_points: [],
            };
        },
    },
});

const form = useForm({
    worker_id: null,
    sale_point_id: null,
});

const dateNow = DateTime.now();
const dateFormat = "dd/MM/yyyy"; // DDDD

// on crée un Interval entre le plus ancien et aujourd'hui
// on va passer une props dates à MobileNav de la forme :
// dates: {
//         current: oldestDay,
//         date_start: null,
//         date_end: null,
//   },

const earliestValidationDate = computed(() => {
    let date = Math.min(
        ...props.options.workers.map(
            (worker) =>
                new Date(
                    worker?.latest_validated_working_range?.current_date ?? null
                )
        )
    );
    return DateTime.fromJSDate(date);
});

const actionLinks = [
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
        color: "red",
    },

    // Reports
    {
        label: "plannings.monthly",
        url: route("manager.plannings.period.month"),
        color: "green",
    },
    {
        label: "plannings.weekly",
        url: route("manager.plannings.period.week"),
        color: "green",
    },
    {
        label: "plannings.daily",
        url: route("manager.plannings.period.day"),
        color: "green",
    },
];
</script>
<template>
    <ManagerLayout
        :title="__('plannings.dashboard')"
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" variant="tonal">
                <p>
                    cette vue Planning Dashboard présente les différents types
                    de plannings et les actions
                </p>
            </v-alert>

            <PreDebug :show="true" class="my-10" title="earliestValidationDate">
                {{ earliestValidationDate }}
            </PreDebug>
            <v-card>
                <v-card-text>
                    <v-btn
                        :to="route('manager.plannings.punchings.index')"
                        color="green"
                    >
                        {{
                            __("index_items", {
                                items: __("punchings"),
                            })
                        }}
                    </v-btn>
                </v-card-text>
            </v-card>

            <v-card :title="__('workers')">
                <v-card-text>
                    <v-table fixed-header height="50vh">
                        <thead>
                            <tr>
                                <th
                                    rowspan="2"
                                    class="border text-center"
                                    scope="col"
                                >
                                    <v-icon icon="mdi-dots-vertical" />
                                </th>

                                <th rowspan="2">{{ __("worker") }}</th>
                                <th class="text-center">
                                    {{ __("last punching") }}
                                    <v-tooltip
                                        activator="parent"
                                        location="top"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-icon
                                                icon="mdi-information"
                                                class="ms-2"
                                                v-bind="props"
                                            />
                                        </template>
                                        <span>Pointage le plus récent</span>
                                    </v-tooltip>
                                </th>
                                <th class="text-center">
                                    {{ __("last working_range validated") }}
                                    <v-tooltip
                                        activator="parent"
                                        location="top"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-icon
                                                icon="mdi-information"
                                                class="ms-2"
                                                v-bind="props"
                                            />
                                        </template>
                                        <span
                                            >Horaire vaildé le plus récent</span
                                        >
                                    </v-tooltip>
                                </th>

                                <th class="text-center">
                                    {{ __("last working_range") }}
                                    <v-tooltip
                                        activator="parent"
                                        location="top"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-icon
                                                icon="mdi-information"
                                                class="ms-2"
                                                v-bind="props"
                                            />
                                        </template>
                                        <span
                                            >Horaire planifié le plus
                                            récent</span
                                        >
                                    </v-tooltip>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    <v-btn
                                        :to="
                                            route(
                                                'manager.plannings.punchings.index'
                                            )
                                        "
                                        color="indigo"
                                        prepend-icon="mdi-timer-cog-outline"
                                    >
                                        {{ __("punchings") }}
                                    </v-btn>
                                </th>
                                <th class="text-center">
                                    <v-btn
                                        :to="
                                            route(
                                                'manager.plannings.validation.index'
                                            )
                                        "
                                        color="indigo"
                                        prepend-icon="mdi-calendar-check-outline"
                                    >
                                        {{ __("validation") }}
                                    </v-btn>
                                </th>
                                <th class="text-center">
                                    <v-btn
                                        :to="
                                            route(
                                                'manager.plannings.punchings.index'
                                            )
                                        "
                                        color="indigo"
                                        prepend-icon="mdi-calendar-plus-outline"
                                    >
                                        {{ __("creation") }}
                                    </v-btn>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(worker, index) in options.workers"
                                :key="`row-worker-${index}`"
                            >
                                <!-- ACTIONS -->
                                <td class="border text-center px-0">
                                    <MenuActions
                                        :items="[
                                            {
                                                label: __('show punching'),
                                                url: route(
                                                    'manager.plannings.punchings.index',
                                                    {
                                                        workers_ids: [
                                                            worker.id,
                                                        ],
                                                    }
                                                ),
                                                icon: 'mdi-eye',
                                            },
                                            {
                                                label: __('show calendar'),
                                                url: route(
                                                    'manager.plannings.workers.show',
                                                    {
                                                        worker: worker,
                                                    }
                                                ),
                                                icon: 'mdi-eye',
                                            },
                                        ]"
                                    >
                                    </MenuActions>
                                </td>

                                <td style="max-width: 8rem">
                                    <div
                                        class="text-h6 text-wrap text-truncate text-capitalize mb-1"
                                    >
                                        {{ worker?.name }}
                                    </div>
                                </td>
                                <td class="border">
                                    <div class="d-flex align-center w-100">
                                        {{
                                            toFormat(
                                                worker?.latest_punching
                                                    ?.current_date,
                                                dateFormat
                                            )
                                        }}

                                        <DiffChip
                                            :start="dateNow"
                                            :end="
                                                worker?.latest_punching
                                                    ?.current_date ?? null
                                            "
                                            class="ms-auto"
                                        />
                                    </div>
                                </td>
                                <td class="border">
                                    <div class="d-flex align-center w-100">
                                        {{
                                            toFormat(
                                                worker
                                                    ?.latest_validated_working_range
                                                    ?.current_date,
                                                dateFormat
                                            )
                                        }}

                                        <DiffChip
                                            :start="dateNow"
                                            :end="
                                                worker
                                                    ?.latest_validated_working_range
                                                    ?.current_date
                                            "
                                            class="ms-auto"
                                        />
                                    </div>
                                </td>
                                <td class="border">
                                    <div class="d-flex align-center w-100">
                                        {{
                                            toFormat(
                                                worker?.latest_working_range
                                                    ?.current_date,
                                                dateFormat
                                            )
                                        }}

                                        <DiffChip
                                            :start="dateNow"
                                            :end="
                                                worker?.latest_working_range
                                                    ?.current_date ?? null
                                            "
                                            class="ms-auto"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-card-text>
            </v-card>

            <v-card>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-select
                                v-model="form.worker_id"
                                :error-messages="form.errors.worker_id"
                                :label="__('worker')"
                                :items="options.workers"
                                item-title="name"
                                item-value="id"
                            />
                        </v-col>
                        <v-col>
                            <v-select
                                v-model="form.sale_point_id"
                                :error-messages="form.errors.sale_point_id"
                                :label="__('sale_point')"
                                :items="options.sale_points"
                                item-title="name"
                                item-value="id"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </ManagerLayout>
</template>
