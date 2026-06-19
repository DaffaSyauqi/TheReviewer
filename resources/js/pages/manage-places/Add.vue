<script setup lang="ts">
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import * as LucideIcons from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import { cn } from '@/lib/utils';
import { useIndonesiaCities } from '@/composables/useIndonesiaCities';
import { LocationPickerDialog, UploadImagesDialog } from '@/components/dialog';
import type { CategoryInfo } from '@/types';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
    categories: CategoryInfo[];
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Add Places',
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    placeName: '',
    category: '',
    description: '',
    adress: '',
    city: '',
    province: '',
    country: '',
    latitude: 0 as number,
    longitude: 0 as number,
    images: [] as File[],
});

const locationPickerOpen = ref(false);
const hasSelectedLocation = ref(false);

function onLocationConfirm(coords: { latitude: number; longitude: number }) {
    hasSelectedLocation.value = true;
}

const locationDisplay = computed(() => {
    if (
        !hasSelectedLocation.value ||
        (form.latitude === 0 && form.longitude === 0)
    )
        return null;
    return `${form.latitude.toFixed(6)}, ${form.longitude.toFixed(6)}`;
});

const { cities, citiesByProvince, isLoading, loadError, fetchCities } =
    useIndonesiaCities();

const cityComboboxOpen = ref(false);

onMounted(() => {
    fetchCities();
});

function onCitySelect(cityName: string) {
    form.city = cityName;
    const selectedCity = cities.value.find((c) => c.name === cityName);
    if (selectedCity) {
        form.province = selectedCity.province;
    }
    cityComboboxOpen.value = false;
}

const isUploadDialogOpen = ref(false);
const uploadedImages = ref<
    Array<{ file: File; preview: string; name: string; size: string }>
>([]);

function onUploadImagesUpdate(images: Array<{ file: File; preview: string; name: string; size: string }>) {
    uploadedImages.value = images;
}

const getIcon = (iconName: string | null) => {
    if (!iconName) return null;
    return (LucideIcons as Record<string, any>)[iconName] || null;
};

const syncImagesToForm = () => {
    form.images = uploadedImages.value.map((img) => img.file);
};

const handleSubmit = () => {
    syncImagesToForm();
    form.post('/manage-places', {
        onSuccess: () => {
            uploadedImages.value = [];
        },
    });
};
</script>

<template>
    <Head title="Add Places" />

    <h1 class="sr-only">Add Places</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Add Places"
            description="Enter the details of the place you want to add"
        />

        <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid gap-2">
                <Label for="placeName">Place Name</Label>
                <Input
                    id="placeName"
                    class="mt-1 block w-full"
                    v-model="form.placeName"
                    placeholder="Place name"
                />
                <InputError class="mt-2" :message="form.errors.placeName" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Select v-model="form.category">
                    <SelectTrigger class="mt-1 w-full">
                        <SelectValue placeholder="Select a category" />
                    </SelectTrigger>
                    <SelectContent class="w-100">
                        <SelectItem
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.slug"
                        >
                            <div class="flex items-center gap-2">
                                <component
                                    :is="getIcon(category.icon)"
                                    v-if="getIcon(category.icon)"
                                    class="h-4 w-4"
                                />
                                {{ category.name }}
                            </div>
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError class="mt-2" :message="form.errors.category" />
            </div>

            <div class="grid gap-2">
                <Label for="placeDescription">Description</Label>
                <Textarea
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    placeholder="Description"
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="adress">Full Address</Label>
                <Input
                    id="adress"
                    class="mt-1 block w-full"
                    v-model="form.adress"
                    placeholder="Full address"
                />
                <InputError class="mt-2" :message="form.errors.adress" />
            </div>

            <div class="grid gap-2">
                <Label>City</Label>
                <Popover v-model:open="cityComboboxOpen">
                    <PopoverTrigger as-child>
                        <Button
                            variant="outline"
                            role="combobox"
                            :aria-expanded="cityComboboxOpen"
                            :aria-label="form.city || 'Select city'"
                            class="mt-1 w-full justify-between font-normal"
                        >
                            <span v-if="form.city" class="truncate">
                                {{ form.city }}
                            </span>
                            <span v-else class="text-muted-foreground">
                                Select city...
                            </span>
                            <LucideIcons.ChevronsUpDown
                                class="ml-2 h-4 w-4 shrink-0 opacity-50"
                            />
                        </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-100 p-0" align="start">
                        <Command>
                            <CommandInput placeholder="Search city..." />
                            <CommandList>
                                <CommandEmpty
                                    v-if="!isLoading && cities.length === 0"
                                >
                                    No city found.
                                </CommandEmpty>
                                <CommandEmpty v-if="isLoading">
                                    Loading cities...
                                </CommandEmpty>
                                <CommandGroup
                                    v-if="!isLoading"
                                    v-for="[
                                        province,
                                        provinceCities,
                                    ] in citiesByProvince"
                                    :key="province"
                                    :heading="province"
                                >
                                    <CommandItem
                                        v-for="city in provinceCities"
                                        :key="city.name"
                                        :value="city.name"
                                        @select="onCitySelect(city.name)"
                                    >
                                        <LucideIcons.Check
                                            :class="
                                                cn(
                                                    'mr-2 h-4 w-4',
                                                    form.city === city.name
                                                        ? 'opacity-100'
                                                        : 'opacity-0',
                                                )
                                            "
                                        />
                                        {{ city.name }}
                                        <span
                                            class="ml-auto text-xs text-muted-foreground"
                                        >
                                            {{ city.type }}
                                        </span>
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
                <InputError class="mt-2" :message="form.errors.city" />
            </div>

            <div class="grid gap-2">
                <Label for="province"
                    >Province
                    <span class="text-xs text-muted-foreground"
                        >(Auto-filled from city)</span
                    ></Label
                >
                <Input
                    id="province"
                    class="mt-1 block w-full"
                    v-model="form.province"
                    placeholder="Province"
                />
                <InputError class="mt-2" :message="form.errors.province" />
            </div>

            <div class="grid gap-2">
                <Label for="country"
                    >Country<span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    >
                </Label>
                <Input
                    id="country"
                    class="mt-1 block w-full"
                    v-model="form.country"
                    placeholder="Country"
                />
                <InputError class="mt-2" :message="form.errors.country" />
            </div>

            <div class="grid gap-2">
                <Label>Location</Label>
                <Button
                    type="button"
                    variant="outline"
                    @click="locationPickerOpen = true"
                    class="w-full"
                >
                    <LucideIcons.MapPin class="mr-2 h-4 w-4" />
                    <span v-if="locationDisplay" class="text-foreground">
                        {{ locationDisplay }}
                    </span>
                    <span v-else class="text-muted-foreground">
                        Select Location...
                    </span>
                </Button>
                <InputError
                    class="mt-2"
                    :message="form.errors.latitude || form.errors.longitude"
                />
            </div>

            <LocationPickerDialog
                v-model:open="locationPickerOpen"
                v-model:latitude="form.latitude"
                v-model:longitude="form.longitude"
                @confirm="onLocationConfirm"
            />

            <div class="grid gap-2">
                <Label for="images"
                    >Images<span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    >
                </Label>
                <Button
                    type="button"
                    variant="outline"
                    @click="isUploadDialogOpen = true"
                    class="w-full"
                >
                    <LucideIcons.Upload class="mr-2 h-4 w-4" />
                    Upload Images
                </Button>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing" type="submit">
                    {{ form.processing ? 'Creating...' : 'Add Place' }}
                </Button>
            </div>
        </form>

        <UploadImagesDialog
            v-model:open="isUploadDialogOpen"
            v-model:images="uploadedImages"
            @update:images="onUploadImagesUpdate"
        />
    </div>
</template>
