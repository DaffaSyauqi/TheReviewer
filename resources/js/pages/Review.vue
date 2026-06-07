<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import {
    X,
    Search,
    Filter,
    Camera,
    UtensilsCrossed,
    Bed,
    ChevronRight,
    MapPinned,
    Star,
} from 'lucide-vue-next';
import NavBar from '@/components/NavBar.vue';
import { Map } from '@/components/map';
import { MapIndonesia } from '@/components/map';

const isLoadingData = ref(false);
const loadError = ref<string | null>(null);
const selectedRegion = ref<{
    province: string;
    city: string;
    type: string;
    properties: Record<string, unknown>;
} | null>(null);

const search = ref('');

const categories = [
    { id: 'all', label: 'Semua', count: 24 },
    { id: 'wisata', label: 'Wisata', count: 8 },
    { id: 'kuliner', label: 'Kuliner', count: 7 },
    { id: 'hotel', label: 'Penginapan', count: 5 },
    { id: 'shopping', label: 'Belanja', count: 4 },
];

const activeCategory = ref('all');

const places = ref([
    {
        id: 1,
        name: 'Gedung Sate',
        category: 'Wisata',
        address: 'Jl. Diponegoro No.22, Bandung Wetan',
        rating: 4.6,
        reviews: '1.2k',
        icon: Camera,
        color: 'text-emerald-500',
    },
    {
        id: 2,
        name: 'Kawah Putih',
        category: 'Wisata',
        address: 'Jl. Raya Ciwidey, Rancabali',
        rating: 4.7,
        reviews: '980',
        icon: Camera,
        color: 'text-emerald-500',
    },
    {
        id: 3,
        name: 'Bakso Rudal',
        category: 'Kuliner',
        address: 'Jl. Gerlong Hilir No.27, Sukajadi',
        rating: 4.5,
        reviews: '860',
        icon: UtensilsCrossed,
        color: 'text-amber-500',
    },
    {
        id: 4,
        name: 'Kopi Anjis',
        category: 'Kuliner',
        address: 'Jl. Braga No.15, Sumur Bandung',
        rating: 4.4,
        reviews: '560',
        icon: UtensilsCrossed,
        color: 'text-amber-500',
    },
    {
        id: 5,
        name: 'The Trans Luxury Hotel',
        category: 'Penginapan',
        address: 'Jl. Gatot Subroto No.289',
        rating: 4.8,
        reviews: '1.1k',
        icon: Bed,
        color: 'text-blue-500',
    },
    {
        id: 5,
        name: 'The Trans Luxury Hotel',
        category: 'Penginapan',
        address: 'Jl. Gatot Subroto No.289',
        rating: 4.8,
        reviews: '1.1k',
        icon: Bed,
        color: 'text-blue-500',
    },
]);

function onRegionClick(payload: typeof selectedRegion.value) {
    loadError.value = null;
    selectedRegion.value = payload;
    console.table(selectedRegion.value);
}

function closeSidebar() {
    selectedRegion.value = null;
}

const filteredPlaces = computed(() => {
    return places.value.filter((place) => {
        const matchSearch =
            place.name.toLowerCase().includes(search.value.toLowerCase()) ||
            place.address.toLowerCase().includes(search.value.toLowerCase());

        const matchCategory =
            activeCategory.value === 'all' ||
            place.category.toLowerCase() === activeCategory.value;

        return matchSearch && matchCategory;
    });
});

function openPlace(place: any) {
    console.log(place);
    // nanti buka dialog detail
}
</script>

<template>
    <Head title="Review" />

    <div class="relative h-screen w-full overflow-hidden">
        <NavBar />

        <Map
            :center="[117.0, -2.5]"
            :zoom="5"
            :min-zoom="4.2"
            :max-zoom="10"
            :max-bounds="[
                [93.0, -12.0],
                [142.0, 7.5],
            ]"
            class="absolute inset-0 rounded-none! border-none!"
        >
            <MapIndonesia
                @region-click="onRegionClick"
                @map-click-outside="closeSidebar"
                @loading="isLoadingData = $event"
                @error="loadError = $event"
            />
        </Map>

        <Transition name="fade">
            <div
                v-if="isLoadingData"
                class="pointer-events-none absolute inset-0 z-40 flex items-center justify-center bg-background/70 backdrop-blur-sm"
            >
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="h-9 w-9 animate-spin rounded-full border-4 border-primary-foreground border-t-primary"
                    />
                    <p class="text-sm text-foreground">
                        Memuat data wilayah...
                    </p>
                </div>
            </div>
        </Transition>

        <Transition name="fade">
            <div
                v-if="loadError"
                class="absolute bottom-6 left-1/2 z-40 -translate-x-1/2 rounded-lg border border-red-200 bg-destructive px-4 py-3 text-sm text-destructive-foreground shadow-md"
            >
                {{ loadError }}
            </div>
        </Transition>

        <Transition name="fade">
            <div
                v-if="!selectedRegion && !isLoadingData"
                class="pointer-events-none absolute bottom-6 left-1/2 z-10 -translate-x-1/2"
            >
                <div
                    class="rounded-full border bg-secondary px-4 py-2 text-sm whitespace-nowrap text-secondary-foreground shadow-sm backdrop-blur-sm"
                >
                    Klik wilayah kota / kabupaten untuk melihat detail
                </div>
            </div>
        </Transition>

        <Transition name="slide-left">
            <aside
                v-if="selectedRegion"
                class="absolute top-0 left-0 z-20 flex h-full w-full flex-col border-l bg-background shadow-xl md:w-96"
            >
                <div
                    class="flex shrink-0 items-start justify-between gap-3 border-b px-5 py-4"
                >
                    <div class="min-w-0">
                        <p
                            class="mb-1 text-[11px] font-medium tracking-widest uppercase"
                        >
                            Wilayah
                        </p>
                        <div class="flex items-center gap-2">
                            <h2
                                class="text-base leading-tight font-semibold text-foreground"
                            >
                                {{ selectedRegion.city }}
                            </h2>

                            <div v-if="selectedRegion.type" class="">
                                <span
                                    class="inline-flex items-center rounded-full border border-border bg-background px-2.5 py-0.5 text-xs font-medium text-primary"
                                >
                                    {{ selectedRegion.type }}
                                </span>
                            </div>
                        </div>
                        <p
                            v-if="selectedRegion.province"
                            class="mt-0.5 text-sm text-muted-foreground"
                        >
                            {{ selectedRegion.province }}
                        </p>
                    </div>
                    <Button variant="ghost" size="icon" @click="closeSidebar">
                        <X />
                    </Button>
                </div>

                <div class="flex min-h-0 flex-1 flex-col px-4 py-4">
                    <!-- Search -->
                    <div class="flex shrink-0 gap-2">
                        <div class="relative flex-1">
                            <Search
                                class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search places..."
                                class="pl-9"
                            />
                        </div>
                    </div>

                    <!-- Categories -->
                    <ScrollArea class="pb-2">
                        <div
                            class="mt-4 flex shrink-0 gap-2 overflow-x-auto pb-2"
                        >
                            <Button
                                v-for="category in categories"
                                :key="category.id"
                                size="sm"
                                :variant="
                                    activeCategory === category.id
                                        ? 'default'
                                        : 'outline'
                                "
                                @click="activeCategory = category.id"
                            >
                                {{ category.label }}
                                <span class="ml-1 opacity-70">
                                    {{ category.count }}
                                </span>
                            </Button>
                        </div>
                        <ScrollBar orientation="horizontal" />
                    </ScrollArea>

                    <!-- Places -->
                    <div class="mt-4 min-h-0 flex-1">
                        <ScrollArea class="h-full">
                            <div class="space-y-3 pr-4">
                                <button
                                    v-for="place in filteredPlaces"
                                    :key="place.id"
                                    @click="openPlace(place)"
                                    class="group w-full rounded-xl border bg-card p-4 text-left transition-all hover:border-primary/40 hover:bg-accent"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="min-w-0 flex-1">
                                            <h3
                                                class="truncate text-sm font-semibold text-foreground"
                                            >
                                                {{ place.name }}
                                            </h3>

                                            <p
                                                class="mt-1 text-xs font-medium text-primary"
                                            >
                                                {{ place.category }}
                                            </p>

                                            <div
                                                class="mt-2 flex items-start gap-1 text-xs text-muted-foreground"
                                            >
                                                <MapPinned
                                                    class="mt-0.5 h-3 w-3 shrink-0"
                                                />
                                                <span class="line-clamp-1">
                                                    {{ place.address }}
                                                </span>
                                            </div>
                                        </div>

                                        <ChevronRight
                                            class="mt-1 h-4 w-4 text-muted-foreground transition-transform group-hover:translate-x-1"
                                        />
                                    </div>
                                </button>
                            </div>
                        </ScrollArea>
                    </div>
                </div>
            </aside>
        </Transition>
    </div>
</template>

<style scoped>
.slide-left-enter-active,
.slide-left-leave-active {
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-left-enter-from,
.slide-left-leave-to {
    transform: translateX(-100%);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
