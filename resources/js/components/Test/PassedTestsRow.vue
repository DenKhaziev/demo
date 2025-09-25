<script setup>
import BlueButton from "@/components/ui/BlueButton.vue";
import SaveButton from "@/components/ui/SaveButton.vue";
import CancelButton from "@/components/ui/CancelButton.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import TestAnswersModal from "@/components/Modal/TestAnswersModal.vue";
import {ref} from "vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";


const props =defineProps({
    passedTests: Array,
    isAdmin: {
        type: Boolean,
        default: false
    }
})

const modalVisible = ref(false);
const selectedAnswers = ref([]);
const selectedAnswerObjects = ref([]);



const openModal = (test) => {
    selectedAnswers.value = typeof test.answers === 'string'
        ? JSON.parse(test.answers)
        : test.answers;

    selectedAnswerObjects.value = test.answerObjects ?? [];

    modalVisible.value = true;
};

const getGradeInfo = (score) => {
    switch (score) {
        case 2:
            return { label: 'неудовлетворительно', color: 'text-red-600' };
        case 3:
            return { label: 'удовлетворительно', color: 'text-yellow-500' };
        case 4:
            return { label: 'хорошо', color: 'text-green-500' };
        case 5:
            return { label: 'отлично', color: 'text-green-700' };
        default:
            return { label: 'неизвестно', color: 'text-gray-400' };
    }
};

const getTypeInfo = (type) => {
    switch (type) {
        case 'Обязательный':
            return {color: 'text-green-700'};
        case 'Дополнительный (не обязательный)':
            return {color: 'text-yellow-700'};
        default:
            return {color: 'text-gray-400'}
    }
}

const attemptsUp =(test) => {
    router.put(route("passedTest.up.attempts", test.id), {}, {
        preserveScroll: true,
        preserveState: true
    });
}

const deleteTest = (test) => {
    const form = useForm({
        passedTest: test.id
    });
        form.delete(route('passedTest.destroy', test.id));
};

const isStudent = usePage().props.auth.is_child
</script>

<template>
    <tbody>
    <tr
        v-for="passedTest in passedTests"
        :key="passedTest.id"
        class="block md:table-row w-full mb-4 md:mb-0 rounded-xl shadow-md md:shadow-none md:bg-white bg-gray-100 hover:bg-gray-100 transition-all"
        :class="{ 'cursor-pointer': isAdmin, 'cursor-default': !isAdmin }"
        @click="() => {
        if (isAdmin) {
            router.visit(route('tests.show', passedTest.test_id));
            }
        }"
    >
        <!-- Предмет -->
        <td class="block md:table-cell text-center px-4 py-3 border-0 md:border md:border-gray-300">
            <span class="md:hidden block font-semibold text-gray-500 mb-1">Предмет</span>
            <span class="text-base font-semibold text-gray-800">{{ passedTest.subject.subject }}</span>
        </td>

        <!-- Тип теста -->
        <td class="block md:table-cell text-center px-4 py-3 border-0 md:border md:border-gray-300">
            <span class="md:hidden block font-semibold text-gray-500 mb-1">Тип теста</span>
            <span :class="getTypeInfo(passedTest.type.label).color" class="text-sm font-bold">
            {{ passedTest.type.label }}
        </span>
        </td>

        <!-- Пересдачи -->
        <td class="block md:table-cell text-center px-4 py-3 border-0 md:border md:border-gray-300">
            Осталось пересдач:
            <span class="text-red-700 font-bold mr-3">{{ passedTest.attempts_left }}</span>
            <UserOutlineButton
                v-if="!isAdmin && isStudent && passedTest.attempts_left > 0"
                class="text-gray-700 border border-gray-300 hover:border-gray-400 hover:bg-gray-100 focus:ring-2 focus:ring-gray-300 hover:bg-gray-700 "
                @click="() => router.visit(route('student.test.show', passedTest.test_id))"
            >
                Пересдать
            </UserOutlineButton>
        </td>


    <!-- Только для админа -->
        <td
            v-if="isAdmin"
            class="border border-gray-300 px-4 py-2 text-center"
            @click.stop
        >
            <BlueButton class="xl:text-xs" @click="openModal(passedTest)">
                Просмотреть ответы
            </BlueButton>
        </td>

        <td class="block md:table-cell text-center px-4 py-2 border-0 md:border md:border-gray-300 font-bold">
            <span class="md:hidden block font-semibold text-gray-500 mb-1">Оценка</span>
            <span :class="getGradeInfo(passedTest.score).color">
        {{ passedTest.score }} ({{ getGradeInfo(passedTest.score).label }})
    </span>
        </td>

        <!-- Только для админа -->
        <td v-if="isAdmin" class="border border-gray-300 text-center gap-6" @click.stop>
            <CancelButton
                class="p-1"
                @click="()=>deleteTest(passedTest)"
            >
                Обнулить тест
            </CancelButton>
            <SaveButton
                v-if="passedTest.attempts_left == 0"
                @click="()=>attemptsUp(passedTest)"
                class="p-1.5 ml-5"
            >
                +2
            </SaveButton>
        </td>
    </tr>

    <!-- модалка -->
    <TestAnswersModal
        :show="modalVisible"
        :answers="selectedAnswers"
        :answerObjects="selectedAnswerObjects"
        @close="modalVisible = false"
    />
    </tbody>
</template>

<style scoped>

</style>
