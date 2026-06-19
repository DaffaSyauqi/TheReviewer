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
import * as LucideIcons from 'lucide-vue-next';
import { ImagePreviewDialog } from '@/components/dialog';
import type { Place, PlaceImage } from '@/types';

interface Props {
    placeId: number;
    placeName: string;
    place?: Place;
}

const props = defineProps<Props>();

const openDeleteDialog = ref(false);
const openDropdown = ref(false);
const openDetailsDialog = ref(false);
const openImageDialog = ref(false);
const isDesktop = useMediaQuery('(min-width: 768px)');

const images = ref<PlaceImage[]>(props.place?.images || []);

const handleDelete = () => {
    openDeleteDialog.value = false;
    openDropdown.value = false;
    router.delete(`/manage-places/${props.placeId}`);
};

const openImagePreview = () => {
    openImageDialog.value = true;
};
</script>

<template>
    <DropdownMenu v-model:open="openDropdown">
        <DropdownMenuTrigger asChild>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <LucideIcons.MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="openDetailsDialog = true">
                <span><LucideIcons.List /></span>
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem @click="openImagePreview">
                <span><LucideIcons.Image /></span>
                Images
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="openDeleteDialog = true">
                <span><LucideIcons.Trash2 /></span>
                Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Details Dialog (Desktop) / Drawer (Mobile) -->
    <template v-if="isDesktop">
        <Dialog v-model:open="openDetailsDialog">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <LucideIcons.List class="h-5 w-5 text-primary" />
                        Detail Information
                    </DialogTitle>
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
                    <DrawerTitle class="flex items-center gap-2">
                        <LucideIcons.List class="h-5 w-5 text-primary" />
                        Detail Information
                    </DrawerTitle>
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
    <ImagePreviewDialog
        v-model:open="openImageDialog"
        :images="images"
    />

    <!-- Delete Dialog -->
    <AlertDialog v-model:open="openDeleteDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Delete Place</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to delete "{{ placeName }}"? This
                    action cannot be undone.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <div class="flex justify-end gap-3">
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleDelete">
                    Delete
                </AlertDialogAction>
            </div>
        </AlertDialogContent>
    </AlertDialog>
</template>
