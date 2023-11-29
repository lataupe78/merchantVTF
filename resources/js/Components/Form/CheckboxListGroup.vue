<template>
    <v-list
        :class="{
            'has-error': error.length,
        }"
    >
        <v-list-subheader
            class="v-field-label"
            :class="{
                'label-error': error.length,
            }"
        >
            {{ label }}
        </v-list-subheader>

        <v-list-item v-if="error">
            <div class="v-input--error">
                <div class="v-input__details">
                    <div class="v-messages" role="alert" aria-live="polite">
                        <div class="v-messages__message">{{ error }}</div>
                    </div>
                </div>
            </div>
        </v-list-item>
        <v-list-item
            density="compact"
            v-for="(opt, optIndex) in options"
            :key="optIndex"
            class="w-100 pt-0 pb-0 mt-0 mb-0"
            :class="{
                'bg-primary-darken-1': modelValue.includes(opt.value),
            }"
        >
            <v-checkbox
                v-model="proxyChecked"
                :value="opt.value"
                density="compact"
                class="w-100"
                
            >
                <template v-slot:label>
                    <div
                        class="w-100 d-block font-weight-bold"
                        :class="{
                            'font-weight-bold': modelValue.includes(opt.value),
                        }"
                    >
                        {{ opt?.label }}
                    </div>
                </template>
            </v-checkbox>
        </v-list-item>
    </v-list>
</template>

<script setup>
import { computed, ref } from "vue"; 

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
    modelValue: {
        type: [Array, Object, String],
        required: true,
    },

    options: {
        type: Array,
        required: true,
        validator: (value) => {
            const hasLabelKey = value.every((option) =>
                Object.keys(option).includes("label")
            );
            const hasValueKey = value.every((option) =>
                Object.keys(option).includes("value")
            );
            return hasLabelKey && hasValueKey;
        },
    },

    error: {
        type: String,
        default: "",
    },
    id: {
        type: String,
        required: true,
    },

    label: {
        type: String,
        default: "",
    },
});

const proxyChecked = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>
<style scoped>
.v-selection-control .v-label {
    width: 100%;
}
.opacity-25 {
}

.has-error {
    border: 1px solid rgb(var(--v-theme-error));
}

.label-error {
    color: rgb(var(--v-theme-error));
}
</style>
