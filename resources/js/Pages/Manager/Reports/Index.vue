<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";
import { formatPrice } from "@/helpers/currency.js";

import useTranslation from "@/composables/useTranslation.js";
const { __ } = useTranslation();

import ManagerLayout from "@/Layouts/ManagerLayout.vue";

import Sorter from "@/Components/Table/Sorter.vue";
import MenuActions from "@/Components/Menus/Actions.vue";
import PaginationSelect from "@/Components/Table/PaginationSelect.vue";

import FiltersReport from "./_FiltersReport.vue";

const props = defineProps({
    reports: { type: [Array, Object], required: true },
    filters: { type: [Array, Object], required: true },
    sale_points: { type: [Array, Object], required: true },
    sellers: { type: [Array, Object], required: true },
});

const params = ref({ ...props.filters, ...{ sort_by: "", dir: "" } });

const sortParams = (field) => {
    console.log({ action: "SORT", field: field });
    params.value.sort_by = field;
    params.value.dir = params.value.dir === "asc" ? "desc" : "asc";
};

const updateCurrentFilters = (filters) => {
    console.log("updateCurrentFilters", { filters: filters });
    params.value = { ...params.value, ...filters };
};

watch(
    params,
    (newParams) => {
        console.log("watching params", newParams);

        router.get(route("manager.reports.index"), newParams, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            onFinish: (visit) => {
                // processing.value = false;
            },
            onError(error) {
                console.error(error);
                // debugger;
            },
        });
    },
    {
        deep: true,
        immediate: false,
    }
);

const currentPage = ref(null);

const updatePagination = (page = null) => {
    params.value.page = page;
};

const deleteItem = (item) => {
    if (confirm("Are you sure ?") === true) {
        router.delete(route("manager.reports.destroy", { report: item }), {
            onError(error) {
                console.error(error);
                // debugger;
            },
        });
    }
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
    <ManagerLayout :title="__('reports')" :action-links="actionLinks">
        <v-container>
            <FiltersReport
                :filters="filters"
                :options="{ sale_points, sellers }"
                @filters-updated="updateCurrentFilters"
            />

            <PaginationSelect sessionKey="pagination.reports" />

            <v-table density="comfortable" fixed-header height="50vh">
                <thead class="text-capitalize">
                    <tr class="text-capitalize">
                        <th class="border text-center" scope="col" rowspan="2">
                            <v-icon icon="mdi-dots-vertical" />
                        </th>

                        <th class="border" rowspan="2">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="date"
                                :label="'date'"
                            />
                        </th>

                        <th>
                            <Sorter
                                class="d-block"
                                :label="__('sale_point')"
                                @sort="sortParams"
                                :params="params"
                                field="sale_point_id"
                            />
                        </th>

                        <th class="border" rowspan="2">
                            {{ __("type") }}
                        </th>

                        <th>
                            <Sorter
                                :label="__('cash')"
                                @sort="sortParams"
                                :params="params"
                                field="cash"
                            />
                        </th>
                        <th>
                            <Sorter
                                :label="__('card')"
                                @sort="sortParams"
                                :params="params"
                                field="card"
                            />
                        </th>

                        <th scope="col" class="border" rowspan="2">
                            {{ __("totals") }}
                        </th>

                        <th>
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="created_at"
                            />
                        </th>

                        <!-- <th class="border" scope="col" rowspan="2">
                        {{ __("comment") }}
                    </th> -->
                    </tr>

                    <tr>
                        <th class="border">
                            <Sorter
                                class="text-secondary"
                                :label="__('seller')"
                                @sort="sortParams"
                                :params="params"
                                field="seller_id"
                            />
                        </th>
                        <th class="border">
                            <Sorter
                                class="text-info"
                                :label="__('cash_reel')"
                                @sort="sortParams"
                                :params="params"
                                field="cash_reel"
                            />
                        </th>
                        <th>
                            <Sorter
                                class="text-info"
                                :label="__('card_reel')"
                                @sort="sortParams"
                                :params="params"
                                field="card_reel"
                            />
                        </th>
                        <th>
                            <Sorter
                                class="text-muted"
                                @sort="sortParams"
                                :params="params"
                                field="updated_at"
                            />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="(report, reportIndex) in reports?.data"
                        :key="reportIndex"
                    >
                        <tr>
                            <!-- ACTIONS -->
                            <td class="border text-center px-0" rowspan="2">
                                <MenuActions
                                    :items="[
                                        {
                                            label: __('edit'),
                                            url: route('manager.reports.edit', {
                                                report: report,
                                            }),
                                            icon: 'mdi-pencil',
                                        },
                                    ]"
                                >
                                    <v-list-item
                                        :title="__('delete')"
                                        @click="deleteItem(report)"
                                    >
                                        <template v-slot:prepend>
                                            <v-icon
                                                color="error"
                                                icon="mdi-delete"
                                            />
                                        </template>
                                    </v-list-item>
                                </MenuActions>

                                <!-- <Link v-if="salepoint?.can['delete']"  -->
                            </td>

                            <th rowspan="2" scope="row">
                                <span
                                    class="date text-nowrap text-capitalize"
                                    >{{
                                        toFormat(report?.date, "EEE dd/MM/yyyy")
                                    }}</span
                                >
                            </th>
                            <!-- SALE_POINT & SELLER -->
                            <td
                                rowspan="2"
                                class="border text-truncate"
                                style="
                                    width: 6rem !important;
                                    max-width: 6rem !important;
                                "
                            >
                                <p
                                    class="text-subtitle-1 font-weight-bold text-wrap text-truncate text-capitalize mb-1"
                                >
                                    {{ report.sale_point?.name }}
                                </p>
                                <p
                                    class="text-subtitle-2 text-secondary text-wrap text-truncate text-capitalize text-muted"
                                >
                                    {{ report.seller?.name }}
                                </p>
                            </td>

                            <td class="border">{{ __("raw") }}</td>
                            <!-- RAW -->
                            <td class="border text-end">
                                {{ formatPrice(report?.cash) }}
                            </td>
                            <td class="border text-end">
                                {{ formatPrice(report?.card) }}
                            </td>
                            <!-- TOTAL RAW -->
                            <td class="border text-end">
                                <strong class="">{{
                                    formatPrice(report?.cash + report?.card)
                                }}</strong>
                            </td>

                            <!-- DATES -->
                            <td rowspan="2">
                                <p
                                    class="date d-block mb-3"
                                    :title="__('created_at')"
                                >
                                    {{
                                        toFormat(
                                            report?.created_at,
                                            "dd/MM/yy HH: mm"
                                        )
                                    }}
                                </p>
                                <p
                                    class="date d-block mb-3 text-muted"
                                    :title="__('updated_at')"
                                    v-if="
                                        report?.created_at !==
                                        report?.updated_at
                                    "
                                >
                                    {{
                                        toFormat(
                                            report?.updated_at,
                                            "dd/MM/yy HH:mm"
                                        )
                                    }}
                                </p>
                            </td>
                            <!-- COMMENT -->
                            <!-- <td
                            class="border text-truncate"
                            rowspan="2"
                            style="
                                width: 4rem !important;
                                max-width: 4rem !important;
                            "
                        >
                            <p
                                class="text-wrap text-truncate text-capitalize mb-1"
                            >
                                <small class="text-muted">{{
                                    report?.comment
                                }}</small>
                            </p>
                        </td> -->
                        </tr>

                        <tr>
                            <td class="border text-info">{{ __("reel") }}</td>
                            <td class="border text-end text-info">
                                {{ formatPrice(report?.cash_reel) }}
                            </td>
                            <td class="border text-end text-info">
                                {{ formatPrice(report?.card_reel) }}
                            </td>
                            <!-- TOTAL_REEL -->
                            <td class="border text-info text-end">
                                <strong class="">{{
                                    formatPrice(
                                        report?.cash_reel + report?.card_reel
                                    )
                                }}</strong>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </v-table>

            <v-pagination
                v-model="currentPage"
                :total-visible="8"
                :length="reports?.last_page"
                @update:modelValue="updatePagination"
            ></v-pagination>
        </v-container>
    </ManagerLayout>
</template>
