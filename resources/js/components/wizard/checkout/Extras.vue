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
    <VBtn @click="clear" class="ml-4">
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
              {{ $t('Guest name') }}
            </th>
            <th>
              {{ $t('extra name') }}
            </th>
            <th>
              {{ $t('Amount') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="extras.length === 0">
            <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
          </tr>
          <template v-else v-for=" ( item, index ) in extras " :key="index">
            <tr v-for=" ( extra, i ) in item.extra_bed_meal " :key="i">
              <td>
                <VCheckbox v-model="selected[index][i]" :true-value="extra.id" :false-value="false"
                  @update:modelValue="selectedCheckbox(index, i)" />
              </td>
              <td>
                {{ item.room.rm_name_en }}
              </td>
              <td>
                {{ item.profile.first_name + ' ' + item.profile.last_name }}
              </td>
              <td>
                {{ extra.extra_bed_meal.name }}
              </td>
              <td>
                {{ extra.amount }}
              </td>
            </tr>
          </template>
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
        {{ $t('Excute') }}
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
      extras: [],
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
    extras() {
      if (this.extras.length != 0) {
        this.disableNext = true
      }
      else {
        this.disableNext = false
      }
    },
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getExtrasAction() {
      await axiosIns.get(`${window.location.origin}/api/getExteraOfCheckInGuest`)
        .then(res => {
          if (res.data.message != 'not found') {
            this.extras = res.data.data
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
      let extras_array = []
      for (let index = 0; index < this.extras.length; index++) {
        for (let i = 0; i < this.extras[index].extra_bed_meal.length; i++) {
          if (selected[index][i] != false) {
            extras_array.push(selected[index][i])
          }
        }
      }
      let final_array = []
      for (let index = 0; index < extras_array.length; index++) {
        if (extras_array[index] != null) {
          final_array.push(extras_array[index])
        }
      }

      if (final_array.length != 0) {
        await axiosIns.post(`${window.location.origin}/api/storeExtraqForGuestToTransaction`, {
          extraIDs: final_array,
        })
          .then(res => {
            this.extras = []
            this.getExtrasAction()
            this.alert('extras has been submited successfully', true)
          })
          .catch(err => {
            console.log(err)
          })
      }
      else {
        this.alert('Please select atleast one extra', false)
      }
    },
    clear() {
      for (let index = 0; index < this.extras.length; index++) {
        for (let i = 0; i < this.extras[index].extra_bed_meal.length; i++) {
          this.selected[index][i] = null
        }
      }
    },
    selectall() {
      for (let index = 0; index < this.extras.length; index++) {
        this.selected[index] = this.extras[index].extra_bed_meal.map(item => item.id)
      }
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
    selectedCheckbox(index, i) {
      if (this.selected[index][i] == false) {
        for (let y = 0; y < this.extras[index].extra_bed_meal.length; y++) {
          this.selected[index][y] = false
        }
      } else {
        for (let y = 0; y < this.extras[index].extra_bed_meal.length; y++) {
          this.selected[index][y] = this.extras[index].extra_bed_meal[y].id
        }
      }
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
    this.getExtrasAction()
  },
}
</script>
