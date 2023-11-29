<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

import FormCheckboxGroup from "@/Components/Form/CheckboxListGroup.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    options: {
        type: [Array, Object],
        default: () => {},
    },
});

const form = useForm({
    id: props.user?.id || null,
    name: props.user?.name || null,
    email: props.user?.email || null,
    phone: props.user?.phone || null,
    password: props.user?.password || "password",
    password_confirmation: props.user?.password_confirmation || "password",

    is_active: props.user?.is_active || true,
    roles: props.user?.roles?.map((role) => role?.id) || [],
    sale_points:
        props.user?.sale_points?.map((sale_point) => sale_point?.id) || [],
});

const action = props.user?.id ? "edit" : "create";

const formRoute =
    action == "edit"
        ? route("manager.users.update", { user: props.user })
        : route("manager.users.store");

const formMethod = action == "edit" ? "put" : "post";

const submitForm = () => {
    console.log("submit form", { form });
    form.transform((data) => ({
        ...data,
        _method: formMethod,
    })).post(formRoute, {
        onFinish: () => {},
    });
};

const rolesOptions = ref();
rolesOptions.value = props.options?.roles?.map((user) => {
    return {
        value: user?.id,
        label: user?.name,
    };
});
const salePointsOptions = ref();
salePointsOptions.value = props.options?.sale_points?.map((sale_point) => {
    return {
        value: sale_point?.id,
        label: sale_point?.name,
    };
});
</script>
<template>
    <PreDebug title="form">{{ form }}</PreDebug>
    <form @submit.prevent="submitForm">
        <v-card class="my-5" :title="__('salepoint')" color="transparent">
            <v-card-text>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="form.name"
                            :label="__('name')"
                            required
                            :error-messages="form.errors.name"
                        />
                        <v-text-field
                            type="email"
                            v-model="form.email"
                            :label="__('email')"
                            required
                            :error-messages="form.errors.email"
                        />

                        <v-checkbox
                            v-model="form.is_active"
                            :label="__('is_active')"
                            :error-messages="form.errors.is_active"
                        />

                        <template v-if="action === 'create'">
                            <div class="mb-3">
                                <v-text-field
                                    type="password"
                                    v-model="form.password"
                                    :label="__('password')"
                                    required
                                    :error-messages="form.errors.password"
                                />
                                <span
                                    v-if="form.password == 'password'"
                                    class="text-info"
                                    >password</span
                                >

                                <v-text-field
                                    type="password"
                                    v-model="form.password_confirmation"
                                    :label="__('password_confirmation')"
                                    required
                                    :error-messages="
                                        form.errors.password_confirmation
                                    "
                                />
                            </div>
                        </template>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <FormCheckboxGroup
                            id="user_roles"
                            v-model="form.roles"
                            :error="form.errors.roles"
                            :options="rolesOptions"
                            :label="__('roles')"
                        />

                        <v-divider class="my-5" />
                        <FormCheckboxGroup
                            id="user_sale_points"
                            v-model="form.sale_points"
                            :error="form.errors.sale_points"
                            :options="salePointsOptions"
                            :label="__('sale_points')"
                        />
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="justify-space-around">
                <v-btn :to="route('manager.sale_points.index')">
                    {{ __("cancel") }}
                </v-btn>
                <v-btn color="primary" type="submit">
                    {{ __(action) }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </form>
</template>
