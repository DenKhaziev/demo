<script setup>
defineProps({
    question: String
})
</script>

<template>
    <img
        v-if="question.image_url"
        :src="question.image_url"
        alt="Изображение к вопросу"
        class=" h-auto rounded-lg shadow mt-3 mx-auto"
    />
</template>

<style scoped>

</style>
