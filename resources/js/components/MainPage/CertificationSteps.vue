<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Header from "@/components/Main/Header.vue";


const page = usePage()
const isLoggedIn = computed(() => !!page.props.auth?.user)
</script>

<template>
    <section id="certification" class="bg-white py-12">
        <div class="container mx-auto px-4">
            <Header>Как пройти аттестацию</Header>

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-10">
                <a href="/register" class="w-full sm:w-[45%] md:w-[30%] lg:w-[22%] text-center group transition-transform hover:scale-[1.04]">
                    <div class="mb-4 flex justify-center">
                        <div class="w-52 h-52 rounded-full bg-white shadow-md p-3 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                            <img :src="'/storage/pics/registration.png'" alt="Регистрация"
                                 class="w-4/5 h-4/5 object-contain" />
                        </div>
                    </div>
                    <div class="text-red-800 font-semibold text-sm">1. Зарегистрироваться</div>
                </a>

                <a href="/register" class="w-full sm:w-[45%] md:w-[30%] lg:w-[22%] text-center group transition-transform hover:scale-[1.04]">
                    <div class="mb-4 flex justify-center">
                        <div class="w-52 h-52 rounded-full bg-white shadow-md p-3 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                            <img :src="'/storage/pics/payment.png'" alt="Оплата"
                                 class="w-4/5 h-4/5 object-contain" />
                        </div>
                    </div>
                    <div class="text-red-800 font-semibold text-sm">2. Оплатить</div>
                </a>

                <div class="w-full sm:w-[45%] md:w-[30%] lg:w-[22%] text-center group transition-transform hover:scale-[1.04]">
                    <a href="/register" class="w-full sm:w-[45%] md:w-[30%] lg:w-[22%] text-center group transition-transform hover:scale-[1.04]">
                        <div class="mb-4 flex justify-center">
                            <div class="w-52 h-52 rounded-full bg-white shadow-md p-3 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                                <img :src="'/storage/pics/add_docs.png'" alt="Оплата"
                                     class="w-4/5 h-4/5 object-contain" />
                            </div>
                        </div>
                        <div class="text-red-800 font-semibold text-sm">3. Прикрепить документы</div>
                    </a>
                    <ul class="mt-4 text-sm text-gray-700 space-y-3">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="block text-left leading-snug">заявление о зачислении</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="block text-left leading-snug">свидетельство о рождении</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="block text-left leading-snug">согласие на обработку персональных данных</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="block text-left leading-snug">аттестат об основном общем образовании (для 10–11 классов)</span>
                        </li>
                    </ul>

                </div>
            </div>
            <div v-if="!isLoggedIn" class="text-center mt-10">
                <a href="/register"  class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none">
                    Зарегистрироваться
                </a>
            </div>


        </div>
    </section>
</template>



<style scoped>
.step-block {
    max-width: 250px;
    transition: transform 0.3s ease;
}
.step-block:hover {
    transform: translateY(-5px);
}
</style>
