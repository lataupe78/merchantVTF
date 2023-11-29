<script setup>
import useTranslation from "@/composables/useTranslation.js";
const { __ } = useTranslation();

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";
import TotalsCard from "@/Components/Reports/TotalsCard.vue";
import ReportChart from "@/Components/Charts/ReportChart.vue";

import { formatPrice } from "@/helpers/currency.js";
import { dateLabel, toFormat } from "@/helpers/dates.js";

const props = defineProps({
    totals: [Array, Object],
    totals_by_day: [Array, Object],
    total_salepoints: [Array, Object],
    reports: [Array, Object],

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
});

const chartLabels = Object.keys(props.totals_by_day).map((date) =>
    toFormat(date, "DDD")
);

const chartDataSets = [
    {
        label: "Cash Reel",
        // backgroundColor: '#f87979',
        data: Object.entries(props.totals_by_day).map((totals) => {
            let date = totals[0];
            let totalsDay = totals[1];
            return totalsDay.reduce(
                (total, total_salepoint) =>
                    total + 1 * total_salepoint.total_cash_reel,
                0
            );
        }),
        fill: false,
        borderColor: "rgb(192, 192, 75)",
        tension: 0.1,
    },
    {
        label: "Card Reel",
        // backgroundColor: '#f87979',
        data: Object.entries(props.totals_by_day).map((totals) => {
            let date = totals[0];
            let totalsDay = totals[1];
            return totalsDay.reduce(
                (total, total_salepoint) =>
                    total + 1 * total_salepoint.total_card_reel,
                0
            );
        }),
        fill: false,
        borderColor: "rgb(75, 192, 192)",
        tension: 0.1,
    },
];

const prevNavLink = {
    url: route("manager.reports.week", {
        week: toFormat(props?.dates?.prev, "yyyy-WW"),
    }),
    label: toFormat(props?.dates?.prev, "yyyy-WW"),
};
const nextNavLink = {
    url: route("manager.reports.week", {
        week: toFormat(props?.dates?.next, "yyyy-WW"),
    }),
    label: toFormat(props?.dates?.next, "yyyy-WW"),
};
const actionLinks = [
    {
        label: "reports",
        icon: "mdi-chart-line",
        url: route("manager.reports.index"),
    },
    {
        label: __("create_item", { item: __("report") }),
        icon: "mdi-plus-circle",
        url: route("manager.reports.create"),
    },
    {
        label: __("reports.monthly"),
        color: "yellow",
        icon: "mdi-chart-line",
        url: route("manager.reports.month"),
    },
    {
        label: "reports.daily",
        color: "yellow",

        icon: "mdi-chart-line",
        url: route("manager.reports.day"),
    },
    {
        label: "reports.weekly",
        color: "yellow",
        icon: "mdi-chart-line",
        url: route("manager.reports.week"),
    },
];
</script>

<template>
    <ManagerLayout
        :title="
            __('reports.weekly') + ' ' + toFormat(dates?.current, 'yyyy-WW')
        "
        :action-links="actionLinks"
    >
        <v-container>
            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />

            <PreDebug title="dates">{{ dates }}</PreDebug>

            <TotalsCard
                v-for="(total, totalIndex) in totals"
                :key="totalIndex"
                :totals="total"
            />

            <ReportChart
                v-if="chartLabels.length > 0"
                :chartData="{
                    labels: chartLabels,
                    datasets: chartDataSets,
                }"
            />

            <v-card :title="__('sale_points')" class="mb-5">
                <v-card-text>
                    <NavPeriod :prev="prevNavLink" :next="nextNavLink" />
                </v-card-text>
                <template v-if="total_salepoints.length < 1">
                    <v-card-text>
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </v-card-text>
                </template>
                <template v-else>
                    <v-table fixed-header>
                        <thead>
                            <tr>
                                <th>{{ __("sale_point") }}</th>
                                <th>{{ __("cash") }}</th>
                                <th>{{ __("cash_reel") }}</th>
                                <th>{{ __("card") }}</th>
                                <th>{{ __("card_reel") }}</th>
                                <th>{{ __("total") }}</th>
                                <th>{{ __("total_reel") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template
                                v-for="(total, totalIndex) in total_salepoints"
                                :key="totalIndex"
                            >
                                <tr>
                                    <th scope="row">
                                        <span class="text-capitalize">{{
                                            total?.sale_point_name
                                        }}</span>
                                    </th>
                                    <td class="text-end">
                                        {{ formatPrice(1 * total?.total_cash) }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            formatPrice(
                                                1 * total?.total_cash_reel
                                            )
                                        }}
                                    </td>
                                    <td class="text-end">
                                        {{ formatPrice(1 * total?.total_card) }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            formatPrice(
                                                1 * total?.total_card_reel
                                            )
                                        }}
                                    </td>

                                    <td class="text-end">
                                        <strong
                                            >{{ formatPrice(1 * total?.total) }}
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <strong
                                            >{{
                                                formatPrice(
                                                    1 * total?.total_reel
                                                )
                                            }}
                                        </strong>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </v-table>
                </template>
            </v-card>

            <v-card :title="__('reports')" class="mb-5">
                <template v-if="totals_by_day.length < 1">
                    <v-card-text>
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </v-card-text>
                </template>
                <template v-else>
                    <v-table fixed-header height="50vh">
                        <thead>
                            <tr>
                                <th scope="col">{{ __("date") }}</th>
                                <th scope="col">
                                    <v-icon icon="mdi-dots-vertical" />
                                </th>

                                <th scope="col">{{ __("sale_point") }}</th>
                                <th scope="col">{{ __("cash") }}</th>
                                <th scope="col">{{ __("cash_reel") }}</th>
                                <th scope="col">{{ __("card") }}</th>
                                <th scope="col">{{ __("card_reel") }}</th>
                                <th scope="col">{{ __("total") }}</th>
                                <th scope="col">{{ __("total_reel") }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template
                                v-for="(totalDay, totalIndex) in totals_by_day"
                                :key="totalIndex"
                            >
                                <tr
                                    v-for="(total, totalDayIndex) in totalDay"
                                    :key="totalDayIndex + '-' + totalIndex"
                                >
                                    <th
                                        :rowspan="totalDay.length"
                                        v-if="totalDayIndex == 0"
                                    >
                                        {{ toFormat(total?.date, "DDD") }}
                                    </th>
                                    <td>
                                        <v-btn
                                            color="primary"
                                            size="x-small"
                                            variant="outlined"
                                            icon="mdi-eye"
                                            class="ms-auto"
                                            :to="
                                                route('manager.reports.day', {
                                                    day: toFormat(
                                                        total.date,
                                                        'yyyy-MM-dd'
                                                    ),
                                                })
                                            "
                                        />
                                    </td>
                                    <th class="d-flex align-center">
                                        <span class="text-capitalize">{{
                                            total?.sale_point_name
                                        }}</span>
                                    </th>

                                    <td class="text-end">
                                        {{ formatPrice(1 * total?.total_cash) }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            formatPrice(
                                                1 * total?.total_cash_reel
                                            )
                                        }}
                                    </td>
                                    <td class="text-end">
                                        {{ formatPrice(1 * total?.total_card) }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            formatPrice(
                                                1 * total?.total_card_reel
                                            )
                                        }}
                                    </td>

                                    <td class="text-end">
                                        <strong
                                            >{{ formatPrice(1 * total?.total) }}
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <strong
                                            >{{
                                                formatPrice(
                                                    1 * total?.total_reel
                                                )
                                            }}
                                        </strong>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </v-table>
                </template>
            </v-card>
        </v-container>
    </ManagerLayout>
</template>
