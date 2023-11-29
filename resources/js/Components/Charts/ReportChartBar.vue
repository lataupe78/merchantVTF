<script setup>
import { onBeforeMount, computed } from "vue";

// spread operator only makes shallow merge, we need deep merge
import { merge } from "lodash";

import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

onBeforeMount(() => {
    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        BarElement,
        CategoryScale,
        LinearScale
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

const defaultData = {
    datasets: [
        {
            backgroundColor: [
                "rgba(255, 99, 132, 0.2)",
                "rgba(255, 159, 64, 0.2)",
                "rgba(255, 205, 86, 0.2)",
                "rgba(75, 192, 192, 0.2)",
                "rgba(54, 162, 235, 0.2)",
                "rgba(153, 102, 255, 0.2)",
                "rgba(201, 203, 207, 0.2)",
            ],
            borderColor: [
                "rgb(255, 99, 132)",
                "rgb(255, 159, 64)",
                "rgb(255, 205, 86)",
                "rgb(75, 192, 192)",
                "rgb(54, 162, 235)",
                "rgb(153, 102, 255)",
                "rgb(201, 203, 207)",
            ],
            borderWidth: 1,
        },
    ],
};

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

const BarData = merge(defaultData, props.chartData);

const BarOptions = merge(defaultOptions, props.chartOptions);

const styles = computed(() => {
    return {
        maxHeight: `20vh`,
        // maxWidth : '512px',
        position: "relative",
    };
});
</script>

<template>
    <v-container>
        <Bar :data="BarData" :options="BarOptions" :style="styles" />
    </v-container>

    <!-- <div class="row">
    <pre class="col">
      BarData
      {{BarData}}
    </pre>
    <pre class="col">
      BarOptions
      {{BarOptions}}
    </pre>
  </div> -->
</template>
