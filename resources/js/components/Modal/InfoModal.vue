<script setup>
import {toRef} from "vue";
import {useModalBehavior} from "@/composables/useModalBehavior.js";

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Информация',
    },
    message: {
        type: String,
        required: true,
    },
})
const emit = defineEmits(['close']);
const showRef = toRef(props, 'show');
useModalBehavior(showRef, emit );

</script>

<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm z-40"
            @click="$emit('close')"
        >
            <div
                class="bg-white rounded-lg w-[600px] max-h-[80vh] overflow-y-auto shadow-lg p-6"
                ref="modalRef"
                @click.stop
            >
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">{{ title }}</h2>
                    <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <slot>
                    <p class="text-gray-700 text-lg leading-relaxed">{{ message }}</p>
                </slot>
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
