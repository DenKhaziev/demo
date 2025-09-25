<script setup>

import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import {router} from "@inertiajs/vue3";
import BlueButton from "@/components/ui/BlueButton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import CardTableTestHead from "@/components/Admin/Tests/CardTableTestHead.vue";
import TestRow from "@/components/Admin/Tests/TestRow.vue";
import GroupedTestsByGrade from "@/components/Admin/Tests/GroupedTestsByGrade.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import TestRowMain from "@/components/Admin/Tests/TestRowMain.vue";


const props = defineProps({
    tests: Array,
});
function sortedTests(tests) {
    return tests.slice().sort((a, b) => a.subject.subject.localeCompare(b.subject.subject));
}
const restoreTest = (test) => {
    router.put(route('tests.restore', test.id), {}, {
        onSuccess:() => {
            test.deleted_at = null;
        }
    })
};
</script>

<template>
        <Card>
        <GoBackbutton />
            <CardHeader class="text-red-700">Список удаленных тестов</CardHeader>
                <GroupedTestsByGrade :tests="tests" :sortedTests="sortedTests">
                    <template #table="{ tests, sortedTests }">
                        <CardTable>
                            <CardTableTestHead/>
                            <TestRow :sortedTests="sortedTests" :tests="tests">
                                <template #test="{test}">
                                    <TestRowMain :test="test"/>
                                </template>
                                <template #actions="{test}">
                                    <td class="border px-4 py-2 text-center"  @click.stop>
                                        <BlueButton
                                            @click="()=>restoreTest(test)"
                                        >
                                            Восстановить
                                        </BlueButton>
                                    </td>
                                </template>
                            </TestRow>
                        </CardTable>
                    </template>
                </GroupedTestsByGrade>
        </Card>
</template>

<style scoped>

</style>
