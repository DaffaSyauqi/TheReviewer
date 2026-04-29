<script setup lang="ts">
import { Map as MapIcon, Users, ShieldCheck } from 'lucide-vue-next';

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
        title: 'Secure_Moderation',
        icon: ShieldCheck,
        desc: 'Submissions reviewed by admin before being published, ensuring data accuracy and spam.',
    },
];
</script>

<template>
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
        </div>

        <div class="grid grid-cols-1 gap-8 sm:gap-10 md:grid-cols-3 md:gap-12">
            <div
                v-for="(feature, i) in features"
                :key="feature.title"
                class="group relative flex min-h-80 flex-col justify-end border border-foreground bg-card p-8 shadow-[6px_6px_0_var(--color-foreground)] transition-all duration-300 hover:translate-x-2 hover:translate-y-2 hover:shadow-none sm:min-h-100 sm:p-10 sm:shadow-[8px_8px_0_var(--color-foreground)] md:h-112.5 md:p-12"
            >
                <div
                    class="absolute top-8 left-8 flex h-16 w-16 items-center justify-center border border-foreground bg-accent transition-transform sm:top-10 sm:left-10 md:top-12 md:left-12"
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
</template>
