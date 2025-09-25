import { defineStore } from 'pinia'

export const useSubjectStore = defineStore('subjectStore', {
    state: () => ({
        editingSubjectId: null,
        subjects: []
    }),
    actions: {
        subjects(data) {
            this.subjects = data
        },
        editSubject(subject) {
            this.editingSubjectId = subject.id
        },
        cancelEdit() {
            this.editingSubjectId = null
        },
        updateSubject(subject) {
            console.log('Обновляем:', subject)
            this.cancelEdit()
        },
        deleteSubject(subject) {
            console.log('Удаляем:', subject)
        }
    }
})
