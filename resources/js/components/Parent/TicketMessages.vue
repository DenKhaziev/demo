<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {formatAsMoscowTime} from "@/utils/dateHelpers.js";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import UserFilledButton from "@/components/ui/UserFilledButton.vue";
import GoBackButton from "@/components/ui/GoBackbutton.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import BaseTextArea from "@/components/ui/BaseTextArea.vue";

const props = defineProps({
    ticket: Object,
    messages: Array,
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
});

const sendMessage = () => {
    form.post(route("parent.messages.store", props.ticket.id), {
        preserveScroll: true,
        onSuccess: () => form.reset("body"),
    });
};


const deleteMessage = (message) => {
    messageToDelete.value = message;
    showConfirmModal.value = true;
};

const confirmDelete = () => {
    if (!messageToDelete.value) return;

    const deleteForm = useForm({});
    deleteForm.delete(route('parent.messages.destroy', messageToDelete.value.id), {
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

onMounted(async () => {
    nextTick(() => {
        const wrapper = document.querySelector('.chat-scroll');
        if (wrapper) wrapper.scrollTop = wrapper.scrollHeight;
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'auto' });
    });
    // отмечаем прочитанным при входе в тикет
    try {
        if (!props.ticket.viewed_by_user) {
            await axios.put(route('parent.ticket.mark-read', props.ticket.id));
        }
    } catch (e) {
        console.error("Ошибка при отметке тикета как прочитанного", e);
    }
    window.Echo.channel('notifications')
        .listen('.TicketMessageAdded', async (e) => {
            const msg = e.message;
            const isSameTicket = Number(msg.ticket_id) === Number(props.ticket.id);
            const isFromAdmin = msg.is_admin;
            if (!isSameTicket) return;
            localMessages.value.push(msg);

            nextTick(() => {
                const wrapper = document.querySelector('.chat-scroll');
                if (wrapper) wrapper.scrollTop = wrapper.scrollHeight;
            });

            // отмечаем прочитанным если уже в тикете и видим сообщение
            if (isFromAdmin && props.ticket.viewed_by_user) {
                try {
                    await axios.put(route('parent.ticket.mark-read', props.ticket.id));
                    props.ticket.viewed_by_user = true;
                } catch (e) {
                    console.error("Ошибка при отметке тикета как прочитанного (на сообщении)", e);
                }
            }
        });
    window._echoSubscribed = true;
});
</script>

<template>
    <div class="w-full px-3 max-w-4xl mx-auto mt-5">
        <GoBackButton :force-to="route('parent.index')"/>
        <Card >
            <CardHeader>Тема обращения:
                <br/>
                <span class="text-red-800 text-[20px] sm:text-xl">{{ticket.subject}}</span>
            </CardHeader>
            <CardWrapper class="bg-gray-100 p-8 chat-scroll max-h-[700px] overflow-y-auto rounded-xl">
                <TransitionGroup name="fade" tag="div" class="space-y-3">
                    <div
                        v-for="message in localMessages"
                        :key="message.id"
                        :class="['flex', message.is_admin ? 'justify-end' : 'justify-start']"
                    >
                        <div
                            :class="[
                              'w-fit max-w-[85%] px-4 py-2 rounded-2xl shadow-2xl text-sm relative break-words',
                              message.is_admin
                                ? 'bg-red-800 text-white rounded-br-none'
                                : 'bg-gray-300 text-gray-800 rounded-bl-none'
                            ]"
                        >
                            <p>
                                <strong>{{ message.is_admin ? 'Администратор школы' : auth.user.name }}:</strong>
                            </p>
                            <p class="w-full break-words text-base leading-relaxed tracking-normal">
                                {{ message.body }}
                            </p>
                            <p class="text-xs opacity-70 text-right text-gray">
                                {{ formatAsMoscowTime(message.created_at) }}
                            </p>
                            <DeleteButton
                                v-if="message.user_id === auth.user.id"
                                @click="() => deleteMessage(message)"
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
                    @keyup.enter="sendMessage"
                />
                <UserFilledButton @click="sendMessage" class="mt-4">Отправить</UserFilledButton>
            </div>
            <ConfirmModal
                :show="showConfirmModal"
                title="Подтверждение удаления"
                message="Вы уверены, что хотите удалить это сообщение?"
                @confirm="confirmDelete"
                @cancel="cancelDelete"
            />
        </Card>
    </div>

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
