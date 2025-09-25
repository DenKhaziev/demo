<script setup>
import {Link, router} from '@inertiajs/vue3';
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import UniversalH2Display from "@/components/ui/UniversalH2Display.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";
import DocumentLinks from "@/components/ui/DocumentLinks.vue";
import SaveButton from "@/components/ui/SaveButton.vue";
import DeleteButton from '@/components/ui/DeleteButton.vue';
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";




const props = defineProps({
    parent: Object,
    children: Array,
});
const editingId = ref(null)
const editValue = ref(null)
const showConfirmModal = ref(false);
const subjectToDelete = ref(null);

const payForChild = (child) => {
    router.put(route("children.pay", child.id), {}, {
        onSuccess: () => {
            child.is_payment = true;

        },
        preserveScroll: true,
        preserveState: true
    });
};

const deleteChild = (child) => {
    subjectToDelete.value = child;
    showConfirmModal.value = true;
};
const confirmDelete = () => {
    if (!subjectToDelete.value) return;

    const form = useForm({ test: subjectToDelete.value.id });

    form.delete(route('children.destroy', subjectToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            subjectToDelete.value = null;
        }
    });
};

const cancelDelete = () => {
    showConfirmModal.value = false;
    subjectToDelete.value = null;
};
const openOrCreateTicket = async () => {
    const openTicket = props.parent.tickets.find(t => t.status === 'open')

    if (openTicket) {
        router.visit(route('messages.index', openTicket))
    } else {
        router.visit(route('tickets.create', { user: props.parent.id }))
    }
}

const form = useForm({ grade: null }) // то, что отправим на бэкенд

const startEdit = (child) => {
    editingId.value = child.id
    editValue.value = child.grade_id
}

const cancelEdit = () => {
    editingId.value = null
    editValue.value = null
}

const saveGrade = (child) => {
    form.grade = editValue.value
    form.patch(route('parents.children.grade.edit', child.id), {
        preserveScroll: true,
        onSuccess: () => {
            cancelEdit()
        },
    })
}
</script>
<template>
    <Card class="w-full">
        <GoBackbutton />
        <CardHeader>Карточка родителя</CardHeader>
        <BlueButton class="mb-5" @click="openOrCreateTicket">Открыть тикет</BlueButton>
        <CardWrapper class="flex  justify-between rounded shadow-lg px-6 mb-3">
            <UniversalH2Display label="ID" :value="parent.id"/>
            <UniversalH2Display label="ФИО родителя:" :value="parent.name"/>
            <UniversalH2Display label="Телефон:" :value="parent.phone"/>
            <UniversalH2Display label="E-mail:" :value="parent.email"/>
        </CardWrapper>
        <table class="w-full border-collapse border border-gray-300 shadow-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="border border-gray-300 px-4 py-2 text-center">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Имя</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Класс</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Аттестация</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Файлы</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Оплата</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Удалить</th>

                </tr>
            </thead>
            <tbody>
                <tr v-for="child in children"
                    :key="child.id"
                    class="hover:bg-gray-100 cursor-pointer"
                    @click="() => router.visit(route('children.show', child.id))"
                >
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ child.id }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ child.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center" @click.stop>
                        <template v-if="editingId === child.id">
                            <input
                                type="number"
                                v-model.number="editValue"
                                class="w-16 border rounded px-2 py-1 text-center"
                                min="1" max="11"
                            />
                            <button class="ml-2" @click="saveGrade(child)">
                                ✅
                            </button>
                            <button class="ml-1" @click="cancelEdit">
                                ❌
                            </button>
                        </template>

                        <template v-else>
                            {{ child.grade_id }}
                            <button class="ml-2" @click="startEdit(child)" title="Редактировать">
                                ✏️
                            </button>
                        </template>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ child.attestation_type }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center" @click.stop>
                        <div v-if="child.documents && child.documents.length">
                            <div v-for="(doc, index) in child.documents" :key="index">
                                <DocumentLinks :doc="doc" :child="child"  child-id=""/>
                            </div>
                        </div>
                        <div v-else class="text-gray-400">Нет документов</div>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center" @click.stop>
                        <template v-if="child.is_payment">
                            ✅ <span class="text-green-600 font-semibold">Оплачено</span>
                        </template>
                        <template v-else>
                            🔴 <span class="text-red-500 font-semibold">Не оплачено</span>
                            <SaveButton
                                @click="()=>payForChild(child)"
                                class="ml-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 shadow-lg"
                            >
                                Оплатить
                            </SaveButton>
                        </template>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center" @click.stop>
                        <DeleteButton
                            @click="()=>deleteChild(child)"
                        >
                        </DeleteButton>
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
    <ConfirmModal
        :show="showConfirmModal"
        title="Подтверждение удаления"
        message="Будет произведено удаление ученика из базы данных вместе со всеми загруженными документами"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />
</template>

<style scoped>

</style>

