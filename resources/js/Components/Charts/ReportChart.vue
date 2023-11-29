<script setup>
import { onBeforeMount, computed } from "vue";
import { Line } from "vue-chartjs";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";

// import { Bar } from 'vue-chartjs'
// import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

onBeforeMount(() => {
    // ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
    ChartJS.register(
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend
    );
});

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    chartOptions: {
        type: Object,
        default: () => {},
    },
});

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: true,

    scales: {
        y: {
            ticks: {
                beginAtZero: true,
                callback: function (value, index, values) {
                    return new Intl.NumberFormat("fr-FR", {
                        style: "currency",
                        currency: "EUR",
                    }).format(value);
                },
            },
        },
    },

    plugins: {
        tooltip: {
            callbacks: {
                label: function (context) {
                    let label = context.dataset.label || "";

                    if (label) {
                        label += ": ";
                    }
                    if (context.parsed.y !== null) {
                        label += new Intl.NumberFormat("fr-FR", {
                            style: "currency",
                            currency: "EUR",
                        }).format(context.parsed.y);
                    }
                    return label;
                },
            },
        },
    },
};

const styles = computed(() => {
    return {
        maxHeight: `25vh`,
        // maxWidth : '512px',
        position: "relative",
    };
});
</script>

<template>
    <v-container>
        <Line
            :data="chartData"
            :options="{ ...defaultOptions, ...chartOptions }"
            :style="styles"
        />
    </v-container>
</template>
