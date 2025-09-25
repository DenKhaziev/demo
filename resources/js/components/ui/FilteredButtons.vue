<script setup>
import FilterButton from "@/components/ui/FilterButton.vue";

const props = defineProps({
    filterType: String,
    onChange: Function
});
</script>

<template>
    <div class="mb-4 flex flex-wrap gap-2">
        <FilterButton :isActive="!filterType" :onClick="() => onChange(null)">Все тесты</FilterButton>
        <FilterButton :isActive="filterType === 'main'" :onClick="() => onChange('main')">Основные</FilterButton>
        <FilterButton :isActive="filterType === 'additional'" :onClick="() => onChange('additional')">Демо</FilterButton>
        <FilterButton :isActive="filterType === 'optional'" :onClick="() => onChange('optional')">Дополнительные</FilterButton>
    </div>
</template>
