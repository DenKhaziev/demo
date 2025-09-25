<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import SaveButton from "@/components/ui/SaveButton.vue";
import CancelButton from "@/components/ui/CancelButton.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import SubjectRow from "@/components/Admin/Subjects/SubjectRow.vue";
import CardTableSubjectHead from "@/components/Admin/Subjects/CardTableSubjectHead.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";

const props = defineProps({
    subjects: Array,
});

const editingSubjectId = ref(null);
const originalSubjects = ref(JSON.parse(JSON.stringify(props.subjects))); // Копия оригинальных данных

const showConfirmModal = ref(false);
const subjectToDelete = ref(null);

const editSubject = (subject) => {
    editingSubjectId.value = subject.id;
};

const updateSubject = (subject) => {
    const updateForm = useForm({
        subject: subject.subject
    });

    updateForm.put(route('subjects.update', subject.id), {
        onSuccess: () => {
            editingSubjectId.value = null;
        },
        preserveScroll: true,
        preserveState: true
    });
};

const cancelEdit = (subject) => {
    // Вернуть оригинальное значение
    const original = originalSubjects.value.find(s => s.id === subject.id);
    if (original) subject.subject = original.subject;
    editingSubjectId.value = null;
};

const deleteSubject = (subject) => {
    subjectToDelete.value = subject;
    showConfirmModal.value = true;
};

const confirmDelete = () => {
    if (!subjectToDelete.value) return;

    const form = useForm({ test: subjectToDelete.value.id });

    form.delete(route('subjects.destroy', subjectToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            subjectToDelete.value = null;
        }
    });
};

const cancelDelete = () => {
    showConfirmModal.value = false;
    subjectToDelete.value = null;
};
</script>

<template>
    <Card class="w-3/4">
        <CardHeader>Cписок предметов</CardHeader>

        <CardTable class="overflow-x-auto">
                <CardTableSubjectHead/>
                <SubjectRow :subjects="subjects">
                    <template #subject="{ subject }">
                        <BaseInput
                            v-if="editingSubjectId === subject.id"
                            v-model="subject.subject"
                            type="text"
                            class="border p-1 rounded-lg shadow-lg w-full text-center"
                        />
                        <span v-else>{{ subject.subject }}</span>
                    </template>
                    <template #actions="{ subject }">
                        <div class="flex justify-between space-x-2">
                            <template v-if="editingSubjectId === subject.id">
                                <SaveButton
                                    @click="()=>updateSubject(subject)"
                                >
                                    Сохранить
                                </SaveButton>
                                <CancelButton
                                    @click="()=>cancelEdit(subject)"
                                >
                                    Отмена
                                </CancelButton>
                            </template>
                            <template v-else>
                                <BlueButton
                                    @click="()=>editSubject(subject)"
                                >
                                    Редактировать
                                </BlueButton>
                                <DeleteButton
                                    @click="()=>deleteSubject(subject)"
                                />
                            </template>
                        </div>
                    </template>
                </SubjectRow>
        </CardTable>
    </Card>
    <ConfirmModal
        :show="showConfirmModal"
        title="Подтверждение удаления"
        message="Удаление предмета необходимо осуществлять только в том случае, если далее тесты с этим предметом не будут использоваться, так как все тесты с этим предметом будут автоматически удалены. Вы уверены, что хотите удалить этот предмет?"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<style scoped>
</style>
