<script setup>
import { ref, computed, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { hasRole } from "@/helpers/roles.js";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";

import ToggleDebug from "./ToggleDebug.vue";

import { useTheme } from "vuetify";

const $page = usePage();

const props = defineProps({
    home: {
        type: String,
        default: "welcome",
    },
    color: {
        type: String,
        default: "primary-darken-1",
    },
    links: {
        type: Array,
        default() {
            return [
                { route: "about", label: "about" },
                { route: "vuetify", label: "vuetify" },
            ];
        },
    },
    linksManager: {
        type: Array,
        default() {
            return [];
        },
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    hasSidebar: {
        type: Boolean,
        default: false,
    },
});

const theme = useTheme();
const darkMode = ref(theme.global.name.current?.dark == true ?? false);

watch(darkMode, (value) => {
    console.log({ darkMode: value });
    theme.global.name.value = !!value == true ? "dark" : "light";
});

const logout = () => {
    router.post(route("logout"));
};

const sidebar = ref(false);

const path = computed(() => window.location.pathname);
</script>
<template>
    <v-app-bar :color="color" prominent>
        <v-app-bar-nav-icon
            v-if="props.hasSidebar === true"
            variant="text"
            @click.stop="sidebar = !sidebar"
        ></v-app-bar-nav-icon>

        <!-- Logo -->
        <Link class="navbar-brand me-4" :href="route(props?.home || 'welcome')">
            <ApplicationLogo width="48" height="48" />
        </Link>

        <!-- Left Side Of Navbar -->

        <template v-if="props.links.length > 0 && props.hasSidebar == false">
            <v-slide-group show-arrows class="gap-4">
                <v-slide-group-item
                    v-for="(link, index) in props.links"
                    :key="index"
                >
                    <v-btn
                        v-if="link?.route"
                        :to="route(link.route)"
                        :active="route().current(link.route)"
                    >
                        {{ __(link?.label || "---") }}
                    </v-btn>
                </v-slide-group-item>
            </v-slide-group>
        </template>

        <v-btn v-if="$page.props.auth?.user" :to="route('dashboard')">
            {{ __("Dashboard") }}
        </v-btn>

        <v-spacer></v-spacer>

        <!-- SETTINGS -->
        <v-menu id="settings-menu">
            <template v-slot:activator="{ props }">
                <v-btn icon="mdi-cog-outline" v-bind="props"> </v-btn>
            </template>
            <v-list>
                <v-list-item>
                    {{ darkMode }}
                    <v-switch
                        v-model="darkMode"
                        color="primary"
                        :label="darkMode ? __('dark') : __('light')"
                    >
                    </v-switch>
                </v-list-item>
                <v-list-item
                    v-if="
                        $page.props.auth?.user &&
                        hasRole($page.props.auth.user, 'Super Admin|manager')
                    "
                >
                    <ToggleDebug />
                </v-list-item>
            </v-list>
        </v-menu>

        <!-- IMPERSONATE -->
        <v-menu
            id="impersonate-menu"
            v-if="
                $page.props.auth?.user &&
                hasRole($page.props.auth.user, 'Super Admin|manager')
            "
        >
            <template v-slot:activator="{ props }">
                <v-btn icon="mdi-incognito" v-bind="props"> </v-btn>
            </template>
            <v-list>
                <v-list-item disabled="true">
                    {{ __("impersonating_user") }}
                </v-list-item>

                <v-list-item :to="route('profile')">
                    <v-list-item-title>{{ __("profile") }}</v-list-item-title>
                </v-list-item>

                <v-list-item :to="route('admin.impersonate.logout')">
                    <v-list-item-title>{{ __("log_out") }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>

        <template v-if="$page.props.auth?.user == null">
            <v-btn :to="route('login')">
                {{ __("Log in") }}
            </v-btn>

            <v-btn
                v-if="$page.props?.settings?.canRegister"
                :to="route('register')"
            >
                {{ __("Register") }}
            </v-btn>
        </template>

        <template v-else>
            <v-menu id="auth-menu">
                <template v-slot:activator="{ props }">
                    <v-btn icon="mdi-account" v-bind="props"> </v-btn>
                </template>

                <v-list>
                    <v-list-item :title="$page.props.auth?.user?.name">
                        <v-list-item-subtitle>
                            <v-chip
                                v-for="(role, roleIndex) in $page.props.auth
                                    ?.user?.roles"
                                :key="`auth-role-${roleIndex}`"
                                color="info"
                            >
                                {{ role.name }}
                            </v-chip>
                        </v-list-item-subtitle>
                    </v-list-item>

                    <v-list-item :to="route('profile.edit')">
                        {{ __("profile.edit") }}
                    </v-list-item>
                    <v-divider></v-divider>

                    <v-list-item to="/">
                        {{ __("Front") }}
                    </v-list-item>
                    <template
                        v-for="(role, roleIndex) in [
                            'seller',
                            'manager',
                            'admin',
                        ]"
                    >
                        <v-list-item
                            v-if="
                                hasRole(
                                    $page.props.auth?.user,
                                    'Super Admin|' + role
                                )
                            "
                            :to="route(role + '.dashboard')"
                        >
                            {{ __(role + ".dashboard") }}
                        </v-list-item>
                        <v-divider></v-divider>
                    </template>
                    <v-list-item>
                        <form @submit.prevent="logout">
                            <v-btn type="submit" block>
                                {{ __("log_out") }}
                            </v-btn>
                        </form>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
    </v-app-bar>

    <template v-if="props.hasSidebar">
        <v-navigation-drawer v-model="sidebar" temporary>
            <v-list>
                <v-list-item title="Navigation drawer"></v-list-item>
                <v-list-item
                    v-for="(link, index) in links"
                    :key="`link-${index}`"
                    :to="route(link.route)"
                    :active="route().current(link.route)"
                >
                    <template v-slot:title class="text-uppercase">
                        {{ __(link?.label || "---") }}
                    </template>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
    </template>
</template>
