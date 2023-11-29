<script setup>
import { formatPrice } from "@/helpers/currency.js";

const props = defineProps({
    totals: {
        type: Object,
        required: true,
    },
    title: String,
});

const cards = [
    { label: "cash", key: props?.totals?.total_cash, showAnyway: true },
    { label: "card", key: props?.totals?.total_card, showAnyway: true },
    {
        label: "cash_reel",
        key: props?.totals?.total_cash_reel,
        showAnyway: true,
    },
    {
        label: "card_reel",
        key: props?.totals?.total_card_reel,
        showAnyway: true,
    },
    { label: "total", key: props?.totals?.total, showAnyway: false },
    { label: "total_reel", key: props?.totals?.total_reel, showAnyway: false },
];
</script>

<template>
  
        <slot name="title">
            <div class="text-capitalize">
                <h3 v-if="title">{{ title }}</h3>
            </div>
        </slot>

        <!-- <pre class="my-3">{{totals}}</pre> -->

        <v-row class="mb-8">
            <template
                v-for="(card, index) in cards"
                :key="`card-${card.label}`"
            >
                <v-col
                    cols="12"
                    md="4"
                    lg="6"
                    v-if="
                        card.key !== undefined ||
                        (card.key === undefined && card?.showAnyway === true)
                    "
                >
                    <v-card :title="__(card.label)" color="info">
                        <v-card-text>
                            <p
                                class="currency"
                                :class="{ 'text-danger': isNaN(1 * card.key) }"
                            >
                                {{ formatPrice(1 * card.key) }}
                            </p>
                            <!-- <pre>{{(card.key==undefined)}}</pre> -->
                        </v-card-text>
                    </v-card>
                </v-col>
            </template>
        </v-row>
  
</template>
