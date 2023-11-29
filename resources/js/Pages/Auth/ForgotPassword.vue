<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
defineProps({
    status: {
        type: String,
    },
});

const processing = ref(false);

const form = useForm({
    email: "",
});

const submit = () => {
    processing.value = true;
    form.post(route("password.email"), {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <form @submit.prevent="submit">
            <v-card
                rounded
                color="primary"
                class="text-center pt-3 px-4 px-md-8 px-lg-12 py-8"
                :loading="processing"
            >
                <v-card-title>
                    {{ __("Forgot Password") }}
                </v-card-title>

                <v-card-text>
                    Forgot your password? No problem. Just let us know your
                    email address and we will email you a password reset link
                    that will allow you to choose a new one.
                </v-card-text>

                <v-card-text v-if="status">
                    <v-sheet color="info" class="py-4 px-8">
                        {{ status }}
                    </v-sheet>
                </v-card-text>
                <v-card-text class="px-8 pt-8">
                    <v-text-field
                        v-model="form.email"
                        :label="__('Email')"
                        class="mb-4"
                        required
                        autofocus
                        autocomplete="username"
                        :error-messages="form.errors.email"
                    />
                </v-card-text>

                <v-card-actions class="justify-space-around px-8">
                    <v-btn
                        size="large"
                        color="white"
                        flat
                        block
                        :disabled="form.processing"
                        type="submit"
                    >
                        {{ __("Email Password Reset Link") }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </GuestLayout>
</template>
