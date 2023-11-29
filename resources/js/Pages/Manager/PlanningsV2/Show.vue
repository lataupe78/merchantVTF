<script setup>
import { ref, watch, computed, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";

const props = defineProps({
    worker: { type: Object, required: true },

    working_ranges: { type: [Array, Object], required: true },

    dates: {
        type: Object,
        default() {
            return {
                current: null,
                date_start: null,
                date_end: null,
                prev: null,
                next: null,
            };
        },
    },

    options: {
        type: [Array, Object],
        required: true,
        default() {
            return {
                workers: [],
                sale_points: [],
            };
        },
    },
});

const prevNavLink = {
    url: route("manager.plannings.calendar.index", {
        month: toFormat(props.dates?.prev, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.prev, "MMMM yyyy"),
};
const nextNavLink = {
    url: route("manager.plannings.calendar.index", {
        month: toFormat(props.dates?.next, "yyyy-MM"),
    }),
    label: toFormat(props?.dates?.next, "MMMM yyyy"),
};
</script>

<template>
    <ManagerLayout
        :title="__('plannings') + ' ' + toFormat(dates?.current, 'MMMM yyyy')"
    >
        <v-container>
            <v-alert type="info" variant="tonal" class="my-10">
                <h3>On teste Vuetify Calendar</h3>
            </v-alert>

            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />
        </v-container>
    </ManagerLayout>
</template>
