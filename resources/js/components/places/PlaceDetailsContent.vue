<script setup lang="ts">
import { computed } from 'vue';
import * as LucideIcons from 'lucide-vue-next';
import { ScrollArea } from '@/components/ui/scroll-area';
import type { Place } from '@/types';

interface Props {
    place?: Place;
    placeId: number;
    placeName: string;
}

const props = defineProps<Props>();

const getStatusIcon = (status: string) => {
    if (status === 'approved') return LucideIcons.CheckCircle2;
    if (status === 'pending') return LucideIcons.Clock;
    return LucideIcons.AlertCircle;
};

const getStatusColor = (status: string) => {
    if (status === 'approved') return 'text-green-600';
    if (status === 'pending') return 'text-yellow-600';
    return 'text-red-600';
};

const getStatusBadgeClass = (status: string) => {
    if (status === 'approved') return 'bg-green-100 text-green-800';
    if (status === 'pending') return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
};

const formatValue = (value: any) => value || '-';

const statusIcon = computed(() =>
    getStatusIcon(props.place?.status || 'pending'),
);
</script>

<template>
    <ScrollArea class="h-100 pr-6">
        <div class="space-y-6 pt-2 pb-2">
            <!-- General Information -->
            <section>
                <div class="mb-4 flex items-center gap-2 border-b pb-3">
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded bg-blue-100"
                    >
                        <LucideIcons.LayoutGrid class="h-4 w-4 text-blue-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-foreground">
                        General Information
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground">Name</span>
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right font-medium text-foreground md:text-left"
                            >{{ placeName }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Category</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.category?.name) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Description</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.description) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Address</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.address) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground">City</span>
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.city) }}</span
                        >
                    </div>
                </div>
            </section>

            <!-- Location Details -->
            <section>
                <div class="mb-4 flex items-center gap-2 border-b pb-3">
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded bg-red-100"
                    >
                        <LucideIcons.MapPin class="h-4 w-4 text-red-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-foreground">
                        Location Details
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Province</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.province) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Country</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.country) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Latitude</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.latitude) }}</span
                        >
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="min-w-24 text-muted-foreground"
                            >Longitude</span
                        >
                        <span class="mx-2 text-muted-foreground">:</span>
                        <span
                            class="flex-1 text-right text-foreground md:text-left"
                            >{{ formatValue(place?.longitude) }}</span
                        >
                    </div>
                </div>
            </section>

            <!-- Status Information -->
            <section>
                <div class="mb-4 flex items-center gap-2 border-b pb-3">
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded bg-orange-100"
                    >
                        <LucideIcons.Info class="h-4 w-4 text-orange-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-foreground">
                        Status Information
                    </h3>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="min-w-24 text-muted-foreground">Status</span>
                    <span class="mx-2 text-muted-foreground">:</span>
                    <div class="flex-1 text-right md:text-left">
                        <span
                            :class="`inline-block rounded px-3 py-1 text-sm font-medium ${getStatusBadgeClass(place?.status || 'pending')}`"
                        >
                            {{
                                place?.status
                                    ? place.status.charAt(0).toUpperCase() +
                                      place.status.slice(1)
                                    : 'Unknown'
                            }}
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </ScrollArea>
</template>

<style scoped>
/* Responsive adjustments */
@media (max-width: 640px) {
    :deep(.drawer-content) {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
