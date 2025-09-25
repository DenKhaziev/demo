<script setup>
const props = defineProps({
    show: Boolean,
    forceSubmitAndLeave: Function,
});

const emit = defineEmits(['update:show']);

const close = () => emit('update:show', false);
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-semibold text-red-600 mb-4">Внимание</h2>
            <p class="mb-4">
                Если вы покинете тест, текущие ответы будут засчитаны и будет выставлена оценка.
            </p>
            <div class="flex justify-end space-x-2">
                <button @click="close" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Продолжить тест
                </button>
                <button @click="forceSubmitAndLeave" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Выйти и завершить
                </button>
            </div>
        </div>
    </div>
</template>
