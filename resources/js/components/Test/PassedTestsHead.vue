<script setup>
const props =defineProps({
    isAdmin: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <thead class="hidden md:table-header-group">
    <tr class="bg-gray-200 text-gray-700">
        <th class="border border-gray-300 px-4 py-2 text-center w-1/6">Предмет</th>
        <th class="border border-gray-300 px-4 py-2 text-center">Вид тестирования</th>
        <th class="border border-gray-300 px-4 py-2 text-center">Статус</th>
        <th class="border border-gray-300 px-4 py-2 text-center" v-if="isAdmin">Ответы</th>
        <th class="border border-gray-300 px-4 py-2 text-center w-1/10">Результат</th>
        <th class="border border-gray-300 px-4 py-2 text-center w-1/8" v-if="isAdmin">Действия</th>
    </tr>
    </thead>
</template>

<style scoped>

</style>
