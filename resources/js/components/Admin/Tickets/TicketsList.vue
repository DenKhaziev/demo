<script setup>
import {defineProps, onMounted, onUnmounted, ref, watch, watchEffect} from "vue";
import {Link, router, useForm} from "@inertiajs/vue3";
import CancelButton from "@/components/ui/CancelButton.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import {formatAsMoscowTime} from '@/utils/dateHelpers';
import SaveButton from "@/components/ui/SaveButton.vue";
import Pagination from "@/components/ui/Pagination.vue";
import SearchInput from "@/components/ui/SearchInput.vue";

const props = defineProps({
    tickets: Object, // { data, links, meta }
    search: String
});

// Работаем только с tickets.data
const localTickets = ref([...props.tickets.data]);
const hoveredTicketId = ref(null)
const searchQuery = ref(props.search ?? '');
const loading = ref(false);

// Если приходит новая страница (например, через поиск или пагинацию) — обновим
watch(() => props.tickets.data, (newVal) => {
    localTickets.value = [...newVal];
});

const getStatusClass = (status) => {
    return status === "open" ? "text-green-500 font-semibold" : "text-gray-500 font-semibold";
};

const form = useForm({});

const toggleTicket = (ticketId) => {
    form.put(route("tickets.toggle", ticketId), {}, {
        preserveScroll: true
    });
};

// Поиск с debounce
let debounceTimeout;
watch(searchQuery, (value) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route('tickets.index'), {
            search: value,
        }, {
            preserveState: true,
            preserveScroll: true
        });
    }, 300);
});

// Лоадер при смене страниц
watch(() => props.tickets.links, () => {
    loading.value = true;
    setTimeout(() => loading.value = false, 300);
});

const highlightMatch = (text) => {
    if (!searchQuery.value) return text;
    const regex = new RegExp(`(${searchQuery.value})`, 'gi');
    return text.replace(regex, '<span class="bg-blue-300 text-blue-800 rounded px-1">$1</span>');
};


onMounted(() => {
    window.Echo.channel('notifications')
        .listen('.TicketCreated', (e) => {
            if (props.tickets.current_page === 1) {
                if (!localTickets.value.find(t => t.id === e.ticket.id)) {
                    localTickets.value.unshift(e.ticket)
                }
            } else {
                console.log('⚠️ Не на первой странице — не добавляем')
            }
        })
        .listen('.TicketMessageAdded', (e) => {
            const updated = e.ticket;
            const index = localTickets.value.findIndex(t => t.id === updated.id);
            if (index !== -1) {
                localTickets.value.splice(index, 1, updated);
            }
        });
    window._echoSubscribed = true
})

const markAsUnread = async (ticketId) => {
    try {
        await axios.get(route('tickets.mark-unread', ticketId))
        window.dispatchEvent(new Event('refresh-unread-count'));
        const target = localTickets.value.find(t => t.id === ticketId)
        if (target) target.viewed_by_admin = false
    } catch (e) {
        alert('❌ Что-то пошло не так: ' + (e.response?.data?.message || e.message));
    }
}
</script>


<template>
    <Card class="p-6 bg-white shadow-lg rounded-lg">
        <CardHeader>Список обращений</CardHeader>
        <SearchInput v-model="searchQuery" placeholder="Поиск тикета по имени родителя..." />
        <CardTable>
            <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2 w-1/12">ID</th>
                <th class="border border-gray-300 px-4 py-2 w-1/3">Тема</th>
                <th class="border border-gray-300 px-4 py-2">Статус</th>
                <th class="border border-gray-300 px-4 py-2">Пользователь</th>
                <th class="border border-gray-300 px-4 py-2">Дата создания</th>
                <th class="border border-gray-300 px-4 py-2">Дата обновления</th>
            </tr>
            </thead>
            <transition name="fade" mode="out-in">
                <tbody :key="loading">
                <tr
                    v-for="ticket in localTickets"
                    :key="ticket.id"
                    @mouseenter="hoveredTicketId = ticket.id"
                    @mouseleave="hoveredTicketId = null"
                    :class="[
                        'hover:bg-gray-100 relative',
                        ticket.status === 'closed' ? 'bg-gray-200 text-gray-400' : 'cursor-pointer',
                        !ticket.viewed_by_admin ? 'bg-yellow-100 border-yellow-400 border-l-4' : ''
                    ]"
                    @click="ticket.status !== 'closed' && router.visit(route('messages.index', ticket.id))"
                >
                    <td class="border border-gray-300 px-4 py-2 text-center font-semibold">
                        <div class="flex items-center justify-center gap-2">
                        <span>{{ ticket.id }}</span>

                        <button
                            v-if="ticket.viewed_by_admin && ticket.status === 'open'"
                            @click.stop="markAsUnread(ticket.id)"
                            title="Пометить как непрочитанное"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:text-yellow-500 transition" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.4583 8.54167C16.4306 9.51389 17.6111 10 19 10c0.9805 0 1.8572 -0.24228 2.6301 -0.72685C21.8767 10.143 22 11.0519 22 12c0 1.3833 -0.2625 2.6833 -0.7875 3.9 -0.525 1.2167 -1.2375 2.275 -2.1375 3.175 -0.9 0.9 -1.9583 1.6125 -3.175 2.1375 -1.2167 0.525 -2.5167 0.7875 -3.9 0.7875 -0.75 0 -1.4833 -0.0792 -2.2 -0.2375 -0.71667 -0.1583 -1.41667 -0.3958 -2.1 -0.7125L1 23l1.95 -6.7c-0.31667 -0.6833 -0.55417 -1.3833 -0.7125 -2.1C2.07917 13.4833 2 12.75 2 12c0 -1.3833 0.2625 -2.68333 0.7875 -3.9 0.525 -1.21667 1.2375 -2.275 2.1375 -3.175 0.9 -0.9 1.95833 -1.6125 3.175 -2.1375C9.31667 2.2625 10.6167 2 12 2c0.9481 0 1.857 0.12331 2.7268 0.36991C14.2423 3.14277 14 4.01947 14 5c0 1.38889 0.4861 2.56944 1.4583 3.54167ZM19 8c-0.8333 0 -1.5417 -0.29167 -2.125 -0.875C16.2917 6.54167 16 5.83333 16 5c0 -0.83333 0.2917 -1.54167 0.875 -2.125C17.4583 2.29167 18.1667 2 19 2c0.8333 0 1.5417 0.29167 2.125 0.875C21.7083 3.45833 22 4.16667 22 5c0 0.83333 -0.2917 1.54167 -0.875 2.125C20.5417 7.70833 19.8333 8 19 8Z"/>
                            </svg>

                        </button>
                        </div>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center font-semibold">
                        {{ ticket.subject }}
                    </td>
                    <td class="border border-gray-300 p-2 text-center" @click.stop>
                        <span :class="getStatusClass(ticket.status)">
                            {{ ticket.status === "open" ? "Открыт" : "Закрыт" }}
                        </span>
                        <CancelButton
                            v-if="ticket.status === 'open' && ticket.viewed_by_admin"
                            @click="() => toggleTicket(ticket.id)"
                            class="ml-3"
                        >
                            Закрыть
                        </CancelButton>

                        <SaveButton
                            v-if="ticket.status === 'closed'"
                            @click="() => toggleTicket(ticket.id)"
                            class="ml-3"
                        >
                            Открыть
                        </SaveButton>
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-center font-semibold"
                        @click.stop
                    >
                        <Link :href="route('parents.children', ticket.user.id)" class="hover:underline">
                            <span v-html="highlightMatch(ticket.user.name)"></span>
                        </Link>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        {{formatAsMoscowTime(ticket.created_at)}}
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        {{formatAsMoscowTime(ticket.updated_at)}}
                    </td>
                </tr>
                </tbody>
            </transition>
        </CardTable>
        <Pagination :links="props.tickets.links" :key="$page.url" />
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


