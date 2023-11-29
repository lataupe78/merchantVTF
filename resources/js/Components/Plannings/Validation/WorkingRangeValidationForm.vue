<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import { getDateTime, toFormat } from "@/helpers/dates.js";

import { Duration } from "luxon";
const emit = defineEmits(["updateDuration"]);
import BoolLabel from "@/Components/BoolLabel.vue";

const props = defineProps({
    range: {
        type: Object,
        required: true,
    },
    punchings: {
        type: Object,
        required: true,
    },
    worker_id: {
        type: [Number, String],
        required: true,
    },
});

const processing = ref(false);
const rangeId = props.range?.id;

let form = useForm({});
form.current_date =
    props.range?.current_date || props.punchings[0]?.current_date || null;
form.worker_id = props.worker_id;

if (rangeId !== null) {
    form.starts_at =
        props.range?.starts_at || {
            date: props.range?.starts_at?.date || form?.current_date || null,
            time: props.range?.starts_at?.time || null,
        } ||
        null;

    form.ends_at =
        props.range?.ends_at || {
            date: props.range?.ends_at?.date || form?.current_date || null,
            time: props.range?.ends_at?.time || null,
        } ||
        null;

    form.validated_started_at =
        props.range?.validated_started_at || {
            date: props.range?.started_at?.date || form?.current_date || null,
            time: props.range?.started_at?.time || null,
            val: props.range?.started_at?.val || null,
        } ||
        null;
    form.validated_ended_at =
        props.range?.validated_ended_at || {
            date: props.range?.ended_at?.date || form?.current_date || null,
            time: props.range?.ended_at?.time || null,
            val: props.range?.ended_at?.val || null,
        } ||
        null;
} else {
    form.starts_at = {
        date: null,
        time: null,
    };
    form.ends_at = {
        date: null,
        time: null,
    };

    form.validated_started_at = {
        date: form?.current_date || null,
        time: null,
    };
    form.validated_ended_at = {
        date: form?.current_date || null,
        time: null,
    };
}

/**
 * rebind dynamic fields to Inertia form
 * otherwise they are passed to the request ( weird )
 * https://stackoverflow.com/questions/75801272/inertia-dynamically-add-items-to-useform
 *
 */
const dynamicFields = () => {
    let data = {};

    data[`worker_id`] = form.worker_id;
    data[`current_date`] = form.current_date;
    data[`validated_started_at`] = form.validated_started_at;
    data[`validated_ended_at`] = form.validated_ended_at;
    // [`field_${x}`] = form[`field_${x}`]
    console.log({ data });
    return data;
};

const submitForm = () => {
    const formRoute =
        props.range?.id != null || props.range?.id !== undefined
            ? route("manager.plannings.validation.update", { rangeId: rangeId })
            : route("manager.plannings.validation.store");

    const formMethod =
        props.range?.id != null || props.range?.id !== undefined
            ? "put"
            : "post";

    form.transform((data) => ({
        ...data,
        ...dynamicFields(),
        _method: formMethod,
    }));

    // form._method = formMethod

    console.log({ formRoute, formMethod, form: form });
    console.log({ validated_started_at: form.validated_started_at });
    console.log({ validated_ended_at: form.validated_ended_at });

    processing.value = true;

    form.post(formRoute, {
        onFinish: () => {
            processing.value = false;
        },
    });
};

const validatedDuration = computed(() => {
    const dateStart = getDateTime(
        form?.validated_started_at.date + " " + form.validated_started_at.time
    );
    const dateEnd = getDateTime(
        form?.validated_ended_at.date + " " + form.validated_ended_at.time
    );

    let duration = null;
    if (dateEnd?.isValid === true && dateStart?.isValid === true) {
        duration = dateEnd.diff(dateStart, ["hours", "minutes"]);
        // console.log({ duration })
    }
    return duration;
});

watch(
    () => validatedDuration,
    (duration) => {
        emit("updateDuration", duration);
    },
    { deep: true, immediate: true }
);
</script>
<template>
    <tr>
        <!-- PUNCHINGS -->
        <td style="max-width: 20%" valign="top">
            <template v-if="punchings.length == 0">
                <v-alert type="info"> No Punchings </v-alert>
            </template>
            <template v-else>
                <v-list class="mb-8">
                    <v-list-item
                        v-for="(punching, punchingIndex) in punchings"
                        :key="`${rangeIndex}-punching-${punchingIndex}`"
                    >
                        <template v-slot:subtitle>
                            <div class="d-flex flex-wrap text-wrap">
                                {{ punching.punched_at }}
                            </div>
                        </template>
                        <template v-slot:append>
                            <BoolLabel
                                :value="!!(punching?.status === 'in')"
                                :labels="[__('off'), __('in')]"
                            />
                        </template>
                    </v-list-item>
                </v-list>
            </template>
        </td>

        <!-- PLANNED -->
        <td style="max-width: 40%" valign="top">
            <template v-if="punchings.length == 0">
                <v-alert type="info"> No work planned </v-alert>
            </template>
            <template v-else>
                <v-card :title="__('starts_at')">
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="date"
                                :label="__('date')"
                                v-model="form.starts_at.date"
                                :error-messages="form.errors.starts_at?.date"
                                disabled
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="time"
                                :label="__('time')"
                                v-model="form.starts_at.time"
                                :error-messages="form.errors.starts_at?.time"
                                disabled
                            />
                        </v-col>
                    </v-row>
                </v-card>

                <v-card :title="__('ends_at')">
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="date"
                                :label="__('date')"
                                v-model="form.ends_at.date"
                                :error-messages="form.errors.ends_at?.date"
                                disabled
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="time"
                                :label="__('time')"
                                v-model="form.ends_at.time"
                                :error-messages="form.errors.ends_at?.time"
                                disabled
                            />
                        </v-col>
                    </v-row>
                </v-card>
            </template>
        </td>

        <!-- VALIDATION -->
        <td style="max-width: 40%" v-align="top">
            <v-card>
                <!-- STARTED_AT -->
                <v-card-text>
                    <h5>{{ __("started_at") }}</h5>
                    <v-row class="mb-8">
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="date"
                                :label="__('date')"
                                v-model="form.validated_started_at.date"
                                required
                                :error-messages="
                                    form.errors.validated_started_at?.date
                                "
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="time"
                                :label="__('time')"
                                v-model="form.validated_started_at.time"
                                required
                                :error-messages="
                                    form.errors.validated_started_at?.time
                                "
                            />
                        </v-col>
                    </v-row>
                    <pre>{{ form.validated_started_at }}</pre>
                </v-card-text>
                <!-- ENDED_AT -->
                <v-card-text>
                    <h5>{{ __("ended_at") }}</h5>
                    <v-row class="mb-8">
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="date"
                                :label="__('date')"
                                v-model="form.validated_ended_at.date"
                                required
                                :error-messages="
                                    form?.errors?.validated_ended_at?.date
                                "
                            />
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field
                                type="time"
                                :label="__('time')"
                                v-model="form.validated_ended_at.time"
                                required
                                :error-messages="
                                    form?.errors?.validated_ended_at?.time
                                "
                            />
                        </v-col>
                    </v-row>

                    <pre>{{ form.validated_ended_at }}</pre>
                </v-card-text>
                <!-- DEBUG -->
                <v-card-text>
                    <v-row>
                        <v-col>
                            <PreDebug title="rangeId">
                                {{ rangeId }}
                            </PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="range">
                                {{ range }}
                            </PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="current_date">
                                {{ form.current_date }}
                            </PreDebug>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <PreDebug title="form">
                                {{ form }}
                            </PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="form errors">
                                {{ form?.errors }}
                            </PreDebug>
                        </v-col>
                    </v-row>
                </v-card-text>
                <!-- ERRORS -->
                <v-card-text v-if="Object.values(form?.errors).length > 0">
                    <v-alert type="error">
                        <pre>{{ form?.errors }}</pre>
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        @click.prevent="submitForm"
                        color="success"
                        :disabled="processing"
                        tonal
                    >
                        {{ __("validate") }}
                    </v-btn>
                </v-card-actions>
            </v-card>

            <!-- <pre>{{range}}</pre> -->
        </td>
    </tr>
</template>
