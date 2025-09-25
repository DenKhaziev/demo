<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/components/BreezeAuth/Dropdown.vue';
import DropdownLink from '@/components/BreezeAuth/DropdownLink.vue';
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";
import NotificationModal from '@/components/Modal/NotificationModal.vue';

// const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="relative min-h-screen bg-gray-100 pb-10">
        <div class="absolute inset-0 bg-[url('/storage/essentials/background_img_1.webp')] bg-center bg-contain opacity-10 pointer-events-none"></div>
        <div class="relative z-10">
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-end">
                        <div class="flex items-center">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <UserOutlineButton
                                                type="button"
                                                class="inline-flex items-center bg-white px-3 py-2 leading-4 transition duration-150 ease-in-out focus:outline-none"
                                            >
                                                {{ $page.props.auth.user?.name }}
                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </UserOutlineButton>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('student.logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Выйти
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                <slot />
            </main>
        </div>
    </div>

    <NotificationModal id="notification-modal" />
</template>
