<script setup lang="ts">
import { Button } from '@/components/ui/button';
import PlanetIlusstration from '@/components/landing/PlanetIlusstration.vue';
</script>

<template>
    <main
        class="relative z-10 mx-auto max-w-7xl px-6 pt-10 pb-20 sm:px-8 md:pb-32 md:pl-28"
    >
        <div class="grid items-start gap-16 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-12">
                <div class="lg:pt-12">
                    <h1
                        class="font-display mb-8 text-[clamp(3rem,9vw,10rem)] leading-[0.85] font-black tracking-tighter uppercase md:mb-12 md:leading-[0.8]"
                    >
                        SPATIAL <br />
                        <span class="outline-text block">CROWD</span>
                        MAPPING.
                    </h1>
                    <div class="max-w-md border-l-4 border-accent py-2 pl-8">
                        <p
                            class="text-sm leading-relaxed font-medium tracking-wide text-primary/70 uppercase"
                        >
                            WE COMBINE CROWDSOURCING WITH GEOSPATIAL
                            VISUALIZATION. EVERY PIN IS A REVIEW. EVERY
                            COORDINATE IS A STATEMENT OF THE REAL WORLD.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col flex-wrap gap-4 sm:flex-row sm:gap-6">
                    <Button
                        class="relative h-auto w-full justify-center overflow-hidden rounded-none bg-foreground px-6 py-4 text-xs font-black tracking-[0.2em] text-accent uppercase shadow-[4px_4px_0_var(--color-accent)] transition-all hover:-translate-x-1 hover:-translate-y-1 hover:bg-foreground hover:text-accent hover:shadow-[8px_8px_0_var(--color-accent)] sm:w-auto sm:px-10 sm:py-5 sm:shadow-[8px_8px_0_var(--color-accent)] sm:hover:shadow-[12px_12px_0_var(--color-accent)]"
                    >
                        EXPLORE_MAP
                    </Button>
                    <Button
                        variant="outline"
                        class="h-auto w-full justify-center rounded-none border-2 border-foreground bg-transparent px-6 py-4 text-xs font-black tracking-[0.2em] text-primary uppercase shadow-none transition-colors hover:bg-foreground/5 hover:text-primary hover:shadow-none sm:w-auto sm:px-10 sm:py-5"
                    >
                        ADD_LOCATION
                    </Button>
                </div>
            </div>

            <div class="relative lg:pt-12">
                <PlanetIlusstration />
            </div>
        </div>
    </main>
</template>
