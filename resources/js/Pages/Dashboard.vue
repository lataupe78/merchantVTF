<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { usePage } from "@inertiajs/vue3";
const $page = usePage();
const auth = $page?.props?.auth;
</script>

<template>
    <FrontLayout :title="__('dashboard')">
        <v-container>
            <v-row>
                <v-col>
                    <PreDebug :show="true" title="$page">
                        {{ $page }}
                    </PreDebug>
                </v-col>
                <v-col>
                    <PreDebug :show="true" title="auth">
                        {{ auth }}
                    </PreDebug>
                </v-col>
            </v-row>
        </v-container>
    </FrontLayout>
</template>
