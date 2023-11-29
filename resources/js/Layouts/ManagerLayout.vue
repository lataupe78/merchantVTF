<script setup>
import { ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useTheme } from "vuetify";

import NavBar from "@/Layouts/Partials/Navbar.vue";
import FlashMessages from "@/Layouts/Partials/FlashMessages.vue";
import BtnAction from "@/Components/Buttons/BtnAction.vue";
import BtnFooter from "@/Components/Buttons/BtnFooter.vue";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },

    footerLinks: {
        type: Array,
        default() {
            return [];
        },
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

const links = [
    // { route: "manager.dashboard", label: "dashboard" },
    // { route: "position.index", label: "positions" },
    { route: "manager.reports.index", label: "reports" },
    { route: "manager.sale_points.index", label: "sale_points" },
    { route: "manager.stocks.index", label: "stocks" },
    { route: "manager.assets.index", label: "assets" },
    { route: "manager.users.index", label: "workers" },
    { route: "manager.plannings.calendar.index", label: "calendar" },
    {
        route: "manager.plannings.validation.index",
        label: "schedule_validation",
    },

    { route: "about", label: "about" },
];

const color = ref("purple accent-4");
const value = ref("music");
</script>

<template>
    <Head :title="title ?? 'Manager Section'" />

    <!--
        How to set background color
        refer to https://stackoverflow.com/questions/50243769/vuetify-how-to-set-background-color 
    -->
    <v-app
        inset
        :style="{
            background: $theme.current.value.colors?.background,
        }"
    >
        <NavBar :links="links" :has-sidebar="true" home="manager.dashboard" />

        <v-main :scrollable="true" inset>
            <!-- Page Heading -->
            <v-card v-if="title" color="indigo-darken-3">
                <v-container>
                    <h4 class="text-h5 my-5">
                        {{ title }}
                    </h4>
                </v-container>
            </v-card>

            <FlashMessages />

            <!-- DEBUG THEME -->
            <v-container>
                <v-row>
                    <v-col>
                        <PreDebug title="theme">
                            <p>global name {{ $theme.global.name }}</p>
                            <p>current {{ $theme.current }}</p>
                            <p>$theme {{ $theme }}</p>
                        </PreDebug>
                    </v-col>
                    <v-col>
                        <PreDebug title="current colors">{{
                            $theme.current.value.colors
                        }}</PreDebug>
                    </v-col>
                </v-row>
            </v-container>

            <v-container v-if="$slots.actions || props.actionLinks.length > 0">
                <!-- <PreDebug title="actionLinks">{{ actionLinks }}</PreDebug> -->

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

            <v-container v-if="$slots.help">
                <v-alert type="info" elevation="2" dismissible>
                    <slot name="help" />
                </v-alert>
            </v-container>

            <!-- Page Content -->

            <slot />

            <template v-if="props.footerLinks.length">
                <v-bottom-navigation
                    v-model="value"
                    app
                    :background-color="color"
                    dark
                    grow
                    hide-on-scroll
                    shift
                >
                    <BtnFooter
                        v-for="(link, linkIndex) in props.footerLinks"
                        :key="`footer-link-${linkIndex}`"
                        :link="link"
                        :value="link.title"
                    />
                </v-bottom-navigation>
            </template>
        </v-main>

        <v-footer app absolute inset color="indigo-accent-4">
            <v-container fluid>
                <template v-if="props.footerLinks.length">
                    <v-slide-group
                        show-arrows
                        center-active
                        justify="space-between"
                        height="160px"
                    >
                        <BtnFooter
                            v-for="(link, linkIndex) in props.footerLinks"
                            :key="`footer-link-${linkIndex}`"
                            :link="link"
                        />
                    </v-slide-group>
                </template>
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
