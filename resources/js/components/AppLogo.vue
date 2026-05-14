<script setup lang="ts">
import { MapPinned } from 'lucide-vue-next';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex h-8 w-8 items-center justify-center bg-accent">
        <MapPinned class="h-5 w-5 text-primary" />
    </div>
    <div class="text-md ml-1 grid flex-1 text-left">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >TheReviewer</span
        >
    </div>
</template>
