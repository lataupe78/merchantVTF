<script setup>
import { ref, computed, watch, watchEffect } from "vue";
import { useForm } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";

const form = useForm({
    title: null,
    lat: null,
    lng: null,
    altitude: null,
    accuracy: null,
    altitude_accuracy: null,
});
const submitForm = () => {
    form.transform((data) => ({
        ...data,
        _method: "post",
    })).post(route("position.store"), {
        onFinish: () => {},
    });
};

const isSupported = "navigator" in window && "geolocation" in navigator;

const coordinates = ref({ latitude: 0, longitude: 0 });
const positionUser = computed(() => ({
    lat: coordinates.value.latitude,
    lng: coordinates.value.longitude,
}));

const getLocation = () => {
    if (isSupported) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                console.log({ position });
                console.log({ coords: position.coords });
                console.log({ timestamp: position.timestamp });

                form.accuracy = position.coords.accuracy;

                form.altitude = position.coords.altitude;
                form.altitude_accuracy = position.coords.altitudeAccuracy;

                form.lat = position.coords.latitude;
                form.lng = position.coords.longitude;

                coordinates.value = position.coords;
            },
            () => alert("geolocation permission denied")
        );
    } else {
        alert("navigator.geolocation not supported!");
    }
};

const formFields = [
    "title",
    "lat",
    "lng",
    "altitude",
    "accuracy",
    "altitude_accuracy",
];
</script>
<template>
    <FrontLayout title="Store Geolocation">
        <template #actions>
            <v-btn :to="route('position.index')" color="primary"
                >{{ __("list_items", { items: __("positions") }) }}
            </v-btn>
        </template>
        <v-container>
            <form @submit.prevent="submitForm">
                <v-card class="px-8">
                    <v-card-text>
                        <v-btn type="success" @click.prevent="getLocation()">
                            Get Location
                        </v-btn>

                        <pre class="my-5">isSupported {{ isSupported }}  </pre>

                        <v-divider class="my-8" />

                        <template
                            v-for="(field, index) in formFields"
                            :key="`field-${index}`"
                        >
                            <v-text-field
                                :label="field"
                                v-model="form[`${field}`]"
                            />
                        </template>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn type="submit" class="primary"> Submit </v-btn>
                    </v-card-actions>
                </v-card>
            </form>

            <v-row>
                <v-col>
                    <PreDebug :show="true" title="form">{{ form }}</PreDebug>
                </v-col>
            </v-row>
        </v-container>
    </FrontLayout>
</template>
