<script setup>
import { ref, computed } from "vue";
import { getDateTime, toFormat } from "@/helpers/dates.js";
import { Duration } from "luxon";

import WorkingRangeValidationForm from "@/Components/Plannings/Validation/WorkingRangeValidationForm.vue";

const props = defineProps({
    user: {
        type: [Array, Object],
        default() {
            return [];
        },
        required: true,
    },
    dayIndex: {
        type: [Number, String],
    },
    userIndex: {
        type: [Number, String],
    },
});

const UserRanges = computed(() => {
    let ranges = Array.from(props.user);
    // console.log({ ranges, length: ranges?.length })
    return ranges.filter((range) => {
        // console.log({ range, validated_at: range?.validated_at })
        return range.hasOwnProperty("validated_at") === true;
    });
});

const UserPunchings = computed(() => {
    let ranges = Array.from(props.user);
    // console.log({ ranges, length: ranges?.length })
    return ranges.filter((range) => {
        // console.log({ range, punched_at: range?.punched_at })
        return range.hasOwnProperty("punched_at") === true;
    });
});

const punchingsDuration = computed(() => {
    let totalDuration = Duration.fromObject({ hours: 0, minutes: 0 });

    console.log({
        action: "parse punchings",
        UserPunchings,
        punchings: UserPunchings.value.length,
    });

    if (UserPunchings.value.length > 0 && UserPunchings.value.length % 2 == 0) {
        for (let i = 0; i < UserPunchings.value.length; i = i + 2) {
            let dateStart = getDateTime(UserPunchings.value?.[i].punched_at);
            let dateEnd = getDateTime(UserPunchings.value?.[i + 1].punched_at);
            console.log({
                dateStart,
                dateEnd,
            });
            if (dateEnd?.isValid === true && dateStart?.isValid === true) {
                let punchingsDuration = dateEnd.diff(dateStart, [
                    "hours",
                    "minutes",
                ]);

                totalDuration = totalDuration.plus(punchingsDuration);
            }
        }

        console.log({ totalDuration });
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
    <v-card :title="user[0]?.worker?.name" class="px-0">
        <v-table fixed-header height="64vh" density="comfortable">
            <thead class="text-uppercase text-h5">
                <tr>
                    <th>{{ __("punchings") }}</th>
                    <th>{{ __("planned") }}</th>
                    <th>{{ __("validation") }}</th>
                </tr>
            </thead>

            <tbody>
                <template v-if="UserRanges.length < 1">
                    <WorkingRangeValidationForm
                        :worker_id="userIndex"
                        :range="{}"
                        :punchings="UserPunchings"
                        @updateDuration="updateValidatedDuration"
                        v-if="UserRanges.length < 1"
                    />
                </template>
                <template
                    v-else
                    v-for="(range, rangeIndex) in UserRanges"
                    :key="`range-${rangeIndex}`"
                >
                    <WorkingRangeValidationForm
                        :worker_id="userIndex"
                        :range="range"
                        :punchings="UserPunchings"
                        @updateDuration="updateValidatedDuration"
                    />
                </template>
            </tbody>
        </v-table>
    </v-card>
</template>
