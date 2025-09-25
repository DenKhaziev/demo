<script setup>
import { defineProps, defineEmits, onMounted, ref } from 'vue'
import IMask from 'imask'

const props = defineProps({
    modelValue: [String, Number],
    placeholder: String,
    label: String,
    error: String,
    type: {
        type: String,
        default: 'text'
    },
    required: {
        type: Boolean,
        default: false
    },
    class: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}

const input = ref(null)

onMounted(() => {
    if (props.type === 'date') {
        IMask(input.value, {
            mask: '00.00.0000'
        })
    }
})
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="block mb-1 text-sm text-gray-600">{{ label }}</label>
        <input
            ref="input"
            :value="modelValue"
            @input="updateValue"
            :placeholder="placeholder"
            :required="required"
            :type="type === 'date' ? 'text' : type"
            :class="[
                'border p-2 w-full rounded-lg shadow',
                error ? 'border-red-500' : 'border-gray-300',
                props.class
            ]"
        />
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
