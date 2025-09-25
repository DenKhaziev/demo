<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import Header from "@/components/Main/Header.vue";

const props = defineProps({
    prices: Object,
})
// Данные — временно хардкодом внутри компонента
const prices = ref([
    { name: 'Сопровождение промежуточной аттестации (ПА)', price: props.prices.PA_attestation, priceprop: '' },
    { name: 'Сопровождение государственной итоговой аттестации (ГИА)', price: props.prices.GIA, priceprop: '' },
])

const page = usePage()
const isLoggedIn = computed(() => !!page.props.auth?.user)


</script>
<template>
    <section id="prices" class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <Header>Стоимость услуг</Header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    v-for="(item, index) in prices"
                    :key="index"
                    class="bg-white rounded-xl shadow-md p-6 text-center"
                >
                    <h3 class="text-lg font-semibold mb-3 text-gray-800">{{ item.name }}</h3>
                    <div class="text-red-700 text-2xl font-bold mb-2">
                        {{ item.price }} <span class="text-base font-medium">руб.</span>
                    </div>
                    <p class="text-gray-700 mb-4">{{ item.priceprop ?? NULL }}</p>

                    <div v-if="!isLoggedIn">
                        <a href="/register" class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none">
                            Зарегистрироваться
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


