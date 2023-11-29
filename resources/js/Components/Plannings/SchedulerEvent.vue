<script setup>
import { reactive } from "vue";
import PreDebug from "../Debug/PreDebug.vue";
const form = reactive({
    scheduling_type: "recurrent",

    repeat_frequency: "",

    repeat_count: 1,

    repeat_on_days: [],

    date_starts_on: "",

    ends_type: "",
    // after_occurences
    // after_date

    ends_after_occurences_count: 1,

    ends_after_date: "",
});

const scheduling_types = ["once", "recurrent"];

const frequencies = ["daily", "weekly", "monthly", "yearly"];

// 0 is for Sunday, 1 for Monday

const weekDays = [
    { label: "Lun", weekday: 1 },
    { label: "Mar", weekday: 2 },
    { label: "Mer", weekday: 3 },
    { label: "Jeu", weekday: 4 },
    { label: "Ven", weekday: 5 },
    { label: "Sam", weekday: 6 },
    { label: "Dim", weekday: 0 },
];
</script>
<template>
    <!-- color="indigo-darken-2" -->
    <v-card
        title="Schedule"
        color="white"
        class="px-4 px-sm-6 px-md-12"
    >
        <v-card-text>
            <v-radio-group label="type" v-model="form.scheduling_type" inline>
                <v-radio label="once" value="once"></v-radio>
                <v-radio label="recurrent" value="recurrent"></v-radio>
            </v-radio-group>

            <template v-if="form.scheduling_type == 'recurrent'">
                <v-divider class="mb-4" />

                <!-- Repeats -->
                <v-row>
                    <v-col cols="12" md="2">
                        <div class="label">Repeats</div>
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-select
                            v-model="form.repeat_frequency"
                            :items="frequencies"
                            label="repeat"
                        />
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-text-field
                            v-model="form.repeat_frequency"
                            type="number"
                            min="1"
                            step="1"
                            label="repeat every"
                        />
                    </v-col>
                </v-row>

                <!-- Repeats on -->
                <v-row>
                    <v-col cols="3" md="2">
                        <div class="label">Repeats on</div>
                    </v-col>

                    <v-col cols="10">
                        <v-chip-group
                            v-model="form.repeat_on_days"
                            multiple
                            active-class="bg-success"
                            active-class="bg-success"
                            @change="handleChange"
                            label="repeat on"
                        >
                            <v-chip
                                v-for="item in weekDays"
                                :key="item"
                                outlined
                                filter
                                :value="item.weekday"
                            >
                                {{ item.label }}
                            </v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>

                <!-- Starts on -->
                <v-row>
                    <v-col cols="3" md="2">
                        <div class="label">Starts on</div>
                    </v-col>

                    <v-col cols="12" md="10">
                        <v-text-field
                            v-model="form.date_starts_on"
                            type="date"
                            label="starts on"
                        />
                    </v-col>
                </v-row>

                <!-- Ends -->
                <v-row>
                    <v-col cols="3" md="2">
                        <div class="label">Ends</div>
                    </v-col>
                    <v-col cols="10">
                        <v-radio-group v-model="form.ends_type" label="Ends">
                            <v-radio value="ends_after_occurences_count">
                                <template v-slot:label>
                                    <v-text-field
                                        length="2"
                                        max-width="2rem"
                                        style="max-width: 4rem !important"
                                        v-model="
                                            form.ends_after_occurences_count
                                        "
                                        type="number"
                                        min="1"
                                        step="1"
                                    >
                                        <template v-slot:prepend>
                                            After
                                        </template>

                                        <template v-slot:append>
                                            occurences
                                        </template>
                                    </v-text-field>
                                </template>
                            </v-radio>

                            <v-radio
                                label="On specific Date"
                                value="ends_after_date"
                            ></v-radio>
                        </v-radio-group>
                    </v-col>
                </v-row>
            </template>
        </v-card-text>
        <v-card-actions class="d-flex align-center justify-space-around">
            <v-btn type="reset"> Cancel </v-btn>

            <v-btn type="submit" color="primary"> Save </v-btn>
        </v-card-actions>
    </v-card>

    <PreDebug class="my-10">{{ form }}</PreDebug>
</template>
<style>
.label {
    font-weight: 900;
    color: var(--v-theme-text-info);
}
</style>
