<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, watch, ref } from "vue";
import FormCheckboxGroup from "@/Components/Form/CheckboxListGroup.vue";

const props = defineProps({
    sale_point: {
        type: Object,
        required: true,
    },
    options: {
        type: [Array, Object],
        default: () => {},
    },
});

const form = useForm({
    is_active: props.sale_point?.is_active || false,
    // team_id: props.sale_point?.team_id || null,
    name: props.sale_point?.name || null,
    description: props.sale_point?.description || null,
    city: props.sale_point?.city || null,
    address: props.sale_point?.address || null,
    latitude: props.sale_point?.latitude || null,
    longitude: props.sale_point?.longitude || null,
    workers: props.sale_point?.workers?.map((worker) => worker?.id) || [],
});

const selected = ref(["John"]);

const workersOptions = ref();

workersOptions.value = props.options?.users?.map((user) => {
    return {
        value: user?.id,
        label: user?.name,
    };
});

const action = props.sale_point?.id ? "edit" : "create";

const formRoute =
    action == "edit"
        ? route("manager.sale_points.update", { sale_point: props.sale_point })
        : route("manager.sale_points.store");

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
</script>
<template>
    <PreDebug title="form">{{ form }}</PreDebug>
    <form @submit.prevent="submitForm">
        <v-card class="my-5" color="transparent">
            <v-card-text>
                <v-row>
                    <v-col cols="12" lg="6">
                        <v-card :title="__('sale_point')" color="primary" class="px-4">
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="form.name"
                                            :label="__('name')"
                                            required
                                            :error-messages="form.errors.name"
                                        />
                                        <v-textarea
                                            v-model="form.description"
                                            :label="__('description')"
                                            :error-messages="
                                                form.errors.description
                                            "
                                        />
                                        <v-checkbox
                                            v-model="form.is_active"
                                            :label="__('is_active')"
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="form.city"
                                            :label="__('city')"
                                            :error-messages="form.errors.city"
                                        />
                                        <v-textarea
                                            v-model="form.address"
                                            :label="__('address')"
                                            :error-messages="
                                                form.errors.address
                                            "
                                        />
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="form.latitude"
                                            :label="__('latitude')"
                                            type="number"
                                            step="0.00000001"
                                            placeholder="48.864716"
                                            :error-messages="
                                                form.errors.latitude
                                            "
                                        />
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            v-model="form.longitude"
                                            :label="__('longitude')"
                                            type="number"
                                            step="0.00000001"
                                            placeholder="2.349014"
                                            :error-messages="
                                                form.errors.longitude
                                            "
                                        />
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" lg="6">
                        <v-card :title="__('workers')" color="primary" class="px-4">
                            <v-card-text>
                                <FormCheckboxGroup
                                    id="members_ids"
                                    class="mb-3"
                                    v-model="form.workers"
                                    :error="form.errors.workers"
                                    :options="workersOptions"
                                />
                            </v-card-text>
                        </v-card>
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
