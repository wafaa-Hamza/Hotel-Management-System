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

const checkoutAddressDataLocal = ref(props.checkoutData)

const deliveryOptions = [
  {
    icon: { icon: 'tabler-user' },
    title: 'Standard',
    desc: 'Get your product in 1 Week.',
    value: 'free',
  },
  {
    icon: { icon: 'tabler-crown' },
    title: 'Express',
    desc: 'Get your product in 3-4 days.',
    value: 'express',
  },
  {
    icon: { icon: 'tabler-rocket' },
    title: 'Overnight',
    desc: 'Get your product in 1 day.',
    value: 'overnight',
  },
]

const resolveAddressBadgeColor = {
  home: 'primary',
  office: 'success',
}

const resolveDeliveryBadgeData = {
  free: {
    color: 'success',
    price: 'Free',
  },
  express: {
    color: 'secondary',
    price: 10,
  },
  overnight: {
    color: 'secondary',
    price: 15,
  },
}

const totalPriceWithDeliveryCharges = computed(() => {
  checkoutAddressDataLocal.value.deliveryCharges = 0
  if (checkoutAddressDataLocal.value.deliverySpeed !== 'free')
    checkoutAddressDataLocal.value.deliveryCharges = resolveDeliveryBadgeData[checkoutAddressDataLocal.value.deliverySpeed].price

  return checkoutAddressDataLocal.value.orderAmount + checkoutAddressDataLocal.value.deliveryCharges
})

const updateAddressData = () => {
  emit('update:checkout-data', checkoutAddressDataLocal.value)
}

const nextStep = () => {
  updateAddressData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

const perviousStep = () => {
  emit('update:currentStep', props.currentStep ? props.currentStep - 1 : 1)
}

watch(() => props.currentStep, updateAddressData)
</script>

<template>
  <div>
    <VRow class="mt-5">
      <VBtn @click="selectall" class="mx-4">
        {{ $t('select all') }}
      </VBtn>
      <VBtn @click="selected = []" class="ml-4">
        {{ $t('unselect all') }}
      </VBtn>
      <VCol cols="12" md="12">
        <VTable height="300" fixed-header class="text-no-wrap">
          <thead>
            <tr>
              <th class="text-uppercase">
                {{ $t('Select') }}
              </th>
              <th class="text-uppercase">
                {{ $t('Hotel Date') }}
              </th>
              <th class="text-uppercase text-center">
                {{ $t('Folio No') }}
              </th>
              <th class="text-uppercase text-center">
                {{ $t('Room') }}
              </th>
              <th class="text-uppercase text-center">
                {{ $t('Ledger Code') }}
              </th>
              <th class="text-uppercase text-center">
                {{ $t('Ledger Name') }}
              </th>
              <th class="text-uppercase text-center">
                {{ $t('Guest Amount') }}
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="PrechargeData.length === 0">
              <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
            </tr>
            <tr v-for="(item, index) in PrechargeData">
              <td>
                <VCheckbox v-model="selected[index]" :true-value="item.id" false-value="false" />
              </td>
              <td>
                {{ item.hotel_date }}
              </td>
              <td class="text-center">
                {{ item.guest.folio_no }}
              </td>
              <td class="text-center">
                {{ item.guest.room.rm_name_en }}
              </td>
              <td class="text-center">
                {{ item.ledger.code }}
              </td>
              <td class="text-center">
                {{ item.ledger.name }}
              </td>
              <td class="text-center">
                {{ item.amount }}
              </td>
            </tr>
          </tbody>
        </VTable>
      </VCol>
      <VCol cols="12" sm="6" md="2">
        <VBtn class="mt-4" @click="perviousStep" block>
          {{ $t('Previous page') }}
        </VBtn>
      </VCol>
      <VCol cols="12" sm="6" md="3"></VCol>
      <VCol cols="12" sm="6" md="2">
        <VBtn class="mt-4" @click="PushData(selected)" block>

          {{ $t('Excute') }}
        </VBtn>
      </VCol>
      <VCol cols="12" sm="6" md="3"></VCol>
      <VCol cols="12" sm="6" md="2">
        <VBtn class="mt-4" @click="nextStep" block>
          Next Page
        </VBtn>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selected: [],
      ips: [],
      URL: window.location.origin,
      getExpectedCheckOut: [],
      PrechargeData: [],
      Hotel_Date: '',
      dataCount: 0,
      para: [],
    }

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPrechargeData()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    insertAlert() {
      const Toast = this.$swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer)
          toast.addEventListener('mouseleave', this.$swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: 'success',
        title: 'Data Moved To Transaction successfully',
        color: 'green',
        timer: 5000,
      })
    },
    getPrechargeData() {
      axios.get(`${this.URL}/api/getPrechargeDataTransfearToTransaction`).then(res => {
        if (res.data.message != 'not found') {
          this.PrechargeData = res.data.data
          this.selectall()
        }
        else {
          this.PrechargeData = []
        }

      })
    },
    async PushData(array) {
      let extend_array = []
      for (let index = 0; index < array.length; index++) {
        if (array[index] != false) {
          extend_array.push(array[index])
        }
      }
      if (extend_array.length != 0) {
        await axios.post(`${this.URL}/api/storeFromPreChargeToTransaction`, {
          pre_charge_id: extend_array,
        }).then(res => {
          this.insertAlert()
          this.getPrechargeData()
        })
      }
      else {

      }

    },
    selectall() {
      this.selected = this.PrechargeData.map(item => item.id)
    },
  },
}
</script>
