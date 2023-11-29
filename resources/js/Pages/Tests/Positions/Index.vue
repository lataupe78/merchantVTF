<script setup>
import { ref, computed, watch, watchEffect } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
// import Pagination from "@/Components/Pagination/Pagination.vue";
import PositionMap from "@/Components/Maps/PositionMap.vue";

const props = defineProps({
    positions: [Array, Object],
});
</script>

<template>
    <FrontLayout title="List Positions">
        <template #actions>
            <div class="ms-auto d-flex flex-wrap gap-3">
                <v-btn :to="route('position.create')" color="primary">
                    {{ __("create_item", { item: __("position") }) }}
                </v-btn>
            </div>
        </template>

        <v-container>
            <PositionMap :collection="positions?.data" />

            <v-table fixed-header height="75vh" density="comfortable" hover>
                <thead>
                    <tr>
                        <th>{{ __("date") }}</th>
                        <th>{{ __("title") }}</th>
                        <th>{{ __("lat") }}</th>
                        <th>{{ __("lng") }}</th>
                        <th>{{ __("altitude") }}</th>
                        <th>{{ __("accuracy") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(position, positionIndex) in positions?.data"
                        :key="positionIndex"
                    >
                        <td>{{ position?.created_at }}</td>
                        <td>{{ position?.title }}</td>
                        <td>{{ position?.lat }}</td>
                        <td>{{ position?.lng }}</td>
                        <td>{{ position?.altitude }}</td>
                        <td>{{ position?.accuracy }}</td>
                    </tr>
                </tbody>
            </v-table>

            <!-- <Pagination
            :links="positions?.links"
            :last-page="positions?.last_page"
            class="mb-5"
        /> -->
        </v-container>
    </FrontLayout>
</template>
