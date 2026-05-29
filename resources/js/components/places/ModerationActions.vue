<script setup lang="ts">
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
import { MoreHorizontal } from 'lucide-vue-next';

interface Props {
    placeId: number;
    placeName: string;
}

const props = defineProps<Props>();

const openApproveDialog = ref(false);
const openRejectDialog = ref(false);
const openDropdown = ref(false);

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
            <DropdownMenuItem @click="openApproveDialog = true">
                Approve
            </DropdownMenuItem>
            <DropdownMenuItem @click="openRejectDialog = true">
                Reject
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <AlertDialog v-model:open="openApproveDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Approve Place</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to approve "{{ placeName }}"? This place will be visible on the map.
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
                    Are you sure you want to reject "{{ placeName }}"? This place will not be visible on the map.
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
