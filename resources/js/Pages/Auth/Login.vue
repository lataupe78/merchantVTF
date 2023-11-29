<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";

import GuestLayout from "@/Layouts/GuestLayout.vue";
import UserLoggerPanel from "@/Components/Panels/UserLoggerPanel.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userList: [Array, Object],
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const processing = ref(false);

const submit = () => {
    processing.value = true;
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
            processing.value = false;
        },
    });
};

const logUser = (payload) => {
    console.log("logUser", payload);
    let user = payload?.user || null;

    // // let role = payload?.role || ''
    // if (['student', 'former', 'customer'].includes(role)) {
    //   this.loginRoute = this.route(`${role}s.login`)
    // } else {
    //   this.loginRoute = this.route(`login`)
    // }

    if (user !== null) {
        form.email = user?.email;
        form.password = "secret";

        // debugger
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <form @submit.prevent="submit" class="mb-5">
            <v-card
                rounded
                color="primary"
                class="text-center pt-3 px-md-8 px-lg-12 py-8"
                :loading="processing"
            >
                <v-card-text v-if="status">
                    {{ status }}
                </v-card-text>
                <v-card-title>
                    {{ __("Connect to your Account") }}
                </v-card-title>
                <v-card-subtitle>
                    {{ __("Enjoy your stay") }}
                </v-card-subtitle>

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

                    <v-text-field
                        v-model="form.password"
                        :label="__('Password')"
                        class="mb-4"
                        type="password"
                        required
                        autocomplete="current-password"
                        :error-messages="form.errors.password"
                    />

                    <v-checkbox
                        v-model="form.remember"
                        :label="__('Remember me')"
                    />
                    <v-btn
                        tonal
                        variant="text"
                        v-if="canResetPassword"
                        :to="route('password.request')"
                    >
                        <div class="text-wrap">
                            {{ __("Forgot your password?") }}
                        </div>
                    </v-btn>
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
                        {{ __("Log in") }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </form>

        <UserLoggerPanel
            :groups="userList"
            @connect="logUser"
            class="mt-8 mb-16"
        />
    </GuestLayout>
</template>
