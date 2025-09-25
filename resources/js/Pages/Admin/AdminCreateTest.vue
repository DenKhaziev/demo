<script setup>

import CreateTestForm from "@/components/Admin/Tests/CreateTestForm.vue";
import {defineProps} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    testTypes: Array,
    subjects: Array,
    grades: Array
});
</script>

<template>
  <AdminLayout>
      <CreateTestForm v-bind="{ testTypes, subjects, grades }" />
  </AdminLayout>
</template>

<style scoped>

</style>
