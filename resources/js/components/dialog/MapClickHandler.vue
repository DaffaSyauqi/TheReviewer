<script setup lang="ts">
import MapLibreGL from 'maplibre-gl'
import { inject, watch, onUnmounted, type Ref, type ComputedRef } from 'vue'

const emit = defineEmits<{
    click: [lngLat: { lng: number; lat: number }]
}>()

const map = inject<Ref<MapLibreGL.Map | null>>('map')
const isLoaded = inject<ComputedRef<boolean>>('isMapLoaded')

function handleClick(e: maplibregl.MapMouseEvent) {
    emit('click', { lng: e.lngLat.lng, lat: e.lngLat.lat })
}

watch(
    () => isLoaded?.value,
    (loaded) => {
        if (!loaded || !map?.value) return
        map.value.on('click', handleClick)
    },
    { immediate: true },
)

onUnmounted(() => {
    if (map?.value) {
        map.value.off('click', handleClick)
    }
})
</script>

<template>
    <slot />
</template>
