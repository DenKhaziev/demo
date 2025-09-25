<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import Header from "@/components/Main/Header.vue";

const advantages = ref([
    { icon: '/storage/pics/img.png', text: 'Прохождение аттестации дистанционно в удобное время.' },
    { icon: '/storage/pics/img2.png', text: 'Аттестация экстерном, в том числе за два и более классов.' },
    { icon: '/storage/pics/img3.png', text: 'Прием обучающихся из всех регионов России и зарубежья.' },
    { icon: '/storage/pics/img4.png', text: 'Самостоятельный выбор времени прохождения аттестации.' },
    { icon: '/storage/pics/img5.png', text: 'Ведение личного дела с оценками и переводом в следующий класс.' },
    { icon: '/storage/pics/img6.png', text: 'Прикрепление к ОАНО СОШ «Пенаты» для прохождения ПА.' },
    { icon: '/storage/pics/img7.png', text: 'Организация прохождения государственной итоговой аттестации (ГИА).' },
    { icon: '/storage/pics/img8.png', text: 'Внесение в АИС «Контингент».' },
    { icon: '/storage/pics/img9.png', text: 'Выдача аттестата (9 класс), в том числе с отличием.' },
    { icon: '/storage/pics/img10.png', text: 'Выдача аттестата (11 класс), в том числе с отличием.' },
    { icon: '/storage/pics/img11.png', text: 'Возможность получения медали «За особые успехи в учении».' },
    { icon: '/storage/pics/img12.png', text: 'Предоставление вопросов и заданий для подготовки к тестированию.' },
    { icon: '/storage/pics/img13.png', text: 'Предоставление демо-версий тестов ПА.' }
])

const page = usePage()
const isLoggedIn = computed(() => !!page.props.auth?.user)
</script>
<template>
    <section id="advantages" class="bg-white py-12">
        <div class="container mx-auto px-4">
            <Header>Наши преимущества</Header>

            <div class="flex flex-wrap justify-center gap-6  ">
                <div
                    v-for="(item, index) in advantages"
                    :key="index"
                    class="w-full sm:w-[45%] md:w-[30%] lg:w-[18%] text-center group transition-all duration-300 transform hover:scale-105"
                >
                    <div
                        class="mb-4 mx-auto w-28 h-28 flex items-center justify-center rounded-full bg-white shadow-md"
                    >
                        <img
                            v-if="item.icon.endsWith('.png') || item.icon.endsWith('.svg')"
                            :src="item.icon"
                            alt=""
                            class="w-20 h-20 object-contain"
                        />
                        <div
                            v-else
                            :class="`sprites ${item.icon}`"
                            class="w-16 h-16"
                        ></div>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed font-semibold max-w-[60%] mx-auto">
                        {{ item.text }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap justify-center mt-10">
                <a
                    href="/family_edu"
                    class="w-full sm:w-[45%] md:w-[30%] lg:w-[22%] text-center block"
                >
                    <div class="mb-4 flex justify-center">
                        <img
                             alt="logo" class="h-24"
                            :src="'/storage/pics/img14.png'"
                        />
                    </div>
                    <p class="text-gray-700 text-sm font-medium">Перейти на семейное образование — просто!</p>
                </a>
            </div>

            <div v-if="!isLoggedIn" class="text-center mt-12">
                <a href="/register"  class=" px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none">
                    Зарегистрироваться
                </a>
            </div>
        </div>
    </section>
</template>



<style scoped>
.sprites {
    background-repeat: no-repeat;
    background-size: contain;
}
</style>
