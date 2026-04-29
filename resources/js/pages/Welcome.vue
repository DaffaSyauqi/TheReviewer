<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';

import Aside from '@/components/landing/Aside.vue';
import Nav from '@/components/landing/Nav.vue';
import Hero from '@/components/landing/Hero.vue';
import CoreSystem from '@/components/landing/CoreSystem.vue';
import Footer from '@/components/landing/Footer.vue';

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
</script>

<template>
    <Head title="TheReviewer" />

    <div
        class="relative min-h-screen overflow-hidden bg-background font-sans text-primary selection:bg-accent/40"
    >
        <div class="grid-lines pointer-events-none absolute inset-0 z-0" />

        <Aside />

        <Nav />

        <Hero />

        <CoreSystem />

        <Footer />
    </div>
</template>
