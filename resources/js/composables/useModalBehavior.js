import { watchEffect, onUnmounted } from 'vue';

export function useModalBehavior(show, emit) {
    const handleKeydown = (e) => {
        if (e.key === 'Escape') emit('close');
    };

    const toggleScrollLock = (enabled) => {
        document.body.classList.toggle('overflow-hidden', enabled);
    };

    watchEffect(() => {
        if (show.value) {
            document.addEventListener('keydown', handleKeydown);
            toggleScrollLock(true);
        } else {
            document.removeEventListener('keydown', handleKeydown);
            toggleScrollLock(false);
        }
    });

    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeydown);
        toggleScrollLock(false);
    });
}
