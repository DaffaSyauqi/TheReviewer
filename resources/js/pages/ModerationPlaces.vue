<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/places/DataTable.vue';
import { columns } from '@/components/places/moderation-columns';
import type { Place } from '@/types';

defineProps<{
    places: Place[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Moderation Places',
            },
        ],
    },
});
</script>

<template>
    <Head title="Moderation Places" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
    >
        <Heading
            variant="small"
            title="Moderation Places"
            description="Moderate the places, approve or reject"
        />

        <div class="w-full">
            <DataTable :columns="columns" :data="places" />
        </div>
    </div>
</template>
