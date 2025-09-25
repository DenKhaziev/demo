<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/components/BreezeAuth/ApplicationLogo.vue';
import Dropdown from '@/components/BreezeAuth/Dropdown.vue';
import DropdownLink from '@/components/BreezeAuth/DropdownLink.vue';
import NavLink from '@/components/BreezeAuth/NavLink.vue';
import ResponsiveNavLink from '@/components/BreezeAuth/ResponsiveNavLink.vue';
import NotificationModal from '@/components/Modal/NotificationModal.vue';
import { Link } from '@inertiajs/vue3';
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="relative min-h-screen bg-gray-100 pb-10">
        <div class="absolute inset-0 bg-[url('/storage/essentials/background_img_1.webp')] bg-center bg-contain opacity-10 pointer-events-none"></div>
        <div class="relative z-10">
            <nav
                class="border-b border-gray-100 bg-white"
            >
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-end">
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <UserOutlineButton
                                                type="button"
                                                class="inline-flex items-center bg-white px-3 py-2 leading-4 transition duration-150 ease-in-out focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

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
<!--                                        <DropdownLink-->
<!--                                            :href="route('parent.index')"-->
<!--                                        >-->
<!--                                            Перейти в ЛК-->
<!--                                        </DropdownLink>-->
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Профиль
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Выйти
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
<!--                    <div class="space-y-1 pb-3 pt-2">-->
<!--                        <ResponsiveNavLink-->
<!--                            :href="route('admin.main')"-->
<!--                            :active="route().current('admin.main')"-->
<!--                        >-->
<!--                            Dashboard-->
<!--                        </ResponsiveNavLink>-->
<!--                    </div>-->

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Профиль
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Выйти
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
    <NotificationModal id="notification-modal" />

</template>
