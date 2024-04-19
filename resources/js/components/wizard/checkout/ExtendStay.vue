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

const perviousStep = () => {
  emit('update:currentStep', props.currentStep ? props.currentStep - 1 : 1)
}

const nextStep = () => {
  updateAddressData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1)
}

watch(() => props.currentStep, updateAddressData)
</script>

<template>
  <div>
    <VRow class="mt-5">

      <VBtn v-model="allSelected" class="mx-4" @click="Select_All">
        {{ $t('Select All') }}
      </VBtn>
      <VBtn class="ml-4" @click="Un_Selected">
        {{ $t('Unselect all') }}
      </VBtn>
      <VCol cols="12" md="12">
        <VTable height="300" fixed-header class="text-no-wrap">
          <thead>
            <tr>
              <th class="text-uppercase">

                {{ $t('Extend Stay') }}
              </th>
              <th class="text-uppercase">

                {{ $t('Room number') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('Folio no') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('Guest Name') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('PAX') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('Rate') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('Balance') }}
              </th>
              <th class="text-uppercase text-center">

                {{ $t('check out') }}
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-if="getExpectedCheckOut.length === 0">
              <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
            </tr>
            <tr v-for="item in getExpectedCheckOut">
              <td>
                <VCheckbox v-model="selectedCheckbox" :value="item.id" />
              </td>

              <td>
                {{ item.room.rm_name_en }}
              </td>
              <td class="text-center">
                {{ item.folio_no }}
              </td>
              <td class="text-center">
                {{ item.profile.first_name }} {{ item.profile.last_name }}
              </td>
              <td class="text-center">
                {{ item.no_of_pax }}
              </td>
              <td class="text-center">
                {{ item.rate_code?.rate_code }}
              </td>
              <td class="text-center">
                {{ item.company.current_balance }}
              </td>
              <td class="text-center">
                {{ item.checked_out }}
              </td>
            </tr>
          </tbody>
        </VTable>

      </VCol>

      <VCol cols="12" sm="6" md="2">
        <VBtn block @click="perviousStep" class="mt-4">
          {{ $t('Previous page') }}
        </VBtn>
      </VCol>
      <VCol cols="12" sm="6" md="3"></VCol>
      <VCol  cols="12" sm="6" md="2" >
        <VBtn block @click="Extend" class="mt-4">
          {{ $t('Extend Stay') }}
        </VBtn>
      </VCol>
      <VCol cols="12" sm="6" md="3"></VCol>
      <VCol cols="12" sm="6" md="2">
        <VBtn block @click="nextStep" class="mt-4">
          {{ $t('Next Page') }}
        </VBtn>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios"
import Swal from 'sweetalert2'
export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selectedCheckbox: [],
      ips: null,
      URL: window.location.origin,
      getExpectedCheckOut: [],
      is: false,
      disableNext: true,
    }

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPrechargeData()
  },
  watch:
  {
    getExpectedCheckOut() {
      if (this.getExpectedCheckOut.length != 0) {
        this.disableNext = true
      }
      else {
        this.disableNext = false
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
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

    getPrechargeData() {
      axios.get(`${this.URL}/api/getExpectedCheckOut`).then(res => {
        if (res.data.message != 'not found') {
          this.getExpectedCheckOut = res.data.data
          this.Select_All()
        }
        else {
          this.disableNext = false
        }


      })
    },
    Extend() {
      axios.post(`${this.URL}/api/extendStay`, {
        guest_id: this.selectedCheckbox,
      }).then(res => {
        this.getExpectedCheckOut = []
        this.getPrechargeData()
        this.alert('Extend stay submited successfully', true)

      }).catch(err => console.log(err))



    },
    Un_Selected() {
      this.selectedCheckbox = []
    },
    Refresh() {
      this.getPrechargeData()
    },
    Select_All() {
      this.is = true
      if (this.is) {
        for (let i = 0; i < this.getExpectedCheckOut.length; i++) {
          this.ips = this.getExpectedCheckOut[i].id


        }


      }


    },
  },
}
</script>
<style>
/* width */
::-webkit-scrollbar {
  block-size: 10px;
  inline-size: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  border-radius: 10px;
  box-shadow: inset 0 0 5px grey;
}

/* Handle */
::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: mediumpurple;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, 60%);
  
}
</style>
