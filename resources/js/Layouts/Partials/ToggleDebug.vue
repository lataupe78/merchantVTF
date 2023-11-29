<script setup>
import { ref, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";

const $page = usePage();

const debug = ref($page.props?.debug || false);

const processing = ref(false);

watch(
    () => debug.value,
    (debugValue) => {
        console.log({ debug: debugValue });

        processing.value = true;
        router.put(
            route("manager.settings.debug.toggle"),
            {
                debug: debugValue,
            },
            {
                replace: true,
                preserveState: false,
                preserveScroll: true,
                onFinish: (visit) => {
                    processing.value = false;
                },
                onError(error) {
                    console.error(error);
                    debugger;
                },
            }
        );
    },
    { immediate: false }
);
</script>
<template>
    <v-switch v-model="debug" color="primary" :label="__('Debug')" />
</template>
