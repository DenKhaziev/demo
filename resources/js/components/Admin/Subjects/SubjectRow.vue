<script setup>
import SubjectTableCell from "@/components/Admin/Subjects/SubjectTableCell.vue";

const props = defineProps({
    subjects: Array,
});
</script>

<template>
    <tbody>
        <tr v-for="subject in subjects" :key="subject.id" class="mb-4">
            <SubjectTableCell>
                <slot name="subject" :subject="subject" />
            </SubjectTableCell>
            <SubjectTableCell>
                <slot name="actions" :subject="subject" />
            </SubjectTableCell>
        </tr>
    </tbody>
</template>

<style scoped>

</style>
