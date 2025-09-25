<script setup>
import { useTestFormStore } from '@/Stores/testFormStore';
import SaveButton from '@/components/ui/SaveButton.vue';
import Card from '@/components/Admin/Layout/Card.vue';
import CreateTestQuestionBody from "@/components/Admin/Tests/CreateTestQuestionBody.vue";

const store = useTestFormStore();
const props = defineProps({
    test: Array,
});
</script>

<template>
    <form @submit.prevent="store.submitQuestion(props.test.id)">
        <Card class="mt-4">
            <CreateTestQuestionBody/>
        </Card>
        <SaveButton
            type="submit"
            class="my-6 py-2"
        >
            Сохранить вопросы
        </SaveButton>
    </form>
</template>

