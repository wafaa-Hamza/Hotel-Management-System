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
  <VRow>

    <VBtn @click="selectall" class="ml-4">
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
              {{ $t('Res no') }}
            </th>
            <th>
              {{ $t('Room no') }}
            </th>
            <th>
              {{ $t('Guest name') }}
            </th>
            <th>
              {{ $t('Pax') }}
            </th>
            <th>
              {{ $t('Rate') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="expected_checkins.length === 0">
            <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
          </tr>
          <tr v-else v-for=" ( item, index ) in expected_checkins " :key="item.id">
            <td>
              <VCheckbox v-model="selected[index]" :true-value="item.id" />
            </td>
            <td>
              <p v-if="$i18n.locale === 'en'">
                {{ item.rm_name_en }}
              </p>
              <p v-else>
                {{ item.rm_name_loc }}
              </p>
            </td>
            <td>
              <p v-if="$i18n.locale === 'en'">
                {{ item.room_type.rm_type_name }}
              </p>
              <p v-else>
                {{ item.room_type.rm_type_loc }}
              </p>
            </td>
            <td>
              <p v-if="$i18n.locale === 'en'">
                {{ item.floors.floor_name }}
              </p>
              <p v-else>
                {{ item.floors.floor_name_loc }}
              </p>
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="perviousStep">
        Previous step
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="3"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="excute">
        No show
      </VBtn>
    </VCol>
    <VCol cols="12" sm="6" md="3"></VCol>
    <VCol cols="12" sm="6" md="2">
      <VBtn block class="mt-4" @click="nextStep">
        Next step
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
      expected_checkins: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {


    rooms() {
      return this.roomStore.rooms
    },
    floors() {
      return this.roomStore.floors
    },



  },
  // eslint-disable-next-line vue/component-api-style
  watch: {


  },


  // eslint-disable-next-line vue/component-api-style
  methods: {
    selectall() {
      this.selected_rooms = this.filteredrooms.map(item => item.id)
    },
    updateRoomStatus() {
      const firstTwoLetters = this.room_status.status_code.substring(0, 2)
      const lastTwoLetters = this.room_status.status_code.substring(2)

      for (let index = 0; index < this.selected_rooms.length; index++) {
        if (this.selected_rooms[index] != false && this.selected_rooms[index] !== undefined)
          this.final_rooms.push(this.selected_rooms[index])
      }
      if (this.final_rooms.length === 0) {
        this.isSnackbarVisible = true
      }
      axiosIns.post(`${window.location.origin}/api/change-room-status`, {
        fo_status: firstTwoLetters,
        hk_status: lastTwoLetters,
        rooms: this.final_rooms,
      })
        .then(res => {
          this.insertAlert('Room status updated successfully')
          this.getRoomsAction()
          this.filter_status = null
          this.floor = null
          this.room_status = null
          this.final_rooms = []
          this.selected_rooms = []
        })
        .catch(err => {
          console.log(err)
        })


    },
    clear() {
      this.company = null
      this.in_date = null
      this.out_date = null
      this.results = []
      this.debit_total = null
      this.credit_total = null
      this.balance = null

    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },

  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {

  },
}
</script>