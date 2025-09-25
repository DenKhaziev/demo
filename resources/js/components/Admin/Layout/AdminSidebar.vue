<script setup>
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import AdminSidebarH3 from "@/components/Admin/Layout/AdminSidebarH3.vue";
import AdminSidebarUl from "@/components/Admin/Layout/AdminSidebarUl.vue";
import AdminSidebarLi from "@/components/Admin/Layout/AdminSidebarLi.vue";
import {onMounted, ref} from "vue";

const unread_ticket_count = ref(0)
const certificateRequestsCount = ref(0)
const personalAffairsRequestsCount = ref(0)
const activateStudentAccounts = ref(0)
const activeChildrenCount = ref(0)
onMounted(async () => {

    const updateCount = async (type) => {
        let url = null;

        switch (type) {
            case 'tickets':
                url = route('tickets.unread-count');
                break;
            case 'certificates':
                url = route('certificates.requests-count');
                break;
            case 'personalAffairs':
                url = route('personal.affairs.requests-count');
                break;
            case 'activateAccounts':
                url = route('activate.requests-count');
                break;
            case 'activeChildren':
                url = route('children.active-count');
                break;
            default:
                return;
        }

        const res = await axios.get(url);

        if (type === 'tickets') unread_ticket_count.value = res.data.count;
        if (type === 'certificates') certificateRequestsCount.value = res.data.count;
        if (type === 'personalAffairs') personalAffairsRequestsCount.value = res.data.count;
        if (type === 'activateAccounts') activateStudentAccounts.value = res.data.count;
        if (type === 'activeChildren') activeChildrenCount.value = res.data.count;

    };

    await updateCount('tickets');
    await updateCount('certificates');
    await updateCount('personalAffairs');
    await updateCount('activateAccounts')
    await updateCount('activeChildren')

    window.Echo.channel('notifications')
        .listen('.TicketCreated', () => updateCount('tickets'))
        .listen('.TicketMessageAdded', () => updateCount('tickets'))
        .listen('.NewCertificateRequest', () => updateCount('certificates'))
        .listen('.NewPersonalAffairRequest', () => updateCount('personalAffairs'))
        .listen('.NewActivateStudentAccount', () => updateCount('activateAccounts'));


    window.addEventListener('refresh-unread-count', () => updateCount('tickets'));

});

</script>

<template>
    <aside class="w-74 bg-white text-white h-2/3 p-4 rounded-lg shadow-lg">
        <CardWrapper>
            <AdminSidebarH3>Админка</AdminSidebarH3>
        </CardWrapper>
        <CardWrapper>
            <AdminSidebarH3>Родители</AdminSidebarH3>
            <AdminSidebarUl>
                <AdminSidebarLi :href="route('parents.index', { status: 'new' })" status="new">Новые</AdminSidebarLi>
                <AdminSidebarLi :href="route('parents.index', { status: 'active' })" status="active">Активные</AdminSidebarLi>
            </AdminSidebarUl>
        </CardWrapper>
        <CardWrapper>
            <AdminSidebarH3>Ученики</AdminSidebarH3>
            <AdminSidebarUl>
                <AdminSidebarLi :href="route('children.index', { status: 'new' })" status="new">Новые</AdminSidebarLi>
                <AdminSidebarLi :href="route('children.index', { status: 'active' })" status="active">Активные (<span class="text-green-300"> {{activeChildrenCount}}</span>)</AdminSidebarLi>
            </AdminSidebarUl>
        </CardWrapper>
        <CardWrapper>
            <AdminSidebarH3>Тесты</AdminSidebarH3>
            <AdminSidebarUl>
                <AdminSidebarLi :href="route('tests')">Тестирования</AdminSidebarLi>
                <AdminSidebarLi :href="route('tests.create')">Добавить тест</AdminSidebarLi>
            </AdminSidebarUl>
        </CardWrapper>
        <CardWrapper>
            <AdminSidebarH3>Предметы</AdminSidebarH3>
            <AdminSidebarUl>
                <AdminSidebarLi :href="route('subjects.index')">Список предметов</AdminSidebarLi>
                <AdminSidebarLi :href="route('subjects.create')">Добавить предмет</AdminSidebarLi>
            </AdminSidebarUl>
        </CardWrapper>
        <CardWrapper>
            <AdminSidebarH3>Остальное</AdminSidebarH3>
            <AdminSidebarUl>
                <AdminSidebarLi
                    :href="route('tickets.index')"
                    class="relative"
                    :class="{ 'animate-pulse-ring': unread_ticket_count > 0 }"
                >
                    Обращения
                    <span
                        v-if="unread_ticket_count > 0"
                        class="absolute -top-0.5 -right-2 bg-[#800020] text-white text-xs font-bold rounded-full px-2 py-1"
                    >
                        {{ unread_ticket_count }}
                    </span>
                </AdminSidebarLi>
                <AdminSidebarLi
                    :href="route('cert_requests.index')"
                    class="relative"
                >
                    Запросы на справки
                    <span
                        v-if="certificateRequestsCount > 0"
                        class="absolute -top-0.5 -right-2 bg-[#800020] text-white text-xs font-bold rounded-full px-2 py-1"
                    >
                        {{ certificateRequestsCount }}
                    </span>
                </AdminSidebarLi>
                <AdminSidebarLi
                    :href="route('personal.affairs.index')"
                    class="relative"

                >
                    Запросы на личные дела
                    <span
                        v-if="personalAffairsRequestsCount > 0"
                        class="absolute -top-0.5 -right-2 bg-[#800020] text-white text-xs font-bold rounded-full px-2 py-1"
                    >
                        {{ personalAffairsRequestsCount }}
                    </span>
                </AdminSidebarLi>
                <AdminSidebarLi
                    :href="route('activate.access.index')"
                    class="relative"
                >
                    Запросы на активацию ЛК
                    <span
                        v-if="activateStudentAccounts > 0"
                        class="absolute -top-0.5 -right-2 bg-[#800020] text-white text-xs font-bold rounded-full px-2 py-1"
                    >
                        {{ activateStudentAccounts }}
                    </span>

                </AdminSidebarLi>
            </AdminSidebarUl>
        </CardWrapper>
    </aside>
</template>

<style scoped>
@keyframes pulse-ring {
    0% {
        box-shadow: 0 0 0 0 rgba(128, 0, 32, 0.4); /* бордовый с прозрачностью */
    }
    70% {
        box-shadow: 0 0 0 10px rgba(128, 0, 32, 0); /* плавное исчезание */
    }
    100% {
        box-shadow: 0 0 0 0 rgba(128, 0, 32, 0);
    }
}

.animate-pulse-ring {
    animation: pulse-ring 1.5s infinite;
    border-radius: 0.5rem; /* если нужно округлить */
    transition: box-shadow 0.3s ease-in-out;
}
</style>
