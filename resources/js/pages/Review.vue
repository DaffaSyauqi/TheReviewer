<template>
    <div class="relative h-screen w-full overflow-hidden">
        <Map
            :center="[117.0, -2.5]"
            :zoom="5"
            :min-zoom="4.2"
            :max-zoom="10"
            :max-bounds="[
                [93.0, -12.0],
                [142.0, 7.5],
            ]"
            class="absolute inset-0 !rounded-none !border-none"
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
                class="pointer-events-none absolute inset-0 z-20 flex items-center justify-center bg-background/70 backdrop-blur-sm"
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
                class="absolute bottom-6 left-1/2 z-20 -translate-x-1/2 rounded-lg border border-red-200 bg-destructive px-4 py-3 text-sm text-destructive-foreground shadow-md"
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

        <Transition name="slide-right">
            <aside
                v-if="selectedRegion"
                class="absolute top-0 right-0 z-10 flex h-full w-80 flex-col border-l bg-background shadow-xl"
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

                <div class="flex-1 overflow-y-auto px-5 py-4">
                    <div
                        class="flex h-36 items-center justify-center rounded-lg border border-dashed border-white"
                    >
                        <p class="text-center text-sm text-muted-foreground">
                            Konten sidebar
                        </p>
                    </div>
                </div>

                <div class="shrink-0 border-t px-5 py-3">
                    <p class="text-center text-xs text-muted-foreground">
                        Klik wilayah lain untuk berpindah
                    </p>
                </div>
            </aside>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { X } from 'lucide-vue-next';
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

function onRegionClick(payload: typeof selectedRegion.value) {
    loadError.value = null;
    selectedRegion.value = payload;
    console.table(selectedRegion.value);
}

function closeSidebar() {
    selectedRegion.value = null;
}
</script>

<style scoped>
.slide-right-enter-active,
.slide-right-leave-active {
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-right-enter-from,
.slide-right-leave-to {
    transform: translateX(100%);
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
