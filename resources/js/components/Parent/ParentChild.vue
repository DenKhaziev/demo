<script setup>

import CardTable from "@/components/Admin/Layout/CardTable.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import UniversalH2Display from "@/components/ui/UniversalH2Display.vue";
import PassedTestsRow from "@/components/Test/PassedTestsRow.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import RemainingTestsRow from "@/components/Test/RemainingTestsRow.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import PassedTestsHead from "@/components/Test/PassedTestsHead.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import RemainingTestsHead from "@/components/Test/RemainingTestsHead.vue";
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import {useForm} from "@inertiajs/vue3";
import {useDocumentModal} from "@/composables/useDocumentModal.js";
import ChildDocumentsModal from "@/components/Modal/ChildDocumentsModal.vue";
import NotificationForParents from "@/components/ui/NotificationForParents.vue";
import {computed} from "vue";
import dayjs from "dayjs";

const props = defineProps({
    child: Object,
    passedTests: Array,
    remainingTests: Array,
})

const form = useForm({
    child_id: props.child.id,
    grade_id: props.child.grade_id,
})

const {
    showModal,
    modalTitle,
    modalDocs,
    openModal,
    groupedCertificates,
} = useDocumentModal(props.child);

const generatePersonalAffair = () =>  {
    form.put(route('parent.personal.affair.request', props.child.documents), {
        onSuccess: () => {
            console.log('Запрос направлен');
        },
        onError: (err) => {
            console.error('Ошибка:', err);
        }
    });
}
const currentGradeDocument = computed(() =>
    props.child.documents.find(d => d.grade_id === props.child.grade_id)
);
const isPersonalAffairRequested = computed(() =>
    currentGradeDocument.value?.has_personal_affair === null && props.child.grade_id === 1
)

</script>

<template>
        <GoBackbutton/>
    <Card v-if="child.grade_id === 10" class="mb-3  text-center font-semibold">
        <h1 class="text-[#800020] font-bold text-3xl mb-3">Важно!</h1>
        <p>
            Ученик <span class="text-[#800020] font-bold">{{child.name}}</span> должен отправить готовый Индивидуальный проект за 10 класс во время или после прохождения промежуточной аттестации на нашу почту
            <span class="text-[#800020] font-bold">so@penaty.ru</span> перед тем как  вы запросите справку о прохождении промежуточной аттестации!
        </p>
        <br>
        <p>
            После этого сотрудник школы сообщит вам в обращении (здесь же в личном кабинете), о принятии нами Индивидуального проекта и вы сможете заказать справку о прохождении
            промежуточно аттестации!
        </p>
        <br>
        <p>
            Большое спасибо.
        </p>
    </Card>
    <Card>
        <div class="flex flex-col items-center text-center md:flex-row md:justify-between w-full">
            <CardHeader class="text-md md:text-3xl">Карточка ученика</CardHeader>
            <UserOutlineButton class="mb-5" @click="() => openModal('Выданные справки', groupedCertificates)">
                Справки ученика
            </UserOutlineButton>
        </div>


        <NotificationForParents :child="child" :passedTests="passedTests" :remainingTests="remainingTests"/>
        <UserFilledButton
            v-if="isPersonalAffairRequested"
            @click="generatePersonalAffair"
            class="mb-10"
        >
            Запросить формирование личного дела ученика
        </UserFilledButton>

        <CardWrapper class="flex justify-around">
            <UniversalH2Display label="Ученик" :value="child.name" />
            <UniversalH2Display label="Логин ученика" :value="child.email" />

            <UniversalH2Display label="Класс" :value="child.grade_id" />
        </CardWrapper>
        <CardSubheader v-if="passedTests.length > 0">Пройденные тесты</CardSubheader>
        <CardTable v-if="passedTests.length > 0">
            <PassedTestsHead :isAdmin="false"/>
            <PassedTestsRow :passedTests="passedTests" :isAdmin="false"/>
        </CardTable>
        <CardSubheader>Оставшиеся тесты</CardSubheader>
        <CardTable>
            <RemainingTestsHead/>
            <RemainingTestsRow :remainingTests="remainingTests" :isAdmin="false"/>
        </CardTable>
    </Card>

    <ChildDocumentsModal
        :show="showModal"
        :title="modalTitle"
        :documents="modalDocs"
        @close="showModal = false"
    />
</template>

<style scoped>

</style>
