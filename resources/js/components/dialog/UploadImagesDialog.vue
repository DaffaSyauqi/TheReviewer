<script setup lang="ts">
import * as LucideIcons from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { toast } from 'vue-sonner';

type UploadedImage = {
    file: File;
    preview: string;
    name: string;
    size: string;
};

const props = withDefaults(
    defineProps<{
        open: boolean
        images?: UploadedImage[]
        maxImages?: number
        acceptedFormats?: string[]
        maxSizeLabel?: string
    }>(),
    {
        images: () => [] as UploadedImage[],
        maxImages: 3,
        acceptedFormats: () => ['image/png', 'image/jpeg', 'image/webp'],
        maxSizeLabel: '10MB per image',
    },
)

const emit = defineEmits<{
    'update:open': [value: boolean]
    'update:images': [images: UploadedImage[]]
}>()

const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 B'
    const k = 1024
    const sizes = ['B', 'KB', 'MB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i]
}

const processFiles = (files: FileList) => {
    const currentImages = [...props.images]
    Array.from(files).forEach((file) => {
        if (currentImages.length >= props.maxImages) {
            toast.error(`You can only upload a maximum of ${props.maxImages} images`)
            return
        }
        if (file.type.startsWith('image/')) {
            const reader = new FileReader()
            reader.onload = (e) => {
                currentImages.push({
                    file,
                    preview: e.target?.result as string,
                    name: file.name,
                    size: formatFileSize(file.size),
                })
                emit('update:images', currentImages)
            }
            reader.readAsDataURL(file)
        }
    })
}

const handleDragOver = (e: DragEvent) => {
    e.preventDefault()
    e.stopPropagation()
}

const handleDrop = (e: DragEvent) => {
    e.preventDefault()
    e.stopPropagation()
    const files = e.dataTransfer?.files
    if (files) {
        processFiles(files)
    }
}

const handleBrowseClick = () => {
    const input = document.createElement('input')
    input.type = 'file'
    input.multiple = true
    input.accept = props.acceptedFormats.join(',')
    input.onchange = (e) => {
        const files = (e.target as HTMLInputElement).files
        if (files) {
            processFiles(files)
        }
    }
    input.click()
}

const removeImage = (index: number) => {
    const updated = [...props.images]
    updated.splice(index, 1)
    emit('update:images', updated)
}

const handleClose = () => {
    emit('update:open', false)
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <LucideIcons.Upload class="h-5 w-5 text-primary" />
                    Upload Images
                </DialogTitle>
                <p class="mt-1 text-sm text-muted-foreground">
                    Upload and manage images &bull; Max {{ maxImages }} images
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
                        PNG, JPG, JPEG, WEBP &bull; {{ maxSizeLabel }}
                    </p>
                </div>

                <div v-if="images.length > 0">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-medium">Image Preview</h3>
                        <p class="text-sm text-muted-foreground">
                            {{ images.length }} / {{ maxImages }} images
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div
                            v-for="(image, index) in images"
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

                <Button type="button" class="w-full" @click="handleClose">
                    Close
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
