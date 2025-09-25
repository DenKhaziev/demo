<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    isOpen: Boolean
});

const contentRef = ref(null);
const contentHeight = ref(0);
let observer = null;

const updateHeight = () => {
    if (contentRef.value) {
        contentHeight.value = contentRef.value.scrollHeight;
    }
};

watch(() => props.isOpen, (val) => {
    if (val) {
        updateHeight();
    } else {
        contentHeight.value = 0;
    }
});

onMounted(() => {
    updateHeight();

    // Автоматический пересчёт через наблюдатель
    observer = new MutationObserver(() => {
        if (props.isOpen) {
            updateHeight();
        }
    });

    if (contentRef.value) {
        observer.observe(contentRef.value, {
            childList: true,
            subtree: true,
        });
    }
});

onBeforeUnmount(() => {
    if (observer) {
        observer.disconnect();
    }
});
</script>

<template>
    <div
        ref="contentRef"
        class="overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.68, -0.55, 0.265, 1.55)]"
        :style="{
            maxHeight: props.isOpen ? contentHeight + 'px' : '0px'
        }"
    >
        <slot />
    </div>
</template>

<style scoped>
</style>
