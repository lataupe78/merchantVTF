<script setup>
import { reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const emit = defineEmits(["range-created"]);

const props = defineProps({
    dates: [Array, Object],
    worker: Object,
    sale_points: [Array, Object],
});

const form = reactive({
    starts_at: null,
    ends_at: null,
    sale_point_id: null,
});

const exportForm = reactive({
    ranges: [],
});

watch(
    () => form,
    (values) => {
        exportForm.ranges.splice(0);

        props.dates.forEach((date) => {
            exportForm.ranges.push({
                current_date: date,
                worker_id: props.worker.id,

                starts_at: {
                    date: date,
                    time: values.starts_at,
                },

                ends_at: {
                    date: date,
                    time: values.ends_at,
                },
                sale_point_id: values?.sale_point_id,
            });
        });
    },
    { deep: true, immediate: true }
);

const createdRange = ref([]);
const errors = ref([]);

const workingRangeList = [
    { starts_at: "09:00", ends_at: "17:00" },
    { starts_at: "10:00", ends_at: "18:00" },
    { starts_at: "11:00", ends_at: "19:00" },
    { starts_at: "12:00", ends_at: "20:00" },
];

const applyHours = (hours) => {
    form.starts_at = hours.starts_at;
    form.ends_at = hours.ends_at;
};

const processing = ref(false);

const saveRanges = () => {
    processing.value = true;

    router.post(route("manager.planning.working_ranges.storeAll"), exportForm, {
        onSuccess: (visit) => {
            console.log("SUCCESS", visit);
            emit("range-created");
        },
        onFinish: (visit) => {
            processing.value = false;
        },
        onError(errors) {
            console.error(errors);
            errors.value = { ...errors };
            debugger;
        },
    });
};
</script>

<template>
    <v-sheet class="mt-4 mb-8" color="teal-lighten-3">
        <form @submit.prevent="saveRanges">
            <v-card
                tonal
                color="light-blue-darken-4 px-8"
                :loading="processing"
            >
                <v-card-text>
                    <v-alert
                        type="error"
                        class="mt-4 mb-8"
                        v-if="errors?.length"
                    >
                        <ul>
                            <li
                                v-for="(error, index) in errors"
                                :key="`error-${index}`"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </v-alert>
                </v-card-text>

                <v-card-text>
                    <p>
                        Ajouter des horaires pour
                        {{
                            dates.length > 1
                                ? "les dates selectionnées"
                                : "la date selectionnée"
                        }}
                    </p>
                </v-card-text>

                <v-card-text class="d-flex flex-wrap ga-4">
                    <template
                        v-for="(hours, index) in workingRangeList"
                        :key="`working-range-${index}`"
                    >
                        <v-btn
                            color="secondary"
                            tonal
                            @click="applyHours(hours)"
                        >
                            {{ hours?.starts_at }} - {{ hours?.ends_at }}
                        </v-btn>
                    </template>
                </v-card-text>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="form.starts_at"
                                type="time"
                                :label="__('starts_at')"
                                required
                            />
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="form.ends_at"
                                type="time"
                                :label="__('ends_at')"
                                required
                            />
                        </v-col>
                        <v-col>
                            <v-select
                                v-model="form.sale_point_id"
                                :label="__('sale_point')"
                                :items="sale_points"
                                item-title="name"
                                item-value="id"
                                required
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-text>
                    <v-row>
                        <v-col>
                            <PreDebug title="dates">{{ dates }}</PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="worker">{{ worker }}</PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="sale_points">{{
                                sale_points
                            }}</PreDebug>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <PreDebug title="form">{{ form }}</PreDebug>
                        </v-col>
                        <v-col>
                            <PreDebug title="exportForm">{{
                                exportForm
                            }}</PreDebug>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn size="x-large" color="white" type="submit">
                        {{ __("Save Hours") }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </v-sheet>
</template>
