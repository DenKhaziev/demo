<script setup>
import { useTestFormStore } from "@/Stores/testFormStore";
import BaseInput from "@/components/ui/BaseInput.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import ImageUploaderCheckbox from "@/components/ui/ImageUploaderCheckbox.vue";
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";

const store = useTestFormStore();
</script>

<template>
    <CardWrapper v-for="(question, index) in store.form.questions" :key="index" class="mt-4 p-4 border rounded-lg shadow-lg">
        <BaseInput
            v-model="question.question"
            placeholder="Вопрос"
            class="border p-2 w-full rounded-lg shadow-lg mb-2"
            required
        />

        <ImageUploaderCheckbox
            v-model="question.has_image"
            :onFileChange="file => question.image = file"
        />

        <div
            v-for="(answer, aIndex) in question.answers"
            :key="aIndex"
            class="mt-2 flex items-center"
        >
            <BaseInput
                v-model="answer.answer"
                placeholder="Ответ"
                class="border p-2 w-full rounded-lg shadow-lg"
                required
            />
            <input type="checkbox" v-model="answer.is_correct" class="ml-2 rounded-lg shadow-lg" />
            <DeleteButton
                class="ml-5"
                @click="()=>store.removeAnswer(question, index)"
            />
        </div>

        <div class="flex gap-4 items-center mt-4">
            <BlueButton
                type="button"
                @click="()=>store.addAnswer(question)"
            >
                Добавить ответ
            </BlueButton>
            <DeleteButton
                @click="()=>store.removeQuestion(index)"
            />
        </div>
    </CardWrapper>
    <BlueButton
        type="button"
        class="mt-4"
        @click="()=>store.addQuestion()"
    >
        Добавить вопрос
    </BlueButton>
</template>

