/* stylelint-disable order/properties-order */
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

const perviousStep = () => {

  emit('update:currentStep', props.currentStep ? props.currentStep - 1 : 1)
}
</script>

<template>
  <VRow>

    <VCol cols="12" sm="6" md="12">
      <div style="height: 368px; display: flex; justify-content: center; align-items: center;"
        v-if="finish_clicked == false">
        <h1>Click finish to finalize day close</h1>
      </div>
      <div class="loading-overlay" v-else>
        <VProgressCircular :size="100" color="primary" indeterminate />
      </div>
    </VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="perviousStep">
        {{ $t('previous page') }}
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="8"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block color="success" class="mt-4" @click="finish">
        {{ $t('Finish') }}
      </VBtn>
    </VCol>
  </VRow>
</template>

<script>
import axiosIns from "@axios"

import Swal from 'sweetalert2'
import moment from 'moment'
import { mapStores, mapActions } from "pinia"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selected: [],
      cashier_closes: [],
      localSetting: null,
      AllSetData: null,
      SettingData: [],
      disableNext: true,
      finish_clicked: false,
      overlay: document.createElement('div'),
      settings: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    freezeWindow() {
      this.overlay.className = 'overlay'
      document.body.appendChild(this.overlay)
      document.body.style.overflow = 'hidden'
    },
     finish() {
       const headers = {
         Accept: 'application/json',
         Authorization: 'Bearer ' + localStorage.getItem("accessToken"),
       }
       const postData = null;

      this.freezeWindow()
      this.finish_clicked = true
       axiosIns.post(`${window.location.origin}/api/finalDayClose`, postData , { headers })
        .then(res => {
          console.log(localStorage.getItem("accessToken"))
          this.overlay.className = 'overlay'
          this.finish_clicked = false
          document.body.removeChild(this.overlay)
          document.body.style.overflow = 'auto'
          this.getSettingsAction()

          this.alert('Day close has been finished successfully', true)
          setTimeout(() => {
            this.$router.push({ name: `index` })
          }, 2000)
        })
        .catch(err => {
          this.overlay.className = 'overlay'
          this.finish_clicked = false
          document.body.removeChild(this.overlay)
          document.body.style.overflow = 'auto'
          this.alert(err, false)
          console.log(err)
        })

    },
    async getSettingsAction() {

      await axiosIns.get(`${window.location.origin}/api/settings`)
        .then(res => {
          this.settings = res.data
          console.log('setting',this.settings)
          localStorage.setItem('settings', JSON.stringify(this.settings))
          console.log(' this.settings', this.settings)
        })
        .catch(err => {
          console.log(err)
        })
    },
    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: type ? 'success' : 'error',
        title: message,
      })
    },
  },

}
</script>

<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  /* Semi-transparent background */
  z-index: 9999;
  /* A high z-index value to cover everything */
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}
</style>
