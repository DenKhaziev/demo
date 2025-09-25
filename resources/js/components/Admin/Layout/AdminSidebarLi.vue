<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    status: String // Необязательный
});

const page = usePage();

const isActive = () => {
    const url = new URL(window.location.href);
    const currentStatus = url.searchParams.get('status');
    const currentPath = url.pathname;

    const targetUrl = new URL(props.href, window.location.origin);
    const targetPath = targetUrl.pathname;
    const targetStatus = props.status;

    // Если статус передан → сверяем и путь и статус
    if (targetStatus !== undefined) {
        return currentPath === targetPath && currentStatus === targetStatus;
    }

    // Если статус не передан → сверяем просто путь
    return currentPath === targetPath;
};
</script>

<template>
    <li>
        <Link
            :href="href"
            preserve-scroll
            :class="[
                'inline-block px-2 py-1 rounded-lg transition-transform duration-200 ease-in-out shadow-xl w-full font-semibold mt-2 text-center',
                isActive() ? 'bg-blue-600 text-white' : 'bg-gradient-to-r from-gray-500 to-gray-600 text-white hover:-translate-y-1 hover:scale-105'
            ]"
        >
            <slot/>
        </Link>
    </li>
</template>
