<script setup>
import {ref, defineProps, onMounted, watch, nextTick} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import GoBackButton from "@/components/ui/GoBackbutton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";
import {formatAsMoscowTime, formatLocalDate} from "../../../utils/dateHelpers.js";
import BaseTextArea from "@/components/ui/BaseTextArea.vue";


const props = defineProps({
    ticket: Object,
    messages: Array,
    currentUserId: Number
});
const page = usePage();
const auth = page.props.auth;
const localMessages = ref([...props.messages]);
const showConfirmModal = ref(false);
const messageToDelete = ref(null);

watch(() => props.messages, (newVal) => {
    localMessages.value = [...newVal];
});


const form = useForm({
    ticket_id: props.ticket.id,
    body: "",
    is_admin: false
});

const sendMessage = (ticket) => {
    form.post(route("messages.store", ticket), {
        preserveScroll: true,
        onSuccess: () => form.reset("body")
    });
};
const deleteMessage = (message) => {
    messageToDelete.value = message;
    showConfirmModal.value = true;
};

const confirmDelete = () => {
    if (!messageToDelete.value) return;

    const form = useForm({ test: messageToDelete.value.id });

    form.delete(route('messages.destroy', messageToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            messageToDelete.value = null;
        }
    });
};

const cancelDelete = () => {
    showConfirmModal.value = false;
    messageToDelete.value = null;
};

onMounted(() => {
    if (auth.user.is_admin && !props.ticket.viewed_by_admin) {
        axios.put(route('admin.ticket.mark-read', props.ticket.id)).catch(() => {});
    }

    window.Echo.channel('notifications')
        .listen('.TicketMessageAdded', async (e) => {
            const msg = e.message;
            const isNotFromMe = msg.user_id !== auth.user.id;
            const isAdmin = auth.user.is_admin;
            if (msg.is_admin === null || msg.is_admin === undefined) {
                msg.is_admin = false;
            }

            // костылек для того, чтобы находясь в тикете и,получив, в этот момент новое сообщение пометить его как прочитанное с задержкой
            if (isAdmin && isNotFromMe) {
                setTimeout(() => {
                    const currentUrl = page.url;
                    const isStillInTicket = currentUrl.includes(`/admin_panel/ticket/${props.ticket.id}/messages`);

                    if (isStillInTicket) {
                        axios.put(route('tickets.mark-read', props.ticket.id)).catch(() => {});
                    }
                }, 1000);
            }

            const isSameTicket = Number(msg.ticket_id) === Number(props.ticket.id);
            if (!isSameTicket) return;

            localMessages.value.push(msg);

            nextTick(() => {
                const wrapper = document.querySelector('.chat-scroll');
                if (wrapper) wrapper.scrollTop = wrapper.scrollHeight;
            });
        });

    window._echoSubscribed = true;
});

watch(
    () => page.url,
    (newUrl) => {
        const leavingTicketPage = !newUrl.includes(`/admin/messages/${props.ticket.id}`);
        if (leavingTicketPage && props.ticket.viewed_by_admin && auth.user.is_admin) {
            axios.put(route('admin.ticket.mark-unread', props.ticket.id)).catch(() => {});
        }
    }
);



</script>

<template>
    <Card class="w-2/3">
        <GoBackButton :force-to="route('tickets.index')"/>
        <CardHeader>Тикет #{{ ticket.id }}: {{ ticket.subject }}</CardHeader>
        <CardWrapper class="bg-gray-100 p-8 chat-scroll max-h-[700px] overflow-y-auto rounded-xl">
            <TransitionGroup name="fade" tag="div" class="space-y-3">
                <div
                    v-for="message in localMessages"
                    :key="message.id"
                    :class="[
                'flex',
                message.is_admin ? 'justify-end' : 'justify-start'
            ]"
                >
                    <div
                        :class="[
                    'min-w-[300px] max-w-[600px] px-4 py-2 rounded-2xl shadow-2xl text-sm relative',
                    message.is_admin
                        ? 'bg-blue-500 text-white rounded-br-none'
                        : 'bg-gray-300 text-gray-800 rounded-bl-none'
                ]"
                    >
                        <p>
                            <strong>{{ message.is_admin ? "Администратор" : ticket.user.name }}:</strong>
                        </p>
                        <p class="w-full break-words text-base leading-relaxed tracking-normal">
                            {{ message.body }}
                        </p>

                        <p class="text-xs opacity-70 text-right text-gray">
                            {{ formatAsMoscowTime(message.created_at) }}
                        </p>
                        <DeleteButton
                            v-if="message.user_id === auth.id"
                            @click="()=>deleteMessage(message)"
                            title="Удалить"
                            class="absolute -right-6 top-1/2 -translate-y-1/2"
                        >
                            ✖
                        </DeleteButton>
                    </div>
                </div>
            </TransitionGroup>
        </CardWrapper>
        <div class="mt-4">
            <BaseTextArea
                v-model="form.body"
                class="border p-2 w-full rounded-lg"
                placeholder="Введите сообщение..."
                @keyup.enter="() => sendMessage(ticket)"
            >

            </BaseTextArea>
            <BlueButton
                @click="()=>sendMessage(ticket)"
                class="mt-4"
            >
                Отправить
            </BlueButton>
        </div>
    </Card>
    <ConfirmModal
        :show="showConfirmModal"
        title="Подтверждение удаления"
        message="Вы уверены, что хотите удалить это сообщение?"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.4s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}
.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>


