<script setup>
const date = ref('')
</script>

<template>
  <AppDateTimePicker
    v-model="date"
    label="Default"
    :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
  />
</template>
