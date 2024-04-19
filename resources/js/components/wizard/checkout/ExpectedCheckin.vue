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
    <VBtn
      class="mx-4"
      @click="selectall"
    >
      {{ $t('select all') }}
    </VBtn>

    <VBtn
      class="ml-4"
      @click="selected_guests = []"
    >
      {{ $t('unselect all') }}
    </VBtn>


    <VCol
      cols="12"
      sm="6"
      md="12"
    >
      <VTable
        height="300"
        fixed-header
      >
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
            <td
              colspan="8"
              style="text-align: center;"
            >
              {{ $t('No data available') }}
            </td>
          </tr>
          <tr
            v-for=" ( item, index ) in expected_checkins.Guests_Filter "
            v-else
            :key="item.id"
          >
            <td>
              <VCheckbox
                v-model="selected_guests[index]"
                :true-value="item.id"
                :false-value="false"
              />
            </td>
            <td>
              {{ item.res_no }}
            </td>
            <td>
              {{ item.room != null ? $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc : null }}
            </td>
            <td>
              {{ item.profile?.first_name + ' ' + item.profile?.last_name }}
            </td>
            <td>
              {{ item.no_of_pax }}
            </td>
            <td>
              {{ item.rm_rate }}
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCol>
    <VCol
      cols="12"
      sm="6"
      md="2"
    >
      <VBtn
        block
        class="mt-4"
        @click="perviousStep"
      >
        Previous step
      </VBtn>
    </VCol>
    <VCol
      cols="12"
      sm="6"
      md="3"
    />
    <VCol
      cols="12"
      sm="6"
      md="2"
    >
      <VBtn
        block
        class="mt-4"
        @click="excute(selected_guests)"
      >
        No show
      </VBtn>
    </VCol>
    <VCol
      cols="12"
      sm="6"
      md="3"
    />
    <VCol
      cols="12"
      sm="6"
      md="2"
    >
      <VBtn
        block
        class="mt-4"
        :disabled="disableNext"
        @click="nextStep"
      >
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
      selected_guests: [],
      expected_checkins: [],
      localSetting: null,
      AllSetData: null,
      SettingData: [],
      disableNext: true,
      myData: [],
      userData: [],
      AllDate:'in_date+date'
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    expected_checkins() {
      if (this.expected_checkins.Guests_Filter.length != 0 && this.expected_checkins.length != 0) {
        this.disableNext = true

      }
      else {
        this.disableNext = false
      }
    },
  },
  mounted() {
    const storedData = localStorage.getItem('userData')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.user
    }
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
      getExpectedCheckinAction() {
       axiosIns.post(`${window.location.origin}/api/guest_filter`, {

        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          is_checked_in: "0",
          is_reservation: 1,
          is_cancel: "0",
          "in_date+date": this.localSetting.hotel_date,
        },

      })
        .then(res => {
          if (res.data.data.Guests_Filter.length != 0) {
            this.expected_checkins = res.data.data
            this.selectall()
          }
          else {
            this.expected_checkins = res.data.data
            this.disableNext = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
   excute(selected_guests) {
      let guests_array = []
      for (let index = 0; index < selected_guests.length; index++) {
        if (selected_guests[index] != false) {
          guests_array.push(selected_guests[index])
        }
      }
      if (guests_array.length != 0) {
       axiosIns.post(`${window.location.origin}/api/noShow`, {
          guest_id: guests_array,
        })
          .then(res => {
            this.alert('No show submited successfully', true)
            this.getExpectedCheckinAction()
          })
          .catch(err => {
            console.log(err)
          })
        this.getExpectedCheckinAction()
      }
      else {
        this.getExpectedCheckinAction()
        this.alert('Please select atleast one guest', false)
      }

     this.getExpectedCheckinAction()
    },
    selectall() {
      this.selected_guests = this.expected_checkins.Guests_Filter.map(item => item.id)
    },
    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale == 'ar'?'top-start':'top-end',
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
    this.getExpectedCheckinAction()
  },
}
</script>
