import { defineStore } from 'pinia';
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';

export const useTestFormStore = defineStore('testFormStore', () => {
    const testTypes = ref([]);
    const subjects = ref([]);
    const grades = ref([]);

    const form = reactive({
        test_type_id: '',
        subject_id: '',
        grade_id: '',
        questions: [
            {
                question: '',
                has_image: false,
                image: null,
                answers: [
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false }
                ]
            }
        ]
    });

    const setTestTypes = (data) => { testTypes.value = data };
    const setSubjects = (data) => { subjects.value = data };
    const setGrades = (data) => { grades.value = data };

    const resetForm = () => {
        form.test_type_id = '';
        form.subject_id = '';
        form.grade_id = '';
        resetQuestions();
    };

    const resetQuestions = () => {
        form.questions = [
            {
                question: '',
                has_image: false,
                image: null,
                answers: [
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false },
                    { answer: '', is_correct: false }
                ]
            }
        ];
    };

    const addQuestion = () => {
        form.questions.push({
            question: '',
            has_image: false,
            image: null,
            answers: [
                { answer: '', is_correct: false },
                { answer: '', is_correct: false },
                { answer: '', is_correct: false },
                { answer: '', is_correct: false }
            ]
        });
    };

    const removeQuestion = (index) => {
        form.questions.splice(index, 1);
    };

    const addAnswer = (question) => {
        question.answers.push({ answer: '', is_correct: false });
    };

    const removeAnswer = (question, index) => {
        question.answers.splice(index, 1);
    };

    const submitTest = () => {
        const inertiaForm = useForm({
            test_type_id: form.test_type_id,
            subject_id: form.subject_id,
            grade_id: form.grade_id,
            questions: form.questions
        });

        inertiaForm.post(route('tests.store'), {
            onSuccess: () => resetForm()
        });
    };

    const submitQuestion = (testId) => {
        const inertiaForm = useForm({
            questions: form.questions
        });

        inertiaForm.post(route('questions.store', testId), {
            onSuccess: () => resetQuestions()
        });
    };

    return {
        form,
        testTypes,
        subjects,
        grades,
        setTestTypes,
        setSubjects,
        setGrades,
        addQuestion,
        removeQuestion,
        addAnswer,
        removeAnswer,
        submitTest,
        submitQuestion
    };
});
