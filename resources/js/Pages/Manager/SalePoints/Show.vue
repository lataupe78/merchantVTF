<script setup>
import { Link } from "@inertiajs/vue3";

import BoolLabel from "@/Components/BoolLabel.vue";
import ManagerLayout from "@/Layouts/ManagerLayout.vue";

import { degToDMS } from "@/helpers/geolocalisation.js";
// import FormSalePoint from '@/Pages/SalePoints/_FormSalePoint.vue';

const props = defineProps({
    sale_point: {
        type: Object,
        required: true,
    },
    options: {
        type: [Array, Object],
        default: () => {},
    },
    can: {
        type: Object,
        default: () => {},
    },
});
</script>
<template>
    <ManagerLayout :title="__('show_item', { item: __('sale_point') })">
        <template #actions>
            <div class="d-flex flex-wrap ga-4 me-4">
                <v-btn
                    color="secondary"
                    density="comfortable"
                    :to="route('manager.sale_points.index')"
                    >{{ __("sale_points.index") }}
                </v-btn>

                <v-btn
                    color="success"
                    density="comfortable"
                    v-if="can?.sale_point?.create"
                    :to="route('manager.sale_points.create')"
                >
                    {{ __("create_item", { item: __("sale_point") }) }}
                </v-btn>
            </div>
            <v-spacer></v-spacer>
            <div class="d-flex flex-wrap ga-4">
                <v-btn
                    color="primary"
                    density="comfortable"
                    v-if="can?.sale_point?.update"
                    :to="
                        route('manager.sale_points.edit', {
                            sale_point: sale_point,
                        })
                    "
                >
                    {{ __("edit_item", { item: __("sale_point") }) }}
                </v-btn>
            </div>
        </template>

        <v-container>
            <PreDebug title="can">{{ can }}</PreDebug>

            <v-row>
                <!-- SALE_POINT -->
                <v-col cols="12" lg="6">
                    <v-card :title="__('sale_point')">
                        <v-card-text>
                            <v-table>
                                <tbody>
                                    <tr>
                                        <td scope="row" class="text-capitalize">
                                            {{ __("is_active") }}
                                        </td>
                                        <td>
                                            <BoolLabel
                                                :value="!!sale_point?.is_active"
                                                :labels="[
                                                    __('inactive'),
                                                    __('active'),
                                                ]"
                                            />
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="(field, index) in [
                                            'name',
                                            'description',
                                            'address',
                                            'city',
                                        ]"
                                    >
                                        <td scope="row" class="text-capitalize">
                                            {{ __(field) }}
                                        </td>
                                        <td>
                                            {{ sale_point?.[field] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row" class="text-capitalize">
                                            {{ __("coordinates") }}
                                        </td>
                                        <td>
                                            <span class="d-block">{{
                                                degToDMS(sale_point?.latitude)
                                            }}</span>
                                            <span class="d-block">{{
                                                degToDMS(sale_point?.longitude)
                                            }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- WORKERS -->
                <v-col cols="12" lg="6">
                    <v-card :title="__('workers')">
                        <v-card-text>
                            <v-table>
                                <tbody>
                                    <tr
                                        v-for="(
                                            worker, workerIndex
                                        ) in sale_point.workers"
                                        :key="`worker-${workerIndex}`"
                                    >
                                        <td scope="row">
                                            {{ __(worker.name) }}
                                        </td>
                                        <td>
                                            <BoolLabel
                                                :value="!!worker?.is_active"
                                                :labels="[
                                                    __('inactive'),
                                                    __('active'),
                                                ]"
                                            />
                                        </td>
                                        <td>
                                            <v-chip
                                                color="info"
                                                v-for="(
                                                    role, roleIndex
                                                ) in worker.roles"
                                                :key="`worker-${workerIndex}-role-${roleIndex}`"
                                            >
                                                {{ role.name }}
                                            </v-chip>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <PreDebug title="sale_point">{{ sale_point }}</PreDebug>
        </v-container>

        <!-- <FormSalePoint :sale_point="sale_point" :options="options" /> -->
    </ManagerLayout>
</template>
