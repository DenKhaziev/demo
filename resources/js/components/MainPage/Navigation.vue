<script setup>
import {ref, onMounted, onBeforeUnmount, computed} from 'vue'
import {router, usePage} from "@inertiajs/vue3";
import { User } from 'lucide-vue-next'

const isOpen = ref(false)
const isDesktop = ref(false)

const page = usePage()

// смотрим если это юзер значит у него есть role и в зависимости от нее редиректим
const user = computed(() => page.props.auth?.user)
const userRole = computed(() => user.value?.role)
const dashboardLink = computed(() => {
    if (!user.value) return '/login'

    switch (userRole.value) {
        case 0:
            return '/parent'
        case 1:
            return '/admin_panel'
        default:
            return '/student' // если role не указана — считаем учеником
    }
})

const checkViewport = () => {
    isDesktop.value = window.innerWidth >= 1024
}

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

onMounted(() => {
    checkViewport()
    window.addEventListener('resize', checkViewport)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', checkViewport)
})
</script>

<template>
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-2 flex items-center justify-between flex-wrap">
            <!-- Левая часть: бургер + кнопка входа -->
            <div class="flex items-center gap-4 justify-between lg:gap-40 w-full lg:w-auto">
                <!-- Бургер -->
                <button
                    @click="isOpen = !isOpen"
                    class="relative w-8 h-8 flex items-center justify-center lg:hidden"
                    aria-label="Toggle navigation"
                >
                    <span
                      :class="[
                      'absolute w-6 h-0.5 bg-gray-700 transition-transform duration-300 ease-in-out',
                      isOpen ? 'rotate-45 translate-y-0' : '-translate-y-2'
                    ]"
                    ></span>
                    <span
                        :class="[
                      'absolute w-6 h-0.5 bg-gray-700 transition-opacity duration-300 ease-in-out',
                      isOpen ? 'opacity-0' : 'opacity-100'
                    ]"
                    ></span>
                    <span
                        :class="[
                      'absolute w-6 h-0.5 bg-gray-700 transition-transform duration-300 ease-in-out',
                      isOpen ? '-rotate-45 translate-y-0' : 'translate-y-2'
                    ]"
                    ></span>
                </button>

                <!-- Десктоп/планшет -->
                <a
                    :href="dashboardLink"
                    class="hidden sm:flex items-center border border-gray-300 rounded-xl shadow-md px-4 py-4 bg-white text-gray-700 hover:text-black transition font-semibold"
                >
                    <User class="w-5 h-5 mr-2" />
                    <span>Перейти в личный кабинет</span>
                </a>

                <!-- Мобильная иконка -->
                <a
                    :href="dashboardLink"
                    class="sm:hidden ml-4 bg-white border border-gray-300 shadow rounded-full p-2 text-gray-700 hover:text-black transition"
                    aria-label="Личный кабинет"
                >
                    <User class="w-5 h-5 text-[#800020]" />
                </a>
            </div>

            <!-- Навигация -->
            <transition name="fade-slide">
                <div
                    v-show="isOpen || isDesktop"
                    class="w-full lg:flex lg:items-center lg:w-auto mt-2 lg:mt-0"
                >
                    <ul
                        class="flex flex-col lg:flex-row lg:space-x-6 text-gray-700 font-medium
                   bg-white lg:bg-transparent rounded-xl shadow-md border border-gray-200
                   p-4 space-y-3 lg:space-y-0 transition-all duration-300 ease-in-out"
                    >
                        <li><a href="#" @click.prevent="scrollTo('advantages')" class="hover:underline">Преимущества</a></li>
                        <li><a href="#" @click.prevent="scrollTo('documents')" class="hover:underline">Документы</a></li>
                        <li><a href="#" @click.prevent="scrollTo('prices')" class="hover:underline">Стоимость услуг</a></li>
                        <li><a href="#" @click.prevent="scrollTo('certification')" class="hover:underline">Как пройти аттестацию</a></li>
                        <li><a href="/partnership" class="hover:underline">Сотрудничество</a></li>
                    </ul>
                </div>
            </transition>
        </div>
    </nav>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
