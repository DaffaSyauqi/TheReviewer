<template>
    <slot />
</template>

<script setup lang="ts">
import { inject, watch, type Ref, type ComputedRef } from 'vue';
import maplibregl from 'maplibre-gl';

const emit = defineEmits<{
    'region-click': [
        payload: {
            province: string;
            city: string;
            type: string;
            properties: Record<string, unknown>;
        },
    ];
    'map-click-outside': [];
    loading: [value: boolean];
    error: [message: string];
}>();

const map = inject<Ref<maplibregl.Map | null>>('map');
const isMapLoaded = inject<ComputedRef<boolean>>('isMapLoaded');

const GEOJSON_URL =
    'https://raw.githubusercontent.com/TheMaggieSimpson/IndonesiaGeoJSON/main/kota-kabupaten.json';
const SOURCE_ID = 'indonesia-kota';
const FILL_ID = 'indonesia-kota-fill';
const BORDER_ID = 'indonesia-kota-border';

let activeFeatureId: string | number | null = null;

function extractInfo(props: Record<string, unknown>) {
    const province = String(props.NAME_1 ?? '');
    const city = String(props.NAME_2 ?? '');
    const type = String(props.TYPE_2 ?? '');
    return { province, city, type };
}

function resetActive(m: maplibregl.Map) {
    if (activeFeatureId !== null) {
        m.setFeatureState(
            { source: SOURCE_ID, id: activeFeatureId },
            { clicked: false },
        );
        activeFeatureId = null;
    }
}

function setupLayers(m: maplibregl.Map, data: GeoJSON.FeatureCollection) {
    if (m.getSource(SOURCE_ID)) return;

    m.addSource(SOURCE_ID, { type: 'geojson', data, generateId: true });

    m.addLayer({
        id: FILL_ID,
        type: 'fill',
        source: SOURCE_ID,
        paint: {
            'fill-color': '#3b82f6',
            'fill-opacity': [
                'case',
                ['boolean', ['feature-state', 'clicked'], false],
                0.35,
                0,
            ],
        },
    });

    m.addLayer({
        id: BORDER_ID,
        type: 'line',
        source: SOURCE_ID,
        paint: {
            'line-color': '#1d4ed8',
            'line-width': [
                'case',
                ['boolean', ['feature-state', 'clicked'], false],
                2.5,
                0,
            ],
        },
    });

    m.on('mouseenter', FILL_ID, () => {
        m.getCanvas().style.cursor = 'pointer';
    });
    m.on('mouseleave', FILL_ID, () => {
        m.getCanvas().style.cursor = '';
    });

    m.on('click', FILL_ID, (e) => {
        if (!e.features?.length) return;
        const feature = e.features[0];

        resetActive(m);
        activeFeatureId = feature.id as string | number;
        m.setFeatureState(
            { source: SOURCE_ID, id: activeFeatureId },
            { clicked: true },
        );

        const props = feature.properties as Record<string, unknown>;
        emit('region-click', { ...extractInfo(props), properties: props });

        const geom = feature.geometry;
        if (geom.type === 'Polygon' || geom.type === 'MultiPolygon') {
            const coords = (
                geom.type === 'Polygon'
                    ? geom.coordinates[0]
                    : geom.coordinates[0][0]
            ) as [number, number][];
            const bounds = coords.reduce(
                (b, c) => b.extend(c),
                new maplibregl.LngLatBounds(coords[0], coords[0]),
            );
            m.fitBounds(bounds, {
                padding: { top: 60, right: 360, bottom: 60, left: 40 },
                maxZoom: 9,
                duration: 500,
            });
        }
    });

    m.on('click', (e) => {
        const hits = m.queryRenderedFeatures(e.point, { layers: [FILL_ID] });
        if (!hits.length) {
            resetActive(m);
            emit('map-click-outside');
        }
    });
}

watch(
    () => isMapLoaded?.value,
    async (loaded) => {
        if (!loaded || !map?.value) return;

        emit('loading', true);
        try {
            const res = await fetch(GEOJSON_URL);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data: GeoJSON.FeatureCollection = await res.json();
            setupLayers(map.value, data);
        } catch (err) {
            emit(
                'error',
                err instanceof Error
                    ? err.message
                    : 'Gagal memuat data GeoJSON',
            );
        } finally {
            emit('loading', false);
        }
    },
    { immediate: true },
);
</script>
