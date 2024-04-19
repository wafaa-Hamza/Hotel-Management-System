<!-- eslint-disable vue/custom-event-name-casing -->
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
    <VBtn class="mx-4" @click="selectall">
      {{ $t('select all') }}
    </VBtn>
    <VBtn class="ml-4" @click="selected = []">
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
              {{ $t('User') }}
            </th>
            <th>
              {{ $t('Date') }}
            </th>
            <th>
              {{ $t('Time') }}
            </th>
            <th>
              {{ $t('Number of opens') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="cashier_closes.length === 0">
            <td colspan="8" style="text-align: center;">
              {{ $t('No data available') }}
            </td>
          </tr>
          <tr v-for=" ( item, index ) in cashier_closes " v-else :key="item.id">
            <td>
              <VCheckbox v-model="selected[index]" :true-value="item.id" :false-value="false" />
            </td>
            <td>
              {{ item.users[0].firstname + ' ' + item.users[0].lastname }}
            </td>
            <td>
              {{ spltiDate(item.open) }}
            </td>
            <td>
              {{ spltiTime(item.open) }}
            </td>
            <td>
              {{ item.no_of_opens }}
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
    <VCol cols="12" sm="6" md="3" />
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="excute(selected)">
        {{ $t('Excute') }}
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="3" />
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" :disabled="disableNext" @click="nextStep">
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
      cashier_closes: [],
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
    cashier_closes() {
      if (this.cashier_closes.length != 0) {
        this.disableNext = true
      }
      else {
        this.disableNext = false
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    spltiDate(date) {
      const dateTimeParts = date.split(" ")

      return dateTimeParts[0]
    },
    spltiTime(date) {
      const dateTimeParts = date.split(" ")

      return dateTimeParts[1]
    },
    async getCashierClosesAction() {
      await axiosIns.get(`${window.location.origin}/api/getOpenCashier`)
        .then(res => {
          if (res.data.message != 'not found') {
            this.cashier_closes = res.data
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
      let closes_array = []
      for (let index = 0; index < selected.length; index++) {
        if (selected[index] != false) {
          closes_array.push(selected[index])
        }
      }
      console.log(closes_array)
      if (closes_array.length != 0) {
        await axiosIns.post(`${window.location.origin}/api/closeCashiers`, {
          cashiersIDs: closes_array,
        })
          .then(res => {
            this.cashier_closes = []
            this.getCashierClosesAction()
            this.alert('cashier has been closed successfully', true)
          })
          .catch(err => {
            console.log(err)
          })
      }
      else {
        this.alert('Please select atleast one cashier', false)
      }
    },
    selectall() {
      this.selected = this.cashier_closes.map(item => item.id)
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
    this.getCashierClosesAction()
  },
}
</script>
