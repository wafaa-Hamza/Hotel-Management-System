<script setup>
import AutoPost from '@/components/wizard/checkout/AutoPost.vue'
import ExpectedCheckin from '@/components/wizard/checkout/ExpectedCheckin.vue'
import OORDservice from '@/components/wizard/checkout/OORDservice.vue'
import ExtendStay from '@/components/wizard/checkout/ExtendStay.vue'
import Extras from '@/components/wizard/checkout/Extras.vue'
import DayClose from '@/components/wizard/checkout/DayClose.vue'
import CashierCloser from '@/components/wizard/checkout/CashierCloser.vue'
import Finish from '@/components/wizard/checkout/Finish.vue'


import googleHome from '@images/pages/google-home.png'
import iphone11 from '@images/pages/iphone-11.png'

const checkoutSteps = [
  {
    title: 'Day Close',
    icon: 'tabler-crown',
  },
  {
    title: 'Expected Checkins',
    icon: 'tabler-award-filled',
  },
  {
    title: 'Extend stay',
    icon: 'tabler-align-box-bottom-center-filled',
  },
  {
    title: 'OORD service',
    icon: 'tabler-3d-rotate',
  },
  {
    title: 'Cashier closer',
    icon: 'tabler-cash',
  },
  {
    title: 'Extras',
    icon: 'tabler-affiliate',
  },
  {
    title: 'Auto post',
    icon: 'tabler-atom-2-filled',
  },
  {
    title: 'Finish',
    icon: 'tabler-thumb-up',
  },
  
]



const currentStep = ref(0)
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>

      <VCardText>
        <!-- 👉 Stepper -->
        <AppStepper v-model:current-step="currentStep" class="checkout-stepper" :items="checkoutSteps" :size="12" />
        <!-- isActiveStepValid -->
      </VCardText>

      <VDivider />

      <VCardText>
        <!-- 👉 stepper content -->
        <VWindow v-model="currentStep" >
          <VWindowItem>
            <DayClose v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <ExpectedCheckin v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <ExtendStay v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <OORDservice v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <CashierCloser v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <Extras v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <AutoPost v-model:current-step="currentStep" />
          </VWindowItem>
          <VWindowItem>
            <Finish v-model:current-step="currentStep" />
          </VWindowItem>
        </VWindow>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
.checkout-stepper {
  .stepper-icon-step {
    .step-wrapper+svg {
      margin-inline: 3.5rem !important;
    }
  }
}
</style>


