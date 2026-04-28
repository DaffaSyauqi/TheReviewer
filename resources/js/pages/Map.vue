<template>
    <div class="relative h-screen w-full">
        <!-- Map Container -->
        <div ref="mapContainer" class="h-full w-full" />

        <!-- Sidebar -->
        <transition
            enter-active-class="transition-transform duration-300 ease-out"
            leave-active-class="transition-transform duration-300 ease-in"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="isSidebarOpen"
                class="absolute top-0 right-0 z-10 h-full w-80 border-l border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900"
            >
                <!-- Sidebar Header -->
                <div
                    class="flex items-center justify-between border-b border-gray-200 p-6 dark:border-gray-800"
                >
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        {{ selectedRegion?.name || 'Informasi Wilayah' }}
                    </h2>
                    <button
                        @click="closeSidebar"
                        class="rounded-lg p-2 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800"
                    >
                        <svg
                            class="h-5 w-5 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Content -->
                <div class="h-[calc(100%-80px)] overflow-y-auto p-6">
                    <div v-if="selectedRegion" class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Nama Wilayah
                            </p>
                            <p
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ selectedRegion.name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Tipe
                            </p>
                            <p
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ selectedRegion.type }}
                            </p>
                        </div>

                        <!-- Tambahkan konten lainnya di sini -->
                        <div
                            class="mt-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800"
                        >
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Klik wilayah lain pada map untuk melihat
                                informasi lebih lanjut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import maplibregl from 'maplibre-gl';
import 'maplibre-gl/dist/maplibre-gl.css';

// Refs
const mapContainer = ref<HTMLDivElement | null>(null);
const map = ref<maplibregl.Map | null>(null);
const isSidebarOpen = ref(false);
const selectedRegion = ref<{
    name: string;
    type: string;
    id: string;
} | null>(null);

// Tracking clicked feature
let clickedFeatureId: string | null = null;

// Data GeoJSON untuk Jakarta (contoh sederhana)
// Dalam implementasi real, Anda bisa load dari file eksternal atau API
const jakartaGeoJSON = {
    type: 'FeatureCollection',
    features: [
        {
            type: 'Feature',
            properties: {
                id: 'jakarta-pusat',
                name: 'Jakarta Pusat',
                type: 'Kota Administratif',
            },
            geometry: {
                type: 'Polygon',
                coordinates: [
                    [
                        [106.8, -6.15],
                        [106.85, -6.15],
                        [106.85, -6.2],
                        [106.8, -6.2],
                        [106.8, -6.15],
                    ],
                ],
            },
        },
        {
            type: 'Feature',
            properties: {
                id: 'jakarta-utara',
                name: 'Jakarta Utara',
                type: 'Kota Administratif',
            },
            geometry: {
                type: 'Polygon',
                coordinates: [
                    [
                        [106.8, -6.1],
                        [106.85, -6.1],
                        [106.85, -6.15],
                        [106.8, -6.15],
                        [106.8, -6.1],
                    ],
                ],
            },
        },
        {
            type: 'Feature',
            properties: {
                id: 'jakarta-barat',
                name: 'Jakarta Barat',
                type: 'Kota Administratif',
            },
            geometry: {
                type: 'Polygon',
                coordinates: [
                    [
                        [106.75, -6.15],
                        [106.8, -6.15],
                        [106.8, -6.2],
                        [106.75, -6.2],
                        [106.75, -6.15],
                    ],
                ],
            },
        },
        {
            type: 'Feature',
            properties: {
                id: 'jakarta-selatan',
                name: 'Jakarta Selatan',
                type: 'Kota Administratif',
            },
            geometry: {
                type: 'Polygon',
                coordinates: [
                    [
                        [106.8, -6.2],
                        [106.85, -6.2],
                        [106.85, -6.25],
                        [106.8, -6.25],
                        [106.8, -6.2],
                    ],
                ],
            },
        },
        {
            type: 'Feature',
            properties: {
                id: 'jakarta-timur',
                name: 'Jakarta Timur',
                type: 'Kota Administratif',
            },
            geometry: {
                type: 'Polygon',
                coordinates: [
                    [
                        [106.85, -6.15],
                        [106.9, -6.15],
                        [106.9, -6.2],
                        [106.85, -6.2],
                        [106.85, -6.15],
                    ],
                ],
            },
        },
    ],
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
    selectedRegion.value = null;

    // Reset highlight when sidebar closed
    if (map.value && clickedFeatureId) {
        map.value.setFeatureState(
            { source: 'jakarta-regions', id: clickedFeatureId },
            { clicked: false },
        );
        clickedFeatureId = null;
    }
};

const initMap = () => {
    if (!mapContainer.value) return;

    // Initialize map
    map.value = new maplibregl.Map({
        container: mapContainer.value,
        style: {
            version: 8,
            sources: {
                'carto-light': {
                    type: 'raster',
                    tiles: [
                        'https://a.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',
                    ],
                    tileSize: 256,
                    attribution:
                        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                },
            },
            layers: [
                {
                    id: 'carto-light-layer',
                    type: 'raster',
                    source: 'carto-light',
                    minzoom: 0,
                    maxzoom: 22,
                },
            ],
        },
        center: [106.8456, -6.2088], // Jakarta coordinates
        zoom: 11,
        minZoom: 10, // Batasi zoom minimum agar tidak terlalu detail
        maxZoom: 14, // Batasi zoom maximum agar tidak menampilkan detail kecamatan
    });

    // Add navigation controls
    map.value.addControl(new maplibregl.NavigationControl(), 'top-right');

    // Wait for map to load
    map.value.on('load', () => {
        if (!map.value) return;

        // Add GeoJSON source
        map.value.addSource('jakarta-regions', {
            type: 'geojson',
            data: jakartaGeoJSON as any,
            generateId: true, // This enables feature state
        });

        // Add fill layer (wilayah)
        map.value.addLayer({
            id: 'jakarta-fill',
            type: 'fill',
            source: 'jakarta-regions',
            paint: {
                'fill-color': [
                    'case',
                    ['boolean', ['feature-state', 'clicked'], false],
                    '#3b82f6', // Blue color when clicked
                    'rgba(59, 130, 246, 0.2)', // Light blue default
                ],
                'fill-opacity': [
                    'case',
                    ['boolean', ['feature-state', 'clicked'], false],
                    0.6,
                    0.3,
                ],
            },
        });

        // Add border layer
        map.value.addLayer({
            id: 'jakarta-border',
            type: 'line',
            source: 'jakarta-regions',
            paint: {
                'line-color': [
                    'case',
                    ['boolean', ['feature-state', 'clicked'], false],
                    '#1e40af', // Darker blue when clicked
                    '#3b82f6',
                ],
                'line-width': [
                    'case',
                    ['boolean', ['feature-state', 'clicked'], false],
                    3,
                    2,
                ],
            },
        });

        // Add labels
        map.value.addLayer({
            id: 'jakarta-labels',
            type: 'symbol',
            source: 'jakarta-regions',
            layout: {
                'text-field': ['get', 'name'],
                'text-size': 14,
                'text-font': ['Open Sans Regular', 'Arial Unicode MS Regular'],
            },
            paint: {
                'text-color': '#1f2937',
                'text-halo-color': '#ffffff',
                'text-halo-width': 2,
            },
        });

        // Change cursor on hover
        map.value.on('mouseenter', 'jakarta-fill', () => {
            if (map.value) {
                map.value.getCanvas().style.cursor = 'pointer';
            }
        });

        map.value.on('mouseleave', 'jakarta-fill', () => {
            if (map.value) {
                map.value.getCanvas().style.cursor = '';
            }
        });

        // Click event handler
        map.value.on('click', 'jakarta-fill', (e) => {
            if (!map.value || !e.features || e.features.length === 0) return;

            const feature = e.features[0];

            // Reset previous clicked feature
            if (clickedFeatureId !== null) {
                map.value.setFeatureState(
                    { source: 'jakarta-regions', id: clickedFeatureId },
                    { clicked: false },
                );
            }

            // Set new clicked feature
            clickedFeatureId = feature.id as string;
            map.value.setFeatureState(
                { source: 'jakarta-regions', id: clickedFeatureId },
                { clicked: true },
            );

            // Update selected region and open sidebar
            selectedRegion.value = {
                id: feature.properties?.id || '',
                name: feature.properties?.name || 'Unknown',
                type: feature.properties?.type || 'Unknown',
            };

            isSidebarOpen.value = true;

            // Optional: Fly to the clicked region
            if (feature.geometry.type === 'Polygon') {
                const coordinates = feature.geometry.coordinates[0] as [
                    number,
                    number,
                ][];
                const bounds = coordinates.reduce(
                    (bounds, coord) => bounds.extend(coord as [number, number]),
                    new maplibregl.LngLatBounds(coordinates[0], coordinates[0]),
                );
                map.value.fitBounds(bounds, { padding: 100, maxZoom: 12 });
            }
        });
    });
};

onMounted(() => {
    initMap();
});

onBeforeUnmount(() => {
    if (map.value) {
        map.value.remove();
    }
});
</script>

<style scoped>
/* Additional styles if needed */
</style>
