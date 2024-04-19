<script setup>
const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
  },
})

const emit = defineEmits([
  'update:currentStep',
])

const nextStep = () => {
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}
</script>

<template>
  <VRow>
    <VCol cols="12" sm="6" md="12">
      <div style="height: 368px; display: flex; justify-content: center; align-items: center;">
        <h1>{{ $t('Welcome to Day close') }}</h1>
      </div>
    </VCol>

    <VCol cols="12" sm="6" md="10"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="nextStep" :disabled="disableNext">
        {{ $t('Next step') }}
      </VBtn>
    </VCol>
  </VRow>
</template>


