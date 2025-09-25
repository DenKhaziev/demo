<script setup>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {capitalizeFirstLetter} from "@/composables/capitalizeFirstLetter.js";
import {defineProps, onMounted, ref} from "vue";
import Card from "@/components/Admin/Layout/Card.vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import BaseLabel from "@/components/ui/BaseLabel.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";


const props = defineProps({
    subjects: Array,
});

const form = useForm({
        subject: '',
    }
)
const submit = () => {
    form.post(route('subjects.store'), {
        onSuccess: () => {
            form.reset();
        }
    })
};

// const logs = ref([])
//
// onMounted(() => {
//     logs.value.push('📡 Подключаемся к notifications...')
//     window.Echo.channel('notifications')
//         .listen('.TicketCreated', (e) => {
//             logs.value.push('💥 TicketCreated получено: ' + JSON.stringify(e.ticket))
//         });
// });

</script>

<template>
    <Card class="w-1/2">
        <CardHeader>Добавить новый предмет</CardHeader>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <BaseLabel
                    :for="subject"
                    text="Название предмета"
                />
                <BaseInput
                    v-model="form.subject"
                    id="subject"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Введите название предмета с большой буквы"
                    required
                />
            </div>
            <BlueButton
            >
                Добавить предмет
            </BlueButton>
        </form>

<!--        <div class="p-4 bg-gray-100 border rounded">-->
<!--            <h2 class="text-lg font-bold mb-2">🎧 Echo Debug</h2>-->
<!--            <ul>-->
<!--                <li v-for="(log, index) in logs" :key="index">{{ log }}</li>-->
<!--            </ul>-->
<!--        </div>-->
    </Card>
</template>

<style scoped>

</style>


