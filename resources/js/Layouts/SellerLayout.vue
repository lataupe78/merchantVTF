<script setup>
import { ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useTheme } from "vuetify";

import NavBar from "@/Layouts/Partials/Navbar.vue";
import FlashMessages from "@/Layouts/Partials/FlashMessages.vue";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },

    actionLinks: {
        type: Array,
        default() {
            return [];
        },
    },
});

const $page = usePage();
const $theme = useTheme();

const color = ref("purple accent-4");
const value = ref("music");
</script>
<template>
    <Head :title="title ?? 'Seller Section'" />

    <v-app inset>
        <NavBar
            :links="links"
            :has-sidebar="true"
            home="seller.dashboard"
            color="orange-darken-4"
        />

        <v-main :scrollable="true" inset>
            <!-- Page Heading -->
            <v-card v-if="title" color="orange-darken-3">
                <v-container>
                    <h4 class="text-h5 my-5">
                        {{ title }}
                    </h4>
                </v-container>
            </v-card>

            <FlashMessages />

            <v-container v-if="$slots.actions || props.actionLinks.length > 0">
                <v-card class="pa-4">
                    <v-slide-group show-arrows>
                        <slot name="actions" />
                        <BtnAction
                            v-for="(link, linkIndex) in props.actionLinks"
                            :key="`footer-link-${linkIndex}`"
                            :link="link"
                        />
                    </v-slide-group>
                </v-card>
            </v-container>

            <v-container>
                <slot />
            </v-container>
        </v-main>

        <v-footer app absolute inset color="orange-accent-4">
            <v-container fluid>
                <v-row>
                    <v-col class="text-center" cols="12">
                        &copy; {{ new Date().getFullYear() }} —
                        <strong>{{ $page.props.settings.app_name }}</strong>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>
