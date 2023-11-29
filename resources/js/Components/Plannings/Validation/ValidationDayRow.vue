<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import { getDateTime, toFormat } from "@/helpers/dates.js";
import { Duration } from "luxon";

import BoolLabel from "@/Components/BoolLabel.vue";

import ValidationDayForm from "@/Components/Plannings/Validation/ValidationDayForm.vue";

// import WorkingRangeValidationForm from "@/Components/Plannings/Validation/WorkingRangeValidationForm.vue";

const props = defineProps({
    user: {
        type: [Array, Object],
        default() {
            return [];
        },
        required: true,
    },
    currentDate: {
        type: String,
        required: true,
    },

    userIndex: {
        type: [Number, String],
    },
});

let form = useForm({
    ranges: [],
});
onMounted(() => {
    populateForm();
});

const populateForm = () => {
    // if (props.user?.working_ranges == undefined || props.user.);

    props.user?.working_ranges.forEach((range, indexRange) => {
        form.ranges.push({
            id: range?.id,
            starts_at: range?.starts_at,
            ends_at: range?.ends_at,
            validated_started_at: range?.validated_started_at,
            validated_ended_at: range?.validated_ended_at,
            index: indexRange,
        });
    });
};

// const UserRanges = computed(() => {
//     let ranges = Array.from(props.user?.working_ranges);
//     // console.log({ ranges, length: ranges?.length })
//     return ranges.filter((range) => {
//         // console.log({ range, validated_at: range?.validated_at })
//         return range.hasOwnProperty("validated_at") === true;
//     });
// });

// const UserPunchings = computed(() => {
//     let ranges = Array.from(props.user?.punchings);
//     // console.log({ ranges, length: ranges?.length })
//     return ranges.filter((range) => {
//         // console.log({ range, punched_at: range?.punched_at })
//         return range.hasOwnProperty("punched_at") === true;
//     });
// });

const punchingsDuration = computed(() => {
    let totalDuration = Duration.fromObject({ hours: 0, minutes: 0 });

    // console.log({
    //     action: "parse punchings",
    //     UserPunchings,
    //     punchings: props.user?.punchings?.length,
    // });

    if (
        props.user?.punchings.length > 0 &&
        props.user?.punchings.length % 2 == 0
    ) {
        for (let i = 0; i < props.user?.punchings.length; i = i + 2) {
            let dateStart = getDateTime(props.user?.punchings?.[i].punched_at);
            let dateEnd = getDateTime(
                props.user?.punchings?.[i + 1].punched_at
            );
            // console.log({
            //     dateStart,
            //     dateEnd,
            // });
            if (dateEnd?.isValid === true && dateStart?.isValid === true) {
                let punchingsDuration = dateEnd.diff(dateStart, [
                    "hours",
                    "minutes",
                ]);

                totalDuration = totalDuration.plus(punchingsDuration);
            }
        }

        // console.log({ totalDuration });
    }
    return totalDuration;
});

const validatedDuration = ref(null);

const updateValidatedDuration = (duration) => {
    console.log("updateValidatedDuration", duration);
    if (duration.value?.isValid) {
        validatedDuration.value = duration.value.toFormat("hh:mm");
    }
};
</script>

<template>
    <!-- <tr>
        <td colspan="4">
            <PreDebug :show="true">{{ form }}</PreDebug>
        </td>
    </tr> -->

    <tr>
        <th
            scope="row"
            style="max-width: 8rem"
            valign="top"
            class="border py-4"
        >
            <div class="text-h6 text-wrap text-truncate text-capitalize mb-1">
                {{ user.name }}
            </div>

            <hr />
            {{ currentDate }}
        </th>

        <!-- PLANNED -->
        <td
            style="min-width: 192px !important"
            valign="top"
            class="border py-4"
        >
            <template v-if="user.working_ranges.length == 0">
                <v-alert type="info" variant="tonal"> No work planned </v-alert>
            </template>
            <template v-else>
                <template
                    v-for="(range, rangeIndex) in user?.working_ranges"
                    :key="`${userIndex}-planned-range-${rangeIndex}`"
                >
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap">
                                {{ __("starts_at") }}
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap text-end">
                                {{ range?.starts_at?.val ?? "---" }}
                            </div>
                        </v-col>
                    </v-row>

                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap">
                                {{ __("ends_at") }}
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap text-end">
                                {{ range?.ends_at?.val ?? "---" }}
                            </div>
                        </v-col>
                    </v-row>
                </template>
            </template>
        </td>

        <!-- PUNCHINGS -->
        <td
            style="min-width: 192px !important"
            valign="top"
            class="border py-4"
        >
            <template v-if="user?.punchings.length == 0">
                <v-alert type="info" variant="tonal"> No Punchings </v-alert>
            </template>
            <template v-else>
                <template
                    v-for="(punching, punchingIndex) in user?.punchings"
                    :key="`punchings-${punchingIndex}`"
                >
                    <v-row no-gutters>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap">
                                {{ punching.punched_at }}
                            </div>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <div class="text-wrap text-end">
                                <BoolLabel
                                    :value="!!(punching?.status === 'in')"
                                    :labels="[__('off'), __('in')]"
                                />
                            </div>
                        </v-col>
                    </v-row>
                </template>

                <div class="text-body-1 font-weight-bold">
                    {{
                        punchingsDuration?.isValid
                            ? punchingsDuration.toFormat("hh:mm")
                            : null
                    }}
                </div>
            </template>
        </td>

        <!-- VALIDATION -->
        <td
            style="min-width: 256px !important"
            valign="top"
            class="border py-4"
        >
            <v-list class=" ">
                <template v-if="user.working_ranges.length == 0">
                    <v-list-item>
                        <ValidationDayForm
                            :currentDate="currentDate"
                            :range="{}"
                            :user="user"
                            :key="`DayValidationForm-user-${user.id}-range-empty`"
                        />
                    </v-list-item>
                </template>
                <template v-else>
                    <v-list-item
                        v-for="(range, rangeIndex) in user?.working_ranges"
                        :key="`list-item-${userIndex}-range-${rangeIndex}`"
                    >
                        <ValidationDayForm
                            :currentDate="currentDate"
                            :range="range"
                            :user="user"
                            :key="`DayValidationForm-user-${user.id}-range-${rangeIndex}`"
                        />
                    </v-list-item>
                </template>
            </v-list>

            <!-- <PreDebug :show="true" title="rangeIndex">{{
                rangeIndex
            }}</PreDebug>
            <PreDebug :show="true" title="form">{{
                form.ranges[rangeIndex]
            }}</PreDebug> -->
        </td>
    </tr>
</template>
