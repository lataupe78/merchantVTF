<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavBar from "@/Layouts/Partials/Navbar.vue";
import FlashMessages from "@/Layouts/Partials/FlashMessages.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
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

         

            <v-container v-if="$slots.actions">
                <v-card
                    class="py-3 d-flex gap-3"
                    color="primary"
                    variant="tonal"
                >
                    <slot name="actions" />
                </v-card>
            </v-container>

            <v-container>

                <FlashMessages />

                <v-row justify="center">
                    <v-col cols="12" class="mt-10 mb-5 text-center">
                        <Link href="/" class="w-100">
                        <ApplicationLogo
                            height="128"
                            width="128"
                            class="mx-auto"
                        />
                        </Link>
                    </v-col>
                    <v-col sm="10" md="8" lg="6">
                        <slot />
                    </v-col>
                </v-row>
                <!-- Page Content -->
            </v-container>
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
