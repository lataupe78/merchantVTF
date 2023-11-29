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
                sale_points: [],
                workers: [],
            };
        },
    },
});

const currentFilters = reactive({
    date_start: props.filters?.date_start || null,
    date_end: props.filters?.date_end || null,
    salepoints_ids: getArrayValues(props.filters, "salepoints_ids"),
    workers_ids: getArrayValues(props.filters, "workers_ids"),
});

const allowedFilters = [
    "date_start",
    "date_end",
    "salepoints_ids[]",
    "workers_ids[]",
];

const applyFilters = () => {
    emit("filters-updated", currentFilters);
};
</script>
<template>
    <FilterPanel :allowedFilters="allowedFilters" @apply="applyFilters">
        <v-row>
            <v-col cols="12" md="6">
                <v-text-field
                    id="date_start"
                    type="date"
                    v-model="currentFilters.date_start"
                />
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field
                    id="date_end"
                    type="date"
                    v-model="currentFilters.date_end"
                />
            </v-col>
        </v-row>

        <v-row>
            <!-- SALE_POINTS -->
            <v-col cols="12" md="6">
                <v-chip-group
                    multiple
                    v-model="currentFilters.salepoints_ids"
                    selected-class="text-success"
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
            </v-col>

            <!-- WORKERS -->
            <v-col cols="12" md="6">
                <v-chip-group
                    multiple
                    v-model="currentFilters.workers_ids"
                    selected-class="text-success"
                >
                    <v-chip
                        v-for="(option, index) in options?.workers"
                        :key="`worker-option-${index}`"
                        :value="option.id"
                        label
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
