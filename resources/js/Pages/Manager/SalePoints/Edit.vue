<script setup>
import { Link } from "@inertiajs/vue3";
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import FormSalePoint from "@/Pages/Manager/SalePoints/_FormSalePoint.vue";

const props = defineProps({
    sale_point: {
        type: Object,
        required: true,
    },
    options: {
        type: [Array, Object],
        default: () => {},
    },
});
</script>
<template>
    <ManagerLayout
        :title="
            __('edit_item', {
                item: __('named_item', {
                    item: __('sale_point'),
                    name: sale_point?.name,
                }),
            })
        "
    >
        <template #actions>
            <v-btn :to="route('manager.sale_points.index')"
                >{{ __("sale_points.index") }}
            </v-btn>
        </template>
        <v-container>
            <FormSalePoint :sale_point="sale_point" :options="options" />
        </v-container>
    </ManagerLayout>
</template>
