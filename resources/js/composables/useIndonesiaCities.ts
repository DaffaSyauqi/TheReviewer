import { ref, computed } from 'vue';

export type IndonesiaCity = {
    name: string;
    province: string;
    type: string;
};

const GEOJSON_URL =
    'https://raw.githubusercontent.com/TheMaggieSimpson/IndonesiaGeoJSON/main/kota-kabupaten.json';

const cities = ref<IndonesiaCity[]>([]);
const isLoading = ref(false);
const loadError = ref<string | null>(null);
let hasFetched = false;

function extractInfo(properties: Record<string, unknown>): IndonesiaCity {
    const province = String(properties.NAME_1 ?? '');
    const name = String(properties.NAME_2 ?? '');
    const type = String(properties.TYPE_2 ?? '');
    return { name, province, type };
}

async function fetchCities() {
    if (hasFetched && cities.value.length > 0) return;
    isLoading.value = true;
    loadError.value = null;
    try {
        const res = await fetch(GEOJSON_URL);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data: GeoJSON.FeatureCollection = await res.json();
        const seen = new Set<string>();
        cities.value = data.features
            .map((f) => extractInfo(f.properties as Record<string, unknown>))
            .filter((c) => {
                if (!c.name || seen.has(c.name)) return false;
                seen.add(c.name);
                return true;
            })
            .sort((a, b) => a.province.localeCompare(b.province) || a.name.localeCompare(b.name));
        hasFetched = true;
    } catch (err) {
        loadError.value =
            err instanceof Error ? err.message : 'Failed to load city data';
    } finally {
        isLoading.value = false;
    }
}

const provinces = computed(() => {
    const set = new Set<string>();
    cities.value.forEach((c) => set.add(c.province));
    return [...set].sort();
});

const citiesByProvince = computed(() => {
    const map = new Map<string, IndonesiaCity[]>();
    cities.value.forEach((c) => {
        const list = map.get(c.province) || [];
        list.push(c);
        map.set(c.province, list);
    });
    return [...map.entries()].sort((a, b) =>
        a[0].localeCompare(b[0]),
    );
});

export function useIndonesiaCities() {
    return {
        cities,
        isLoading,
        loadError,
        provinces,
        citiesByProvince,
        fetchCities,
    };
}
