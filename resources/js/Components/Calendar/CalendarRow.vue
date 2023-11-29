<script setup>
const props = defineProps({
    currentRange: [Array, Object],
    indexRow: [Number, String],
    showInfo: Boolean,
    showCellLabel: Boolean,
    isSelectable: Boolean,
});

const daysLength = props.currentRange.length;

const infoCellWidth = "160px";
</script>
<template>
    <tr>
        <template v-if="showInfo || $slots.info">
            <th
                scope="row"
                class="col-info border text-truncate"
                style="max-width: 160px !important"
            >
                <slot name="info" :index="indexRow" :range="currentRange">
                    INfOS
                </slot>
            </th>
        </template>

        <template
            v-for="(date, colIndex) in currentRange"
            :key="`week-${indexRow}-day-${date?.labelData}`"
        >
            <td
                class="day border cell-container"
                :class="{ 
                    'date-selected': !!date?.selected,
                    'date-inactive': !!!date?.isActive,
             }"
            >
                <span
                    class="cell-label text-secondary text-body-2"
                    v-if="showCellLabel"
                >
                    {{ date.labelShort }}
                </span>

                <slot
                    name="cell"
                    :date="date"
                    :row="indexRow"
                    :title="date.labelLong"
                    :column="colIndex"
                />
            </td>
        </template>
    </tr>
</template>
<style>
.cell-container {
    position: relative;
    min-height: 48px !important;
    background: rgba(var(--v-theme-primary), 0.25);
}

.cell-container:hover {
    background: rgba(var(--v-theme-primary), 0.5);
}

.cell-container.date-inactive {
    background: rgba(var(--v-theme-dark), 0.5) !important;
}

.cell-container.date-selected {
    background: rgba(var(--v-theme-success), 0.5) !important;
}

.cell-container.date-selected:hover {
    background: rgba(var(--v-theme-success), 0.75) !important;
}
.cell-label {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
}
</style>
