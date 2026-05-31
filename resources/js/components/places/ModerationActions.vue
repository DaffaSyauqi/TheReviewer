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

interface ModerationPlace {
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
    place?: ModerationPlace;
}

const props = defineProps<Props>();

const openApproveDialog = ref(false);
const openRejectDialog = ref(false);
const openDropdown = ref(false);
const openDetailsDialog = ref(false);
const isDesktop = useMediaQuery('(min-width: 768px)');

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
