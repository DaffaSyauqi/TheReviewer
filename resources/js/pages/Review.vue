<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import * as LucideIcons from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import NavBar from '@/components/NavBar.vue';
import { Map } from '@/components/map';
import { MapIndonesia } from '@/components/map';
import PlaceDetailDialog from '@/components/dialog/PlaceDetailDialog.vue';
import type { Place, ReviewCategory } from '@/types';

const props = defineProps<{
    places: Place[];
    categories: ReviewCategory[];
}>();

const isLoadingData = ref(false);
const loadError = ref<string | null>(null);
const selectedRegion = ref<{
    province: string;
    city: string;
    type: string;
    properties: Record<string, unknown>;
} | null>(null);

const search = ref('');
const activeCategory = ref('all');

const places = ref(props.places);

const selectedPlace = ref<Place | null>(null);
const placeDialogOpen = ref(false);

const getIcon = (iconName: string | null) => {
    if (!iconName) return null;
    return (LucideIcons as Record<string, any>)[iconName] || null;
};

function onRegionClick(payload: typeof selectedRegion.value) {
    loadError.value = null;
    selectedRegion.value = payload;
    activeCategory.value = 'all';
    search.value = '';
}

function closeSidebar() {
    selectedRegion.value = null;
}

const cityPlaces = computed(() => {
    if (!selectedRegion.value) return places.value;
    return places.value.filter(
        (place) => place.city === selectedRegion.value!.city,
    );
});

const cityCategories = computed(() => {
    const base = props.categories.filter((c) => c.id !== 'all');
    const cityCount = cityPlaces.value.length;

    const mapped = base.map((c) => {
        const count = cityPlaces.value.filter(
            (p) => p.category.slug === c.id,
        ).length;
        return { ...c, count };
    });

    return [
        { id: 'all', label: 'Semua', icon: 'LayoutGrid', count: cityCount },
        ...mapped,
    ];
});

const filteredPlaces = computed(() => {
    return cityPlaces.value.filter((place) => {
        const matchSearch =
            place.name.toLowerCase().includes(search.value.toLowerCase()) ||
            place.address.toLowerCase().includes(search.value.toLowerCase());

        const matchCategory =
            activeCategory.value === 'all' ||
            place.category.slug === activeCategory.value;

        return matchSearch && matchCategory;
    });
});

function translateLanguage() {
    if (!selectedRegion.value) return '';

    const type = selectedRegion.value.type.toLowerCase();
    if (type === 'kota') return 'City';
    if (type === 'kabupaten') return 'Regency';

    return selectedRegion.value.type;
}

function openPlace(place: Place) {
    selectedPlace.value = place;
    placeDialogOpen.value = true;
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
                        Loading region data...
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
                    Click on the City/Regency area to see details
                </div>
            </div>
        </Transition>

        <Transition name="slide-left">
            <aside
                v-if="selectedRegion"
                class="absolute top-0 left-0 z-40 flex h-full w-full flex-col border-l bg-background shadow-xl md:w-96"
            >
                <div
                    class="flex shrink-0 items-start justify-between gap-3 border-b px-5 py-4"
                >
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h2
                                class="text-base leading-tight font-semibold text-foreground"
                            >
                                {{ selectedRegion.city }}
                            </h2>

                            <div>
                                <span
                                    class="inline-flex items-center rounded-full border border-border bg-background px-2.5 py-0.5 text-xs font-medium text-primary"
                                >
                                    {{ translateLanguage() }}
                                </span>
                            </div>
                        </div>
                        <p class="mt-0.5 text-sm text-muted-foreground">
                            {{ selectedRegion.province }}
                        </p>
                    </div>
                    <Button variant="ghost" size="icon" @click="closeSidebar">
                        <LucideIcons.X />
                    </Button>
                </div>

                <div class="flex min-h-0 flex-1 flex-col px-4 py-4">
                    <!-- Search -->
                    <div class="flex shrink-0 gap-2">
                        <div class="relative flex-1">
                            <LucideIcons.Search
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
                                v-for="category in cityCategories"
                                :key="category.id"
                                size="sm"
                                :variant="
                                    activeCategory === category.id
                                        ? 'default'
                                        : 'outline'
                                "
                                @click="activeCategory = category.id"
                            >
                                <component
                                    :is="getIcon(category.icon)"
                                    v-if="getIcon(category.icon)"
                                    class="mr-1 h-4 w-4"
                                />
                                {{ category.label }}
                                <span class="ml-1 opacity-70">
                                    {{ category.count }}
                                </span>
                            </Button>
                        </div>
                        <ScrollBar orientation="horizontal" />
                    </ScrollArea>

                    <!-- Places -->
                    <div
                        v-if="filteredPlaces.length > 0"
                        class="mt-4 min-h-0 flex-1"
                    >
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
                                                {{ place.category.name }}
                                            </p>

                                            <div
                                                class="mt-2 flex items-start gap-1 text-xs text-muted-foreground"
                                            >
                                                <LucideIcons.MapPinned
                                                    class="mt-0.5 h-3 w-3 shrink-0"
                                                />
                                                <span class="line-clamp-1">
                                                    {{ place.address }}
                                                </span>
                                            </div>
                                        </div>

                                        <LucideIcons.ChevronRight
                                            class="mt-1 h-4 w-4 text-muted-foreground transition-transform group-hover:translate-x-1"
                                        />
                                    </div>
                                </button>
                            </div>
                        </ScrollArea>
                    </div>

                    <div v-else class="py-12 text-center">
                        <div class="mb-4 flex justify-center">
                            <LucideIcons.MapPinX
                                class="h-12 w-12 text-muted-foreground"
                            />
                        </div>
                        <p class="mb-4 text-muted-foreground">
                            No places found
                        </p>
                        <Link href="/manage-places/create">
                            <Button>Add Places</Button>
                        </Link>
                    </div>
                </div>
            </aside>
        </Transition>

        <PlaceDetailDialog
            v-model:open="placeDialogOpen"
            :place="selectedPlace"
        />
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
