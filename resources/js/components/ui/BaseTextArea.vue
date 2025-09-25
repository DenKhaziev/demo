<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    modelValue: String,
    placeholder: String,
})
const emit = defineEmits(['update:modelValue'])

const textareaRef = ref(null)

const resize = () => {
    const el = textareaRef.value
    if (!el) return
    el.style.height = 'auto' // сбрасываем
    el.style.height = el.scrollHeight + 'px' // ставим по содержимому
}

onMounted(() => resize())
</script>

<template>
  <textarea
      ref="textareaRef"
      class="border p-2 w-full rounded-lg resize-none overflow-hidden"
      :placeholder="placeholder"
      :value="modelValue"
      @input="e => { emit('update:modelValue', e.target.value); resize() }"
  />
</template>
