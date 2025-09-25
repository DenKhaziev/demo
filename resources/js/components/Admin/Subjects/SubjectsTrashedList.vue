<script setup>

import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import {router} from "@inertiajs/vue3";
import BlueButton from "@/components/ui/BlueButton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import CardTableSubjectHead from "@/components/Admin/Subjects/CardTableSubjectHead.vue";
import SubjectRow from "@/components/Admin/Subjects/SubjectRow.vue";


const props = defineProps({
    subjects: Object,
});

const restoreSubject = (subject) => {
    router.put(route('subjects.restore', subject.id), {}, {
        onSuccess:() => {
            subject.deleted_at = null;
        }
    })
};
</script>

<template>
        <Card class="w-2/3">
        <GoBackbutton/>
            <CardTable>
                <CardTableSubjectHead/>
                <SubjectRow :subjects="subjects">
                    <template #subject="{ subject }">
                        {{ subject.subject }}
                    </template>
                    <template #actions="{subject}">
                            <BlueButton
                                @click="()=>restoreSubject(subject)"
                            >
                                Восстановить
                            </BlueButton>
                    </template>
                </SubjectRow>
            </CardTable>
        </Card>
</template>

<style scoped>

</style>
