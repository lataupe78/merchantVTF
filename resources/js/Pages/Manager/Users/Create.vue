<script setup>
import ManagerLayout from "@/Layouts/ManagerLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import FormUser from "./_FormUser.vue";
const props = defineProps({
    user: { type: [Array, Object], required: true },
    options: [Array, Object],
});
</script>
<template>
    <ManagerLayout>
        <Head :title="__('create_item', { item: __('worker') })" />

   
        <template #actions>
            <v-btn
                color="secondary"
                density="comfortable"
                :to="route('manager.users.index')"
                :text="
                    __('index_items', {
                        items: __('workers'),
                    })
                "
            />

           
        </template>

        <v-container>
            <FormUser :user="user" :options="options" />
        </v-container>
    </ManagerLayout>
</template>
