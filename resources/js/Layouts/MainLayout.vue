<script setup>
import {router} from "@inertiajs/vue3";

const scrollTo = (anchor) => {
    if (window.location.pathname !== '/') {
        router.visit('/', {
            preserveScroll: false,
            preserveState: true,
            onFinish: () => {
                setTimeout(() => {
                    const el = document.getElementById(anchor)
                    if (el) el.scrollIntoView({ behavior: 'smooth' })
                }, 100)
            },
        })
    } else {
        const el = document.getElementById(anchor)
        if (el) el.scrollIntoView({ behavior: 'smooth' })
    }
}
</script>
<template>
    <div class="min-h-screen flex flex-col bg-white text-gray-800">
        <!-- Header -->
        <header class="bg-[#800020] text-white shadow-md py-4">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row lg:justify-around items-center gap-4">
                    <!-- Логотип + Слоган -->
                    <div class="flex items-center gap-4 flex-wrap justify-center text-center lg:text-left">
                        <a href="/" class="block">
                            <img :src="'/storage/essentials/logo.svg'" alt="Пенаты" class="h-20 md:h-24 lg:h-28 w-auto" />
                        </a>
                        <div>
                            <p class="text-lg md:text-xl lg:text-2xl font-medium uppercase tracking-wide text-white/80">
                                Семейное образование
                            </p>
                            <p class="text-xl md:text-2xl lg:text-3xl font-bold leading-tight">Пенаты</p>
                        </div>
                    </div>

                    <!-- Контакты десктоп -->
                    <div class="hidden md:block text-sm md:text-base text-right space-y-1">
                        <p class="flex items-center gap-2 justify-start font-semibold">
                            <a href="tel:+79998887766" class="hover:underline text-white">+7 (936) 555-55-09</a>
                        </p>
                        <p class="flex items-center gap-2 justify-start font-semibold">
                            <a href="mailto:so@penaty.ru" class="hover:underline text-white">so@penaty.ru</a>
                        </p>
                        <p class="flex items-start gap-2 justify-start font-semibold">
                            <span class="text-white/90">Москва, СВАО Алтуфьевское ш., 12 Б</span>
                        </p>
                    </div>

                    <!-- Контакты мобилка -->
                    <div class="block md:hidden text-center text-sm space-y-1">
                        <p><a href="tel:+79998887766" class="text-white">+7 (936) 555-55-09</a></p>
                        <p><a href="mailto:so@penaty.ru" class="text-white">so@penaty.ru</a></p>
                        <p class="text-white/90">Москва, СВАО Алтуфьевское ш., 12 Б</p>
                    </div>

                    <!-- Картинка справа -->
                    <div class="hidden lg:block">
                        <img :src="'/storage/essentials/head_img.png'" alt="Header Image" class="h-24 lg:h-28 object-contain" />
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 py-6 border-t-[3px] border-[#800020]">
            <div class="container mx-auto px-4">
                <ul class="flex flex-col sm:flex-row gap-2 sm:gap-4 justify-center text-gray-700 font-medium mb-6 text-center text-sm">
                    <li><a href="#" @click.prevent="scrollTo('advantages')" class="hover:underline">Преимущества</a></li>
                    <li><a href="#" @click.prevent="scrollTo('documents')" class="hover:underline">Документы</a></li>
                    <li><a href="#" @click.prevent="scrollTo('prices')" class="hover:underline">Стоимость услуг</a></li>
                    <li><a href="#" @click.prevent="scrollTo('certification')" class="hover:underline">Как пройти аттестацию</a></li>
                    <li><a href="/partnership" class="hover:underline">Сотрудничество</a></li>
                </ul>

                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start gap-6 text-gray-600 text-sm">
                    <!-- Лого -->
                    <div class="text-center sm:text-left">
                        <a href="/" class="flex items-center justify-center sm:justify-start gap-2 text-xl font-bold text-[#800020]">
                            <img :src="'/storage/essentials/footer-logo.png'" alt="Пенаты" class="w-10 h-10 object-contain " />
                            Пенаты
                        </a>
                        <p class="mt-2 text-[#800020]">Семейное образование</p>
                    </div>

                    <!-- Контакты -->
                    <div class="text-center sm:text-left space-y-1">
                        <p><span class="font-bold mr-2">📞</span><a href="tel:+74951234567">+7 (936) 555-55-09</a></p>
                        <p><span class="font-bold mr-2">📧</span><a href="mailto:so@penaty.ru">so@penaty.ru</a></p>
                        <p><span class="font-bold mr-2">📍</span>г. Москва, СВАО Алтуфьевское ш., 12 Б</p>
                    </div>

                    <!-- Политика -->
                    <div class="text-center sm:text-right space-y-1">
                        <p><a href="/policy" class="hover:underline">Политика конфиденциальности</a></p>
                        <p>&copy; 2025 Все права защищены</p>
                        <p>Использование контента запрещено</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>



<style scoped>
</style>
