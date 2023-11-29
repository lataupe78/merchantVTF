<script setup>
import useTranslation from "@/composables/useTranslation.js";
const { __ } = useTranslation();

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import FormReport from "@/Pages/Manager/Reports/_FormReport.vue";
const props = defineProps({
    report: {
        type: Object,
        required: true,
    },
    options: {
        type: [Array, Object],
        default: () => {},
    },
});

const actionLinks = [
    {
        label: "reports",
        icon: "mdi-chart-line",
        url: route("manager.reports.index"),
    },
];
</script>
<template>
    <ManagerLayout
        :title="__('create_item', { item: __('report') })"
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" variant="tonal">
                <div v-html="__('infos_create_report')" />
            </v-alert>

            <FormReport :report="report" :options="options" />
        </v-container>
    </ManagerLayout>
</template>
