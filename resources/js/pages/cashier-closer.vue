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
        <VForm ref="refForm" @submit.prevent="SearchReservation()">
          <VRow class="mb-2">
            <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
              Please Enter right data
            </VSnackbar>
            <VCol cols="12" sm="6" md="4" >
              <VCombobox v-model="user" :label="$t('users')" :items="users" item-title="firstname"
                         :menu-props="{ maxHeight: '200px' }" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <AppDateTimePicker v-model="date_from" :label="$t('Date')"
                                 :config="{ altInput: true,altFormat: 'Y-m-d l',dateFormat: 'Y-m-d'}"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VBtn class="float-end " type="submit" @click="refForm?.validate()">
                {{ $t('Search') }}
              </VBtn>
              <VBtn class="float-end mr-4" @click="Clear">
                {{ $t('Clear') }}
              </VBtn>
            </VCol>
          </VRow>
          <VTable height="500">
            <thead>
            <tr>
              <th>
                {{ $t('Cash') }}
              </th>
              <th>
                {{ $t('Cash out') }}
              </th>
              <th>
                {{ $t('Card') }}
              </th>
              <th>
                {{ $t('Transfer') }}
              </th>
              <th>
                {{ $t('Payment type name') }}
              </th>
              <th>
                {{ $t('Voucher') }}
              </th>
              <th>
                {{ $t('Room') }}
              </th>
              <th>
                {{ $t('Folio') }}
              </th>
              <th>
                {{ $t('Guest Name') }}
              </th>
              <th>
                {{ $t('Description') }}
              </th>
              <th>
                {{ $t('User') }}
              </th>
              <th>
                {{ $t('Date time') }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="cashier_closer.length == 0">
              <td colspan="12" style="text-align: center;">{{ $t('No data available') }}</td>
            </tr>
            <tr v-else v-for="(item,index) in cashier_closer" :key="item.id" :class="{'last-row-td': index == cashier_closer.length - 1}">
              <td >
                {{
                  (item.payment_type?.payment_modes_id == 1 && item.payment_type?.type == 'CR')||item.total ? item.total ? item.total_cash : item.payment_amount : null
                }}
              </td>
              <td>
                {{ (item.payment_type?.payment_modes_id == 1 && item.payment_type?.type == 'DR')||item.total ? item.total ? item.total_cash_out : item.payment_amount : null }}
              </td>
              <td>
                {{ item.payment_type?.payment_modes_id == 2 ||item.total ? item.total ? item.total_card : item.payment_amount : null }}
              </td>
              <td>
                {{ item.payment_type?.payment_modes_id == 3||item.total ? item.total ? item.total_transfer : item.payment_amount : null }}
              </td>
              <td>
                {{ $i18n.locale == 'en' ? item.payment_type?.name : item.payment_type?.name_loc }}
              </td>
              <td>
                {{ item.trans_no!=null?item.trans_no:null}}
              </td>
              <td>
                {{ $i18n.locale == 'en' ? item.room?.rm_name_en : item.room?.rm_name_loc }}
              </td>
              <td>
                {{ item.guest_id!=null?item.guest_id:null }}
              </td>
              <td>
                {{ item.guest_id!=null?item.guest?.profile.first_name + ' ' + item.guest?.profile.last_name:null }}
              </td>
              <td>
                {{ item.description!=null?item.description:null }}
              </td>
              <td>
                {{ item.created_by?.firstname }}
              </td>
              <td>
                {{ item.created_at!=null?moment(item.created_at).format('YYYY-MM-DD h:mm:ss a'):null }}
              </td>
            </tr>
            </tbody>
          </VTable>
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

export default {
  name: 'Cashier closer',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      SettingData: null,
      user: null,
      search: null,
      isSnackbarVisible: false,
      refForm: ref(),
      date_from: null,
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
    users() {
      if (this.users != null) {
        this.users.unshift({
          firstname: 'All users',
          id: 0
        })
      }
    },
    cashier_closer() {
      if (this.cashier_closer != null) {
        this.calculateTotals()
      }

    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchCashierCloser', 'searchCheckoutInvoicesAction', 'getAllusersAction', 'getReservationstatusesAction', 'getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'searchReservationsAction']),
    ...mapActions(useRoomStore, ['getRoomsAction']),
    Clear() {
      this.date_from = null
      this.user = null
    },
    SearchReservation() {
      if (this.user?.id == 0) {
        this.searchCashierCloser({
          Date_From: this.date_from != null ? this.date_from : null,
          Date_To: this.date_from != null ? this.date_from : null,
        })
      }
      else {
        this.searchCashierCloser({
          user_id: this.user?.id,
          Date_From: this.date_from != null ? this.date_from : null,
          Date_To: this.date_from != null ? this.date_from : null,
        })
      }

    },
    selectreservation(reservation) {
      this.$router.push({ name: `selectedFolio-folio`, params: { folio: reservation.id } })
    },
    calculateTotals() {
      
      let total_cash=0, total_cash_out=0, total_card=0, total_transfer=0

      for (let index = 0; index < this.cashier_closer.length; index++) {
        if (this.cashier_closer[index].payment_type.payment_modes_id == 1 && this.cashier_closer[index].payment_type.type == 'CR') {
          total_cash+= this.cashier_closer[index].payment_amount
        }
        if (this.cashier_closer[index].payment_type.payment_modes_id == 1 && this.cashier_closer[index].payment_type.type == 'DR') {
          total_cash_out+= this.cashier_closer[index].payment_amount
        }
        if (this.cashier_closer[index].payment_type.payment_modes_id == 2) {
          total_card+= this.cashier_closer[index].payment_amount
        }
        if (this.cashier_closer[index].payment_type.payment_modes_id == 3 ) {
          total_transfer+= this.cashier_closer[index].payment_amount
        }

      }
      this.cashier_closer.push({
        total: true,
        total_cash: total_cash,
        total_cash_out: total_cash_out,
        total_card: total_card,
        total_transfer: total_transfer,
      })
    },

  },
  // eslint-disable-next-line vue/component-api-style
  created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.date_from = this.SettingData.hotel_date
    this.getRoomsAction()
    this.searchCheckoutInvoicesAction({
      checked_out: 1,
      start_date: moment().format("YYYY-MM-DD"),
      end_date: moment().format("YYYY-MM-DD"),
    })
    this.getAllusersAction()

  },

}
</script>

<style scoped>
.v-selection-control__input input {
  cursor: pointer;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
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
