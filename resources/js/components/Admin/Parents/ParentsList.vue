<script setup>
import { router } from '@inertiajs/vue3';
import Card from "@/components/Admin/Layout/Card.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import { formatAsMoscowTime } from '@/utils/dateHelpers';
import Pagination from "@/components/ui/Pagination.vue";
import { ref, computed, watch } from "vue";
import SearchInput from "@/components/ui/SearchInput.vue";

const props = defineProps({
    parents: Object,
    search: String,
    status: String,
});

const searchQuery = ref(props.search ?? '');
const loading = ref(false);

// Дебаунс и запрос на сервер при изменении поиска
let debounceTimeout;
watch(searchQuery, (value) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route('parents.index'), {
            search: value,
            status: props.status,
        }, {
            preserveState: true,
            preserveScroll: true
        });
    }, 300);
});

// Плавная анимация загрузки
watch(() => props.parents.links, () => {
    loading.value = true;
    setTimeout(() => loading.value = false, 300);
});

// Highlight совпадения
const highlightMatch = (text) => {
    if (!searchQuery.value) return text;
    const regex = new RegExp(`(${searchQuery.value})`, 'gi');
    return text.replace(regex, '<span class="bg-blue-300 text-blue-800 rounded px-1">$1</span>');
}
</script>

<template>
    <Card class="w-2/3">
        <CardHeader>Список родителей</CardHeader>
        <SearchInput v-model="searchQuery" placeholder="Поиск родителей..."/>
        <CardTable>
            <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border border-gray-300 px-4 py-2 text-center w-1/2">ФИО</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Дата регистрации</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Дата активации</th>
            </tr>
            </thead>
            <transition name="fade" mode="out-in">
                <tbody v-if="!loading" :key="searchQuery">
                <tr
                    v-for="parent in parents.data"
                    :key="parent.id"
                    class="hover:bg-gray-100 cursor-pointer"
                    @click="() => router.visit(route('parents.children', parent.id))"
                >
                    <td class="border border-gray-300 px-4 py-2 text-center font-semibold"
                        v-html="highlightMatch(parent.name)"></td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{
                            formatAsMoscowTime(parent.created_at)
                        }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{
                            formatAsMoscowTime(parent.updated_at)
                        }}
                    </td>
                </tr>
                </tbody>
            </transition>
        </CardTable>
        <Pagination :links="parents.links" :key="$page.url"/>
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
