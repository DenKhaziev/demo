<script setup>

import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import dayjs from "dayjs";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";


const props = defineProps({
    child: Object,
    passedTests: Array,
    remainingTests: Array,
})

const isSubmitting = ref(false);
const successMessage = ref(false)

const hasPayment = computed(() => !!props.child.is_payment)
const isHasRequiredTest = computed(() =>
    props.remainingTests.some(test => test.type?.type === 'required')
);
const isHasNegativeScore = computed(() =>
    Array.isArray(props.passedTests) &&
    props.passedTests.some(test => test?.score === 2 && test?.test_type_id !== 1)
);
const isAwaitingCertificate = computed(() =>
    props.child.certificate?.is_has_a_certificate === 0 && props.child.certificate?.grade_id === props.child.grade_id
)
const isHasCertificate = computed(() =>
    props.child.certificate?.is_has_a_certificate === 1 && props.child.certificate?.grade_id === props.child.grade_id
)

// расчет для количества пройденных рабочих дней
function businessDaysDiff(start, end) {
  const s = dayjs(start).startOf('day')
  const e = dayjs(end).startOf('day')
  if (!s.isValid() || !e.isValid() || e.isBefore(s, 'day')) return 0

  const days = e.diff(s, 'day')            // прошедшие календарные дни (стартовый день не считаем)
  const fullWeeks = Math.floor(days / 7)
  let business = fullWeeks * 5

  const remainder = days % 7
  for (let i = 0; i < remainder; i++) {
    const dow = s.add(i, 'day').day()      // 0=вс, 6=сб
    if (dow !== 0 && dow !== 6) business++
  }
  return business
}

// количество пройденных рабочих дней с момента открытия доступа в ЛК ученика
const daysSincePayment = computed(() => {
  if (!props.child.is_payment || !props.child.payment_date) return 0
  return businessDaysDiff(props.child.payment_date, dayjs())
})
// количество обязательных тестов
const totalRequiredCount = computed(() => {
    const allTests = [...props.remainingTests, ...props.passedTests];
    const requiredTests = allTests.filter(t => t.type?.type === 'required')
    return props.child.grade_id > 9
        ? Math.floor(requiredTests.length / 2)
        : requiredTests.length;
})
console.log(totalRequiredCount.value)

// добавить N рабочих дней к дате (пн–пт), стартовый день не считаем
function addBusinessDays(start, n) {
  let d = dayjs(start).startOf('day')
  let added = 0
  while (added < n) {
    d = d.add(1, 'day')
    const dow = d.day() // 0=вс, 6=сб
    if (dow !== 0 && dow !== 6) added++
  }
  return d
}

// осталось календарных дней до даты, когда накопится нужное число рабочих дней
const daysLeftToCertificateRequest = computed(() => {
  if (!props.child.is_payment || !props.child.payment_date) return 0
  const target = addBusinessDays(props.child.payment_date, totalRequiredCount.value)
  const today = dayjs().startOf('day')
  return Math.max(0, target.startOf('day').diff(today, 'day'))
})

// условие - пройденные дни должны быть больше чем количество обязательных тестов
const isDelayPassed = computed(() =>
    daysSincePayment.value >= totalRequiredCount.value
)

const form = useForm({
    child_id: props.child.id,
    grade_id: props.child.grade_id,
})



const requestCertificate = async () => {

    if (isSubmitting.value) return;
    isSubmitting.value = true;

    await form.post(route('certificates.request-send'), {
        onSuccess: () => {

            successMessage.value = true;
        },
        onError: (err) => {
            console.error('Ошибка:', err);
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
}
</script>

<template>
    <button
        v-if="isAwaitingCertificate"
        class="bg-yellow-200 text-gray-700 cursor-not-allowed mb-10 p-2 rounded-xl font-semibold mx-auto block w-fit shadow-xl"
        disabled
    >
        <span class="text-[#800020]">Справка будет сформирована в течение 10ти рабочих дней </span> и появится здесь, в разделе "Справки ученика".
        <br/>
        Если вам понадобится получить оригинал справки, вы можете забрать его самостоятельно по адресу - г. Москва, Алтуфьевское шоссе д.12Б  в рабочие дни с 10-17,
        <br/>
        или заказать курьерскую доставку самостоятельно на это же время, в этом случае обязательно проинформируйте нас по email <span class="text-[#800020]">so@penaty.ru,</span> или написав обращение в личном кабинете,
        <br/>
        указав дату и время передачи документа курьеру, адрес и ФИО получателя!
    </button>
    <button
        v-if="isHasCertificate"
        class="bg-green-100 text-gray-700 cursor-not-allowed mb-10 p-4 rounded-xl font-semibold mx-auto block w-fit shadow-xl"
        disabled
    >
        <span class="text-green-900">Ваша справка сформирована! Можете просмотреть и скачать ее по кнопке "Справки ученика"</span>
        <br/>
        Если вам понадобится получить оригинал справки, вы можете забрать его самостоятельно по адресу - г. Москва, Алтуфьевское шоссе д.12Б  в рабочие дни с 10-17,
        <br/>
        или заказать курьерскую доставку самостоятельно на это же время, в этом случае обязательно проинформируйте нас по email <span class="text-[#800020]">so@penaty.ru,</span> или написав обращение в личном кабинете,
        <br/>
        указав дату и время передачи документа курьеру, адрес и ФИО получателя!
    </button>
    <button
        v-if="!isHasCertificate && !isAwaitingCertificate"
        class="bg-gray-200 text-gray-600 cursor-not-allowed mb-10 p-4 rounded-xl font-semibold mx-auto block w-fit shadow-xl"
        disabled
    >
        Уважаемый представитель, справку о прохождении промежуточной аттестации можно будет запросить после того, как ученик пройдет все обязательные тестирования.
        <br/>
        Есть строгий лимит по времени - не более 1 тестирования в день, не учитывая "демо" тесты.
        <br/>
        Вы можете пройти все тесты сразу без ограничений, но справку вы сможете запросить не менее чем через количество рабочих дней равное количеству обязательных тестирований.
        <br/>
        Так же учитывайте, что для формирования справки не должно быть оценок 2 (неудовлетворительно) по обязательным тестированиям.
        <br/>
        Кнопка для формирования справки о прохождении промежуточной аттестации появится ниже данного сообщения!
        <br/>
        <span class="text-red-700" >С Момента запроса документа есть регламентированные сроки его предоставления - 10 рабочих дней!</span>
    </button>
    <CardWrapper v-if="hasPayment && daysLeftToCertificateRequest > 0" class="border-4 border-red-700">
            Справку о прохождении промежуточной аттестации сможете запросить минимум через: <span class="font-semibold text-[#800020]">{{daysLeftToCertificateRequest}}</span> дн.
    </CardWrapper>
    <UserFilledButton
        v-if="!isHasRequiredTest && !isHasNegativeScore && !successMessage && !isHasCertificate && isDelayPassed && hasPayment && !isAwaitingCertificate"
        @click="requestCertificate"
        class="mb-10 md:mr-5"
    >
        {{ isSubmitting ? 'Отправка...' : 'Запросить формирование справки о прохождении промежуточной аттестации' }}
    </UserFilledButton>
</template>

<style scoped>

</style>
