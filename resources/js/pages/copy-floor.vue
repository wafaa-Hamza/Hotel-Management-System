<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>


    <VCard>

      <VContainer>
        <VForm ref="refForm" @submit.prevent="copyFloor">
          <VRow>
            <VCol cols="12" sm="6" md="3">
              <VCombobox item-color="customColorValue" v-model="from_floor" :label="$t('From floor')" :items="from_floors" :rules="[requiredValidator]"
                         :item-title="$i18n.locale == 'en' ? 'floor_name' : 'floor_name_loc'" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="to_floor" :label="$t('To floor')" :items="to_floors" :rules="[requiredValidator]"
                         :item-title="$i18n.locale == 'en' ? 'floor_name' : 'floor_name_loc'" />
            </VCol>
            <VCol cols="12" sm="3" md="3">
              <VCombobox v-model="no_of_digits" :label="$t('Number of digits')" :items="digits"
                         :rules="[requiredValidator]"  item-color="customColorValue" />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="new_digit" :label="$t('New digit')" type="number" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="1">
              <VBtn class="float-end " type="submit" @click="refForm?.validate()">
                {{ $t('Submit') }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VContainer>
    </VCard>
  </div>
</template>

<script>
import { useGeneralStore } from '../stores/GeneralStore'
import { useRoomStore } from '../stores/RoomStore'
import { mapStores, mapActions } from 'pinia'
import moment from 'moment'
import axios from '@axios'
import Swal from 'sweetalert2'

export default {
  name: 'Cashier closer',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      SettingData: null,
      refForm: ref(),
      new_digit: null,
      no_of_digits: null,
      to_floor: null,
      from_floor: null,
      from_floors: [],
      to_floors: [],
      digits: [1, 2, 3],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore, useRoomStore),

    cashier_closer() {
      return this.generalStore.cashier_closer
    },
    reservations() {
      return this.generalStore.reservations
    },
    response() {
      return this.generalStore.response
    },
    users() {
      return this.generalStore.users
    },
    searched_checkout_guests() {
      return this.generalStore.searched_checkout_guests
    },
    rooms() {
      return this.roomStore.rooms
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchCashierCloser', 'searchCheckoutInvoicesAction', 'getAllusersAction', 'getReservationstatusesAction', 'getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'searchReservationsAction']),
    ...mapActions(useRoomStore, ['getRoomsAction']),
    Clear() {
      this.to_floor = null
      this.from_floor = null
      this.no_of_digits = null
      this.new_digit = null
    },
    async copyFloor() {
      Swal.fire({
        icon: 'warning',
        title: 'Are you sure you want to copy this floor?',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Copy`,
      }).then(async result => {
        if (result.isDenied) {
          await axios.post(`${window.location.origin}/api/copyRoomsFromFloorToAnother`,
            {
              FromFloor_id: this.from_floor?.id,
              toFloorID: this.to_floor?.id,
              newDegit: this.new_digit,
              numberOfDegit: this.no_of_digits,
            })
            .then(async res => {
              if (res.status != undefined) {
                this.$alert('Floor rooms has been copied successfully', true)
                this.clear()
                this.getFloorsWithoutRooms()
                this.getFloorsWithRooms()
              }
            })
        }

      })


    },
    getFloorsWithoutRooms() {
      axios.get(`${window.location.origin}/api/get-floors-without-rooms`)
        .then(res => {

          this.to_floors = res.data.floor_without_rooms
        })
        .catch(err => {
          console.log(err)
        })
    },
    getFloorsWithRooms() {
      axios.get(`${window.location.origin}/api/get-floors-with-rooms`)
        .then(res => {
          this.from_floors = res.data.floor_with_rooms
        })
        .catch(err => {
          console.log(err)
        })
    },


  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getFloorsWithRooms()
    this.getFloorsWithoutRooms()
  },

}
</script>

<style scoped>
.v-selection-control__input input {

  position: absolute;
  left: 9px;
  top: 9px;
  width: 50%;
  height: 50%;
  opacity: 1;
}

.custom-combobox .v-select-list {
  max-height: 50px;
  /* Set your desired fixed height here */
  overflow-y: auto;
  /* Enable vertical scrolling if the content exceeds the height */
}

.last-row-td {
  background-color: beige;
  /* Add any other styles you want for the last row here */
}
</style>
