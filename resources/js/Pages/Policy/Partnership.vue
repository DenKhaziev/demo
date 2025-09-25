<script setup>

import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import Navigation from "@/components/MainPage/Navigation.vue";
import Header from "@/components/Main/Header.vue";
</script>

<template>
    <MainLayout>
        <Navigation/>
        <div class="w-full mx-auto px-4 mt-10 max-w-screen-sm md:max-w-screen-md lg:max-w-3xl">
            <Header class="text-center text-2xl sm:text-3xl md:text-4xl mb-6">
                Сотрудничество
            </Header>

            <CardSubheader class="text-center text-base sm:text-lg md:text-xl">
                Приглашаем к сотрудничеству в сфере
            </CardSubheader>
            <CardSubheader class="text-center text-base sm:text-lg md:text-xl mb-4">
                семейного и заочного образования:
            </CardSubheader>

            <ul class="text-center font-semibold mb-8 space-y-2 text-sm sm:text-base">
                <li>- Частные школы (без аккредитации)</li>
                <li>- Образовательные и репетиторские центры</li>
                <li>- Семейные классы</li>
                <li>- Сообщества родителей хоумскуллеров</li>
            </ul>

            <CardSubheader class="text-center text-2xl sm:text-3xl md:text-4xl mb-6">
                Чтобы стать партнером
            </CardSubheader>

            <p class="text-center font-semibold text-sm sm:text-base">
                Позвоните нам +7 (936) 555-55-09
            </p>
            <p class="text-center font-semibold text-sm sm:text-base mb-8">
                Или напишите на почту <span class="text-red-700">so@penaty.ru</span>
            </p>

            <CardSubheader class="text-center text-2xl sm:text-3xl md:text-4xl mb-6">
                Наша команда поможет вашим ученикам успешно сдать аттестации и получить аттестат!
            </CardSubheader>
            
        </div>

    </MainLayout>
</template>

<style scoped>

</style>
