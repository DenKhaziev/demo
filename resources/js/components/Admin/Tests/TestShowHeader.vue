<script setup>
import CardWrapper from "@/components/Admin/Layout/CardWrapper.vue";

defineProps({
    test: Array
})
</script>

<template>
    <CardWrapper>
    <div class="flex justify-between my-1">
        <h2 class="text-xl font-bold  ">Предмет:
            <span class="text-red-700">
                {{test.subject.subject}}
            </span>
        </h2>
        <h2 class="text-xl font-bold">Тип:
            <span class="text-red-700">
                {{test.type.label}}
            </span>
        </h2>
        <h2 class="text-xl font-bold">Класс:
            <span class="text-red-700">
                {{test.grade.class_number }}
            </span>
        </h2>
    </div>
    </CardWrapper>
</template>

<style scoped>

</style>
