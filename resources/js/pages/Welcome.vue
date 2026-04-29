<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import {
    Sparkles,
    Map as MapIcon,
    Users,
    MapPin,
    Radio,
    Layers,
    Zap,
    Moon,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const mounted = ref(false);
const mousePos = ref({ x: 0, y: 0 });

const handleMouseMove = (e: MouseEvent) => {
    mousePos.value = { x: e.clientX, y: e.clientY };
};

onMounted(() => {
    mounted.value = true;
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});

const features = [
    {
        title: 'Spatial_Mapping',
        icon: MapIcon,
        desc: 'Interactive digital cartography plotting user experiences across real-world coordinates.',
    },
    {
        title: 'Crowd_Synthesis',
        icon: Users,
        desc: 'Aggregated intelligence from global contributors mapped in real-time.',
    },
    {
        title: 'Live_Feedback',
        icon: Radio,
        desc: 'Synchronous updates ensuring place information remains perpetually relevant.',
    },
];
</script>

<template>
    <div
        class="relative min-h-screen overflow-hidden bg-background font-sans text-primary selection:bg-accent/40"
    >
        <div class="grid-lines pointer-events-none absolute inset-0 z-0" />

        <aside
            class="pointer-events-none fixed top-0 bottom-0 left-0 z-20 hidden w-20 flex-col items-center justify-between border-r border-foreground/10 py-12 md:flex"
        >
            <div
                class="vertical-rl rotate-180 transform text-[10px] font-bold tracking-[0.3em] uppercase opacity-40"
            >
                ESTABLISHED.2024
            </div>
            <div
                class="vertical-rl rotate-180 transform text-[10px] font-bold tracking-[0.3em] uppercase opacity-40"
            >
                VOLUME_NO.08
            </div>
        </aside>

        <nav
            class="relative z-50 mx-auto flex max-w-7xl items-center justify-between px-6 py-8 sm:px-8 md:py-10 md:pl-28"
        >
            <div
                class="group flex cursor-pointer items-center gap-3 duration-500 ease-out"
            >
                <div
                    class="flex h-8 w-8 transform items-center justify-center rounded-none bg-foreground transition-transform group-hover:rotate-90"
                >
                    <Sparkles class="h-5 w-5 text-accent" />
                </div>
                <span
                    class="font-display text-xl font-black tracking-tighter uppercase"
                    >TheReviewer_Sys</span
                >
            </div>

            <div class="hidden items-center gap-12 lg:flex">
                <a
                    v-for="(item, i) in [
                        'Map_View',
                        'Contributions',
                        'Guidelines',
                        'About_Us',
                    ]"
                    :key="item"
                    href="#"
                    class="relative text-[10px] font-bold tracking-widest text-primary uppercase transition-colors after:absolute after:-bottom-1 after:left-0 after:h-[1px] after:w-full after:origin-left after:scale-x-0 after:bg-accent after:transition-transform hover:text-primary/60 hover:after:scale-x-100"
                >
                    {{ item }}
                </a>
            </div>

            <Button
                variant="outline"
                class="h-auto rounded-none border-none bg-foreground px-4 py-2 text-[10px] font-bold tracking-widest text-background uppercase shadow-[4px_4px_0_var(--color-accent)] transition-all duration-300 hover:bg-accent hover:text-primary hover:shadow-none sm:px-6 sm:py-2"
            >
                Launch_Map
            </Button>
        </nav>

        <main
            class="relative z-10 mx-auto max-w-7xl px-6 pt-10 pb-20 sm:px-8 md:pb-32 md:pl-28"
        >
            <div class="grid items-start gap-16 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-12">
                    <div>
                        <div
                            class="mb-8 inline-block bg-foreground px-4 py-1 font-mono text-[10px] tracking-[0.2em] text-background uppercase"
                        >
                            MOD_TYPE: MAP_01 / ACTIVE
                        </div>
                        <h1
                            class="font-display mb-8 text-[clamp(3rem,9vw,10rem)] leading-[0.85] font-black tracking-tighter uppercase md:mb-12 md:leading-[0.8]"
                        >
                            SPATIAL <br />
                            <span class="outline-text block">CROWD</span>
                            MAPPING.
                        </h1>
                        <div
                            class="max-w-md border-l-4 border-accent py-2 pl-8"
                        >
                            <p
                                class="text-sm leading-relaxed font-medium tracking-wide text-primary/70 uppercase"
                            >
                                WE COMBINE CROWDSOURCING WITH GEOSPATIAL
                                VISUALIZATION. EVERY PIN IS A REVIEW. EVERY
                                COORDINATE IS A STATEMENT OF THE REAL WORLD.
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col flex-wrap gap-4 sm:flex-row sm:gap-6"
                    >
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

                <div class="relative pt-20">
                    <div
                        class="animate-fade-in-left relative mx-auto flex aspect-square max-w-[400px] items-center justify-center lg:max-w-none"
                        style="animation-duration: 1.2s"
                    >
                        <div
                            class="absolute z-0 h-[90%] w-[90%] animate-[spin_30s_linear_infinite] rounded-full border-[4px] border-dashed border-foreground/30"
                        ></div>
                        <div
                            class="absolute z-0 h-[110%] w-[110%] rounded-full border-[2px] border-foreground/10"
                        ></div>

                        <div
                            class="absolute top-[5%] right-[5%] z-20 flex h-12 w-12 animate-bounce items-center justify-center border-[4px] border-foreground bg-background shadow-[6px_6px_0_var(--color-foreground)]"
                        >
                            <div
                                class="h-3 w-3 animate-pulse border-[2px] border-foreground bg-accent"
                            ></div>
                        </div>

                        <div
                            class="absolute right-[-5%] bottom-[10%] z-20 flex h-16 w-16 items-center justify-center rounded-full bg-foreground shadow-[8px_8px_0_var(--color-accent)]"
                        >
                            <div
                                class="h-6 w-6 animate-spin rounded-full border-[3px] border-background border-t-accent"
                            ></div>
                        </div>

                        <div
                            class="relative z-10 h-3/4 w-3/4 overflow-hidden rounded-full border-[6px] border-foreground bg-accent shadow-[24px_24px_0_var(--color-foreground)]"
                        >
                            <div
                                class="grid-lines absolute inset-0 mix-blend-overlay"
                            ></div>

                            <div
                                class="absolute top-1/2 left-1/2 h-[140%] w-[140%] -translate-x-1/2 -translate-y-1/2 rounded-[50%] border-[2px] border-foreground opacity-50"
                            ></div>
                            <div
                                class="absolute top-1/2 left-1/2 h-[180%] w-[180%] -translate-x-1/2 -translate-y-1/2 rounded-[50%] border-[2px] border-foreground opacity-50"
                            ></div>
                            <div
                                class="absolute top-1/2 h-[4px] w-full -translate-y-1/2 bg-foreground opacity-60"
                            ></div>
                            <div
                                class="absolute left-1/2 h-full w-[4px] -translate-x-1/2 bg-foreground opacity-60"
                            ></div>

                            <div
                                class="absolute top-[15%] left-[10%] h-[30%] w-[40%] bg-foreground"
                            >
                                <div
                                    class="h-full w-full bg-[repeating-linear-gradient(45deg,transparent,transparent_4px,var(--color-background)_4px,var(--color-background)_8px)] opacity-30"
                                ></div>
                            </div>
                            <div
                                class="absolute top-[55%] left-[20%] h-[20%] w-[25%] bg-foreground"
                            ></div>
                            <div
                                class="absolute top-[25%] right-[10%] h-[45%] w-[35%] bg-foreground"
                                style="
                                    clip-path: polygon(
                                        0% 10%,
                                        100% 0%,
                                        90% 100%,
                                        10% 90%
                                    );
                                "
                            ></div>

                            <div
                                class="absolute top-[25%] left-[30%] flex items-center justify-center"
                            >
                                <div
                                    class="absolute z-10 h-4 w-4 border-[2px] border-foreground bg-background"
                                ></div>
                                <div
                                    class="absolute h-10 w-10 animate-ping border-[2px] border-background"
                                ></div>
                            </div>

                            <div
                                class="absolute top-[65%] left-[30%] flex items-center justify-center"
                            >
                                <div
                                    class="absolute h-4 w-4 rounded-full border-[2px] border-foreground bg-background"
                                ></div>
                            </div>

                            <div
                                class="absolute top-[45%] right-[25%] flex items-center justify-center"
                            >
                                <div
                                    class="absolute z-10 h-5 w-5 border-[3px] border-background bg-accent"
                                ></div>
                                <div
                                    class="absolute h-12 w-12 animate-ping rounded-full bg-background/30"
                                ></div>
                            </div>
                        </div>

                        <div
                            class="absolute bottom-4 -left-4 z-30 border border-background/20 bg-foreground p-5 font-mono text-[10px] leading-tight text-background shadow-[8px_8px_0_var(--color-accent)] backdrop-blur-sm md:-left-12"
                        >
                            COORD: -6.17 / 106.82<br />
                            NODES: 1,402,110<br />
                            STATUS: SYNCED_LIVE<br />
                            BUFFER: REAL-TIME
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <section
            class="relative z-10 mx-auto max-w-7xl border-t border-foreground/10 px-6 py-20 sm:px-8 md:py-32 md:pl-28"
        >
            <div
                class="mb-16 flex flex-col items-start justify-between gap-8 md:mb-24 md:flex-row md:items-end"
            >
                <div class="max-w-xl">
                    <h2
                        class="font-display mb-4 text-4xl font-black tracking-tighter uppercase italic sm:text-5xl md:mb-6 md:text-6xl"
                    >
                        Core_Systems
                    </h2>
                    <p
                        class="text-xs font-bold tracking-widest text-primary/60 uppercase sm:text-sm"
                    >
                        Aggregating geospatial data through collective user
                        intelligence.
                    </p>
                </div>
                <div
                    class="font-display text-8xl leading-none font-black text-primary/5 select-none sm:text-[10rem] md:text-[12rem]"
                >
                    001
                </div>
            </div>

            <div
                class="grid grid-cols-1 gap-8 sm:gap-10 md:grid-cols-3 md:gap-12"
            >
                <div
                    v-for="(feature, i) in features"
                    :key="feature.title"
                    class="group relative flex min-h-[320px] flex-col justify-end border border-foreground bg-card p-8 shadow-[6px_6px_0_var(--color-foreground)] transition-all duration-300 hover:translate-x-2 hover:translate-y-2 hover:shadow-none sm:min-h-[400px] sm:p-10 sm:shadow-[8px_8px_0_var(--color-foreground)] md:h-[450px] md:p-12"
                >
                    <div
                        class="absolute top-8 left-8 flex h-16 w-16 items-center justify-center border border-foreground bg-accent transition-transform group-hover:rotate-12 sm:top-10 sm:left-10 md:top-12 md:left-12"
                    >
                        <component
                            :is="feature.icon"
                            class="h-8 w-8 text-primary"
                        />
                    </div>
                    <h3
                        class="font-display mb-4 text-2xl font-black tracking-tight uppercase md:mb-6"
                    >
                        {{ feature.title }}
                    </h3>
                    <p
                        class="text-xs leading-relaxed font-bold tracking-wider text-primary/60 uppercase"
                    >
                        {{ feature.desc }}
                    </p>
                </div>
            </div>
        </section>

        <footer
            class="relative z-10 border-t border-foreground/10 bg-foreground py-16 text-background md:py-20 md:pl-28"
        >
            <div
                class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-12 px-6 sm:px-8 md:flex-row md:items-end"
            >
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center bg-accent"
                        >
                            <Sparkles class="h-5 w-5 text-primary" />
                        </div>
                        <span
                            class="font-display text-2xl font-black tracking-tight uppercase"
                            >THEREVIEWER_SYS</span
                        >
                    </div>
                    <p
                        class="max-w-xs text-[10px] leading-relaxed font-bold tracking-[0.2em] text-background/50 uppercase"
                    >
                        CROWDSOURCED RECOMMENDATIONS SINCE THE VOID. EST. 2026 /
                        ALL ZONES NOMINAL.
                    </p>
                </div>

                <div class="space-y-4 text-left md:text-right">
                    <h2
                        class="font-display text-3xl font-black tracking-tighter uppercase sm:text-4xl"
                    >
                        Spatial.Digital
                    </h2>
                    <div
                        class="flex flex-wrap justify-start gap-6 text-[10px] font-bold tracking-[0.3em] text-background/40 uppercase sm:gap-10 md:justify-end"
                    >
                        <a href="#" class="transition-colors hover:text-accent"
                            >Map</a
                        >
                        <a href="#" class="transition-colors hover:text-accent"
                            >Guidelines</a
                        >
                        <a href="#" class="transition-colors hover:text-accent"
                            >Contribute</a
                        >
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
