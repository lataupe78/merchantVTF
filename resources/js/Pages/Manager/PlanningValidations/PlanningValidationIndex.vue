<script setup>
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import { toFormat, dateLabel } from "@/helpers/dates.js";
const props = defineProps({
    ranges: { type: [Array, Object], required: true },
    filters: { type: [Array, Object] },
});

const actionLinks = [
    {
        label: "plannings Dashboard",
        icon: "mdi-chart-line",
        url: route("manager.plannings.dashboard"),
        color: "orange",
    },
];
</script>
<template>
    <ManagerLayout
        :title="__('schedule_validation')"
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" variant="tonal" class="mb-8">
                <p>Voici la liste des jours avec des pointages non validés</p>
            </v-alert>

            <v-sheet>
                <v-card class="mb-16" color="indigo-darken-3">
                    <v-card-title>
                        Liste des pointages non validés
                    </v-card-title>
                    <v-card-subtitle>
                        <div class="text-info">
                            {{
                                trans_choice(
                                    "pagination_items_count",
                                    Object.values(ranges).length || 0,
                                    { item: __("day"), items: __("days") }
                                )
                            }}
                        </div>
                    </v-card-subtitle>

                    <v-card-text>
                        <template v-if="ranges.length < 1">
                            <v-alert type="info" variant="outlined">
                                {{ __("no results") }}
                            </v-alert>
                        </template>
                        <template v-else>
                            <v-table fixed-header :hover="true" height="50vh">
                                <thead>
                                    <tr>
                                        <th>{{ __("date") }}</th>
                                        <th>{{ __("count") }}</th>
                                        <th>{{ __("actions") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(dayRanges, dayIndex) in ranges"
                                        :key="dayIndex"
                                    >
                                        <td>
                                            <span class="date d-block">{{
                                                dateLabel(
                                                    dayIndex,
                                                    "DATE_MED_WITH_WEEKDAY"
                                                )
                                            }}</span>
                                        </td>
                                        <td>{{ dayRanges?.length }}</td>
                                        <td class="actions text-end">
                                            <v-btn
                                                color="primary"
                                                :to="
                                                    route(
                                                        'manager.plannings.validation.show',
                                                        {
                                                            day: dayIndex,
                                                        }
                                                    )
                                                "
                                                :text="__('show')"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </template>
                    </v-card-text>
                </v-card>
            </v-sheet>
            <PreDebug title="ranges">{{ ranges }}</PreDebug>
        </v-container>
    </ManagerLayout>
</template>
