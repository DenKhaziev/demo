<script setup>
import TestImage from "@/components/Admin/Tests/TestImage.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";

const props = defineProps(
    {
        questions: Array
    }
)
</script>

<template>
    <tbody>
    <tr v-for="(question, index) in questions" :key="question.id" class="hover:bg-gray-100">
        <td class="border border-gray-300 px-4 py-2 text-center">{{index +1}}</td>
        <td class="border border-gray-300 px-4 py-2 text-center w-100">
            <div v-html="question.question"></div>
            <TestImage :question="question"/>
        </td>
        <td class="border border-gray-300 px-4 py-2 text-center">
            <ul>
                <li v-for="(answer, index) in question.answers" :key="answer.id" class="flex gap-5 mb-2 p-2 border border-gray-30 border-radius border-collapse rounded-lg shadow-md">
                    {{ index + 1 }}. <div v-html="answer.answer"></div>
                </li>
            </ul>
        </td>
        <td class="border border-gray-300 px-4 py-2 text-center">
            <ul>
                <li
                    v-for="answer in question.answers.filter(a => a.is_correct)"
                    :key="answer.id"
                    class="flex gap-3"
                >
                    ✅<div v-html="answer.answer"></div>
                </li>
            </ul>
        </td>
        <slot :question="question"/>
    </tr>
    </tbody>
</template>

<style scoped>

</style>
