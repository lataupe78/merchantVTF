<script setup>
import { toFormat } from "@/helpers/dates.js";
import { ref, reactive, watch, computed, getCurrentInstance } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { set } from "lodash";

const $page = usePage();
const $key = getCurrentInstance().vnode.key;

const props = defineProps({
    range: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    currentDate: {
        type: String,
        required: true,
    },
});

const showForm = ref(false);
const processing = ref(false);

const rangeId = props.range?.id ?? null;

const range = computed(() => {
    return props.range;
});

const rangeIsValidated = computed(() => {
    if (
        range.value?.validated_at == null ||
        range.value?.validated_at == undefined
    ) {
        return false;
    }
    return true;
});

const form = reactive({
    id: range.value?.id || null,
    current_date: range.value?.current_date ?? props.currentDate ?? null,
    worker_id: range.value?.worker_id || props.user?.id || null,

    validated_started_at: {
        date:
            range.value?.validated_started_at?.date ||
            props?.currentDate ||
            null,
        time: range.value?.validated_started_at?.time || null,
    },

    validated_ended_at: {
        date:
            range.value?.validated_ended_at?.date || props?.currentDate || null,
        time: range.value?.validated_ended_at?.time || null,
    },
});

// const fields = Object.keys(form);
// debugger;

const errors = reactive({
    id: null,
    current_date: null,
    worker_id: null,

    validated_started_at: {
        date: null,
        time: null,
    },

    validated_ended_at: {
        date: null,
        time: null,
    },
});

const hasErrors = computed(() => {
    let values = Object.values(errors);
    if (values.length == 0) {
        return false;
    }
    values.forEach((key) => {});
});

const submitForm = () => {
    const formRoute =
        rangeId != null
            ? route("manager.plannings.validation.update", { rangeId: rangeId })
            : route("manager.plannings.validation.store");

    const formMethod = rangeId != null ? "put" : "post";

    console.log({ formRoute, formMethod, form: form });
    console.log({ validated_started_at: form.validated_started_at });
    console.log({ validated_ended_at: form.validated_ended_at });

    processing.value = true;

    router.visit(formRoute, {
        method: formMethod,
        data: form,

        errorBag: "error_" + $key,
        preserveState: ($page) => Object.keys($page.props.errors).length,
        preserveScroll: ($page) => Object.keys($page.props.errors).length,

        onSuccess: (page) => {},
        onError: (serverErrors) => {
            console.log({ serverErrors });
            for (const [key, value] of Object.entries(serverErrors)) {
                console.log(`${key}: ${value}`);
                set(errors, key, value);
            }
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>
<template>
    <form @submit.prevent="submitForm">
        <v-card
            :loading="processing"
            :color="rangeIsValidated ? 'success' : 'default'"
            variant="tonal"
            width="100%"
        >
            <v-card-text
                v-if="
                    (range?.validated_started_at?.val ?? null) !== null &&
                    (range?.validated_ended_at?.val ?? null) !== null
                "
            >
                <v-row no-gutters>
                    <v-col cols="12" sm="6">
                        <div class="text-wrap">
                            {{ __("starts_at") }}
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <div class="text-wrap text-end">
                            {{ range?.validated_started_at?.val ?? "---" }}
                        </div>
                    </v-col>
                </v-row>

                <v-row no-gutters>
                    <v-col cols="12" sm="6">
                        <div class="text-wrap">
                            {{ __("ends_at") }}
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <div class="text-wrap text-end">
                            {{ range?.validated_ended_at?.val ?? "---" }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
            <!-- </template> -->

            <v-card-actions>
                <div class="text-uppercase text-wrap">
                    <template v-if="(range?.validated_at ?? null) === null">
                        {{ __("unvalidated") }}
                    </template>
                    <template v-else>
                        {{
                            __("validated_at", {
                                at: toFormat(
                                    range?.validated_at,
                                    "dd/MM/yy HH:mm"
                                ),
                            })
                        }}
                    </template>
                </div>
                <v-spacer />
                <v-btn
                    label
                    :append-icon="
                        !!showForm ? 'mdi-chevron-up' : 'mdi-chevron-down'
                    "
                    @click="showForm = !showForm"
                >
                    {{ __("Form") }}
                </v-btn>
            </v-card-actions>
        </v-card>
        <v-expand-transition>
            <v-card v-if="showForm === true" color="info" variant="tonal">
                <v-card-text>
                    <v-text-field
                        :label="__('id')"
                        v-model="form.id"
                        clearable
                        :error-messages="errors.id"
                    />

                    <v-text-field
                        :label="__('worker')"
                        v-model="form.worker_id"
                        required
                        clearable
                        :error-messages="errors.worker_id"
                    />
                    <v-divider />

                    <!-- STARTED_AT -->
                    <h5 class="text-body-1 text-capitalize mb-4">
                        {{ __("started_at") }}
                    </h5>

                    <v-row no-gutters>
                        <v-col cols="12" lg="6" class="py-0">
                            <v-text-field
                                type="date"
                                :label="__('date')"
                                v-model="form.validated_started_at.date"
                                required
                                :error-messages="
                                    errors.validated_started_at.date
                                "
                            />
                        </v-col>
                        <v-col cols="12" lg="6" class="py-0">
                            <v-text-field
                                type="time"
                                :label="__('time')"
                                v-model="form.validated_started_at.time"
                                :error-messages="
                                    errors.validated_started_at.time
                                "
                            />
                        </v-col>
                    </v-row>

                    <!-- ENDED_AT -->

                    <h5 class="text-body-1 text-capitalize mb-4">
                        {{ __("ended_at") }}
                    </h5>

                    <v-row no-gutters>
                        <v-col cols="12" lg="6" class="py-0">
                            <v-text-field
                                class="mb-0 pb-0"
                                type="date"
                                :label="__('date')"
                                v-model="form.validated_ended_at.date"
                                required
                                :error-messages="errors.validated_ended_at.date"
                            />
                        </v-col>
                        <v-col cols="12" lg="6" class="py-0">
                            <v-text-field
                                class="mb-0 pb-0"
                                type="time"
                                :label="__('time')"
                                v-model="form.validated_ended_at.time"
                                required
                                :error-messages="errors.validated_ended_at.time"
                            />
                        </v-col>
                    </v-row>

                    <!-- <pre>{{ form.validated_ended_at }}</pre> -->

                    <!-- DEBUG -->
                    <div>
                        <!-- <v-row >
                <v-col>
                    <PreDebug title="currentDate">
                        {{ currentDate }}
                    </PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="rangeId">
                        {{ rangeId }}
                    </PreDebug>
                </v-col>
                <v-col>
                    <PreDebug title="range">
                        {{ range }}
                    </PreDebug>
                </v-col>
            </v-row> -->
                    </div>
                    <!-- ERRORS -->
                    <div v-if="hasErrors">
                        <v-alert type="error">
                            <pre>{{ errors }}</pre>
                        </v-alert>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />

                    <v-btn
                        type="input"
                        color="success"
                        variant="tonal"
                        :disabled="processing"
                    >
                        {{ __("validate") }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-expand-transition>

        <v-row>
            <v-col cols="12" lg="6">
                <PreDebug title="form">
                    {{ form }}
                </PreDebug>
            </v-col>
            <v-col cols="12" lg="6">
                <PreDebug title="form errors">
                    {{ form?.errors }}
                </PreDebug>
            </v-col>
        </v-row>
    </form>
</template>
