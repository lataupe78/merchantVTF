<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FrontLayout from "@/Layouts/FrontLayout.vue";
// import VuetifyLayout from "@/Layouts/VuetifyLayout.vue";
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: false,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <FrontLayout title="Welcome">
        <v-container class="d-flex flex-column">
            <ApplicationLogo
                style="max-height: 25vh !important"
                class="mx-auto text-center"
            />

            <v-card class="p-12 text-center">
                <template v-slot:title>
                    <div class="text-h2 text-center my-8">Merchant v4 App</div>
                </template>
                <template v-slot:subtitle>
                    <p class="text-h5">Laravel 10 - Inertia - Vuetify</p>
                </template>
            </v-card>
        </v-container>
    </FrontLayout>
</template>
