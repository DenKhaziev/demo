<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import SwiperCore from "swiper";
import Header from "@/components/Main/Header.vue";

SwiperCore.use([Pagination, Navigation, Autoplay])

const imageDocs = ref([
    {
        image: '/storage/slider/accred.jpg',
        alt: 'О бессрочной аккредитации'
    },
    {
        image: '/storage/slider/app1.jpg',
        alt: 'Приложение номер 1.1'
    },
    {
        image: '/storage/slider/app2.jpg',
        alt: 'Приложение номер 1'
    },
    {
        image: '/storage/slider/certif_gov_law.jpg',
        alt: 'Свидетельство о государственной регистрации права'
    },
    {
        image: '/storage/slider/certificate_gov_accrd.jpg',
        alt: 'Свидетельство о государственной аккредитации'
    },
    {
        image: '/storage/slider/licence.jpg',
        alt: 'Лицензия'
    }
])


</script>
<template>
    <section id="documents" class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <Header>Документы</Header>

            <Swiper
                class="document-slider mb-8"
                :slides-per-view="1"
                :space-between="0"
                :breakpoints="{
                  500: { slidesPerView: 2 },
                  768: { slidesPerView: 3 },
                  1024: { slidesPerView: 6 }
                }"
                :loop="true"
                :speed="1000"
                :pagination="{ clickable: true }"
                :autoplay="{ delay: 4000 }"
                :navigation="true"
                modules="[Pagination, Navigation, Autoplay]"
            >
                <SwiperSlide v-for="(doc, index) in imageDocs" :key="index">
                    <div class="bg-white rounded-xl shadow-lg p-4 mx-2 h-full flex items-center justify-center mb-10 w-fit">
                        <a :href="doc.image" data-fancybox="gallery" class="block">
                            <img
                                :src="doc.image"
                                :alt="doc.alt"
                                class="w-full h-[220px] object-contain rounded-md transition-transform duration-300 hover:scale-105"
                            />
                        </a>
                    </div>
                </SwiperSlide>
            </Swiper>

            <div class="mt-8 space-y-2">
                <p v-for="(file, index) in fileDocs" :key="index">
                    <a :href="file.file" download class="text-blue-600 hover:underline">{{ file.filename }}</a>
                </p>
            </div>
        </div>
    </section>
</template>

<style scoped>
.document-slider img {
    max-height: 250px;
}
</style>
