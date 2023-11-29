<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toFormat } from "@/helpers/dates.js";

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

const form = useForm({
    sale_point_id: props.report?.sale_point_id || null,
    date: toFormat(props.report?.date || new Date(), "yyyy-MM-dd"),
    cash: props.report?.cash || 0,
    cash_reel: props.report?.cash_reel || 0,
    card: props.report?.card || 0,
    card_reel: props.report?.card_reel || 0,
    comment: props.report?.comment || "",
    seller_id: props.report?.seller_id || null,
    author_id: props.report?.author_id || null,
});

const action = props.report?.id ? "edit" : "create";

const formRoute =
    action == "edit"
        ? route("manager.reports.update", { report: props.report })
        : route("manager.reports.store");

const formMethod = action == "edit" ? "put" : "post";

const submitForm = () => {
    form.transform((data) => ({
        ...data,
        _method: formMethod,
    })).post(formRoute, {
        onFinish: () => {},
    });
};
</script>
<template>
    <form @submit.prevent="submitForm">
        <v-card class="my-5" :title="__('report')">
            <v-card-text>
                <v-row>
                    <v-col cols="12" sm="4">
                        <v-text-field
                            v-model="form.date"
                            :label="__('date')"
                            :disabled="action == 'edit'"
                            autofocus
                            required
                            :error-messages="form.errors.date"
                        />
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-select
                            v-model="form.sale_point_id"
                            :label="__('sale_point')"
                            :items="options.sale_points"
                            :error-messages="form.errors.sale_point_id"
                            item-title="name"
                            item-value="id"
                        />
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-select
                            v-model="form.seller_id"
                            :label="__('seller')"
                            :items="options.sellers"
                            :error-messages="form.errors.seller_id"
                            item-title="name"
                            item-value="id"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="form.cash"
                            :label="__('cash')"
                            required
                            :error-messages="form.errors.cash"
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="form.cash_reel"
                            :label="__('cash_reel')"
                            required
                            :error-messages="form.errors.cash_reel"
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="form.card"
                            :label="__('card')"
                            required
                            :error-messages="form.errors.card"
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="form.card_reel"
                            :label="__('card_reel')"
                            required
                            :error-messages="form.errors.card_reel"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-textarea
                            :label="__('comment')"
                            v-model="form.comment"
                            :error-messages="form.errors.comment"
                        />
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="justify-space-around">
                <v-btn size="x-large" variant="outlined" class="px-12" :to="route('manager.reports.index')">
                    {{ __("cancel") }}
                </v-btn>
                <v-btn 
                color="secondary"
                type="submit"
                size="x-large" variant="outlined" class="px-12" 
                >
                    {{ __(action) }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </form>

    <PreDebug :show="true" title="form">{{ form }}</PreDebug>
</template>
