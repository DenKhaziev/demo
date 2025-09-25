<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";

const props = defineProps({
  questions: Array, // 10 случайных
  testId: Number,
  childId: Number,
});

const form = useForm({
  test_id: props.testId,
  child_id: props.childId,
  answers: {}, // { question_id: selected_answer_id | [ids] }
});

const isSubmitting = ref(false);
const selectedAnswers = reactive({});
const validationErrors = reactive({});

const timer = ref(60 * 60); // 60 минут
let intervalId = null;

// (опционально) подписки Inertia для отписки
let offInertiaStart = null;
let offInertiaFinish = null;

onMounted(() => {
  // таймер
  intervalId = setInterval(() => {
    if (timer.value > 0) {
      timer.value--;
    } else {
      clearInterval(intervalId);
      submitTest();
    }
  }, 1000);

  // (опционально) индикаторы глобальной навигации
  offInertiaStart = router.on('start', () => {});
  offInertiaFinish = router.on('finish', () => {});
});

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId);
  if (offInertiaStart) offInertiaStart();
  if (offInertiaFinish) offInertiaFinish();
});

const formatTime = computed(() => {
  const m = Math.floor(timer.value / 60).toString().padStart(2, '0');
  const s = (timer.value % 60).toString().padStart(2, '0');
  return `${m}:${s}`;
});

const isMultipleAnswer = (question) =>
  question.answers.filter(a => a.is_correct).length > 1;

function selectAnswer(questionId, answerId) {
  if (isSubmitting.value) return; // не даём менять во время отправки
  const question = props.questions.find(q => q.id === questionId);
  if (!question) return;

  if (isMultipleAnswer(question)) {
    if (!Array.isArray(selectedAnswers[questionId])) {
      selectedAnswers[questionId] = [];
    }
    const idx = selectedAnswers[questionId].indexOf(answerId);
    if (idx > -1) {
      selectedAnswers[questionId].splice(idx, 1);
    } else {
      selectedAnswers[questionId].push(answerId);
    }
  } else {
    selectedAnswers[questionId] = answerId;
  }
}

function isSelected(questionId, answerId) {
  const val = selectedAnswers[questionId];
  return Array.isArray(val) ? val.includes(answerId) : val === answerId;
}

const allAnswered = computed(() =>
  props.questions.every(q => !!selectedAnswers[q.id] && (
    Array.isArray(selectedAnswers[q.id]) ? selectedAnswers[q.id].length > 0 : true
  ))
);

async function submitTest() {
  if (isSubmitting.value) return;

  // очистка ошибок
  Object.keys(validationErrors).forEach(k => delete validationErrors[k]);

  // валидация на клиенте
  let ok = true;
  for (const q of props.questions) {
    const v = selectedAnswers[q.id];
    if (!v || (Array.isArray(v) && v.length === 0)) {
      validationErrors[q.id] = 'Необходимо ответить на вопрос';
      ok = false;
    }
  }
  if (!ok) {
    const firstErrorId = Number(Object.keys(validationErrors)[0]);
    const el = document.getElementById(`question-${firstErrorId}`);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      el.classList.add('ring-2', 'ring-red-500', 'rounded-md');
      setTimeout(() => el.classList.remove('ring-2', 'ring-red-500'), 1500);
    }
    return;
  }

  isSubmitting.value = true;

  form.test_id = props.testId;
  form.child_id = props.childId;
  form.answers = { ...selectedAnswers };
  form.time_left = timer.value;

  await form.post(route('student.test.submit'), {
    // если контроллер редиректит — этого достаточно:
    preserveState: false,
    preserveScroll: false,

    onError: (errs) => {
      console.error('Ошибка сабмита', errs);
    },

    onFinish: () => {
      // вернём кнопку, если сервер вернул в эту же страницу из-за ошибок
      isSubmitting.value = false;
    },
  });
}
</script>

<template>
  <div class="p-4" :key="testId">
    <!-- Таймер -->
    <Card class="text-2xl font-semibold text-red-700 mb-4 w-fit mx-auto">
      ⏱ Осталось: {{ formatTime }}
    </Card>

    <!-- Вопросы -->
    <Card>
      <CardWrapper
        v-for="(question, index) in questions"
        :key="question.id"
        :id="`question-${question.id}`"
        class="mb-6 border p-4 rounded-lg shadow"
      >
        <p class="mb-2">
          {{ index + 1 }}. <span v-html="question.question"></span>
        </p>

        <img
          v-if="question.has_image && question.image_url"
          :src="question.image_url"
          alt="Изображение к вопросу"
          class="mb-4 max-w-full rounded"
        />

        <ul :class="validationErrors[question.id] ? 'border border-red-500 rounded p-2' : ''">
          <li
            v-for="answer in question.answers"
            :key="answer.id"
            @click="selectAnswer(question.id, answer.id)"
            :class="[
              'cursor-pointer p-2 rounded mb-1 border transition',
              isSelected(question.id, answer.id)
                ? 'bg-green-500 text-white border-green-600'
                : 'bg-white hover:bg-gray-100'
            ]"
          >
              <div v-html="answer.answer"></div>
          </li>
        </ul>

        <p v-if="validationErrors[question.id]" class="text-red-600 text-sm mt-1">
          {{ validationErrors[question.id] }}
        </p>
      </CardWrapper>
    </Card>

    <!-- Сабмит -->
    <div class="text-center mt-6">
      <UserFilledButton
        @click="submitTest"
        :disabled="isSubmitting"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
      >
        {{ isSubmitting ? 'Завершение...' : 'Завершить тестирование' }}
      </UserFilledButton>
    </div>
  </div>
</template>
