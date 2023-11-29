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
                sellers: [],
            };
        },
    },
});

const currentFilters = reactive({
    active: props.filters?.active || null,
    search: props.filters?.search || "",
    roles: getArrayValues(props.filters, "roles"),
    sale_points: getArrayValues(props.filters, "sale_points"),
});

const allowedFilters = ["is_active", "search", "roles[]", "sale_points[]"];

const applyFilters = () => {
    emit("filters-updated", currentFilters);
};

const active_states = [
    { label: "active", value: "active" },
    { label: "inactive", value: "inactive" },
    { label: "les deux", value: "" },
];
</script>

<template>
    <FilterPanel :allowedFilters="allowedFilters" @apply="applyFilters">
        <v-row>
            <v-col cols="12" sm="6">
                <v-text-field
                    type="search"
                    clearable
                    prepend-icon="mdi-magnify"
                    v-model="currentFilters.search"
                />
            </v-col>

            <v-col cols="12" sm="6">
                <v-radio-group v-model="currentFilters.active">
                    <v-radio
                        v-for="(state, index) in active_states"
                        :key="`state-${index}`"
                        :label="state.label"
                        :value="state.value"
                    />
                </v-radio-group>
            </v-col>

            <!-- SALE_POINTS -->
            <v-col cols="12" md="6">
                <v-chip-group
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
            </v-col>

            <!-- ROLES -->
            <v-col cols="12" md="6">
                <v-chip-group
                    multiple
                    v-model="currentFilters.roles"
                    selected-class="text-primary"
                >
                    <v-chip
                        v-for="(option, index) in options?.roles"
                        :key="`role-option-${index}`"
                        :value="option.value"
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
