<script setup>
import { computed, ref } from "vue";
import {router, useForm} from "@inertiajs/vue3";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";

import GradeButtons from "@/components/ui/GradeButtons.vue";
import FilteredButtons from "@/components/ui/FilteredButtons.vue";
import CardTableTestHead from "@/components/Admin/Tests/CardTableTestHead.vue";
import TestRow from "@/components/Admin/Tests/TestRow.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";

const props = defineProps({
    tests: Array,
});

const gradeId = ref(null);
const filterType = ref(null);

const showConfirmModal = ref(false);
const testToDelete = ref(null);

const changeGrade = (id) => {
    gradeId.value = id;
};

const changeFilter = (type) => {
    filterType.value = type;
};

const filteredTests = computed(() => {
    return props.tests.filter((test) => {
        const byGrade = gradeId.value ? test.grade_id === gradeId.value : true;
        const byType = filterType.value
            ? (filterType.value === 'main' && test.type.type === 'required') ||
            (filterType.value === 'additional' && test.type.type === 'demo') ||
            (filterType.value === 'optional' && test.type.type === 'optional')
            : true;
        return byGrade && byType;
    });
});

const groupedTests = computed(() => {
    const groups = {};
    filteredTests.value.forEach((test) => {
        const grade = test.grade_id;
        if (!groups[grade]) {
            groups[grade] = [];
        }
        groups[grade].push(test);
    });
    return groups;
});

const sortedTests = (tests) => {
    return tests.slice().sort((a, b) => a.subject.subject.localeCompare(b.subject.subject));
};

const deleteTest = (test) => {
    testToDelete.value = test;
    showConfirmModal.value = true;
};

const confirmDelete = () => {
    if (!testToDelete.value) return;

    const form = useForm({ test: testToDelete.value.id });

    form.delete(route('tests.destroy', testToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            testToDelete.value = null;
        }
    });
};

const cancelDelete = () => {
    showConfirmModal.value = false;
    testToDelete.value = null;
};
</script>

<template>
    <Card>
        <CardHeader>Список тестов</CardHeader>
        <FilteredButtons :filterType="filterType" :onChange="changeFilter" />
        <GradeButtons :changeGrade="changeGrade" :gradeId="gradeId" />
        <div v-for="(tests, grade) in groupedTests" :key="grade" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-gray-700">{{ grade }} класс</h2>
            <CardTable>
                <CardTableTestHead/>
                <TestRow :sortedTests="sortedTests" :tests="tests">
                    <template #test="{test}">
                        <td class="border px-4 py-2 text-center">
                            {{ test.subject.subject }}
                        </td>
                        <td class="border px-4 py-2 text-center">{{ test.type.label }}</td>
                        <td class="border px-4 py-2 text-center">{{ test.type.type === 'demo' ? 0 : test.number_of_attempts }}</td>
                        <td class="border px-4 py-2 text-center">{{ test.questions_count }}</td>
                    </template>
                    <template #actions="{test}">
                        <td class="border px-4 py-2 text-center"  @click.stop>
                            <DeleteButton @click="()=>deleteTest(test)"/>
                        </td>
                    </template>
                </TestRow>
            </CardTable>
        </div>
    </Card>
    <ConfirmModal
        :show="showConfirmModal"
        title="Подтверждение удаления"
        message="После удаления теста его можно будет восстановить в блоке 'Удаленные тесты' ниже."
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<style scoped></style>
