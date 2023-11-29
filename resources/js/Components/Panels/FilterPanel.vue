<script setup>
import { ref, computed } from "vue";

const emit = defineEmits(["apply"]);

const props = defineProps({
    filters: [Array, Object],
    allowedFilters: [Array],
});

const processing = ref(false);

const hasActiveFilters = computed(() => {
    if (props.allowedFilters.length == 0) {
        return true;
    }

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    // const allowedFilters = [
    //     "date_start",
    //     "date_end",
    //     "salepoints_ids[]",
    //     "sellers_ids[]",
    // ];

    let keysForDel = [];
    urlParams.forEach((value, key) => {
        if (props.allowedFilters.includes(key) == false || value == "") {
            keysForDel.push(key);
        }
    });

    // console.log({
    //   urlParams: urlParams.toString(),
    //   size: urlParams.size
    // })

    return urlParams.size > 0;
});

const showFilters = ref(hasActiveFilters.value ? "show" : "");
</script>
<template>
    
    <v-card color="indigo-darken-3" class="mb-8">
        <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
                label
                prepend-icon="mdi-filter-outline"
                :append-icon="
                    showFilters ? 'mdi-chevron-up' : 'mdi-chevron-down'
                "
                @click="showFilters = !showFilters"
            >
                {{ __("filters") }}
            </v-btn>
        </v-card-actions>

        <v-expand-transition>
            <div v-show="showFilters">
                <v-divider></v-divider>

                <v-card-text>
                    <slot />
                </v-card-text>

                <v-card-actions>
                    <v-spacer />
                    <v-btn
                        color="primary"
                        size="large"
                        variant="flat"
                        @click.prevent="emit('apply')"
                        :disabled="processing"
                    >
                        {{ __("filters.apply") }}
                    </v-btn>
                    <v-spacer />
                </v-card-actions>
            </div>
        </v-expand-transition>
    </v-card>
</template>
