<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import StudentLayout from "@/Layouts/StudentLayout.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import UniversalH2Display from "@/components/ui/UniversalH2Display.vue";
import RemainingTestsRow from "@/components/Test/RemainingTestsRow.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import RemainingTestsHead from "@/components/Test/RemainingTestsHead.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import PassedTestsRow from "@/components/Test/PassedTestsRow.vue";
import PassedTestsHead from "@/components/Test/PassedTestsHead.vue";
import Header from "@/components/Main/Header.vue";

const props = defineProps({
    child: Object,
    passedTests: Array,
    remainingTests: Array,
})

</script>

<template>
    <MainLayout>
        <StudentLayout>
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
                <Header class="text-3xl sm:text-4xl md:text-5xl text-center mb-6">
                    Личный кабинет ученика
                </Header>

                <Card v-if="child.is_payment" class="space-y-8">
                    <!-- Информация об ученике -->
                    <CardWrapper class="flex flex-col md:flex-row md:justify-around gap-4">
                        <UniversalH2Display label="Ученик" :value="child.name" />
                        <UniversalH2Display label="Класс" :value="child.grade_id" />
                    </CardWrapper>
                    <CardWrapper v-if="child.grade_id === 10" class="mb-3  text-center font-semibold text-[#800020]">
                        <p>
                            Уважаемый ученик 10го класса - вам необходимо отправить выполненный Индивидуальный проект на почту so@penaty.ru перед тем как ваш представиттель (родитель)
                            запросит справку о прохождении промежуточной аттестации!
                        </p>
                        <br>
                        <p>
                            И после того как проект будет принят мы уведомим вашего представителя (родителя) об этом!
                        </p>
                    </CardWrapper>

                    <!-- Пройденные тесты -->
                    <div v-if="passedTests.length > 0">
                        <CardSubheader>Пройденные тесты</CardSubheader>
                        <div class="overflow-x-auto">
                            <CardTable>
                                <PassedTestsHead :isAdmin="false" />
                                <PassedTestsRow :passedTests="passedTests" :isAdmin="false" />
                            </CardTable>
                        </div>
                    </div>

                    <!-- Оставшиеся тесты -->
                    <CardSubheader>Оставшиеся тесты</CardSubheader>
                    <div class="overflow-x-auto">
                        <CardTable>
                            <RemainingTestsHead />
                            <RemainingTestsRow :remainingTests="remainingTests" />
                        </CardTable>
                    </div>
                </Card>

                <!-- Если нет оплаты -->
                <Card v-else class="text-center py-10">
                    <p class="text-xl sm:text-2xl text-gray-700 leading-relaxed px-4">
                        Доступ в личный кабинет ученика для начала прохождения промежуточной аттестации
                        откроется после оплаты в личном кабинете представителя.
                    </p>
                </Card>
            </div>
        </StudentLayout>
    </MainLayout>
</template>


<style scoped>

</style>
