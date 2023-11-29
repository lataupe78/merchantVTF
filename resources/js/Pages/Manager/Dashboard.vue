<script setup>
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const blocks = [
    {
        title: "reports",
        route: "manager.reports.index",
        icon: "chart-line",
        content: `
<p>
    Gestion des Rapports pour aide à la comptabilité :
    <ul>
        <li>
            Par Période ( semaine, mois, année )
        </li>
        <li>
            Par points de vente
        </li>
    </ul>
</p>
`,
    },

    {
        title: "sale_points",
        route: "manager.sale_points.index",
        icon: "map-marker",
        content: `
<p>
    Gestion des Points de vente et des employés associés
</p>
`,
    },


    {
        title: "plannings",
        route: "manager.plannings.calendar.index",
        icon: "calendar-multiple",
        content: `
<p>
    Planning des employés
</p>
`,
    },

    {
        title: "schedule_validation",
        route: "manager.plannings.validation.index",
        icon: "calendar-check",
        content: `
<p>
    Validation des horaires
</p>
`,
    },

    {
        title: "stocks",
        route: "manager.stocks.index",
        icon: "archive-marker",
        content: `
<p>
    Etat des stocks par points de vente
</p>
`,
    },

    {
        title: "assets",
        route: "manager.assets.index",
        icon: "food",
        content: `
<p>
    Gestion des produits
</p>
`,
    },


];
</script>
<template>
    <ManagerLayout>
        <Head :title="__('Manager Dashboard')" />

        <v-container>
            <v-row>
                <v-col
                    cols="12"
                    sm="6"
                     
                    v-for="(block, index) in blocks"
                    :key="index"
                >
                    <v-card
                        :title="__(block.title)"
                        :to="route(block.route)"
                        exact
                    >
                        <template v-slot:prepend>
                            <v-icon
                                size="x-large"
                                :icon="`mdi-${block.icon}`"
                                color="info"
                            ></v-icon>
                        </template>
                        <v-card-text>
                            <div v-html="block.content" />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </ManagerLayout>
</template>
