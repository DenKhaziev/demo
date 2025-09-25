<script setup>
const props = defineProps({
    modelValue: String,
    placeholder: String
})

const emit = defineEmits(['update:modelValue'])

const update = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <div class="mb-4 flex items-center">
        <input
            :value="modelValue"
            @input="update"
            :placeholder="placeholder"
            class="border-gray-400 shadow-xl rounded-md px-3 py-2 w-full focus:outline-none focus:ring focus:border-blue-300"
            type="text"
        >
        <button
            v-if="modelValue"
            @click="() => emit('update:modelValue', '')"
            class="ml-2 text-gray-500 hover:text-gray-700"
        >
            ✕
        </button>
    </div>
</template>
