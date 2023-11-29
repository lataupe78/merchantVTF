<script setup>
import { ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    perPage: {
        type: Number,
        default: 10,
    },
    sessionKey: {
        type: String,
        required: true,
        default: "",
    },
});

const unflat = (obj, is, value) => {
    if (typeof is == "string") return unflat(obj, is.split("."), value);
    else if (is.length == 1 && value !== undefined) return (obj[is[0]] = value);
    else if (is.length == 0) return obj;
    else return unflat(obj[is[0]], is.slice(1), value);
};

const paginationKey = unflat(page?.props, props.sessionKey) || 0;

console.log({ sessionKey: props.sessionKey, paginationKey: paginationKey });

const perPage = ref(paginationKey || props.perPage);

const perPageItems = [2, 5, 10, 25, 50, 100, 250, 1000];
// route "pagination.update" should be guarded by auth middleware
// it affects session only
// use session key wisely like :
// pagination.seller.assets ..

watch(
    perPage,
    (perPageNew) => {
        if (props.sessionKey !== "") {
            axios
                .post(route("pagination.update"), {
                    per_page: perPageNew,
                    key: props.sessionKey,
                })
                .then(({ data }) => {
                    router.reload();
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {});
        }
    },
    {
        lazy: true,
        immediate: false,
    }
);
</script>

<template>
    <v-container class="pb-0">
        <v-row class="my-2">
            <div class="d-flex align-center">
                <v-select
                    v-model="perPage"
                    id="pagination-select"
                    :items="perPageItems"
                    density="compact"
                >
                    <template #append>
                        <label for="pagination-select" class="v-label">{{
                            __("pagination per_page", { per_page: "" })
                        }}</label>
                    </template>
                </v-select>
            </div>
        </v-row>
    </v-container>

    <!-- <pre>{{ page.props?.pagination }}</pre> -->
</template>
