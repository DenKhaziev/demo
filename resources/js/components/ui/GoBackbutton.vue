<script setup>
const props = defineProps({
    steps: {
        type: Number,
        default: 1
    },
    forceTo: {
        type: String,
        default: null
    }
});

const goBack = () => {
    if (props.forceTo) {
        // Переход на указанный URL с полной перезагрузкой
        window.location.href = props.forceTo;
        return;
    }

    if (window.history.length > props.steps) {
        window.history.go(-props.steps);
    } else {
        window.location.href = '/'; // fallback
    }
};
</script>

<template>
    <button
        @click="goBack"
        class="inline-block px-3 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-200 ease-in-out shadow-xl font-semibold mb-3 text-center"
    >
        Назад
    </button>
</template>
