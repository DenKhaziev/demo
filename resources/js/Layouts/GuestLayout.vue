<script setup>
import ApplicationLogo from '@/components/BreezeAuth/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 relative z-10">
        <div class="absolute inset-0 bg-[url('/storage/essentials/background_img_1.webp')] bg-center bg-contain opacity-10 pointer-events-none"></div>
        <div class="relative z-10">
            <div>
                <Link href="/">
                    <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
                </Link>
            </div>

            <div
                class="my-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
