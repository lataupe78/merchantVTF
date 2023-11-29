<script setup>
import { ref, computed, watch, watchEffect } from 'vue'
// import FrontLayout from '@/Layouts/FrontLayout.vue';

// import { useGeolocation } from '@/composables/useGeoLocation'

const isSupported = 'navigator' in window && 'geolocation' in navigator

const coordinates = ref({ latitude: 0, longitude: 0 })

let watcher = null

// const { coordinates, isSupported } = useGeolocation();
const time = ref()

const positionUser = computed(() => ({
  lat: coordinates.value.latitude,
  lng: coordinates.value.longitude,
}));

// watchEffect(() => {
//   time.value = new Date()
//   console.log({ p: positionUser.value });
// })
const infos = ref({})
const isWatching = ref(false)

watch(positionUser, (coordinates) => {
  time.value = new Date()
  // infos.value = coordinates
  // console.log({ p: positionUser.value });
}, {
  immediate: true,
  deep: true
})


let options = {
  enableHighAccuracy: true,
  maximumAge: 30000,
  timeout: 27000,
  // navigator: defaultNavigator,
  immediate: true,
}
const getLocation = () => {
  if (isSupported) {
    navigator.geolocation.getCurrentPosition(
      position => {
        console.log({ position })
        console.log({ coords: position.coords })
        console.log({ timestamp: position.timestamp })

        infos.value = {
          // coords: position.coords,
          accuracy: position.coords.accuracy,
          altitude: position.coords.altitude,
          altitudeAccuracy: position.coords.altitudeAccuracy,
          heading: position.coords.heading,
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
          speed: position.coords.speed,

          timestamp: position.timestamp
        }
        coordinates.value = position.coords
      },
      () => alert('geolocation permission denied')
    )
  } else {
    alert('navigator.geolocation not supported!')
  }
}
const toggleWatchLocation = () => {
  if (!isSupported) {
    alert('navigator.geolocation not supported!')
    return
  }

  isWatching.value = !isWatching.value

  if (isWatching.value == true) {
    watcher = navigator.geolocation.watchPosition(
      position => {

        infos.value = {
          // coords: position.coords,
          accuracy: position.coords.accuracy,
          altitude: position.coords.altitude,
          altitudeAccuracy: position.coords.altitudeAccuracy,
          heading: position.coords.heading,
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
          speed: position.coords.speed,

          timestamp: position.timestamp
        }
        
        coordinates.value = position.coords
      },
      () => alert('geolocation permission denied')
    )
  } else {
    if (watcher) navigator.geolocation.clearWatch(watcher)
  }
}
</script>
<template>
  <!-- <FrontLayout title="Test Geolocation"> -->

  <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    Test Geolocation
  </h2>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="card my-5">

        <div class="card-body">
          <div class="flex items-center justify-around">
            <button class="bg-green-500 py-2 px-4 btn btn-lg btn-success" @click.prevent="getLocation()">
              Get Location
            </button>
            <button class="bg-green-500 py-2 px-4 btn btn-lg btn-success" @click.prevent="toggleWatchLocation()">
              <span v-if="isWatching==false">Watch location</span>
              <span v-else>Stop Watching location</span>
            </button>
          </div>
        </div>
      </div>
      <pre class="my-5">isWatching {{isWatching}}</pre>
      <pre class="my-5">isSupported {{isSupported}}  </pre>
      <pre class="my-5">infos {{infos}}  </pre>
      <pre class="my-5">coordinates {{coordinates}}  </pre>
      <pre class="my-5">positionUser {{positionUser}}  </pre>
      <pre class="my-5">time {{time}}  </pre>
    </div>



  </div>

  <!-- </FrontLayout> -->
</template>
