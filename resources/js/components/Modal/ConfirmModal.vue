<script setup>
import CancelButton from "@/components/ui/CancelButton.vue";

import {defineProps, defineEmits, onUnmounted, watch} from 'vue';
import BlueButton from "@/components/ui/BlueButton.vue";

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
});

const emit = defineEmits(['confirm', 'cancel']);

watch(() => props.show, (isOpen) => {
    if (isOpen) {
        document.body.classList.add('overflow-hidden');
    } else {
        document.body.classList.remove('overflow-hidden');
    }
});
onUnmounted(() => {
    document.body.classList.remove('overflow-hidden');
});
</script>

<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm z-40"
            @click="$emit('close')"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
                <p class="mb-6">{{ message }}</p>
                <div class="flex justify-center gap-4">
                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold"
                        @click="$emit('confirm')"
                    >
                        Удалить
                    </button>
                    <BlueButton
                        class="px-4 py-2"
                        @click="$emit('cancel')"
                    >
                        Отмена
                    </BlueButton>
                </div>
            </div>
        </div>
    </transition>

</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
