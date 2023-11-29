<script setup>
import { computed } from "vue";
import { getDateTime, toFormat } from "@/helpers/dates.js";

const props = defineProps({
    start: {
        type: [String, Object, null],
        required: true,
        default: null,
    },
    end: {
        type: [String, Object, null],
        required: true,
        default: null,
    },
});

const diffDuration = computed(() => {
    let duration = null;

    let start = props.start;
    let end = props.end;

    if (typeof start == "string") {
        start = getDateTime(start);
    }
    if (typeof end == "string") {
        end = getDateTime(end);
    }

    if (start?.isValid === true && end?.isValid === true) {
        duration = end.diff(start, ["days"]);

        console.log({ duration });
        return Math.round(duration.days);
    }

    return duration;
});

const color = computed(() => {
    if (diffDuration == null) {
        return "error";
    }

    if (diffDuration.value > -7) {
        return "info";
    }
    return "error";
});
</script>
<template>
    <div>
        <v-tooltip activator="parent" location="top">
            <template v-slot:activator="{ props }">
                <v-chip :color="color" v-if="diffDuration !== null">
                    {{ diffDuration }}
                </v-chip>
            </template>
            <span
                >{{ diffDuration }} jours par rapport au
                {{ toFormat(start, "dd/MM/yyyy") }}</span
            >
        </v-tooltip>
    </div>
</template>
