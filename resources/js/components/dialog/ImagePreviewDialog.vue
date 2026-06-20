<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useMediaQuery } from '@vueuse/core'
import * as LucideIcons from 'lucide-vue-next'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import {
    Drawer,
    DrawerContent,
    DrawerHeader,
    DrawerTitle,
} from '@/components/ui/drawer'
import type { PlaceImage } from '@/types'

type ImageItem = PlaceImage | { id: number; url: string }

const props = withDefaults(
    defineProps<{
        open: boolean
        images: ImageItem[]
        title?: string
        emptyText?: string
        startIndex?: number
    }>(),
    {
        title: 'Image Preview',
        emptyText: 'No images uploaded',
        startIndex: 0,
    },
)

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const isDesktop = useMediaQuery('(min-width: 768px)')

const currentIndex = ref(props.startIndex)

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            currentIndex.value = props.startIndex
        }
    },
)

const hasImages = computed(() => props.images.length > 0)
const hasMultiple = computed(() => props.images.length > 1)

const nextImage = () => {
    if (!hasImages.value) return
    currentIndex.value = (currentIndex.value + 1) % props.images.length
}

const prevImage = () => {
    if (!hasImages.value) return
    currentIndex.value =
        (currentIndex.value - 1 + props.images.length) % props.images.length
}

const selectImage = (index: number) => {
    currentIndex.value = index
}
</script>

<template>
    <Dialog
        v-if="isDesktop"
        :open="open"
        @update:open="emit('update:open', $event)"
    >
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <LucideIcons.Image class="h-5 w-5 text-primary" />
                    {{ title }}
                </DialogTitle>
            </DialogHeader>

            <div v-if="hasImages" class="space-y-4">
                <div
                    class="relative flex aspect-video items-center justify-center overflow-hidden rounded-lg bg-black"
                >
                    <img
                        :src="images[currentIndex].url"
                        :alt="`Image ${currentIndex + 1}`"
                        class="h-full w-full object-cover"
                    />
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

                <div
                    v-if="hasMultiple"
                    class="flex gap-2 overflow-x-auto pb-2"
                >
                    <button
                        v-for="(image, index) in images"
                        :key="image.id"
                        @click="selectImage(index)"
                        class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 transition-colors"
                        :class="
                            currentIndex === index
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
                    <LucideIcons.Image class="h-12 w-12 text-muted-foreground" />
                </div>
                <p class="text-muted-foreground">{{ emptyText }}</p>
            </div>
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
                    <LucideIcons.Image class="h-5 w-5 text-primary" />
                    {{ title }}
                </DrawerTitle>
            </DrawerHeader>
            <div class="px-4 pb-6">
                <div v-if="hasImages" class="space-y-4">
                    <div
                        class="relative flex aspect-video items-center justify-center overflow-hidden rounded-lg bg-black"
                    >
                        <img
                            :src="images[currentIndex].url"
                            :alt="`Image ${currentIndex + 1}`"
                            class="h-full w-full object-cover"
                        />
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

                    <div
                        v-if="hasMultiple"
                        class="flex gap-2 overflow-x-auto pb-2"
                    >
                        <button
                            v-for="(image, index) in images"
                            :key="image.id"
                            @click="selectImage(index)"
                            class="h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 transition-colors"
                            :class="
                                currentIndex === index
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
                        <LucideIcons.Image class="h-12 w-12 text-muted-foreground" />
                    </div>
                    <p class="text-muted-foreground">{{ emptyText }}</p>
                </div>
            </div>
        </DrawerContent>
    </Drawer>
</template>
