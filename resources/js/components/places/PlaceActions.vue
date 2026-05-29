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

const openDeleteDialog = ref(false);
const openDropdown = ref(false);

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
            <DropdownMenuItem @click="openDeleteDialog = true">
                Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <AlertDialog v-model:open="openDeleteDialog">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Delete Place</AlertDialogTitle>
                <AlertDialogDescription>
                    Are you sure you want to delete "{{ placeName }}"? This action cannot be undone.
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
