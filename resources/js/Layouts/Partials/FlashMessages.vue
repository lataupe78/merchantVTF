<script setup>
import { ref, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
const page = usePage();

const flash = computed(() => page.props.flash);
const showFlash = ref(false);
watch(
    flash,
    (newValue) => {
        console.log("FLASH", newValue);
        showFlash.value = true;
    },
    {
        deep: true,
        immediate: true,
    }
);
</script>
<template>
    <v-container>
        <TransitionGroup name="slide-fade">
            <v-alert
                v-if="$page.props?.flash?.success && showFlash == true"
                type="success"
                closable
            >
                <span v-html="$page.props.flash.success" />
            </v-alert>
            <v-alert
                v-if="$page.props?.flash?.error && showFlash == true"
                type="error"
                closable
            >
                <span v-html="$page.props.flash.error" />
            </v-alert>
            <v-alert
                v-if="
                    Object.keys($page.props?.errors).length > 0 &&
                    showFlash == true
                "
                type="error"
            >
                <span v-if="Object.keys($page.props?.errors).length === 1"
                    >There is one form error.</span
                >
                <span v-else
                    >There are
                    {{ Object.keys($page.props?.errors).length }} form
                    errors.</span
                >
            </v-alert>
        </TransitionGroup>
    </v-container>
</template>

<style>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
