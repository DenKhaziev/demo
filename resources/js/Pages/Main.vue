<script setup>
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import Navigation from '@/components/MainPage/Navigation.vue'
import Slider from '@/components/MainPage/Slider.vue'
import CertificationSteps from '@/components/MainPage/CertificationSteps.vue'
import Prices from '@/components/MainPage/Prices.vue'
import Advantages from '@/components/MainPage/Advantages.vue'
import Documents from '@/components/MainPage/Documents.vue'
import DocumentsDownload from '@/components/MainPage/DocumentsDownload.vue'

const page = usePage()
const props = defineProps({
    prices: Object,
})
</script>

<template>
    <Head>
        <meta property="og:title" content="Семейное образование ОАНО СОШ 'ПЕНАТЫ'">
        <meta name="title" content="Семейное образование ОАНО СОШ 'ПЕНАТЫ'">
        <meta property="og:description" content="Онлайн-платформа для сопровождения промежуточной аттестации, ГИА, сертификации и взаимодействия между школой, родителями и учениками.">
        <meta name="description" content="Онлайн-платформа для сопровождения промежуточной аттестации, ГИА, сертификации и взаимодействия между школой, родителями и учениками.">
        <meta property="og:image" content="/meta.png">
        <meta name="image" content="/meta.png">
        <meta property="og:url" content="https://new.so-penaty.ru">
        <meta name="url" content="https://new.so-penaty.ru">
        <meta property="og:type" content="website">
        <meta name="type" content="website">
    </Head>
    <MainLayout>
        <Navigation class="sticky top-0 z-50 bg-white shadow" />
        <Slider />
        <CertificationSteps />
        <Prices :prices="prices" />
        <Advantages />
        <Documents />
        <DocumentsDownload />
    </MainLayout>
</template>
