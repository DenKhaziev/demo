<script setup>
const props = defineProps({
    label: String,
    modelValue: [String, Number],
    options: Array,
    optionLabel: {
        type: String,
        default: 'label'
    },
    optionValue: {
        type: String,
        default: 'value'
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="mb-4">
        <label class="block mb-1 font-medium text-gray-700">{{ label }}</label>
        <select
            :value="modelValue"
            @input="event => emit('update:modelValue', event.target.value)"
            class="border p-2 w-full rounded-lg shadow-lg"
            required
        >
            <option
                v-for="item in options"
                :key="item[optionValue]"
                :value="item[optionValue]"
            >
                {{ item[optionLabel] }}
            </option>
        </select>
    </div>
</template>
