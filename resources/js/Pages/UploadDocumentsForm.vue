<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    childId: Number,         // передается в компонент
    initialGradeId: Number,  // опционально
})

const form = useForm({
    grade_id: props.initialGradeId || '',
    child_id: props.childId,
    document_birth: null,
    document_application_for_transfer: null,
    document_personal_of_processing_data: null,
    document_payment: null,
    document_from_9_graduate: null,
})

const show9GradeDoc = computed(() =>
    form.grade_id === 10 || form.grade_id === 11
)

const submit = () => {
    form.post(route('documents.upload'), {
        forceFormData: true, // важный флаг для загрузки файлов
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4 bg-white p-4 rounded shadow-md">
        <label class="block mb-2">child_id</label>
        <input v-model="form.child_id" type="number" min="1" max="101" class="border p-2 w-full mb-4" required/>
        <div>
            <label for="grade_id" class="block font-semibold">Класс</label>
            <select v-model="form.grade_id" id="grade_id" class="w-full border rounded p-2">
                <option disabled value="">Выберите класс</option>
                <option v-for="n in 11" :key="n" :value="n">{{ n }} класс</option>
            </select>
        </div>

        <div>
            <label class="block">Свидетельство о рождении</label>
            <input type="file" @change="form.document_birth = $event.target.files[0]" />
        </div>

        <div>
            <label class="block">Заявление о переводе</label>
            <input type="file" @change="form.document_application_for_transfer = $event.target.files[0]" />
        </div>

        <div>
            <label class="block">Согласие на обработку данных</label>
            <input type="file" @change="form.document_personal_of_processing_data = $event.target.files[0]" />
        </div>

        <div>
            <label class="block">Документ об оплате</label>
            <input type="file" @change="form.document_payment = $event.target.files[0]" />
        </div>

        <div v-if="show9GradeDoc">
            <label class="block">Документ об окончании 9 класса</label>
            <input type="file" @change="form.document_from_9_graduate = $event.target.files[0]" />
        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
        >
            Отправить
        </button>
    </form>
</template>
