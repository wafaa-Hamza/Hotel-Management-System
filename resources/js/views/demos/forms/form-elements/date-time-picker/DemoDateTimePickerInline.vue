<script setup>
const date = ref('')
</script>

<template>
  <AppDateTimePicker
    v-model="date"
    label="Inline"
    :config="{ inline: true , dateFormat: 'Y-m-d l' }"
  />
</template>
