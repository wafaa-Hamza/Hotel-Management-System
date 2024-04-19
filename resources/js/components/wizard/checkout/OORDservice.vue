<script setup>
const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
  },
  checkoutData: {
    type: null,
    required: true,
  },
})

const emit = defineEmits([
  'update:currentStep',
  'update:checkout-data',
])



const nextStep = () => {

  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

const perviousStep = () => {

  emit('update:currentStep', props.currentStep ? props.currentStep - 1 : 1)
}
</script>

<template>
  <VRow class="mt-5">
    <VBtn @click="selectall" class="mx-4">
      {{ $t('select all') }}
    </VBtn>
    <VBtn @click="selected = []" class="ml-4">
      {{ $t('unselect all') }}
    </VBtn>
    <VCol cols="12" sm="6" md="12">
      <VTable height="300" fixed-header>
        <thead>
          <tr>
            <th>
              {{ $t('select') }}
            </th>
            <th>
              {{ $t('Room') }}
            </th>
            <th>
              {{ $t('OORD type') }}
            </th>
            <th>
              {{ $t('End date') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="oord.length === 0">
            <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
          </tr>
          <tr v-else v-for=" ( item, index ) in oord " :key="item.id">
            <td>
              <VCheckbox v-model="selected[index]" :true-value="item.id" :false-value="false" />
            </td>
            <td>
              {{ item.room.rm_name_en }}
            </td>
            <td>
              {{ item.oord_type }}
            </td>
            <td>
              {{ item.end_date }}
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="perviousStep">
        {{ $t('Previous step') }}
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="3"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="excute(selected)">
        {{ $t('Extend one day') }}
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="3"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="nextStep" :disabled="disableNext">
        {{ $t('Next step') }}
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
      oord: [],
      localSetting: null,
      AllSetData: null,
      SettingData: [],
      disableNext: true,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    oord() {
      if (this.oord.length != 0) {
        this.disableNext = true
      }
      else {
        this.disableNext = false
      }
    },
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getOORDServiceAction() {
      await axiosIns.get(`${window.location.origin}/api/OrderAndService`)
        .then(res => {
          if (res.data.message != 'not found') {
            this.oord = res.data.data
            this.selectall()
          }
          else {
            this.disableNext = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    async excute(selected) {
      let oord_array = []
      for (let index = 0; index < selected.length; index++) {
        if (selected[index] != false) {
          oord_array.push(selected[index])
        }
      }
      console.log(oord_array)
      if (oord_array.length != 0) {
        await axiosIns.post(`${window.location.origin}/api/ToExtendOOrdServicesOneDay`, {
          oordServicesID: oord_array,
        })
          .then(res => {
            this.oord = []
            this.getOORDServiceAction()
            this.alert('OORD submited successfully', true)
          })
          .catch(err => {
            console.log(err)
          })
      }
      else {
        this.alert('Please select atleast one OORD', false)
      }
    },
    selectall() {
      this.selected = this.oord.map(item => item.id)
    },
    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
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
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    const SettingData = localStorage.getItem('keyinfo')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
    this.getOORDServiceAction()
  },
}
</script>
