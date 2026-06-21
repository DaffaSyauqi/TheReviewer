<script setup lang="ts">
import { ref, computed } from 'vue';
import { useMediaQuery } from '@vueuse/core';
import type { Place } from '@/types';
import * as LucideIcons from 'lucide-vue-next';

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

import {
    Drawer,
    DrawerContent,
    DrawerHeader,
    DrawerTitle,
} from '@/components/ui/drawer';

import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { ImagePreviewDialog } from '@/components/dialog';

interface Props {
    open: boolean;
    place: Place | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const isDesktop = useMediaQuery('(min-width: 768px)');

const openImageDialog = ref(false);
const images = computed(() => props.place?.images || []);
const currentIndex = ref(0);

const hasMultiple = computed(() => images.value.length > 1);

const nextImage = () => {
    if (!images.value.length) return;
    currentIndex.value = (currentIndex.value + 1) % images.value.length;
};

const prevImage = () => {
    if (!images.value.length) return;
    currentIndex.value =
        (currentIndex.value - 1 + images.value.length) % images.value.length;
};

const openImagePreview = () => {
    openImageDialog.value = true;
};

const googleMapsUrl = computed(() => {
    if (!props.place?.latitude || !props.place?.longitude) return null;
    return `https://maps.google.com/?q=${props.place.latitude},${props.place.longitude}`;
});

const formatValue = (value: any) => value || '-';
</script>

<template>
    <Dialog
        v-if="isDesktop"
        :open="open"
        @update:open="emit('update:open', $event)"
    >
        <DialogContent
            class="max-h-[98vh] max-w-[98vw] overflow-hidden p-0 sm:max-w-6xl"
        >
            <template v-if="place">
                <DialogHeader class="px-4 pt-4">
                    <DialogTitle class="flex items-center gap-2">
                        <LucideIcons.ListCollapse
                            class="h-5 w-5 text-primary"
                        />Place Details
                    </DialogTitle>
                </DialogHeader>

                <div class="px-4 pb-4">
                    <div v-if="images.length > 0" class="space-y-4">
                        <div
                            class="relative h-72 rounded-lg bg-black"
                        >
                            <button
                                @click="openImagePreview"
                                class="h-full w-full cursor-pointer"
                            >
                                <img
                                    :src="images[currentIndex].url"
                                    :alt="`Image ${currentIndex + 1}`"
                                    class="h-full w-full object-cover"
                                />
                            </button>
                            <button
                                v-if="hasMultiple"
                                @click="prevImage"
                                class="absolute top-1/2 left-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                            >
                                <LucideIcons.ChevronLeft class="h-5 w-5" />
                            </button>
                            <button
                                v-if="hasMultiple"
                                @click="nextImage"
                                class="absolute top-1/2 right-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                            >
                                <LucideIcons.ChevronRight class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div v-else class="py-12 text-center">
                        <div class="mb-4 flex justify-center">
                            <LucideIcons.Image
                                class="h-12 w-12 text-muted-foreground"
                            />
                        </div>
                        <p class="text-muted-foreground">No images uploaded</p>
                    </div>

                    <Separator />

                    <div class="mt-4 grid gap-6 lg:grid-cols-[1fr_auto_1fr]">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <h2
                                    class="inline-flex items-center text-xl leading-tight font-semibold text-foreground"
                                >
                                    {{ formatValue(place.name) }}
                                </h2>

                                <span
                                    class="inline-flex items-center rounded-full border border-border px-2.5 py-0.5 text-sm font-medium text-primary"
                                >
                                    {{ formatValue(place.category?.name) }}
                                </span>
                            </div>

                            <Separator />

                            <div>
                                <h3 class="text-md mb-2 font-semibold">
                                    Description
                                </h3>

                                <p
                                    class="text-sm leading-6 text-muted-foreground"
                                >
                                    {{ formatValue(place.description) }}
                                </p>
                            </div>
                        </div>

                        <Separator orientation="vertical" />

                        <div class="space-y-2">
                            <div class="flex gap-3">
                                <LucideIcons.MapPin
                                    class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                />

                                <div>
                                    <p class="text-xs text-muted-foreground">
                                        Address
                                    </p>

                                    <p class="text-sm font-medium">
                                        {{ formatValue(place.address) }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex gap-3">
                                <LucideIcons.Building2
                                    class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                />

                                <div>
                                    <p class="text-xs text-muted-foreground">
                                        City
                                    </p>

                                    <p class="text-sm font-medium">
                                        {{ formatValue(place.city) }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex gap-3">
                                <LucideIcons.Map
                                    class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                />

                                <div>
                                    <p class="text-xs text-muted-foreground">
                                        Province
                                    </p>

                                    <p class="text-sm font-medium">
                                        {{ formatValue(place.province) }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex gap-3">
                                <LucideIcons.Globe
                                    class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                />

                                <div>
                                    <p class="text-xs text-muted-foreground">
                                        Country
                                    </p>

                                    <p class="text-sm font-medium">
                                        {{ formatValue(place.country) }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="flex gap-3">
                                <LucideIcons.Link
                                    class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                />

                                <div>
                                    <p class="text-xs text-muted-foreground">
                                        Maps Link
                                    </p>

                                    <a
                                        v-if="googleMapsUrl"
                                        :href="googleMapsUrl"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm font-medium text-primary hover:underline"
                                    >
                                        Open in Google Maps
                                    </a>
                                    <p
                                        v-else
                                        class="text-sm font-medium text-muted-foreground"
                                    >
                                        Coordinates not available
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ImagePreviewDialog
                        v-model:open="openImageDialog"
                        :images="images"
                        :startIndex="currentIndex"
                    />
                </div>
            </template>
        </DialogContent>
    </Dialog>

    <Drawer
        v-if="!isDesktop"
        :open="open"
        @update:open="emit('update:open', $event)"
    >
        <DrawerContent>
            <DrawerHeader>
                <DrawerTitle class="flex items-center gap-2">
                    <LucideIcons.ListCollapse
                        class="h-5 w-5 text-primary"
                    />Place Details
                </DrawerTitle>
            </DrawerHeader>
            <div class="px-4 pb-6">
                <ScrollArea class="h-100 pr-6">
                    <template v-if="place">
                        <div v-if="images.length > 0" class="space-y-4">
                            <div
                                class="relative h-48 rounded-lg bg-black"
                            >
                                <button
                                    @click="openImagePreview"
                                    class="h-full w-full cursor-pointer"
                                >
                                    <img
                                        :src="images[currentIndex].url"
                                        :alt="`Image ${currentIndex + 1}`"
                                        class="h-full w-full object-cover"
                                    />
                                </button>
                                <button
                                    v-if="hasMultiple"
                                    @click="prevImage"
                                    class="absolute top-1/2 left-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                                >
                                    <LucideIcons.ChevronLeft class="h-5 w-5" />
                                </button>
                                <button
                                    v-if="hasMultiple"
                                    @click="nextImage"
                                    class="absolute top-1/2 right-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                                >
                                    <LucideIcons.ChevronRight class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        <div v-else class="py-8 text-center">
                            <div class="mb-3 flex justify-center">
                                <LucideIcons.Image
                                    class="h-10 w-10 text-muted-foreground"
                                />
                            </div>
                            <p class="text-muted-foreground">
                                No images uploaded
                            </p>
                        </div>

                        <Separator />

                        <div class="mt-4 space-y-4">
                            <div class="flex items-center gap-2">
                                <h2
                                    class="inline-flex items-center text-lg leading-tight font-semibold text-foreground"
                                >
                                    {{ formatValue(place.name) }}
                                </h2>

                                <span
                                    class="inline-flex items-center rounded-full border border-border px-2 py-0.5 text-xs font-medium text-primary"
                                >
                                    {{ formatValue(place.category?.name) }}
                                </span>
                            </div>

                            <Separator />

                            <div>
                                <h3 class="text-md mb-2 font-semibold">
                                    Description
                                </h3>

                                <p
                                    class="text-sm leading-6 text-muted-foreground"
                                >
                                    {{ formatValue(place.description) }}
                                </p>
                            </div>

                            <Separator />

                            <div class="space-y-2">
                                <div class="flex gap-3">
                                    <LucideIcons.MapPin
                                        class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                    />

                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Address
                                        </p>

                                        <p class="text-sm font-medium">
                                            {{ formatValue(place.address) }}
                                        </p>
                                    </div>
                                </div>

                                <Separator />

                                <div class="flex gap-3">
                                    <LucideIcons.Building2
                                        class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                    />

                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            City
                                        </p>

                                        <p class="text-sm font-medium">
                                            {{ formatValue(place.city) }}
                                        </p>
                                    </div>
                                </div>

                                <Separator />

                                <div class="flex gap-3">
                                    <LucideIcons.Map
                                        class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                    />

                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Province
                                        </p>

                                        <p class="text-sm font-medium">
                                            {{ formatValue(place.province) }}
                                        </p>
                                    </div>
                                </div>

                                <Separator />

                                <div class="flex gap-3">
                                    <LucideIcons.Globe
                                        class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                    />

                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Country
                                        </p>

                                        <p class="text-sm font-medium">
                                            {{ formatValue(place.country) }}
                                        </p>
                                    </div>
                                </div>

                                <Separator />

                                <div class="flex gap-3">
                                    <LucideIcons.Link
                                        class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                    />

                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Maps Link
                                        </p>

                                        <a
                                            v-if="googleMapsUrl"
                                            :href="googleMapsUrl"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-sm font-medium text-primary hover:underline"
                                        >
                                            Open in Google Maps
                                        </a>
                                        <p
                                            v-else
                                            class="text-sm font-medium text-muted-foreground"
                                        >
                                            Coordinates not available
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ImagePreviewDialog
                            v-model:open="openImageDialog"
                            :images="images"
                            :startIndex="currentIndex"
                        />
                    </template>
                </ScrollArea>
            </div>
        </DrawerContent>
    </Drawer>
</template>
