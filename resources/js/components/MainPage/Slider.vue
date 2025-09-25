<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

// Пример картинок
const slides = [
    {
        url: '/storage/slider/classwork.png',
        label: 'Сопровождение \n государственной \n итоговой аттестации \n (ГИА)',
    },
    {
        url: '/storage/slider/homework.png',
        label: 'Сопровождение \n промежуточной \n аттестации \n (ПА)',
    }
]
</script>
<template>
    <div class="w-screen overflow-hidden">
        <Swiper
            :modules="[Autoplay, Pagination, Navigation]"
            :slides-per-view="1"
            :loop="true"
            :autoplay="{ delay: 4000, disableOnInteraction: false }"
            :speed="1000"
            class="w-3/4 h-[400px] md:h-[600px] lg:h-[1000px] "
        >
            <!-- Слайды -->
            <SwiperSlide v-for="(slide, index) in slides" :key="index">
                <div
                    class="relative w-full h-full bg-cover bg-center"
                    :style="{ backgroundImage: `url(${slide.url})` }"
                >
                    <div
                        v-if="slide.label"
                        class="absolute top-2 left-2 md:top-6 md:left-6 lg:top-10 lg:left-10
                             backdrop-blur-md bg-black/40 text-white
                             px-2 py-1 md:px-4 md:py-3 lg:px-8 lg:py-6
                             text-xs md:text-base lg:text-2xl font-semibold leading-snug
                             rounded-md md:rounded-xl lg:rounded-2xl shadow-lg whitespace-pre-line
                             min-w-[140px] min-h-[50px] md:min-w-[220px] md:min-h-[80px] lg:min-w-[300px] lg:min-h-[120px]
                             flex items-center justify-center text-center"
                    >
                        {{ slide.label }}
                    </div>
                </div>
            </SwiperSlide>
        </Swiper>
    </div>
</template>



<style scoped>
/* Если хочешь сделать стрелки поверх слайдов: */
.swiper-button-prev,
.swiper-button-next {
    color: white;
}
.swiper-pagination-bullet-active {
    background-color: white;
}
</style>
