<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/places/DataTable.vue';
import { columns } from '@/components/places/columns';
import type { ManagePlace } from '@/types';

defineProps<{
    places: ManagePlace[];
}>();

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
