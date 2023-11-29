<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { LMap, LTileLayer, LMarker, LTooltip, LFeatureGroup } from "@vue-leaflet/vue-leaflet";

import "leaflet/dist/leaflet.css";

// import { LCircleMarker, LMap, LTileLayer } from "@src/components";

const props = defineProps({
  collection: {
    type: Array, Object,
    default() { return [] }
  },
  center: {
    type: Object,
    default() {
      return {
        lat: 48.864716,
        lng: 2.349014
      }
    }
  }
})

const zoom = ref(12)
const center = ref([
  props.center.lat, props.center.lng
])

const markers = ref([])
const map = ref(null)
const featureGroup = ref(null)



onMounted(() => {
  console.log({ featureGroup: featureGroup.value })

  props.collection.forEach((point) => {
    console.log({ pointPositionMap: point })
    if (point?.lat && point?.lng) {
      markers.value.push({
        latlng: [point?.lat, point?.lng],
        title: point?.title
      })
    }
  })
  console.log({
    message: 'mounted',
    collection: props.collection,
    markers
  })
});

const addMarker = () => {
  const leafletObject = map.value?.leafletObject
  const bounds = leafletObject.getBounds()
  let newMarker =
  {
    latlng: [
      props.center.lat + (10 / 111.1) * (1 - Math.random() * 2),
    props.center.lng + (10 / 111.1) * (1 - Math.random() * 2)
    ],
    title: "new Marker"
  }
  markers.value.push(newMarker)
  bounds.extend(newMarker.latlng)
  map.value.leafletObject.fitBounds(bounds)
  // fitBounds()

}

const fitBounds = () => {

  // console.log({ "Ref featureGroup": featureGroup.value })
  console.log({ "Ref Map": map.value })
  const leafletObject = map.value?.leafletObject

  console.log({ leafletObject })

  const bounds = leafletObject?.getBounds()

  console.log({ bounds })

  map.value.leafletObject.fitBounds(bounds, { padding: [2, 2], })
}

  // } catch (error) {
  //   console.error(error)
  // }
// }


</script>

<template>
  <div class="map-container">
    <div class="map-frame">

      <LMap ref="map" v-model:zoom="zoom" :center="center" :use-global-leaflet="false" style="height: 360px; width:100%">

        <LTileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base" name="OpenStreetMap" />

        <LFeatureGroup ref="featureGroup" @ready="fitBounds">
          <LMarker v-for="(marker, pointIndex) in markers" :key="pointIndex" :lat-lng="marker.latlng">

            <LTooltip :options="{
              interactive: true,
              permanent: false
            }">
              <h6>{{marker?.title||'New Marker - no title 😎'}}</h6>
            </LTooltip>

          </LMarker>
        </LFeatureGroup>

      </LMap>

    </div>
  </div>
  <div class="d-flex w-100">
    <button class="btn btn-success" @click.prevent="addMarker">Add marker</button>
    <div class="me-2">{{zoom}}</div>
    <div class="me-2">{{center}}</div>

  </div>
</template>
<!-- <style>
.map-container {
  position: relative;
  z-index : 1000;
  
  padding: 0px 2rem 2rem 2rem;
  padding: 0px 2vmin 2vmin 2vmin;
  flex: 1 0 auto;
  min-height: 160px;
}

.map-frame {
  position: relative;
  z-index : 1000;
  height: 100%;
  width: 100%;
  min-height: 160px; 
  outline: 1px solid black;
  flex: 1 0 auto;
}
</style> -->