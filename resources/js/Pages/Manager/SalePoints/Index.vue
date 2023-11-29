<script setup>
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import BoolLabel from "@/Components/BoolLabel.vue";
import SalePointMap from "@/Components/Maps/SalePointMap.vue";

defineProps({
    sale_points: { type: [Array, Object], required: true },
    can: {
        type: Object,
        default: () => {},
    },
});

const params = ref({ ...{ sort_by: "", dir: "" } });

const currentPage = ref(null);

const updatePagination = (page = null) => {
    params.value.page = page;
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

const deleteItem = (item) => {
    if (confirm("Are you sure ?") === true) {
        router.delete(
            route("manager.sale_points.destroy", { sale_point: item }),
            {
                onError(error) {
                    console.error(error);
                    // debugger;
                },
            }
        );
    }
};
</script>

<template>
    <ManagerLayout :title="__('sale_points.index')">
        <template #actions>
            <template v-if="can?.sale_point?.create">
                <v-btn :to="route('manager.sale_points.create')">
                    {{ __("create_item", { item: __("sale_point") }) }}
                </v-btn>
            </template>
        </template>

        <v-container>
            <v-card :title="__('sale_points')" class="mb-5">
                <template v-if="sale_points?.data?.length < 1">
                    <v-card-text>
                        <v-alert type="info" variant="outlined">
                            {{ __("no results") }}
                        </v-alert>
                    </v-card-text>
                </template>
                <template v-else>
                    <SalePointMap
                        :collection="sale_points?.data"
                        class="mb-4"
                    />

                    <v-alert type="info" variant="tonal" closable>
                        Vous pouvez éditer chaque point de vente et gérez les
                        employés associés
                    </v-alert>

                    <v-table fixed-header heigth="50vh">
                        <thead class="table">
                            <tr>
                                <th scope="col">
                                    <v-icon icon="mdi-dots-vertical" />
                                </th>
                                <th scope="col">
                                    {{ __("sale_points") }}
                                </th>
                                <th scope="col">
                                    {{ __("is_active") }}
                                </th>
                                <th scope="col">
                                    {{ __("address") }}
                                </th>

                                <th scope="col">
                                    {{ __("workers") }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(sale_point, index) in sale_points.data"
                                :key="`salepoint-${index}`"
                                class=""
                            >
                                <td>
                                    <v-menu>
                                        <template v-slot:activator="{ props }">
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
                                                :title="__('show')"
                                                :to="
                                                    route(
                                                        'manager.sale_points.show',
                                                        {
                                                            sale_point:
                                                                sale_point,
                                                        }
                                                    )
                                                "
                                            >
                                                <template v-slot:prepend>
                                                    <v-icon icon="mdi-eye" />
                                                </template>
                                            </v-list-item>
                                            <v-list-item
                                                :title="__('edit')"
                                                :to="
                                                    route(
                                                        'manager.sale_points.edit',
                                                        {
                                                            sale_point:
                                                                sale_point,
                                                        }
                                                    )
                                                "
                                            >
                                                <template v-slot:prepend>
                                                    <v-icon icon="mdi-pencil" />
                                                </template>
                                            </v-list-item>

                                            <v-list-item
                                                :title="__('delete')"
                                                @click="deleteItem(sale_point)"
                                            >
                                                <template v-slot:prepend>
                                                    <v-icon
                                                        color="error"
                                                        icon="mdi-delete"
                                                    />
                                                </template>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </td>

                                <th scope="row" class="font-weight-bold">
                                    {{ sale_point?.name }}
                                </th>
                                <td>
                                    <BoolLabel
                                        :value="!!sale_point?.is_active"
                                        :labels="[__('inactive'), __('active')]"
                                    />
                                </td>
                                <td>
                                    <p>
                                        {{ sale_point?.address }}
                                        <span class="text-uppercase">{{
                                            sale_point?.city
                                        }}</span>
                                    </p>
                                </td>

                                <td>
                                    <span class="text-nowrap">{{
                                        trans_choice(
                                            "pagination_items_count",
                                            sale_point?.workers.length,
                                            {
                                                item: __("worker"),
                                                items: __("workers"),
                                            }
                                        )
                                    }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </template>
            </v-card>

            <v-pagination
                v-model="currentPage"
                :total-visible="8"
                :length="sale_points?.last_page"
                @update:modelValue="updatePagination"
            ></v-pagination>
        </v-container>
    </ManagerLayout>
</template>
