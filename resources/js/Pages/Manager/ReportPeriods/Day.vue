<script setup>
import useTranslation from "@/composables/useTranslation.js";
const { __ } = useTranslation();

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import NavPeriod from "@/Components/Navs/NavPeriod.vue";
import { formatPrice } from "@/helpers/currency.js";
import { dateLabel, toFormat } from "@/helpers/dates.js";

const props = defineProps({
    totals: [Array, Object],
    reports_grouped: [Array, Object],
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

const prevNavLink = {
    url: route("manager.reports.day", {
        day: toFormat(props?.dates?.prev, "yyyy-MM-dd"),
    }),
    label: toFormat(props?.dates?.prev, "DDD"),
};
const nextNavLink = {
    url: route("manager.reports.day", {
        day: toFormat(props?.dates?.next, "yyyy-MM-dd"),
    }),
    label: toFormat(props?.dates?.next, "DDD"),
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
        // color: "blue",
        textColor: "text-orange",
        icon: "mdi-chart-line",
        url: route("manager.reports.month"),
    },
    {
        label: "reports.daily",
        color: "orange",
        // textColor: "text-orange",
        icon: "mdi-chart-line",
        url: route("manager.reports.day"),
    },
    {
        label: "reports.weekly",
        textColor: "text-orange",
        icon: "mdi-chart-line",
        url: route("manager.reports.week"),
    },
];
</script>

<template>
    <ManagerLayout
        :title="__('reports.daily') + ' ' + toFormat(dates?.current, 'DDD')"
        :action-links="actionLinks"
    >
        <v-container>
            <NavPeriod :prev="prevNavLink" :next="nextNavLink" />

            <TotalsCard
                v-for="(total, totalIndex) in totals"
                :key="totalIndex"
                :totals="total"
            />

            <v-card :title="__('reports')" class="mb-5">
                <v-card-text>
                    <template v-if="reports.length < 1">
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </template>
                    <template v-else>
                        <v-table fixed-header height="50vh">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __("sale_point") }}</th>
                                    <th scope="col">
                                        <v-icon icon="mdi-dots-vertical" />
                                    </th>
                                    <th scope="col">{{ __("seller") }}</th>
                                    <th scope="col">{{ __("cash") }}</th>
                                    <th scope="col">{{ __("cash_reel") }}</th>
                                    <th scope="col">{{ __("card") }}</th>
                                    <th scope="col">{{ __("card_reel") }}</th>
                                    <th scope="col">{{ __("total") }}</th>
                                    <th scope="col">{{ __("total_reel") }}</th>
                                    <th scope="col">{{ __("comment") }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <template
                                    v-for="(
                                        reports, sale_point_index
                                    ) in reports_grouped"
                                    :key="sale_point_index"
                                >
                                    <template
                                        v-for="(report, reportIndex) in reports"
                                        :key="reportIndex"
                                    >
                                        <tr class=" ">
                                            <td
                                                :rowspan="1 + reports.length"
                                                v-if="reportIndex == 0"
                                                class="border text-truncate"
                                            >
                                                <strong class="text-truncate">
                                                    {{
                                                        report?.sale_point?.name
                                                    }}
                                                </strong>
                                            </td>

                                            <!-- ACTIONS -->
                                            <td class="border">
                                                <v-menu>
                                                    <template
                                                        v-slot:activator="{
                                                            props,
                                                        }"
                                                    >
                                                        <v-btn
                                                            variant="outlined"
                                                            size="x-small"
                                                            color="primary"
                                                            v-bind="props"
                                                            icon="mdi-dots-vertical"
                                                        />
                                                    </template>
                                                    <v-list>
                                                        <v-list-item
                                                            :title="__('edit')"
                                                            :to="
                                                                route(
                                                                    'manager.reports.edit',
                                                                    {
                                                                        report: report,
                                                                    }
                                                                )
                                                            "
                                                        >
                                                            <template
                                                                v-slot:prepend
                                                            >
                                                                <v-icon
                                                                    icon="mdi-pencil"
                                                                />
                                                            </template>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </td>

                                            <td
                                                class="border text-truncate"
                                                style="max-width: 8rem"
                                            >
                                                <strong
                                                    class="text-wrap text-truncate text-capitalize"
                                                >
                                                    {{ report?.seller?.name }}
                                                </strong>
                                            </td>
                                            <td class="border text-end">
                                                {{
                                                    formatPrice(
                                                        1 * report?.cash
                                                    )
                                                }}
                                            </td>
                                            <td class="border text-end">
                                                {{
                                                    formatPrice(
                                                        1 * report?.cash_reel
                                                    )
                                                }}
                                            </td>
                                            <td class="border text-end">
                                                {{
                                                    formatPrice(
                                                        1 * report?.card
                                                    )
                                                }}
                                            </td>
                                            <td class="border text-end">
                                                {{
                                                    formatPrice(
                                                        1 * report?.card_reel
                                                    )
                                                }}
                                            </td>

                                            <td class="border text-end">
                                                <strong
                                                    >{{
                                                        formatPrice(
                                                            1 *
                                                                (report?.cash +
                                                                    report?.card)
                                                        )
                                                    }}
                                                </strong>
                                            </td>
                                            <td class="border text-end">
                                                <strong
                                                    >{{
                                                        formatPrice(
                                                            1 *
                                                                (report?.cash_reel +
                                                                    report?.card_reel)
                                                        )
                                                    }}
                                                </strong>
                                            </td>

                                            <td class="border">
                                                {{ report?.comment }}
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- TOTALS -->
                                    <tr class="h6 fw-bold">
                                        <td
                                            colspan="2"
                                            class="border text-info text-end"
                                        >
                                            {{ __("totals") }}
                                        </td>
                                        <td class="border text-info text-end">
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 * report.cash,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>
                                        <td class="border text-info text-end">
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 *
                                                                report.cash_reel,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>
                                        <td class="border text-info text-end">
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 * report.card,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>
                                        <td class="border text-info text-end">
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 *
                                                                report.card_reel,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="border text-info font-weight-bold"
                                        >
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 * report.card +
                                                            1 * report.cash,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="border text-info font-weight-bold"
                                        >
                                            {{
                                                formatPrice(
                                                    reports.reduce(
                                                        (total, report) =>
                                                            total +
                                                            1 *
                                                                report.card_reel +
                                                            1 *
                                                                report.cash_reel,
                                                        0
                                                    )
                                                )
                                            }}
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </v-table>
                    </template>
                </v-card-text>
            </v-card>
        </v-container>
    </ManagerLayout>
</template>
