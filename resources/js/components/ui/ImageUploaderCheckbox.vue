<script setup>
const props = defineProps({
    modelValue: Boolean,
    onFileChange: Function,
    label: {
        type: String,
        default: 'Вопрос с изображением'
    }
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex flex-col space-y-2 mt-2">
        <label class="flex items-center space-x-2">
            <input
                type="checkbox"
                :checked="modelValue"
                @change="event => emit('update:modelValue', event.target.checked)"
            />
            <span>{{ label }}</span>
        </label>

        <input
            v-if="modelValue"
            type="file"
            @change="event => onFileChange(event.target.files[0])"
            class="rounded-lg shadow-lg"
        />
    </div>
</template>
