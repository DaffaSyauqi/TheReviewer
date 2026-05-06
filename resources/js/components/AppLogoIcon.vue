<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { MapPinned } from 'lucide-vue-next';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <MapPinned class="h-6 w-6 text-accent" />
</template>
