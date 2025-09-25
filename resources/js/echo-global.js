import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { createToastInterface } from 'vue-toastification'
import axios from 'axios'
window.axios = axios

// Настройка Echo
window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    cluster: 'mt1', // заглушка
    wsHost: window.location.hostname,
    wsPort: 8085,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws'],
})

// // Глобальный toast
const toast = createToastInterface()

export default function EchoGlobal(currentUser) {
    if (!currentUser) {
        console.warn('EchoGlobal: currentUser не передан!');
        return;
    }

    if (!window._echoSubscribed) {
        window.Echo.channel('notifications')
            .listen('.TicketCreated', (e) => {
                if (e.ticket.last_message.user_id !== currentUser.id) {
                    toast.success(`📬 Новое обращение от пользователя #${e.ticket.user.name}`, {
                        timeout: 6000,
                    });
                }
            })
            .listen('.TicketMessageAdded', (e) => {
                const msg = e.message;
                const isMessageFromOther = msg.user_id !== currentUser.id;

                if (isMessageFromOther && currentUser.is_parent) {
                    const isFromAdmin = msg.is_admin === true;

                    const sender = isFromAdmin
                        ? 'администратора школы'
                        : `пользователя #${msg.user?.name || 'неизвестно'}`;

                    toast.success(`📬 Новое сообщение от ${sender}`, {
                        timeout: 6000,
                    });
                }
            })
            .listen('.NewCertificateRequest', (e) => {
                 if (currentUser?.is_admin) {
                    toast.success(`📬 Новый запрос на справку для ученика #${e.certificate.child.name}`, {
                        timeout: 6000,
                    })
                }
            })
            .listen('.NewPersonalAffairRequest', (e) => {
                if (currentUser?.is_admin) {

                    toast.success(`📬 Новый запрос на формирование личного дела ученика #${e.personalAffair.child.name}`, {
                        timeout: 6000,
                    })
                }
            })
            .listen('.NewActivateStudentAccount', (e) => {
                if (currentUser?.is_admin) {
                    toast.success(`📬 Новый запрос на активацию личного кабинета ученика#${e.activateAccount[0].child.name}`, {
                        timeout: 6000,
                    })
                }
            })
        window._echoSubscribed = true;
    }
}
