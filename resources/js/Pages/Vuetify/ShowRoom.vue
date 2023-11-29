<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { Head } from "@inertiajs/vue3";
const semanticClasses = [
    "primary",
    "secondary",
    "background",
    "surface",
    "success",
    "error",
    "warning",
    "info",
];
const coloredClasses = [
    "black",
    "white",
    "grey",
    "blue",
    "blue-grey",
    "light-blue",
    "cyan",
    "indigo",
    "red",
    "green",
    "yellow",
    "orange",
    "deep-orange",
];
const tintedClasses = [
    "darken-1",
    "darken-2",
    "darken-3",
    "darken-4",
    "lighten-1",
    "lighten-2",
    "lighten-3",
    "lighten-4",
    "accent-1",
    "accent-2",
    "accent-3",
    "accent-4",
];
</script>

<template>
    <Head title="Vuetify ShowRoom" />

    <FrontLayout>
        <v-container>
            <v-card class="mb-5">
                <v-card-title>Semantic Classes</v-card-title>
                <v-card-text>
                    <v-row
                        align-content="center"
                        v-for="(color, index) in semanticClasses"
                        :key="`semantic-row-${index}`"
                    >
                        <v-col>
                            <v-sheet
                                class="pa-2 text-center"
                                :color="`${color}`"
                            >
                                <h3>{{ color }}</h3>
                            </v-sheet>
                        </v-col>
                        <v-col
                            v-for="(tint, index) in tintedClasses"
                            :key="`colored-tint-${index}`"
                        >
                            <v-sheet
                                class="pa-2 text-center"
                                :color="`${color}-${tint}`"
                            >
                                {{ color }} {{ tint }}
                            </v-sheet>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <v-card class="mb-5">
                <v-card-title>Colored Classes</v-card-title>
                <v-card-text>
                    <v-row
                        align-content="center"
                        v-for="(color, index) in coloredClasses"
                        :key="`colored-${index}`"
                    >
                        <v-col class="d-flex flex-grow-1 align-stretch">
                            <v-sheet
                                class="pa-2 text-center w-100 align-self-center"
                                :color="`${color}`"
                            >
                                <h3>{{ color }}</h3>
                            </v-sheet>
                        </v-col>
                        <v-col
                            v-for="(tint, index) in tintedClasses"
                            :key="`colored-tint-${index}`"
                        >
                            <v-sheet
                                class="pa-2 text-center"
                                :color="`${color}-${tint}`"
                            >
                                {{ color + "-" + tint }}
                            </v-sheet>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </FrontLayout>
</template>
