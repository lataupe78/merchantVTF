<script setup>
const props = defineProps({
    rangeDates: [Array, Object],
    currentRange: {
        type: [Array, Object],
        default() {
            return [];
        },
    },
    showTitle: {
        type: Boolean,
        default: true,
    },
    showInfo: {
        type: Boolean,
        default: false,
    },
    showDates: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <thead>
        <!-- WEEK_TITLE -->

        <tr v-if="$slots.title">
            <th
                v-if="showInfo"
                class="col-info border text-center"
                colspan="1"
                rowspan="3"
            ></th>

            <th colspan="7" class="border text-center text-h5">
                <slot name="title" />
            </th>
        </tr>

        <!-- WEEK_DAYS -->
        <tr v-if="currentRange.length">
            <th
                class="day border text-center pa-0"
                v-for="date in currentRange"
                :key="`label-long-${date?.labelData}`"
            >
                <span class="text-subtitle-1 text-uppercase">
                    {{ date.labelLong[0] }}
                </span>
            </th>
        </tr>
        <!-- WEEK_DAYS_DATES -->
        <tr v-if="showDates">
            <!-- <th>{{__('sale_point')}}</th> -->
            <th
                class="day border text-center"
                v-for="date in currentRange"
                :key="`date-short-${date?.labelData}`"
            >
                {{ date.dateShort }}
            </th>
        </tr>
    </thead>
</template>
