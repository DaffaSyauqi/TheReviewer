<script setup lang="ts">
import { MapPinned } from 'lucide-vue-next';
</script>

<template>
    <footer
        class="relative z-10 border-t border-foreground/10 bg-background py-16 text-foreground md:py-20 md:pl-28"
    >
        <div
            class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-12 px-6 sm:px-8 md:flex-row md:items-end"
        >
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center bg-accent"
                    >
                        <MapPinned class="h-5 w-5 text-primary" />
                    </div>
                    <span
                        class="font-display text-2xl font-black tracking-tight uppercase"
                        >TheReviewer</span
                    >
                </div>
                <p
                    class="max-w-xs text-[10px] leading-relaxed font-bold tracking-[0.2em] text-foreground/50 uppercase"
                >
                    CROWDSOURCED RECOMMENDATIONS SINCE THE VOID. EST. 2026 / ALL
                    ZONES NOMINAL.
                </p>
            </div>
        </div>
    </footer>
</template>
