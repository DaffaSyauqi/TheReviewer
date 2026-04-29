<script setup lang="ts">
import { MapPinned } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
    <nav
        class="relative z-50 mx-auto flex max-w-7xl items-center justify-between px-6 py-8 sm:px-8 md:py-10 md:pl-28"
    >
        <div
            class="group flex cursor-pointer items-center gap-3 duration-500 ease-out"
        >
            <div
                class="flex h-8 w-8 transform items-center justify-center rounded-none bg-foreground transition-transform"
            >
                <MapPinned class="h-5 w-5 text-accent" />
            </div>
            <span
                class="font-display text-xl font-black tracking-tighter uppercase"
                >TheReviewer</span
            >
        </div>

        <Button
            variant="outline"
            class="h-auto rounded-none border-none bg-foreground px-4 py-2 text-[10px] font-bold tracking-widest text-primary uppercase shadow-[4px_4px_0_var(--color-accent)] transition-all duration-300 hover:bg-accent hover:text-primary hover:shadow-none sm:px-6 sm:py-2"
        >
            Launch_Map
        </Button>
    </nav>
</template>
