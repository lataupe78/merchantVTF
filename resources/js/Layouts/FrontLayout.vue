<script setup>
import { Head } from "@inertiajs/vue3";
import NavBar from "@/Layouts/Partials/Navbar.vue";
import FlashMessages from "@/Layouts/Partials/FlashMessages.vue";

defineProps({
    title: String,
});
</script>

<template>
    <Head :title="title || 'Merchant v4'" />

    <v-app class="">
        <NavBar />

        <v-main class="mb-5">
            <!-- Page Heading -->
            <v-card v-if="$slots.header" color="indigo-darken-1">
                <v-card-text>
                    <v-container>
                        <slot name="header"></slot>
                    </v-container>
                </v-card-text>
            </v-card>

            <FlashMessages />

            <v-container v-if="$slots.actions">
                <v-card
                    class="py-3 d-flex gap-3"
                    color="primary"
                    variant="tonal"
                >
                    <slot name="actions" />
                </v-card>
            </v-container>

            <!-- Page Content -->

            <slot />
        </v-main>
        <v-footer>
            <v-container>
                <v-row align-content="center" justify="center">
                    <v-col cols="6">
                        <v-sheet
                            class="pa-2 ma-2 text-center"
                            color="yellow-lighten-3"
                        >
                            2023
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>
