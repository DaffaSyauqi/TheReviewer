<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
</script>

<template>
    <header class="pointer-events-none absolute top-0 left-0 z-30 w-full">
        <div class="mx-auto flex h-16 w-full items-center justify-end px-6">
            <div class="pointer-events-auto flex items-center gap-2">
                <Link href="/login">
                    <Button>Sign In</Button>
                </Link>
                <Link href="/register">
                    <Button variant="secondary">Sign Up</Button>
                </Link>
            </div>
        </div>
    </header>
</template>
