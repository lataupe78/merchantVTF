import { onUnmounted, onMounted, ref } from 'vue'

export function useGeolocation() {
  const coordinates = ref({ latitude: 0, longitude: 0 })
  const isSupported = 'navigator' in window && 'geolocation' in navigator

  let watcher = null
  onMounted(() => {
    if (isSupported) {
      watcher = navigator.geolocation.watchPosition(
        position => {
          coordinates.value = position.coords
        },
        () => alert('geolocation permission denied')
      )
    } else {
      alert('navigator.geolocation not supported!')
    }
  })

  onUnmounted(() => {
    if (watcher) navigator.geolocation.clearWatch(watcher)
  })
  return { coordinates, isSupported }
}