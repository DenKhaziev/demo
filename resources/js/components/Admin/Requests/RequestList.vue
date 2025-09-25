<script setup>
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import BlueButton from "@/components/ui/BlueButton.vue"
import Card from "@/components/Admin/Layout/Card.vue";

import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import {nextTick, onMounted, ref, watch} from "vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";
import CancelButton from "@/components/ui/CancelButton.vue";
import SaveButton from "@/components/ui/SaveButton.vue";
import { Button } from "bootstrap";


const showConfirmModal = ref(false);
const subjectToDelete = ref(null);
const props = defineProps({
    title: String,
    items: Array, // certificates или documents
    getUserName: Function,
    getChildName: Function,
    getGradeNumber: Function,
    parentLink: Function,
    childLink: Function,
    doneRoute: Function,
    deleteRoute: Function,

    doneLabel: {
        type: String,
        default: 'Готово'
    },
    deleteLabel: {
        type: String,
        default: ''
    },
    unActiveLabel: {
        type: String,
        default: ''
    },
    description: Function, // функция, возвращающая текст запроса
});

const form = useForm();

const localItems = ref([...props.items]);
watch(() => props.items, (newVal) => {
    localItems.value = [...newVal];
});

const done = (id) => {
    form.put(props.doneRoute(id));
};
const deleteRequest = (id) => {
    subjectToDelete.value = id;
    showConfirmModal.value = true;
};

const confirmDelete = () => {
    if (!subjectToDelete.value) return;
    form.put(props.deleteRoute(subjectToDelete.value), {
        onFinish: () => {
            showConfirmModal.value = false;
            subjectToDelete.value = null;
        }
    });
};

const cancelDelete = () => {
    showConfirmModal.value = false;
    subjectToDelete.value = null;
};

const unActive = (id) => {
  form.put(route('activate.access.unactive', id), {
    onSuccess: () => {
      const item = localItems.value.find(i => i.id === id);
      if (item) {
        item.updated_at = null;
      }
    },
  });
};

onMounted(() => {
    window.Echo.channel('notifications')
        .listen('.NewCertificateRequest', (e) => {
            const newRequest = e.certificate;
            const exists = localItems.value.find(item => item.id === newRequest.id);
            if (!exists) {
                localItems.value.push(newRequest);
            }
        })
        .listen('.NewPersonalAffairRequest', (e) => {
                const newRequest = e.personalAffair;
                const exists = localItems.value.find(item => item.id === newRequest.id);
            if (!exists) {
                localItems.value.push(newRequest);
            }
        })
        .listen('.NewActivateStudentAccount', (e) => {
                const newRequest = e.activateAccount[0];
                const exists = localItems.value.find(item => item.id === newRequest.id);
                if (!exists) {
                    localItems.value.push(newRequest);
                }
            });

    window._echoSubscribed = true;
});
</script>

<template>
    <Card >
        <CardHeader>{{ title }}</CardHeader>
        <CardWrapper class="border p-4 bg-gray-100 rounded-lg mb-4 shadow-xl">
    <div
        v-for="item in localItems"
        :key="item.id"
        :class="[
            'p-2 rounded-lg shadow mb-3 transition-all',
            item.updated_at === null
            ? 'bg-yellow-100'
            : 'bg-green-50'
        ]"
    >
        <!-- Время обновления -->
        <div class="text-sm text-gray-600 mb-2">
            От:
            <span class="font-medium text-gray-800">
                {{ new Date(item.updated_at).toLocaleString('ru-RU') }}
            </span>
        </div>

        <!-- Основной контент -->
        <div class="flex justify-between items-center">
            <p class="flex-1">
                <span class="font-bold text-red-900">
                    <Link :href="parentLink(item)" class="hover:underline">
                        {{ getUserName(item) }}
                    </Link>
                </span>
                {{ description(item) }}
                <span class="font-bold text-red-900">
                    <Link :href="childLink(item)" class="hover:underline">
                        {{ getChildName(item) }}
                    </Link>
                </span>
                за
                <span class="font-bold text-red-900">
                    {{ getGradeNumber(item) }}
                </span>
                класс
            </p>
            <div class="flex items-center ml-4 gap-3">
            <!-- Жёлтая кнопка -->
            <button
                v-if="item.updated_at != null && unActiveLabel"
                @click="unActive(item.id)"
                class="px-4 py-2 rounded font-semibold text-yellow-900 bg-yellow-300 hover:bg-yellow-400 transition"
            >
                {{ unActiveLabel }}
            </button>

            <!-- Красная кнопка -->
            <button
                v-if="deleteLabel"
                @click="deleteRequest(item.id)"
                class="px-4 py-2 rounded font-semibold text-white bg-red-500 hover:bg-red-600 transition"
            >
                {{ deleteLabel }}
            </button>

            <!-- Зелёная кнопка -->
            <button
                @click="done(item.id)"
                class="px-4 py-2 rounded font-semibold text-white bg-green-500 hover:bg-green-600 transition"
            >
                {{ doneLabel }}
            </button>
            </div>
        </div>
    </div>
</CardWrapper>
    </Card>
    <ConfirmModal
        :show="showConfirmModal"
        title="Подтверждение удаления"
        message="При удалении запроса на активацию он будет перенесен в архив запросов, документы в базе данных сохранятся, но не будут доступны в админ панели. Карточка представителя и ученика сохранится."
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<style>
.transition-all {
  transition: background-color 0.5s ease;
}
</style>