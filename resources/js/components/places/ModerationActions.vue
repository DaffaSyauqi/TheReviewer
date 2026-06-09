<script setup lang="ts">
import PlaceDetailsContent from './PlaceDetailsContent.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
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
import { useMediaQuery } from '@vueuse/core';
import {
    MoreHorizontal,
    ChevronLeft,
    ChevronRight,
    Image,
} from 'lucide-vue-next';
import type { ManagePlace, PlaceImage } from '@/types';

interface Props {
    placeId: number;
    placeName: string;
    place?: ManagePlace;
}

const props = defineProps<Props>();

const openApproveDialog = ref(false);
const openRejectDialog = ref(false);
const openDropdown = ref(false);
const openDetailsDialog = ref(false);
const isDesktop = useMediaQuery('(min-width: 768px)');
const openImageDialog = ref(false);
const currentImageIndex = ref(0);

const images = ref<PlaceImage[]>(props.place?.images || []);

const handleApprove = () => {
    openApproveDialog.value = false;
    openDropdown.value = false;
    router.patch(`/moderation/places/${props.placeId}/approve`);
};

const handleReject = () => {
    openRejectDialog.value = false;
    openDropdown.value = false;
    router.patch(`/moderation/places/${props.placeId}/reject`);
};

const nextImage = () => {
    if (images.value.length > 0) {
        currentImageIndex.value =
            (currentImageIndex.value + 1) % images.value.length;
    }
};

const prevImage = () => {
    if (images.value.length > 0) {
        currentImageIndex.value =
            (currentImageIndex.value - 1 + images.value.length) %
            images.value.length;
    }
};

const selectImage = (index: number) => {
    currentImageIndex.value = index;
};

const openImagePreview = () => {
    currentImageIndex.value = 0;
    openImageDialog.value = true;
};
</script>

<template>
    <DropdownMenu v-model:open="openDropdown">
        <DropdownMenuTrigger asChild>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="openDetailsDialog = true">
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem @click="openImagePreview">
                Images
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="openApproveDialog = true">
                Approve
            </DropdownMenuItem>
            <DropdownMenuItem @click="openRejectDialog = true">
                Reject
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Details Dialog (Desktop) / Drawer (Mobile) -->
    <template v-if="isDesktop">
        <Dialog v-model:open="openDetailsDialog">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Detail Information</DialogTitle>
                </DialogHeader>
                <PlaceDetailsContent
                    :place="place"
                    :placeId="placeId"
                    :placeName="placeName"
                />
            </DialogContent>
        </Dialog>
    </template>
    <template v-else>
        <Drawer v-model:open="openDetailsDialog">
            <DrawerContent>
                <DrawerHeader>
                    <DrawerTitle>Detail Information</DrawerTitle>
                </DrawerHeader>
                <div class="px-4 pb-6">
                    <PlaceDetailsContent
                        :place="place"
                        :placeId="placeId"
                        :placeName="placeName"
                    />
                </div>
            </DrawerContent>
        </Drawer>
    </template>

    <!-- Image Preview Dialog -->
    <Dialog v-model:open="openImageDialog">
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <Image class="h-5 w-5" />
                    Image Preview
                </DialogTitle>
            </DialogHeader>

            <div v-if="images.length > 0" class="space-y-4">
                <div
                    class="relative flex aspect-video items-center justify-center overflow-hidden rounded-lg bg-black"
                >
                    <img
                        :src="images[currentImageIndex].url"
                        :alt="`Image ${currentImageIndex + 1}`"
                        class="h-full w-full object-cover"
                    />
                    <button
                        v-if="images.length > 1"
                        @click="prevImage"
                        class="absolute top-1/2 left-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                    >
                        <ChevronLeft class="h-5 w-5" />
                    </button>
                    <button
                        v-if="images.length > 1"
                        @click="nextImage"
                        class="absolute top-1/2 right-4 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white transition-colors hover:bg-black/70"
                    >
                        <ChevronRight class="h-5 w-5" />
                    </button>
                </div>

                <div
                    v-if="images.length > 1"
                    class="flex gap-2 overflow-x-auto pb-2"
                >
                    <button
                        v-for="(image, index) in images"
                        :key="image.id"
                        @click="selectImage(index)"
                        class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 transition-colors"
                        :class="
                            currentImageIndex === index
                                ? 'border-primary'
                                : 'border-muted'
                        "
                    >
                        <img
                            :src="image.url"
                            :alt="`Thumbnail ${index + 1}`"
                            class="h-full w-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <div v-else class="py-12 text-center">
                <div class="mb-4 flex justify-center">
                    <Image class="h-12 w-12 text-muted-foreground" />
                </div>
                <p class="text-muted-foreground">No images uploaded</p>
            </div>
        </DialogContent>
    </Dialog>

    <AlertDialog v-model:open="openApproveDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Approve Place</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to approve "{{ placeName }}"? This
                    place will be visible on the map.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <div class="flex justify-end gap-3">
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleApprove">
                    Approve
                </AlertDialogAction>
            </div>
        </AlertDialogContent>
    </AlertDialog>

    <AlertDialog v-model:open="openRejectDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Reject Place</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to reject "{{ placeName }}"? This
                    place will not be visible on the map.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <div class="flex justify-end gap-3">
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleReject">
                    Reject
                </AlertDialogAction>
            </div>
        </AlertDialogContent>
    </AlertDialog>
</template>
