<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const $page = usePage();

const minHeight = 160;

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    show: {
        type: Boolean,
        default: false,
    },
    classes: {
        type: String,
        default: " ",
    },
});

const showFullHeight = ref(false);
</script>

<template>
    <pre
        v-if="show || $page?.props?.debug == true"
        class="predebug border border-2 rounded-3 pa-4 mb-4 text-start"
        @click.prevent="showFullHeight = !showFullHeight"
        :class="classes"
        :style="{
            'max-height': showFullHeight ? '100%' : minHeight + 'px',
        }"
    ><h5 class="fw-bold fs-5 mt-0 mb-2" v-show="props.title"> {{ title }}</h5><div class="content"><slot /></div></pre>
</template>
<style>
.predebug {
    width: 100%;
    word-wrap: break-word;
    white-space: pre-wrap;
    cursor: pointer;
    font-size: 0.8rem;
    line-height: 0.92rem;
    overflow: hidden;
    min-height: 96px;
    background-color: rgba(var(--v-theme-info), 0.5) !important;
}
.predebug .content {
    /* border: 2px solid red; */
    max-height: 512px;
    overflow-y: auto !important;
}
</style>
