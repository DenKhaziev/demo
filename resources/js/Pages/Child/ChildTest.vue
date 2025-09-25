<script setup>

import StudentLayout from "@/Layouts/StudentLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import StudentTestRunner from "@/components/Test/StudentTestRunner.vue";
const props = defineProps({
    questions: Array,
    test: Array,
    child: Array,
});
</script>

<template>
    <MainLayout>
        <StudentLayout>
            <div class="w-90% md:w-2/3 mx-auto mt-20">
                <StudentTestRunner :questions="questions" :testId="test.id" :childId="child.id"/>
            </div>
        </StudentLayout>
    </MainLayout>
</template>

<style scoped>

</style>
