<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import AdminMenuContent from '@/components/AdminMenuContent.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { User } from '@/types';

const page = usePage();
const user = computed<User | undefined>(() => page.props.auth?.user);
</script>

<template>
    <header class="pointer-events-none absolute top-0 left-0 z-30 w-full">
        <div class="mx-auto flex h-16 w-full items-center justify-end px-6">
            <div class="pointer-events-auto flex items-center gap-2">
                <template v-if="user">
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-12 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                            >
                                <Avatar
                                    class="size-10 overflow-hidden rounded-full"
                                >
                                    <AvatarImage
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                        :alt="user.name"
                                    />
                                    <AvatarFallback
                                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ getInitials(user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <AdminMenuContent
                                v-if="user.role === 'admin'"
                                :user="user"
                            />
                            <UserMenuContent v-else :user="user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>

                <template v-else>
                    <Link href="/login">
                        <Button>Sign In</Button>
                    </Link>
                    <Link href="/register">
                        <Button variant="secondary">Sign Up</Button>
                    </Link>
                </template>
            </div>
        </div>
    </header>
</template>
