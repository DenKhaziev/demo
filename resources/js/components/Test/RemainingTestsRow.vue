<script setup>
import {router} from "@inertiajs/vue3";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";

const props = defineProps({
    remainingTests: Array,
    sortedTests: Function,
    isAdmin: {
        type: Boolean,
        default: false
    }
})


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

const sortedTests = (tests) => {
    return tests.slice().sort((a, b) => a.subject.subject.localeCompare(b.subject.subject));
};
</script>

<template>
    <tbody>
    <tr
        v-for="test in sortedTests(remainingTests)"
        :key="test.id"
        class="block md:table-row w-full mb-4 md:mb-0 rounded-xl md:bg-white bg-gray-100
           shadow-md md:shadow-none hover:bg-blue-100 transition-all"
        :class="{ 'cursor-pointer': isAdmin, 'cursor-default': !isAdmin }"
        @click="() => {
        if (isAdmin) {
          router.visit(route('tests.show', test.id));
        }
    }"
    >
        <!-- Предмет -->
        <td class="block md:table-cell text-left md:text-center px-4 py-2 md:border md:border-gray-300">
            <span class="md:hidden font-semibold text-gray-600">Предмет: </span>
            {{ test.subject.subject }}
        </td>

        <!-- Тип -->
        <td class="block md:table-cell text-left md:text-center px-4 py-2 md:border md:border-gray-300">
            <span class="md:hidden font-semibold text-gray-600">Тип теста: </span>
            <span :class="getTypeInfo(test.type.label).color" class="font-semibold">
            {{ test.type.label }}
        </span>
        </td>

        <!-- Время -->
        <td class="block md:table-cell text-left md:text-center px-4 py-2 md:border md:border-gray-300">
            <span class="md:hidden font-semibold text-gray-600">Время: </span>
            {{ test.allotted_time }} минут
        </td>

        <!-- Статус -->
        <td class="block md:table-cell text-center px-4 py-2 md:border md:border-gray-300">
            <template v-if="$page.props.auth?.is_child">
                <div class="flex justify-center">
                    <UserFilledButton
                        @click.stop="() => router.visit(route('student.test.show', test.id))"
                        class="mt-2 md:mt-0"
                    >
                        Начать тестирование
                    </UserFilledButton>
                </div>
            </template>
            <template v-else>
                <span class="text-gray-600 font-medium">Не пройдено</span>
            </template>
        </td>
    </tr>


    </tbody>
</template>

<style scoped>

</style>
