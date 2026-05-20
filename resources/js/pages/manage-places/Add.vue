<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
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

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
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

        <Form class="space-y-6" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="placeName">Place Name</Label>
                <Input
                    id="placeName"
                    class="mt-1 block w-full"
                    name="placeName"
                    required
                    placeholder="Place name"
                />
                <InputError class="mt-2" :message="errors.placeName" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Select>
                    <SelectTrigger class="mt-1 w-full">
                        <SelectValue placeholder="Select a category" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="restaurant"> Restaurant </SelectItem>
                        <SelectItem value="hotel"> Hotel </SelectItem>
                        <SelectItem value="tourism"> Tourism </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="grid gap-2">
                <Label for="placeDescription">Description</Label>
                <Textarea
                    id="description"
                    class="mt-1 block w-full"
                    name="description"
                    required
                    placeholder="Description"
                />
                <InputError class="mt-2" :message="errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="adress">Full Address</Label>
                <Input
                    id="adress"
                    class="mt-1 block w-full"
                    name="adress"
                    required
                    placeholder="Full address"
                />
                <InputError class="mt-2" :message="errors.adress" />
            </div>

            <div class="grid gap-2">
                <Label for="city">City</Label>
                <Input
                    id="city"
                    class="mt-1 block w-full"
                    name="city"
                    required
                    placeholder="City"
                />
                <InputError class="mt-2" :message="errors.city" />
            </div>

            <div class="grid gap-2">
                <Label for="province"
                    >Province
                    <span class="text-xs text-muted-foreground"
                        >(Optional)</span
                    ></Label
                >
                <Input
                    id="province"
                    class="mt-1 block w-full"
                    name="province"
                    required
                    placeholder="Province"
                />
                <InputError class="mt-2" :message="errors.province" />
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
                    name="country"
                    required
                    placeholder="Country"
                />
                <InputError class="mt-2" :message="errors.country" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing" data-test="update-profile-button"
                    >Add</Button
                >
            </div>
        </Form>
    </div>
</template>
