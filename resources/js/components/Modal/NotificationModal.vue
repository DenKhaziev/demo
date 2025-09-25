<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const notifications = ref([]);
let idCounter = 0;

const lastShown = ref({
    success: null,
    warning: null,
});

const addNotification = (message, type = 'success') => {
    const id = idCounter++;
    notifications.value.push({ id, message, type });

    setTimeout(() => {
        notifications.value = notifications.value.filter(n => n.id !== id);
    }, 5000);
};

watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash.success) {
            addNotification(flash.success, 'success');
            usePage().props.flash.success = null; // ⚠️ удаляем после показа
        }
        if (flash.warning) {
            addNotification(flash.warning, 'warning');
            usePage().props.flash.warning = null;
        }
    },
    { immediate: true }
);
</script>

<template>
    <div class="fixed top-4 right-4 z-50 space-y-2">
        <TransitionGroup name="fade" tag="div">
            <div
                v-for="(notification, index) in notifications"
                :key="notification.id"
                :class="[
          'rounded-lg shadow-lg px-4 py-3 text-white w-72 animate-fade-in-out font-semibold mb-2',
          notification.type === 'success' ? 'bg-green-500' : 'bg-yellow-500'
        ]"
            >
                <div class="flex justify-between items-center">
                    <span>{{ notification.message }}</span>
                    <button @click="remove(index)" class="text-white ml-2">&times;</button>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
.animate-fade-in-out {
    animation: fadeInOut 0.3s ease-in-out;
}
@keyframes fadeInOut {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
