<script setup>
import { ref, reactive, computed, watch } from "vue";
import { getDateTime, toFormat } from "@/helpers/dates.js";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
// import { useForm } from "@inertiajs/vue3";

const emit = defineEmits(["close-modal"]);

onMounted(() => {
    console.log("MODAL MOUNTED");
});

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    range: Object,
    worker: Object,
    // location: Object,

    options: {
        type: [Array, Object],
        default() {
            return {
                sale_points: [],
                workers: [],
            };
        },
    },
});

const emptyDate = {
    date: null,
    time: null,
};
let form = useForm({});

const worker = computed(() => {
    return props.worker;
});
const range = computed(() => {
    return props.range;
});

const canValidate = computed(() => {
    return (
        (range.value?.starts_at?.date ?? false) &&
        (range.value?.starts_at?.time ?? false) &&
        (range.value?.ends_at?.date ?? false) &&
        (range.value?.ends_at?.time ?? false)
    );
});

const populateForm = () => {
    form.title = range.value?.title || "";

    form.worker_id = worker.value?.id;
    form.current_date = range.value?.current_date || null;
    form.id = range.value?.id || null;
    form.sale_point_id = range.value?.sale_point?.id ?? null;

    form.starts_at = {
        date: range.value?.starts_at?.date || range.value?.current_date || null,
        time: range.value?.starts_at?.time || null,
    };

    form.ends_at = {
        date: range.value?.ends_at?.date || range.value?.current_date || null,
        time: range.value?.ends_at?.time || null,
    };

    form.validated_started_at = {
        date:
            range.value?.validated_started_at?.date ||
            (canValidate.value == true ? range.value?.current_date : null),
        time: range.value?.validated_started_at?.time || null,
    };

    form.validated_ended_at = {
        date:
            range.value?.validated_ended_at?.date ||
            (canValidate.value == true ? range.value?.current_date : null),
        time: range.value?.validated_ended_at?.time || null,
    };

    console.log("NOW FORM", { form: form.value });
};

watch(
    () => props.open,
    (value) => {
        console.log("OPEN MODAL CHANGED");
        populateForm();
    },
    { deep: true, immediate: true }
);

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

const processing = ref(false);
const action = computed(() => (!!range.value?.id ? "edit" : "create"));

const formRoute = computed(() => {
    return action.value == "edit"
        ? route("manager.planning.working_ranges.update", {
              range: range.value.id,
          })
        : route("manager.planning.working_ranges.store");
});
const formMethod = computed(() => (action.value == "edit" ? "put" : "post"));

const saveForm = () => {
    processing.value = true;
    console.log("submit form", { form });

    form.transform((data) => ({
        ...data,
        ...form,
        _method: formMethod.value,
    }));

    form.post(formRoute.value, {
        onFinish: () => {
            processing.value = false;

            if (form?.errors.length == 0) {
                closeModal();
            }
        },
        onError: (error) => {
            console.error(error);
        },
    });
};

const closeModal = () => {
    emit("close-modal");
    form.value = { ...form, ...{} };
};
</script>
<template>
    <pre>open - {{ open }}</pre>

    <v-dialog v-model="props.open" max-width="960px" :persistent="false">
        <form @submit="saveForm">
            <v-card :loading="processing" class="px-5">
                <v-card-text>
                    <v-row>
                        <v-col>
                            <PreDebug title="range">{{ range }}</PreDebug>
                            <PreDebug title="worker">{{ worker }}</PreDebug>
                            <!-- <PreDebug title="location">{{ location }}</PreDebug> -->
                        </v-col>
                        <v-col>
                            <PreDebug title="form">{{ form }}</PreDebug>
                        </v-col>
                    </v-row>
                    <v-row class="text-info">
                        <v-col>action : {{ action }}</v-col>
                        <v-col>formRoute : {{ formRoute }}</v-col>
                        <v-col>formMethod : {{ formMethod }}</v-col>
                        <v-col>canValidate : {{ canValidate }}</v-col>
                    </v-row>
                </v-card-text>

                <v-card-text
                    v-if="Object.values(form?.errors).length > 0"
                    class="text-danger"
                >
                    <v-alert type="error">{{ form?.errors }}</v-alert>
                </v-card-text>

                <v-card-title class="text-center">
                    Horaires de
                    <span class="text-info text-uppercase font-weight-bold">{{
                        worker?.name
                    }}</span>
                    le
                    <span class="text-gray text-uppercase font-weight-bold">{{
                        range?.current_date
                    }}</span>
                </v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.title"
                        :error-messages="form.errors.title"
                        :label="__('title')"
                    />

                    <v-row>
                        <v-col cols="12" sm="4">
                            <v-text-field
                                v-model="form.current_date"
                                :error-messages="form.errors.current_date"
                                :label="__('current_date')"
                            />
                        </v-col>
                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="form.sale_point_id"
                                :error-messages="form.errors.sale_point_id"
                                :label="__('sale_point')"
                                :items="options.sale_points"
                                item-title="name"
                                item-value="id"
                            />
                        </v-col>
                        <v-col cols="12" sm="4">
                            <v-select
                                v-model="form.worker_id"
                                :error-messages="form.errors.worker_id"
                                :label="__('worker')"
                                :items="options.workers"
                                item-title="name"
                                item-value="id"
                            />
                        </v-col>
                    </v-row>

                    <!-- PLANNED -->
                    <v-row>
                        <!-- STARTS_AT -->
                        <v-col cols="12" md="6" class="text-center">
                            <div class="text-body-2">{{ __("starts_at") }}</div>
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.starts_at.date"
                                        :error-messages="
                                            form.errors[`starts_at.date`]
                                        "
                                        type="date"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.starts_at.time"
                                        :error-messages="
                                            form.errors[`starts_at.time`]
                                        "
                                        type="time"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                        <!-- ENDS_AT -->
                        <v-col cols="12" md="6" class="text-center">
                            <div class="text-body-2">{{ __("ends_at") }}</div>
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.ends_at.date"
                                        :error-messages="
                                            form.errors[`ends_at.date`]
                                        "
                                        type="date"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.ends_at.time"
                                        :error-messages="
                                            form.errors[`ends_at.time`]
                                        "
                                        type="time"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                    <v-divider />

                    <!-- COMPLETED -->
                    <v-row class="my-5">
                        <v-col cols="12" sm="6" class="text-center">
                            <div class="text-body-2">
                                {{ __("started_at") }}
                            </div>
                            <span class="text-info">
                                {{ range.started_at?.val || "---" }}
                            </span>
                        </v-col>
                        <v-col cols="12" sm="6" class="text-center">
                            <div class="text-body-2">{{ __("ended_at") }}</div>
                            <span class="text-info">
                                {{ range.ended_at?.val || "---" }}
                            </span>
                        </v-col>
                    </v-row>
                    <v-divider />

                    <!-- VALIDATED -->
                    <v-row v-if="canValidate">
                        <!-- VALIDATED_STARTED_AT -->
                        <v-col cols="12" md="6" class="text-center">
                            <div class="text-body-2">
                                {{ __("started_at") }}
                            </div>
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.validated_started_at.date"
                                        :error-messages="
                                            form.errors[
                                                `validated_started_at.date`
                                            ]
                                        "
                                        type="date"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.validated_started_at.time"
                                        :error-messages="
                                            form.errors[
                                                `validated_started_at.time`
                                            ]
                                        "
                                        type="time"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                        <!-- VALIDATED_ENDED_AT -->
                        <v-col cols="12" md="6" class="text-center">
                            <div class="text-body-2">{{ __("ended_at") }}</div>
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.validated_ended_at.date"
                                        :error-messages="
                                            form.errors.validated_ended_at?.date
                                        "
                                        type="date"
                                    />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.validated_ended_at.time"
                                        :error-messages="
                                            form.errors.validated_ended_at?.time
                                        "
                                        type="time"
                                    />
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions
                    class="d-flex align-center justify-space-around"
                >
                    <v-btn color="secondary" size="x-large" @click="closeModal">
                        Close Dialog
                    </v-btn>

                    <v-btn size="x-large" color="primary" type="submit">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </v-dialog>
</template>
