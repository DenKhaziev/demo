<script setup>
const props = defineProps({
  test: Array
})
</script>

<template>
  <td class="border px-4 py-2 text-center">
    {{ test.subject.subject }}
  </td>
  <td class="border px-4 py-2 text-center">{{ test.type.type }}</td>
  <td class="border px-4 py-2 text-center">{{ test.type.type === 'demo' ? 0 : test.number_of_attempts }}</td>
  <td class="border px-4 py-2 text-center">{{ test.questions_count }}</td>
</template>

<style scoped>

</style>
