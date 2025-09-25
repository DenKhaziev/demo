<script setup>

import Card from "@/components/Admin/Layout/Card.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import UniversalH2Display from "@/components/ui/UniversalH2Display.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import CardSubheader from "@/components/Admin/Layout/CardSubheader.vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import UserOutlineButton from "@/components/ui/UserOutlineButton.vue";
import ChildDocumentsModal from "@/components/Modal/ChildDocumentsModal.vue";
import {useDocumentModal} from "@/composables/useDocumentModal.js";

import ChildDocumentsUploaderComponent from "@/components/ui/ChildDocumentsUploaderComponent.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import InfoModal from "@/components/Modal/InfoModal.vue";
import SlideToggle from "@/components/ui/SlideToggle.vue";
import ApplicationForTransfer from "@/components/Admin/Parents/ApplicationForTransfer.vue";
import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';
import Header from "@/components/Main/Header.vue";

const props = defineProps({
    parent: Object,
    children: Array,
});

const showModal = ref(false)
const showUploadModal = ref(false)
const showCertificateNoticeModal = ref(false)
const modalTitle = ref('')
const modalDocs = ref([])
const currentModal = ref(null)
const isOpen = ref(false);
const selectedChild = ref(null)
const page = usePage();
const auth = page.props.auth;
const hasUnreadTicketRaw = ref(false)
const hasUnreadTicket = computed(() => hasUnreadTicketRaw.value)

// чтобы загрузка аттестата не отображалась у всех учеников, а только у 10 и 11кл
const openUploadModalForChild = (child) => {
    selectedChild.value = child
    showUploadModal.value = true
}

const openCertificateInfoModal = () => {
    showCertificateNoticeModal.value = true
}

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const updateHasUnreadTicket = async () => {
    const res = await axios.get(route('tickets.unread-check')); //
    hasUnreadTicketRaw.value = res.data.hasUnread;
}
const handleOpenModal = (child) => {
    const modal = useDocumentModal(child)
    showModal.value = modal.showModal
    modalTitle.value = modal.modalTitle
    modalDocs.value = modal.groupedDocs.value
    modal.openModal('Документы ученика', modal.groupedDocs.value)

    // для повторного обращения к модалке
    currentModal.value = modal
}

const isDocumentsUploadedMap = computed(() => {
    const map = {}
    props.children.forEach(child => {
        const { isDocumentsUploaded } = useDocumentModal(child)
        map[child.id] = isDocumentsUploaded.value
    })
    return map
})

function uploadOPD() {
    const file = 'soglasie_OPD.pdf' // или динамически, если нужно
    window.open(route('documents.download', { file }), '_blank')
}

const openOrCreateTicket = async () => {
    const openTicket = props.parent.tickets.find(t => t.status === 'open')

    if (openTicket) {
        router.visit(route('parent.ticket'))
    } else {
        router.visit(route('parent.ticket.create'))
    }
}


// Тур
const startTour = () => {
    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: {
                enabled: true
            },
            classes: '.shepherd-step', // стиль модалки
            scrollTo: { behavior: 'smooth', block: 'center' },
            modalOverlayOpeningPadding: 10,
            modalOverlayOpeningRadius: 10
        }
    });

    tour.addStep({
        id: 'step-fio',
        text: 'При нажатии на ФИО Вы попадете на страницу с аттестационными тестированиями (не для сдачи тестирований - данный функционал разработан в личном кабинете ученика), так же можете контролировать оценки ученика и запрашивать справки о прохождении аттестации!',
        attachTo: {
            element: '.parent-info',
            on: 'bottom'
        },
        highlightClass: 'shepherd-highlight', // выделяем элемент
        modalOverlayOpeningPadding: 5,
        buttons: [
            {
                text: 'Далее',
                action: tour.next,
            }
        ]
    });

    tour.addStep({
        id: 'step-upload',
        text: 'Нажмите, чтобы сформировать заявление на зачисление и загрузить необходимые документы ученика.',
        attachTo: {
            element: '.btn-upload-docs',
            on: 'bottom'
        },
        highlightClass: 'shepherd-highlight',
        buttons: [
            {
                text: 'Далее',
                action: tour.next,
            }
        ]
    });

    tour.addStep({
        id: 'step-open-docs',
        text: 'При необходимости нажмите, чтобы проверить загруженные документы.',
        attachTo: {
            element: '.btn-open-docs',
            on: 'bottom'
        },
        highlightClass: 'shepherd-highlight',
        buttons: [
            {
                text: 'Далее',
                action: tour.next,
            }
        ]
    });

    tour.addStep({
        id: 'step-status',
        text: 'Здесь Вы можете перейти на страницу для оплаты, а также увидеть статус активации личного кабинета ученика.',
        attachTo: {
            element: '.status-col',
            on: 'bottom'
        },
        highlightClass: 'shepherd-highlight',
        buttons: [
            {
                text: 'Далее',
                action: tour.next,
            }
        ]
    });

    tour.addStep({
        id: 'step-add-child',
        text: 'Здесь Вы можете добавить нового ученика для прохождения аттестаций.',
        attachTo: {
            element: '.btn-add-child',
            on: 'top'
        },
        highlightClass: 'shepherd-highlight',
        buttons: [
            {
                text: 'Далее',
                action: tour.next,
            }
        ]
    });

    tour.addStep({
        id: 'step-ticket',
        text: 'Напишите обращение с вашим вопросом администратору школы',
        attachTo: {
            element: '.btn-ticket',
            on: 'top'
        },
        highlightClass: 'shepherd-highlight',
        buttons: [
            {
                text: 'Завершить',
                action: tour.complete,
            }
        ]
    });

    tour.start();
};

onMounted(async () => {
    await updateHasUnreadTicket();

    window.Echo.channel('notifications')
        .listen('.TicketCreated', () => updateHasUnreadTicket())
        .listen('.TicketMessageAdded', () => updateHasUnreadTicket());
});

</script>
<template>
    <Header class="text-5xl flex justify-center items-center">Личный кабинет представителя</Header>
    <Card class="mb-3 flex text-center font-semibold text-[#800020]">
        <p>
            Уважаемые родители (представители) для сдачи аттестационных тестирований ученику необходимо самостоятельно зайти в личный кабинет ученика по доступам, которые вы получили вместе с регистрацией!
        </p>
    </Card>
    <Card class="mb-3 flex flex-col gap-4 items-center text-center font-semibold md:flex-row md:justify-between md:text-left md:items-start">
        <div>
            <p>Добро пожаловать в личный кабинет представителя (родителя) ученика.</p>
            <p>Чтобы познакомиться с возможностями, нажмите на кнопку!</p>
        </div>
        <UserFilledButton @click="startTour" class="w-fit">Познакомиться</UserFilledButton>
    </Card>
    <Card class="mb-3 flex flex-col gap-4 items-center justify-between text-center font-semibold md:flex-row md:justify-between md:text-left md:items-start">
        <div>
            <p>Уважаемые представители ученика, важная информация по заказу оригиналов справок.</p>
            <p>Чтобы познакомиться с информацией, нажмите на кнопку!</p>
        </div>
        <UserFilledButton @click="() => openCertificateInfoModal()" class="btn-upload-docs">
            Информация!
        </UserFilledButton>
    </Card>
    <Card class="w-full">
        <CardWrapper class="flex flex-col gap-4 rounded shadow-lg px-4 py-4 mb-3 md:flex-row md:justify-between md:items-start">
            <UniversalH2Display label="ваше ФИО:" :value="parent.name" />
            <UniversalH2Display label="Телефон:" :value="parent.phone" />
            <UniversalH2Display label="E-mail:" :value="parent.email" />
        </CardWrapper>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse shadow-lg md:border md:border-gray-300 md:mb-0 border-none">
            <thead class="hidden md:table-header-group">
                <tr class="bg-gray-200 text-gray-700 md:">
                    <th class="border border-gray-300 px-4 py-2 text-center">Имя ребенка</th>
                    <th class="border border-gray-300 px-4 py-2 text-center w-1/12">Класс</th>
                    <th class="border border-gray-300 px-4 py-2 text-center w-1/12">Аттестация</th>
                    <th class="border border-gray-300 px-4 py-2 text-center w-1/3">Документы ученика</th>
                    <th class="border border-gray-300 px-4 py-2 text-center w-1/5">Статус</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="child in children" :key="child.id">
                    <tr
                        class="block md:table-row w-full mb-4 md:mb-0 border border-gray-200 md:border-0 rounded-xl shadow-md md:shadow-none bg-white hover:bg-gray-50 transition-all cursor-pointer"
                        @click="() => router.visit(route('parent.child.show', child.id))"
                    >
                        <!-- Имя ребенка -->
                        <td class="block md:table-cell px-4 py-2 border-b border-blue-100 md:border md:border-gray-300 text-center md:text-center parent-info">
                            <span class="md:hidden font-semibold text-gray-600 block mb-1">Имя ребенка</span>
                            <div class="font-semibold ">{{ child.name }}</div>
                            <template v-if="!child.is_payment && !isDocumentsUploadedMap[child.id]">
                                <p class="text-xs text-gray-500 mt-1 font-semibold">
                                    (пожалуйста, загрузите все необходимые документы)
                                </p>
                            </template>
                        </td>

                        <!-- Класс -->
                        <td class="block md:table-cell px-4 py-2 border-b border-blue-100 md:border md:border-gray-300 text-center md:text-center">
                            <span class="md:hidden font-semibold text-gray-600 block mb-1">Класс</span>
                            {{ child.grade_id }}
                        </td>

                        <!-- Аттестация -->
                        <td class="block md:table-cell px-4 py-2 border-b border-blue-100 md:border md:border-gray-300 text-center md:text-center">
                            <span class="md:hidden font-semibold text-gray-600 block mb-1">Аттестация</span>
                            {{ child.attestation_type }}
                        </td>

                        <!-- Документы -->
                        <td class="block md:table-cell px-4 py-2 border-b border-blue-100 md:border md:border-gray-300 text-center md:text-center" @click.stop>
                            <span class="md:hidden font-semibold text-gray-600 block mb-2">Документы</span>
                            <div class="flex flex-col md:flex-row md:justify-around gap-2">
                                <UserFilledButton @click="() => openUploadModalForChild(child)" class="btn-upload-docs">
                                    Загрузить документы
                                </UserFilledButton>
                                <UserOutlineButton @click="() => handleOpenModal(child)" class="btn-open-docs">
                                    Открыть документы
                                </UserOutlineButton>
                            </div>
                        </td>

                        <!-- Статус -->
                        <td class="block md:table-cell px-4 py-2 border-0 md:border md:border-gray-300 text-center md:text-center status-col" @click.stop>
                            <span class="md:hidden font-semibold text-gray-600 block mb-1">Статус</span>
                            <template v-if="child.is_payment">
                                <span class="bg-green-100 text-green-700 font-semibold rounded px-2 py-1 inline-block">Доступ открыт</span>
                            </template>
                            <template v-else-if="isDocumentsUploadedMap[child.id]">
                                <span class="text-gray-600 text-sm font-medium">⏳ Документы загружены, доступ в личный кабинет ученика откроется в течение 3х рабочих дней</span>
                            </template>
                            <template v-else>
                                <UserOutlineButton @click="() => router.visit(route('parent.payment'))">Оплатить</UserOutlineButton>
                            </template>
                        </td>
                    </tr>

                </template>
                </tbody>
            </table>
        </div>
        <div class="flex gap-3 justify-center items-center mt-10">
            <UserFilledButton
                @click="() => router.visit(route('parent.child.add'))"
                class="btn-add-child"
            >
                Добавить ребенка
            </UserFilledButton>
            <UserFilledButton
                @click="openOrCreateTicket"
                :class="['btn-ticket', { 'animate-pulse-ring': hasUnreadTicket }]"
            >
                <div class="flex items-center gap-2">
                    <span v-if="!hasUnreadTicket">Обращение в школу</span>
                    <span v-else class="font-semibold text-md animate-pulse">
                        Новое сообщение!
                    </span>
                </div>
            </UserFilledButton>
        </div>
    </Card>
    <ChildDocumentsModal
        :show="showModal"
        :title="modalTitle"
        :documents="modalDocs"
        @close="showModal = false"
    />

    <!-- Модалка ЗАГРУЗКИ документов -->
    <InfoModal
        :show="showUploadModal"
        title="Загрузите документы ученика"
        @close="showUploadModal = false"
    >
        <div>

            <UserFilledButton @click="uploadOPD">
                Скачать бланк согласия на обработку персональных данных (ОПД)
            </UserFilledButton>
            <UserFilledButton
                @click="toggle"
                class="my-2 mx-auto block w-fit"
                :isOpen="isOpen"
            >
                {{ isOpen ? 'Свернуть' : 'Сформировать заявление на зачисление ученика.' }}
            </UserFilledButton>
            <SlideToggle :isOpen="isOpen">
                <ApplicationForTransfer :child="selectedChild" />
            </SlideToggle>

            <div class="grid place-items-center">
                <ChildDocumentsUploaderComponent v-if="selectedChild" :child="selectedChild" />

            </div>
            <CardWrapper class="text-center text-[#800020]">
                Если вы загрузили ошибочно некорректный документ — ничего страшного!
                <br/>
                Подгрузите повторно корректный!
            </CardWrapper>
        </div>
    </InfoModal>
    <InfoModal
        :show="showCertificateNoticeModal"
        title=""
        @close="showCertificateNoticeModal = false"
    >
        <section aria-labelledby="docs-title">
            <h2 class="text-center text-[#800020] font-bold text-3xl mb-5">Получение оригиналов справок</h2>

            <p><strong>Уважаемые представители!</strong> Информация ниже относится как к
                <strong>справкам о зачислении</strong>, так и к
                <strong>справкам о прохождении промежуточной аттестации</strong>.
            </p>
            <br>
            <p>
                Сведения по справкам о промежуточной аттестации также дублируются
                в момент оформления заказа документа и после готовности — в карточке ученика (здесь же в личном кабинете).
            </p>
            <br>
            <hr>

            <h3>Способы получения оригинала справки о зачислении:</h3>

            <ol>
                <li>
                    <p><strong>Лично</strong><br>
                        Адрес: г. Москва, Алтуфьевское шоссе, 12Б<br>
                        Время: любой рабочий будний день с 10:00 до 17:00<br>
                        Перед визитом, пожалуйста, уведомьте нас по электронной почте, указав:
                        дату и время визита, ФИО (ваши или представителя, забирающего документы).
                    </p>
                </li>
                <br>
                <li>
                    <p><strong>Курьером транспортной компании</strong><br>
                        Забор: любой рабочий будний день с 10:00 до 17:00<br>
                        Адрес: г. Москва, Алтуфьевское шоссе, 12Б<br>
                        Отправитель: ОАНО СОШ «Пенаты»<br>
                        Телефон для связи: <a href="tel:+79365555509">+7&nbsp;(936)&nbsp;555-55-09</a>
                    </p>

                    <p>После оформления отправления направьте, пожалуйста, письмо на
                        <a href="mailto:so@penaty.ru">so@penaty.ru</a> со следующей информацией:
                    </p>

                    <ul>
                        <li>перечень документов для отправки (ФИО ребёнка, класс);</li>
                        <li>название транспортной компании;</li>
                        <li>дата забора документов;</li>
                        <li>город получения посылки;</li>
                        <li>ФИО получателя.</li>
                    </ul>
                </li>
            </ol>
            <br>
            <p><strong>Важно:</strong> отправка Почтой России <u class="text-[#800020] font-bold">не производится</u>.</p>

            <p>
                Если появились вопросы, позвоните по телефону
                <a href="tel:+79365555509">+7&nbsp;(936)&nbsp;555-55-09</a> (доб.&nbsp;1)
                для получения дополнительной информации.
            </p>
            <br>
            <p><strong>Благодарим за выбор нашей школы!</strong></p>
        </section>

    </InfoModal>
</template>


<style>
/* СТИЛЬ МОДАЛКИ (серый фон, бордюр, скругления) */
.shepherd-element,
.shepherd-element .shepherd-content {
    background-color: #f3f4f6 !important;
    color: #1f2937 !important; /* text-gray-800 */
    //border: 1px solid #d1d5db !important; /* border-gray-300 */
    border-radius: 1rem !important; /* rounded-2xl */
    //box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    padding: 0.5rem !important; /* p-6 */
    max-width: 400px;
}

.shepherd-element .shepherd-arrow:before {
    background-color: #f3f4f6 !important;
    border-color: #f3f4f6 !important;
}


/* СТИЛИ КНОПКИ (как UserOutline) */
.shepherd-button {
    background-color: #800020 !important;
    border: 1px solid #800020 !important; /* бордовая обводка */
    color: #ffffff !important;
    font-weight: 600;
    padding: 0.5rem 1.25rem !important; /* py-2 px-5 */
    border-radius: 0.5rem !important; /* rounded-md */
    transition: all 0.3s ease;
    box-shadow: none;
}

.shepherd-button:hover {
    background-color: #800020 !important;
    color: #ffffff !important;
}

@keyframes pulse-ring {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); /* red-500 */
    }
    70% {
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

.animate-pulse-ring {
    animation: pulse-ring 1.5s infinite;
}

</style>

