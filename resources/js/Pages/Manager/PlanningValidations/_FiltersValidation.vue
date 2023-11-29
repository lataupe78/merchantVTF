<script setup>
import { ref, reactive } from "vue";
import { getArrayValues } from "@/helpers/arrays.js";
import FilterPanel from "@/Components/Panels/FilterPanel.vue";

const emit = defineEmits(["filters-updated"]);

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default() {
            return {
                workers: [],
            };
        },
    },
});

const currentFilters = reactive({
    validated: props.filters?.validated || "both",
    planned: props.filters?.planned || "both",
    completed: props.filters?.completed || "both",
    workers_ids: getArrayValues(props.filters, "workers_ids"),
});

const allowedFilters = ["validated", "planned", "completed", "workers_ids[]"];

const applyFilters = () => {
    emit("filters-updated", currentFilters);
};
</script>
<template>
    <FilterPanel :allowedFilters="allowedFilters" @apply="applyFilters">
        <v-row>
            <v-col cols="12" md="4">
                <fieldset>
                    <legend>{{ __("planned") }}</legend>
                    <v-radio-group v-model="currentFilters.planned">
                        <v-radio
                            v-for="(option, index) in [
                                'planned',
                                'unplanned',
                                'both',
                            ]"
                            :key="`option-planned-${option}`"
                            :label="option"
                            :value="option"
                        />
                    </v-radio-group>
                </fieldset>
            </v-col>
            <v-col cols="12" md="4">
                <fieldset>
                    <legend>{{ __("completed") }}</legend>
                    <v-radio-group v-model="currentFilters.completed">
                        <v-radio
                            v-for="(option, index) in [
                                'completed',
                                'uncompleted',
                                'both',
                            ]"
                            :key="`option-completed-${option}`"
                            :label="option"
                            :value="option"
                        />
                    </v-radio-group>
                </fieldset>
            </v-col>
            <v-col cols="12" md="4">
                <fieldset>
                    <legend>{{ __("validated") }}</legend>
                    <v-radio-group v-model="currentFilters.validated">
                        <v-radio
                            v-for="(option, index) in [
                                'validated',
                                'unvalidated',
                                'both',
                            ]"
                            :key="`option-validated-${option}`"
                            :label="option"
                            :value="option"
                        />
                    </v-radio-group>
                </fieldset>
            </v-col>
        </v-row>

        <v-row>
            <!-- SALE_POINTS -->
            <!-- <v-col cols="12" md="4">
                <v-chip-group
                    clearable
                    multiple
                    v-model="currentFilters.salepoints_ids"
                    selected-class="text-primary"
                >
                    <v-chip
                        v-for="(option, index) in options?.sale_points"
                        :key="`sale_point-option-${index}`"
                        :value="option.value"
                        label
                    >
                        {{ option.name }}
                    </v-chip>
                </v-chip-group>
            </v-col> -->

            <!-- workerS -->
            <v-col cols="12" md="6">
                <v-chip-group
                    multiple
                    v-model="currentFilters.workers_ids"
                    selected-class="text-primary"
                >
                    <v-chip
                        v-for="(option, index) in options?.workers"
                        :key="`worker-option-${index}`"
                        :value="option.id"
                    >
                        {{ option.name }}
                    </v-chip>
                </v-chip-group>
            </v-col>
        </v-row>
    </FilterPanel>
    <v-row>
        <v-col>
            <PreDebug title="currentFilters">{{ currentFilters }}</PreDebug>
        </v-col>
    </v-row>
</template>
