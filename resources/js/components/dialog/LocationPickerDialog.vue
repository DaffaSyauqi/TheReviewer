<script setup lang="ts">
import { ref, computed, watch, onUnmounted } from 'vue';
import { useMediaQuery } from '@vueuse/core';
import * as LucideIcons from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Drawer,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
} from '@/components/ui/drawer';
import { Map } from '@/components/map';
import { MapMarker } from '@/components/map';
import { MapControls } from '@/components/map';
import MapClickHandler from './MapClickHandler.vue';

type Coordinates = {
    latitude: number;
    longitude: number;
};

const props = withDefaults(
    defineProps<{
        open: boolean;
        latitude?: number;
        longitude?: number;
    }>(),
    {
        latitude: 0,
        longitude: 0,
    },
);

const emit = defineEmits<{
    'update:open': [value: boolean];
    'update:latitude': [value: number];
    'update:longitude': [value: number];
    confirm: [coords: Coordinates];
}>();

const isMobile = useMediaQuery('(max-width: 768px)');

const hasLocation = computed(
    () => props.latitude !== 0 || props.longitude !== 0,
);

const markerLat = ref(hasLocation.value ? props.latitude : -2.5);
const markerLng = ref(hasLocation.value ? props.longitude : 117.0);
const markerVisible = ref(hasLocation.value);

const coordinatesDisplay = computed(() => {
    if (!markerVisible.value) return null;
    return `${markerLat.value.toFixed(6)}, ${markerLng.value.toFixed(6)}`;
});

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            if (hasLocation.value) {
                markerLat.value = props.latitude;
                markerLng.value = props.longitude;
                markerVisible.value = true;
            } else {
                markerLat.value = -2.5;
                markerLng.value = 117.0;
                markerVisible.value = false;
            }
        }
    },
);

function onMarkerDragEnd(lngLat: { lng: number; lat: number }) {
    markerLat.value = lngLat.lat;
    markerLng.value = lngLat.lng;
    markerVisible.value = true;
}

function onMapClick(lngLat: { lng: number; lat: number }) {
    markerLat.value = lngLat.lat;
    markerLng.value = lngLat.lng;
    markerVisible.value = true;
}

function handleConfirm() {
    emit('update:latitude', markerLat.value);
    emit('update:longitude', markerLng.value);
    emit('confirm', { latitude: markerLat.value, longitude: markerLng.value });
    emit('update:open', false);
}

function handleCancel() {
    emit('update:open', false);
}
</script>

<template>
    <!-- Desktop: Dialog -->
    <Dialog
        v-if="!isMobile"
        :open="open"
        @update:open="emit('update:open', $event)"
    >
        <DialogContent
            class="flex h-[80vh] max-h-[80vh] w-[80vw]! flex-col gap-0 overflow-hidden p-0 sm:max-w-250!"
            :show-close-button="false"
        >
            <DialogHeader class="shrink-0 border-b px-6 py-4">
                <DialogTitle class="flex items-center gap-2">
                    <LucideIcons.MapPin class="h-5 w-5 text-primary" />
                    Select Location
                </DialogTitle>
                <DialogDescription>
                    Click on the map or drag the marker to set the exact
                    location.
                </DialogDescription>
            </DialogHeader>

            <div class="flex min-h-0 flex-1 flex-col gap-3 px-6 py-4">
                <!-- Map -->
                <div
                    class="relative min-h-0 flex-1 overflow-hidden rounded-lg border bg-muted"
                >
                    <Map
                        :center="[117.0, -2.5]"
                        :zoom="5"
                        :min-zoom="4.2"
                        :max-zoom="18"
                        :max-bounds="[
                            [93.0, -12.0],
                            [142.0, 7.5],
                        ]"
                        class="absolute inset-0 rounded-none! border-none!"
                    >
                        <MapClickHandler @click="onMapClick" />
                        <MapMarker
                            v-if="markerVisible"
                            :longitude="markerLng"
                            :latitude="markerLat"
                            :draggable="true"
                            @drag-end="onMarkerDragEnd"
                        >
                            <div class="flex flex-col items-center">
                                <LucideIcons.MapPin
                                    class="h-8 w-8 text-primary drop-shadow-md"
                                />
                                <div
                                    class="h-2 w-2 rounded-full bg-primary/50"
                                />
                            </div>
                        </MapMarker>
                        <MapControls
                            position="bottom-right"
                            :show-zoom="true"
                            :show-locate="true"
                        />
                    </Map>

                    <div
                        v-if="!markerVisible"
                        class="absolute bottom-12 left-1/2 z-10 -translate-x-1/2 rounded-full border bg-secondary px-4 py-2 text-sm whitespace-nowrap text-secondary-foreground shadow-sm backdrop-blur-sm"
                    >
                        Click anywhere on the map to place a marker
                    </div>

                    <div
                        v-if="coordinatesDisplay"
                        class="absolute bottom-12 left-2 z-10 rounded-md border bg-background/90 px-2 py-1 text-xs font-medium text-foreground shadow-sm backdrop-blur-sm"
                    >
                        {{ coordinatesDisplay }}
                    </div>
                </div>
            </div>

            <DialogFooter
                class="shrink-0 flex-row justify-between border-t px-6 py-4 sm:justify-between"
            >
                <Button variant="outline" @click="handleCancel">
                    Cancel
                </Button>
                <Button :disabled="!markerVisible" @click="handleConfirm">
                    <LucideIcons.Check class="mr-2 h-4 w-4" />
                    Confirm Location
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Mobile: Drawer -->
    <Drawer
        v-if="isMobile"
        :open="open"
        @update:open="emit('update:open', $event)"
    >
        <DrawerContent
            class="flex h-[95vh]! max-h-[95vh]! flex-col rounded-t-lg!"
        >
            <DrawerHeader class="shrink-0 border-b px-4 py-3">
                <DrawerTitle class="flex items-center gap-2">
                    <LucideIcons.MapPin class="h-5 w-5 text-primary" />
                    Select Location
                </DrawerTitle>
                <DrawerDescription>
                    Tap on the map or drag the marker to set the exact location.
                </DrawerDescription>
            </DrawerHeader>

            <div class="flex min-h-0 flex-1 flex-col gap-3 px-4 py-3">
                <!-- Map -->
                <div
                    class="relative min-h-0 flex-1 overflow-hidden rounded-lg border bg-muted"
                >
                    <Map
                        :center="[117.0, -2.5]"
                        :zoom="5"
                        :min-zoom="4.2"
                        :max-zoom="18"
                        :max-bounds="[
                            [93.0, -12.0],
                            [142.0, 7.5],
                        ]"
                        class="absolute inset-0 rounded-none! border-none!"
                    >
                        <MapClickHandler @click="onMapClick" />
                        <MapMarker
                            v-if="markerVisible"
                            :longitude="markerLng"
                            :latitude="markerLat"
                            :draggable="true"
                            @drag-end="onMarkerDragEnd"
                        >
                            <div class="flex flex-col items-center">
                                <LucideIcons.MapPin
                                    class="h-8 w-8 text-primary drop-shadow-md"
                                />
                                <div
                                    class="h-2 w-2 rounded-full bg-primary/50"
                                />
                            </div>
                        </MapMarker>
                        <MapControls
                            position="bottom-right"
                            :show-zoom="true"
                            :show-locate="true"
                        />
                    </Map>

                    <div
                        v-if="!markerVisible"
                        class="absolute bottom-12 left-1/2 z-10 -translate-x-1/2 rounded-full border bg-secondary px-4 py-2 text-sm whitespace-nowrap text-secondary-foreground shadow-sm backdrop-blur-sm"
                    >
                        Tap anywhere on the map to place a marker
                    </div>

                    <div
                        v-if="coordinatesDisplay"
                        class="absolute bottom-12 left-2 z-10 rounded-md border bg-background/90 px-2 py-1 text-xs font-medium text-foreground shadow-sm backdrop-blur-sm"
                    >
                        {{ coordinatesDisplay }}
                    </div>
                </div>
            </div>

            <DrawerFooter class="shrink-0 border-t px-4 py-3">
                <div class="flex w-full gap-3">
                    <Button
                        variant="outline"
                        class="flex-1"
                        @click="handleCancel"
                    >
                        Cancel
                    </Button>
                    <Button
                        class="flex-1"
                        :disabled="!markerVisible"
                        @click="handleConfirm"
                    >
                        <LucideIcons.Check class="mr-2 h-4 w-4" />
                        Confirm
                    </Button>
                </div>
            </DrawerFooter>
        </DrawerContent>
    </Drawer>
</template>
