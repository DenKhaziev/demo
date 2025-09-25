<script setup>
import {router, useForm} from "@inertiajs/vue3";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import SaveButton from "@/components/ui/SaveButton.vue";
import PassedTestsHead from "@/components/Test/PassedTestsHead.vue";
import PassedTestsRow from "@/components/Test/PassedTestsRow.vue";
import RemainingTestsHead from "@/components/Test/RemainingTestsHead.vue";
import RemainingTestsRow from "@/components/Test/RemainingTestsRow.vue";
import {computed, ref} from "vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import UniversalH2Display from "@/components/ui/UniversalH2Display.vue";
import OutlineButton from "@/components/ui/OutlineButton.vue";
import FileUploaderButton from "@/components/ui/FileUploaderButton.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import ChildDocumentsModal from "@/components/Modal/ChildDocumentsModal.vue";
import PersonalInfoModal from "@/components/Modal/PersonalInfoModal.vue";

import axios from 'axios';
import ChildDocumentsUploaderComponent from "@/components/ui/ChildDocumentsUploaderComponent.vue";
import SlideToggle from "@/components/ui/SlideToggle.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import {useDocumentModal} from "@/composables/useDocumentModal.js";
import dayjs from "dayjs";
window.axios = axios;

const props = defineProps({
    child: Object,
    passedTests: Array,
    remainingTests: Array,
})

const isOpen = ref(false);
const modalVisible = ref(false);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const {
    showModal,
    modalTitle,
    modalDocs,
    openModal,
    groupedCertificates,
    groupedDocs
} = useDocumentModal(props.child);

const upToNextGrade = (child) => {
    if(confirm("Перевод на следующий год обучения возможен только при выдаче ученику справки об успешной аккредитации за текущий учебный год. " +
        "Статус ученика обновится до 'не оплачено'"))
    router.put(route("children.up.grade", child.id));
};

const isHasRequiredTest = computed(() =>
    props.remainingTests.some(test => test.type?.type === 'required')
);
const isHasNegativeScore = computed(() =>
    Array.isArray(props.passedTests) &&
    props.passedTests.some(test => test?.score === 2 && test?.test_type_id !== 1)
);


// проверка справки в таблице documents_by_grades если она есть и соответствует текущему классу и child_id то кнопка "upGrade" активна
const isHasCertificate = computed(() => {
    return props.child.documents.some(doc =>
        doc.grade_id === props.child.grade_id && doc.document_certificate_by_grade !== null
    );
});


const generateCertificate = async () => {
    try {
        const response = await axios.post(route('certificates.generate', props.child.id));

        if (response.data.success) {
            // После генерации — скачивание
            window.location.href = route('certificates.download', props.child.id);
        }
    } catch (e) {
        console.error('Ошибка при генерации справки', e);
    }
};


// количество обязательных тестов
const totalRequiredCount = computed(() => {
    const allTests = [...props.remainingTests, ...props.passedTests];
    const requiredTests = allTests.filter(t => t.type?.type === 'required')
    return props.child.grade_id > 9
        ? Math.floor(requiredTests.length / 2)
        : requiredTests.length;
})

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


// редактируем фио ученика
const editingName = ref(false);
const nameForm = useForm({
  name: '',
});
const isSavingName = computed(() => nameForm.processing);

function startEditName() {
  nameForm.clearErrors();
  nameForm.name = props.child.name ?? '';
  editingName.value = true;
  // небольшой defer, чтобы фокуснуть поле
  requestAnimationFrame(() => {
    const el = document.getElementById('child-name-input');
    el?.focus();
    el?.select();
  });
}

function cancelEditName() {
  nameForm.reset('name');
  nameForm.clearErrors();
  editingName.value = false;
}

function saveName() {
  if (!nameForm.name?.trim()) {
    nameForm.setError('name', 'Введите ФИО');
    return;
  }

  nameForm.put(route('children.update', props.child.id), {
    preserveScroll: true,
    onSuccess: () => {
      editingName.value = false;
    },
    onError: () => {

    },
  });
}

function handleNameKeydown(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    saveName();
  } else if (e.key === 'Escape') {
    e.preventDefault();
    cancelEditName();
  }
}
// закончиили редактирование

// открваем окно с персональными данными
const openPersonalInfoModal = () =>  {
    modalVisible.value = true;
}

</script>
<template>
    <Card class="">
        <GoBackbutton/>
         <!-- <a
                :href="route('admin.documents.downloadAll', props.child.id)"
                class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-1 rounded shadow"
        >
            Скачать все
        </a> -->
        <CardHeader>
            Карточка ученика:
            <br>
            <span class="text-red-900 font-bold text-2xl align-middle">
                <template v-if="!editingName">
                {{ child.name }}
                </template>
                <template v-else>
                <input
                    id="child-name-input"
                    type="text"
                    v-model="nameForm.name"
                    @keydown="handleNameKeydown"
                    class="text-red-900 font-bold text-2xl align-middle bg-white border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                </template>
            </span>
            <span class="ml-3 inline-flex gap-2 align-middle">
                <button
                v-if="!editingName"
                type="button"
                @click="startEditName"
                class="px-3 py-1 text-sm border rounded hover:bg-gray-100"
                title="Редактировать ФИО"
                >
                Редактировать
                </button>
                <button
                v-if="!editingName"
                type="button"
                @click="openPersonalInfoModal()"
                class="px-3 py-1 text-sm border rounded hover:bg-gray-100"
                title="Персональная информация"
                >
                Информация
                </button>


                <template v-else>
                <button
                    type="button"
                    @click="saveName"
                    :disabled="isSavingName"
                    class="px-3 py-1 text-sm border rounded hover:bg-gray-100 disabled:opacity-50"
                >
                    {{ isSavingName ? 'Сохранение…' : 'Сохранить' }}
                </button>
                <button
                    type="button"
                    @click="cancelEditName"
                    :disabled="isSavingName"
                    class="px-3 py-1 text-sm border rounded hover:bg-gray-100 disabled:opacity-50"
                >
                    Отмена
                </button>
                </template>
            </span>
        </CardHeader>
        <CardWrapper v-if="daysLeftToCertificateRequest > 0" class="border-4">
                Справка через: <span class="font-semibold text-[#800020]">{{daysLeftToCertificateRequest}}</span> дн.
        </CardWrapper>
        <CardWrapper class="flex justify-between">
            <UniversalH2Display label="ID" :value="child.id" />
            <UniversalH2Display label="Логин ученика" :value="child.email" />
            <UniversalH2Display label="Класс" :value="child.grade_id" />
            <UniversalH2Display
                label="Родитель"
                :value="child.user.name"
                :isLink="true"
                :linkRoute="route('parents.children', child.user_id)"
            />
        </CardWrapper>

        <SaveButton
            v-if="child.grade_id !== 11 && !isHasRequiredTest && isHasCertificate"
            @click="()=>upToNextGrade(child)"
            class="mr-3"
        >
            Перевести в следующий класс
        </SaveButton>
        <!--        при запросе на формирование справки от родителя заполняется таблица certificates -->
        <SaveButton
            v-if="!isHasRequiredTest && !isHasNegativeScore"
            @click="()=>generateCertificate()"
        >
            Сформировать справку ПА
        </SaveButton>

<!--        Сдвижная панель -->
        <div @click="toggle" class="flex items-center cursor-pointer mb-2">
            <BlueButton
                :isOpen="isOpen"
                class="mt-3"
            >
               {{isOpen ? 'Свернуть' : 'Работа с документами'}}
            </BlueButton>

        </div>
        <SlideToggle :isOpen="isOpen">
            <CardSubheader>Подгрузить справки</CardSubheader>
                <div class="flex gap-3">
                    <FileUploaderButton
                        :child-id="child.id"
                        :grade-id="child.grade_id"
                        field="document_certificate_by_grade"
                        label="Справку о прохождении аттестации"
                    />
                    <FileUploaderButton
                        :child-id="child.id"
                        :grade-id="child.grade_id"
                        field="document_about_transfer"
                        label="Справку о зачислении"
                    />
                </div>
            <CardSubheader>Подгрузить документы ученика</CardSubheader>
                <div class="flex gap-3">
                    <ChildDocumentsUploaderComponent :child="child"/>
                </div>
                <CardSubheader>Все файлы ученика</CardSubheader>
                <div class="flex gap-3 py-2">
                    <OutlineButton @click="() => openModal('Выданные справки', groupedCertificates)">
                        Выданные справки
                    </OutlineButton>
                    <OutlineButton @click="() => openModal('Документы ученика', groupedDocs)">
                        Документы ученика
                    </OutlineButton>
                </div>
        </SlideToggle>
<!--        конец сдвижной панели-->

        <CardSubheader v-if="passedTests.length > 0">Пройденные тесты</CardSubheader>
        <CardTable v-if="passedTests.length > 0">
            <PassedTestsHead :isAdmin="true"/>
            <PassedTestsRow :passedTests="passedTests" :isAdmin="true"/>
        </CardTable>
        <CardSubheader>Оставшиеся тесты</CardSubheader>
        <CardTable>
            <RemainingTestsHead/>
            <RemainingTestsRow :remainingTests="remainingTests" :isAdmin="true"/>
        </CardTable>
</Card>

    <!-- модалка с документами-->
    <ChildDocumentsModal
        :show="showModal"
        :title="modalTitle"
        :documents="modalDocs"
        @close="showModal = false"
    />

    <!-- модалка с перс данными -->
    <PersonalInfoModal
        :show="modalVisible"
        :info="child.user_info ?? {}"
        :userName="child.user.name"
        @close="modalVisible = false"
    />

</template>

<style scoped>

</style>
