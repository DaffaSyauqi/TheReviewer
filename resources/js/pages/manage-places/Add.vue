<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import * as LucideIcons from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        icon: string | null;
    }>;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Add Places',
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    placeName: '',
    category: '',
    description: '',
    adress: '',
    city: '',
    province: '',
    country: '',
    images: [] as File[],
});

const isUploadDialogOpen = ref(false);
const uploadedImages = ref<
    Array<{ file: File; preview: string; name: string; size: string }>
>([]);

watch(isUploadDialogOpen, (isOpen) => {
    if (!isOpen) {
    }
});

const getIcon = (iconName: string | null) => {
    if (!iconName) return null;
    return (LucideIcons as Record<string, any>)[iconName] || null;
};

const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};

const handleDragOver = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
};

const handleDrop = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    const files = e.dataTransfer?.files;
    if (files) {
        processFiles(files);
    }
};

const handleBrowseClick = () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.multiple = true;
    input.accept = 'image/png,image/jpeg,image/webp';
    input.onchange = (e) => {
        const files = (e.target as HTMLInputElement).files;
        if (files) {
            processFiles(files);
        }
    };
    input.click();
};

const processFiles = (files: FileList) => {
    Array.from(files).forEach((file) => {
        if (uploadedImages.value.length >= 3) {
            toast.error('You can only upload a maximum of 3 images');
            return;
        }
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                uploadedImages.value.push({
                    file,
                    preview: e.target?.result as string,
                    name: file.name,
                    size: formatFileSize(file.size),
                });
            };
            reader.readAsDataURL(file);
        }
    });
};

const removeImage = (index: number) => {
    uploadedImages.value.splice(index, 1);
};

const syncImagesToForm = () => {
    form.images = uploadedImages.value.map((img) => img.file);
};

const handleSubmit = () => {
    syncImagesToForm();
    form.post('/manage-places', {
        onSuccess: () => {
            uploadedImages.value = [];
        },
    });
};
</script>

<template>
    <Head title="Add Places" />

    <h1 class="sr-only">Add Places</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Add Places"
            description="Enter the details of the place you want to add"
        />

        <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid gap-2">
                <Label for="placeName">Place Name</Label>
                <Input
                    id="placeName"
                    class="mt-1 block w-full"
                    v-model="form.placeName"
                    placeholder="Place name"
                />
                <InputError class="mt-2" :message="form.errors.placeName" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Select v-model="form.category">
                    <SelectTrigger class="mt-1 w-full">
                        <SelectValue placeholder="Select a category" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.slug"
                        >
                            <div class="flex items-center gap-2">
                                <component
                                    :is="getIcon(category.icon)"
                                    v-if="getIcon(category.icon)"
                                    class="h-4 w-4"
                                />
                                {{ category.name }}
                            </div>
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError class="mt-2" :message="form.errors.category" />
            </div>

            <div class="grid gap-2">
                <Label for="placeDescription">Description</Label>
                <Textarea
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    placeholder="Description"
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="adress">Full Address</Label>
                <Input
                    id="adress"
                    class="mt-1 block w-full"
                    v-model="form.adress"
                    placeholder="Full address"
                />
                <InputError class="mt-2" :message="form.errors.adress" />
            </div>

            <div class="grid gap-2">
                <Label for="city">City</Label>
                <Input
                    id="city"
                    class="mt-1 block w-full"
                    v-model="form.city"
                    placeholder="City"
                />
                <InputError class="mt-2" :message="form.errors.city" />
            </div>

            <div class="grid gap-2">
                <Label for="province"
                    >Province
                    <span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    ></Label
                >
                <Input
                    id="province"
                    class="mt-1 block w-full"
                    v-model="form.province"
                    placeholder="Province"
                />
                <InputError class="mt-2" :message="form.errors.province" />
            </div>

            <div class="grid gap-2">
                <Label for="country"
                    >Country<span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    >
                </Label>
                <Input
                    id="country"
                    class="mt-1 block w-full"
                    v-model="form.country"
                    placeholder="Country"
                />
                <InputError class="mt-2" :message="form.errors.country" />
            </div>

            <div class="grid gap-2">
                <Label for="country"
                    >Images<span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    >
                </Label>
                <Button
                    type="button"
                    variant="outline"
                    @click="isUploadDialogOpen = true"
                    class="w-full"
                >
                    <LucideIcons.Upload class="mr-2 h-4 w-4" />
                    Upload Images
                </Button>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing" type="submit">
                    {{ form.processing ? 'Creating...' : 'Add Place' }}
                </Button>
            </div>
        </form>

        <Dialog v-model:open="isUploadDialogOpen">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <LucideIcons.Upload class="h-5 w-5" />
                        Upload Images
                    </DialogTitle>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Upload and manage images • Max 3 images
                    </p>
                </DialogHeader>

                <div class="space-y-6">
                    <div
                        @dragover="handleDragOver"
                        @drop="handleDrop"
                        class="cursor-pointer rounded-lg border-2 border-dashed border-muted-foreground/30 p-8 text-center transition-colors hover:border-muted-foreground/50"
                    >
                        <LucideIcons.Image
                            class="mx-auto mb-4 h-12 w-12 text-muted-foreground"
                        />
                        <p class="mb-1 font-medium">
                            Drag and drop images here
                        </p>
                        <p class="mb-4 text-sm text-muted-foreground">
                            or click to browse from your device
                        </p>
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="handleBrowseClick"
                        >
                            <LucideIcons.FolderOpen class="mr-2 h-4 w-4" />
                            Browse Images
                        </Button>
                        <p class="mt-4 text-xs text-muted-foreground">
                            PNG, JPG, JPEG, WEBP • Max 10MB per image
                        </p>
                    </div>

                    <div v-if="uploadedImages.length > 0">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-medium">Image Preview</h3>
                            <p class="text-sm text-muted-foreground">
                                {{ uploadedImages.length }} / 3 images
                            </p>
                        </div>

                        <div
                            v-if="uploadedImages.length > 0"
                            class="grid grid-cols-3 gap-4"
                        >
                            <div
                                v-for="(image, index) in uploadedImages"
                                :key="index"
                                class="group relative"
                            >
                                <div
                                    class="relative aspect-square overflow-hidden rounded-lg bg-muted"
                                >
                                    <img
                                        :src="image.preview"
                                        :alt="image.name"
                                        class="h-full w-full object-cover"
                                    />
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100"
                                    >
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            @click="removeImage(index)"
                                            class="h-8 w-8 rounded-full bg-destructive/20 hover:bg-destructive/30"
                                        >
                                            <LucideIcons.Trash2
                                                class="h-4 w-4 text-destructive"
                                            />
                                        </Button>
                                    </div>
                                </div>
                                <p class="mt-2 truncate text-xs font-medium">
                                    {{ image.name }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ image.size }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-else class="py-8 text-center">
                        <LucideIcons.Image
                            class="mx-auto mb-3 h-10 w-10 text-muted-foreground/50"
                        />
                        <p class="mb-1 text-sm text-muted-foreground">
                            No images uploaded yet
                        </p>
                        <p class="text-xs text-muted-foreground">
                            Uploaded images will appear here
                        </p>
                    </div>

                    <Button
                        type="button"
                        class="w-full"
                        @click="isUploadDialogOpen = false"
                    >
                        Close
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>
