<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";

import BoolLabel from "@/Components/BoolLabel.vue";
import Sorter from "@/Components/Table/Sorter.vue";
import PaginationSelect from "@/Components/Table/PaginationSelect.vue";

import FiltersPunching from "./_FiltersPunching.vue";

const props = defineProps({
    punchings: { type: [Array, Object], required: true },
    filters: { type: [Array, Object], required: true },
    options: {
        type: [Array, Object],
        default() {
            return {
                workers: [],
                sale_points: [],
            };
        },
    },
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

const currentPage = ref(null);

const updatePagination = (page = null) => {
    params.value.page = page;
};

watch(
    params,
    (newParams) => {
        console.log("watching params", newParams);

        router.get(route("manager.plannings.punchings.index"), newParams, {
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

const actionLinks = [
    {
        label: "plannings Dashboard",
        icon: "mdi-chart-line",
        url: route("manager.plannings.dashboard"),
        color: "orange",
    },
    {
        label: "Validation Index",
        icon: "mdi-chart-line",
        url: route("manager.plannings.validation.index"),
       
    },
];
</script>
<template>
    <ManagerLayout
        :title="
            __('index_items', {
                items: __('punchings'),
            })
        "
        :action-links="actionLinks"
    >
        <v-container>
            <v-alert type="info" variant="tonal" class="mb-8">
                <p>Liste des pointages, filtrable</p>
            </v-alert>

            <FiltersPunching
                :filters="filters"
                :options="options"
                @filters-updated="updateCurrentFilters"
            />

            <PreDebug title="punchings">{{ punchings }}</PreDebug>
            <v-card   class="mb-16" color="indigo-darken-3">
                <v-card-text>
                    <PaginationSelect sessionKey="pagination.punchings" />
                    <template v-if="punchings.data?.length < 1">
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </template>
                    <template v-else>
                        <v-table fixed-header :hover="true" height="50vh">
                            <template v-slot:top>
                                <div class="text-body-1 text-info px-4 my-4">
                                    {{
                                        trans_choice(
                                            "pagination_items_count",
                                            punchings.total,
                                            {
                                                item: __("punching"),
                                                items: __("punchings"),
                                            }
                                        )
                                    }}
                                </div>
                            </template>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <Sorter
                                            @sort="sortParams"
                                            :params="params"
                                            field="worker_id"
                                            :label="'worker'"
                                        />
                                    </th>
                                    <th scope="col">
                                        <Sorter
                                            @sort="sortParams"
                                            :params="params"
                                            field="current_date"
                                            :label="'date'"
                                        />
                                    </th>
                                    <th scope="col">{{ __("status") }}</th>
                                    <th scope="col">
                                        <Sorter
                                            @sort="sortParams"
                                            :params="params"
                                            field="punched_at"
                                            :label="'punched_at'"
                                        />
                                    </th>

                                    <th scope="col">{{ __("location") }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(
                                        punching, punchingIndex
                                    ) in punchings?.data"
                                    :key="`row-punching-${punchingIndex}`"
                                >
                                    <td style="max-width: 8rem">
                                        <div
                                            class="text-body-1 font-weight-bold text-wrap text-truncate text-capitalize mb-1"
                                        >
                                            {{
                                                punching.worker?.name ||
                                                punching.worker_id
                                            }}
                                        </div>
                                    </td>
                                    <td>
                                        {{
                                            toFormat(
                                                punching.current_date,
                                                "dd/MM/yyyy"
                                            )
                                        }}
                                    </td>

                                    <td>
                                        <BoolLabel
                                            :value="
                                                !!(punching?.status === 'in')
                                            "
                                            :labels="[__('off'), __('in')]"
                                        />
                                    </td>
                                    <td>
                                        {{
                                            toFormat(
                                                punching.punched_at,
                                                "dd/MM/yyyy HH:mm:ss"
                                            )
                                        }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </v-table>

                        <v-pagination
                            v-model="currentPage"
                            :total-visible="8"
                            :length="punchings?.last_page"
                            @update:modelValue="updatePagination"
                        ></v-pagination>
                    </template>
                </v-card-text>
            </v-card>
        </v-container>
    </ManagerLayout>
</template>
