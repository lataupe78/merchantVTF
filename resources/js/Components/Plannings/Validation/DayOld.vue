<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FormInput from "@/Components/Form/Input.vue";
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

const processing = ref(false);
// const rangeId = props.range?.id

// const submitForm = () => {
//   processing.value = true;
//   form.put(route('manager.plannings.validation.update', { rangeId: rangeId }), {
//     onFinish: () => {
//       processing.value = false
//     }
//   });
// }

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
    <!-- <pre class="text-danger">user {{user}}</pre> -->
    <table class="table table-bordered caption-top mb-5">
        <caption class="h3 text-info">
            {{
                user[0]?.worker?.name
            }}
        </caption>
        <thead>
            <tr>
                <th>{{ __("planned") }}</th>
                <th>{{ __("punchings") }}</th>
                <th>{{ __("validation") }}</th>
            </tr>
        </thead>
        <tbody>
            <WorkingRangeValidationForm
                :worker_id="userIndex"
                :range="{}"
                :punchings="UserPunchings"
                @updateDuration="updateValidatedDuration"
                v-if="UserRanges.length < 1"
            />
            <template v-else>
                <template
                    v-for="(range, rangeIndex) in UserRanges"
                    :key="`range-${rangeIndex}`"
                >
                    <!-- PUNCHINGS -->

                    <WorkingRangeValidationForm
                        :worker_id="userIndex"
                        :range="range"
                        :punchings="UserPunchings"
                        @updateDuration="updateValidatedDuration"
                    />
                </template>
            </template>

            <!-- <tr>
        <td>
          <pre class="text-info"><h5>UserPunchings</h5>  {{UserPunchings}}</pre>
        </td>
        <td>

          <pre class="text-info"><h5>UserRanges</h5>  {{UserRanges}}</pre>
        </td>
        <td></td>
      </tr> -->
        </tbody>

        <tfoot>
            <tr>
                <th class="text-center" colspan="1">
                    {{
                        punchingsDuration?.isValid
                            ? punchingsDuration.toFormat("hh:mm")
                            : null
                    }}
                </th>

                <th class="text-center">
                    {{
                        Duration.fromObject({
                            minute: UserRanges.reduce((acc, range) => {
                                return acc + range?.duration_minutes;
                            }, 0),
                        }).toFormat("hh:mm")
                    }}
                </th>

                <th class="text-center">{{ validatedDuration }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- <pre><h5>UserRanges</h5>  {{UserRanges}}</pre> -->
    <!-- <pre class="text-warning">{{user}}</pre> -->
</template>
