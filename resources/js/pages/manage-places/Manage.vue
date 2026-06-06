<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/places/DataTable.vue';
import { columns } from '@/components/places/columns';

type Props = {
    places: Array<{
        id: number;
        name: string;
        description: string;
        category: string;
        address: string;
        city: string;
        province?: string;
        country?: string;
        latitude?: number | string;
        longitude?: number | string;
        status: 'pending' | 'approved' | 'rejected';
        created_at: string;
        images?: Array<{ id: number; url: string }>;
    }>;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Manage Places',
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Manage Places" />

    <h1 class="sr-only">Manage Information</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Manage Information"
            description="Manage the places you have submitted, including deleting them."
        />

        <div class="w-full max-w-6xl">
            <DataTable :columns="columns" :data="places" />
        </div>
    </div>
</template>
