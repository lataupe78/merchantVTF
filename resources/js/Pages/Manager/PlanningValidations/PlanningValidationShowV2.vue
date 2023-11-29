<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
import { dateLabel, toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

import ValidationDayRow from "@/Components/Plannings/Validation/ValidationDayRow.vue";
import FiltersValidation from "./_FiltersValidation.vue";
const props = defineProps({
    users: [Array, Object],
    dates: [Array, Object],
    filters: { type: [Array, Object], required: true },
    options: {
        type: [Array, Object],
        default() {
            return {
                workers: [],
            };
        },
    },
});

const params = ref({ ...props.filters });

const updateCurrentFilters = (filters) => {
    console.log("updateCurrentFilters", { filters: filters });
    params.value = { ...params.value, ...filters };
};
watch(
    params,
    (newParams) => {
        console.log("watching params", newParams);

        router.get(
            route("manager.plannings.validation.show", {
                day: props.dates.current,
            }),
            newParams,
            {
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
            }
        );
    },
    {
        deep: true,
        immediate: false,
    }
);

// on parcourt les users à la recherche de punchings / de ranges not validated_at
const totalPunchings = computed(() => {
    return props.users.reduce((acc, user) => {
        return acc + user?.punchings?.length ?? 0;
    }, 0);
});

const totalRangesToValidate = computed(() => {
    return props.users.reduce((acc, user) => {
        const totalUserRanges = user?.working_ranges?.length ?? 0;
        if (totalUserRanges == 0) {
            return acc;
        }

        const totalUserUnvalidatedRanges = user.working_ranges.filter(
            (range, indexRange) => {
                return range?.validated_at ?? null === null;
            }
        );

        return acc + totalUserUnvalidatedRanges?.length ?? 0;
    }, 0);
});

const actionLinks = [
    {
        label: "plannings Dashboard",
        icon: "mdi-chart-line",
        url: route("manager.plannings.dashboard"),
        color: "orange",
    },
    {
        label: "Validation Index",
        icon: "mdi-chart-line",
        url: route("manager.plannings.validation.index"),
    },
];

const prevLink = props?.dates?.prev
    ? {
          route: route("manager.plannings.validation.show", {
              day: toFormat(props?.dates?.prev, "yyyy-MM-dd"),
          }),
          label: toFormat(props?.dates?.prev, "DDDD"),
      }
    : null;
const nextLink = props?.dates?.next
    ? {
          route: route("manager.plannings.validation.show", {
              day: toFormat(props?.dates?.next, "yyyy-MM-dd"),
          }),
          label: toFormat(props?.dates?.next, "DDDD"),
      }
    : null;
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
                    <PreDebug title="users" :show="true">{{ users }}</PreDebug>
                </v-col>
            </v-row>

            <NavPeriod :prev="prevLink" :next="nextLink" />

            <FiltersValidation
                :filters="filters"
                :options="options"
                @filters-updated="updateCurrentFilters"
            />
            <!-- <pre class="text-danger">{{day}}</pre> -->

            <v-card class="mb-16" color="indigo-darken-3">
                <v-card-title>
                    {{
                        __("workers validation") +
                        " - " +
                        toFormat(dates.current, "DDDD")
                    }}
                </v-card-title>
                <v-card-subtitle>
                    {{ totalPunchings }} punchings -
                    {{ totalRangesToValidate }} ranges to validate
                </v-card-subtitle>
                <v-table fixed-header :hover="true" density="compact">
                    <thead class="text-uppercase font-weight-bold">
                        <tr>
                            <th class="border py-5">{{ __("worker") }}</th>
                            <th class="border py-5">{{ __("planned") }}</th>
                            <th class="border py-5">{{ __("punchings") }}</th>
                            <th class="border py-5">{{ __("validation") }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <ValidationDayRow
                            v-for="(user, userIndex) in users"
                            :key="`validation-day-worker-${userIndex}`"
                            :user="user"
                            :userIndex="userIndex"
                            :currentDate="dates?.current"
                        />
                    </tbody>
                </v-table>
            </v-card>
        </v-container>
    </ManagerLayout>
</template>
