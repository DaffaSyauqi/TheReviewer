<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Manage Places',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Manage Places" />

    <h1 class="sr-only">Manage Places</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Manage Places"
            description="Manage the places, edit or delete them"
        />
    </div>
</template>
