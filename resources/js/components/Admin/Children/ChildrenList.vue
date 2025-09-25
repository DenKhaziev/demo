<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";

import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import GradeButtons from "@/components/ui/GradeButtons.vue";
import Pagination from "@/components/ui/Pagination.vue";
import SearchInput from "@/components/ui/SearchInput.vue";
import dayjs from "dayjs";

const props = defineProps({
    children: Object,
    gradeId: Number,
    changeGrade: Function,
    search: String,
    status: String
});

const searchQuery = ref(props.search ?? '');
const loading = ref(false);

// Дебаунс и запрос на сервер при изменении поиска
let debounceTimeout;
watch(searchQuery, (value) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route('children.index'), {
            search: value,
            status: props.status,
            grade: props.gradeId
        }, {
            preserveState: true,
            preserveScroll: true
        });
    }, 300);
});

// Watch на смену grade → сразу запрос на сервер
watch(() => props.gradeId, (newGrade) => {
    router.get(route('children.index'), {
        search: searchQuery.value,
        status: props.status,
        grade: newGrade
    }, {
        preserveState: true,
        preserveScroll: true
    });
});

// Плавная анимация загрузки
watch(() => props.children.links, () => {
    loading.value = true;
    setTimeout(() => loading.value = false, 300);
});

// Highlight совпадения
const highlightMatch = (text) => {
    if (!searchQuery.value) return text;
    const regex = new RegExp(`(${searchQuery.value})`, 'gi');
    return text.replace(regex, '<span class="bg-blue-300 text-blue-800 rounded px-1">$1</span>');
};
</script>

<template>
    <Card class="w-2/3">
        <CardHeader>Список детей</CardHeader>
        <GradeButtons :changeGrade="changeGrade" :gradeId="gradeId" />
        <SearchInput v-model="searchQuery" placeholder="Поиск учеников..." />
        <transition name="fade" mode="out-in">
            <div v-if="!loading" key="list">
                <CardTable class="mt-1">
                    <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="border border-gray-300 px-4 py-2 text-center">ФИО</th>
                        <th  class="border border-gray-300 px-4 py-2 text-center w-1/4">Дата активации</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr
                        v-for="child in props.children.data"
                        :key="child.id"
                        class="hover:bg-gray-100 cursor-pointer"
                        @click="() => router.visit(route('children.show', child.id))"
                    >
                        <td class="border border-gray-300 px-4 py-2 text-center"
                            v-html="highlightMatch(child.name)">
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-center">
                            {{child.payment_date ? dayjs(child.payment_date).format('DD.MM.YYYY') : 'не активирован'}}
                        </td>
                    </tr>
                    </tbody>
                </CardTable>
            </div>
        </transition>
        <Pagination :links="props.children.links" :key="$page.url" />
    </Card>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
