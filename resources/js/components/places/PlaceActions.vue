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
import { MoreHorizontal } from 'lucide-vue-next';

interface Place {
    id: number;
    name: string;
    description: string;
    category: { name: string };
    address: string;
    city: string;
    province?: string;
    country?: string;
    latitude?: number | string;
    longitude?: number | string;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
}

interface Props {
    placeId: number;
    placeName: string;
    place?: Place;
}

const props = defineProps<Props>();

const openDeleteDialog = ref(false);
const openDropdown = ref(false);
const openDetailsDialog = ref(false);
const isDesktop = useMediaQuery('(min-width: 768px)');

const handleDelete = () => {
    openDeleteDialog.value = false;
    openDropdown.value = false;
    router.delete(`/manage-places/${props.placeId}`);
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
            <DropdownMenuItem @click="openDeleteDialog = true">
                Delete
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
