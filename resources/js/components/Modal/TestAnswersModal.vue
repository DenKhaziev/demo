<script setup>
import {computed, ref, toRef} from "vue";
import {useModalBehavior} from "@/composables/useModalBehavior.js";

const props = defineProps({
    show: Boolean,
    answers: Object,          // JSON: { question_id: answer_id | [answer_ids] }
    answerObjects: Array      // [ { id, answer, question: { question, answers: [...] } } ]
});

const emit = defineEmits(['close']);
const modalRef = ref(null);
const showRef = toRef(props, 'show');
useModalBehavior(showRef, emit);

const answerMap = computed(() => {
    return Object.fromEntries(props.answerObjects.map(a => [a.id, a]));
});

// распарсим вопросы и ответы
const parsedAnswers = computed(() => {
    return Object.entries(props.answers).map(([questionId, answerIds]) => {
        const selectedIds = Array.isArray(answerIds) ? answerIds : [answerIds];
        const selectedAnswers = selectedIds.map(id => answerMap.value[id]).filter(Boolean);

        const question = selectedAnswers[0]?.question?.question ?? `(вопрос #${questionId})`;
        const correctAnswers = selectedAnswers[0]?.question?.answers?.filter(a => a.is_correct).map(a => a.id).sort() ?? [];
        const selectedSorted = [...selectedIds].sort();
        const isCorrect = JSON.stringify(correctAnswers) === JSON.stringify(selectedSorted);

        return {
            question,
            selected_answer: selectedAnswers.map(a => a.answer).join(', '),
            is_correct: isCorrect,
        };
    });
});


</script>

<template>
    <transition name="fade">
        <div
            v-if="props.show"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-40 flex items-center justify-center"
            @click="emit('close')"
        >
            <div
                ref="modalRef"
                @click.stop
                class="bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl relative z-50 max-h-[80vh] overflow-y-auto">
                <button
                    @click="emit('close')"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl"
                >
                    &times;
                </button>

                <h2 class="text-2xl font-semibold mb-4 text-center">Ответы ученика</h2>

                <div v-for="(answer, index) in parsedAnswers" :key="index" class="mb-4 p-4 rounded-lg" :class="answer.is_correct ? 'bg-green-100' : 'bg-red-100'">
                    <h3 class="text-lg font-semibold mb-2">Вопрос {{ index + 1 }}:</h3>
                    <p class="mb-2" v-html="answer.question"></p>
                    <p>Ответ: <span class="font-bold">{{ answer.selected_answer }}</span></p>
                    <p v-if="answer.is_correct" class="text-green-700 font-semibold">Правильно</p>
                    <p v-else class="text-red-700 font-semibold">
                        Неправильно
                    </p>
                </div>

            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
