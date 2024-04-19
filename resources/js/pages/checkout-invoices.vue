<script setup>
</script>

<template>
  <VCard>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VContainer>
      <VForm ref="refForm" @submit.prevent="SearchReservation()">
        <VRow class="mb-2">
          <!-- <VCol cols="12" sm="6" md="6">
            <VRadioGroup v-model="inlineRadio" inline>
              <VRadio :label="$t('By arrival date')" value="1" />
              <VRadio :label="$t('By Created date')" value="0" />
            </VRadioGroup>
          </VCol> -->
          <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
            Please Enter right data
          </VSnackbar>

          <VCol cols="12" sm="6" md="3">
            <AppDateTimePicker v-model="date_from" :label="$t('Date from')"
                               :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"/>
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <AppDateTimePicker v-model="date_to" :label="$t('Date to')"
                               :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="res_no_from" :label="$t('Reservation number from')" type="number" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="res_no_to" :label="$t('Reservation number to')" type="number" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="guest_name" :label="$t('Guest name')" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VCombobox v-model="room" :label="$t('Room')" :items="rooms" item-title="rm_name_en" item-value="room"
              :menu-props="{ maxHeight: '200px' }" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="invoice_from" :label="$t('Invoice From')" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="invoice_to" :label="$t('Invoice To')" />
          </VCol>


          <VCol cols="12" sm="6" md="12" class="mt-6">
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
                {{ $t('Res no') }}
              </th>
              <th>
                {{ $t('Room') }}
              </th>
              <th>
                {{ $t('Guest name') }}
              </th>
              <th>
                {{ $t('In Date') }}
              </th>
              <th>
                {{ $t('Out Date') }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in searched_checkout_guests.Guests_Filter" :key="item.id" @click="selectreservation(item)"
              class="hover-pointer">
              <td>{{ item.id }}</td>
              <td v-if="$i18n.locale === 'en' && item.room != null">{{ item.room.rm_name_en }}</td>
              <td v-if="$i18n.locale !== 'en' && item.room != null">{{ item.room.rm_name_loc }}</td>
              <td>{{ item.profile.first_name + ' ' + item.profile.last_name }}</td>
              <td>{{ item.in_date }}</td>
              <td>{{ item.out_date }}</td>
            </tr>
          </tbody>
        </VTable>
      </VForm>
    </VContainer>
  </VCard>
</template>

<script>
import moment from 'moment'
import { mapActions, mapStores } from 'pinia'
import { useGeneralStore } from '../stores/GeneralStore'
import { useRoomStore } from '../stores/RoomStore'

export default {
  name: 'Guest',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      isSnackbarVisible: false,
      inlineRadio: '1',
      refForm: ref(),
      date_from: null,
      date_to: null,
      res_no_from: null,
      res_no_to: null,
      ref_no: '',
      company: '',
      segment: '',
      country: '',
      created_by: '',
      res_status: [],
      selected_status: [],
      guest_name: '',
      room: '',
      invoice_from: '',
      invoice_to: '',
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore, useRoomStore),


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
    searched_checkout_guests() {
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchCheckoutInvoicesAction', 'getAllusersAction', 'getReservationstatusesAction', 'getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'searchReservationsAction']),
    ...mapActions(useRoomStore, ['getRoomsAction']),
    Clear() {

      this.date_from = null
      this.date_to = null
      this.res_no_from = null
      this.res_no_to = null
      this.ref_no = ''
      this.invoice_from = ''
      this.invoice_to = ''
      this.guest_name = ''
      this.room = ''
    },


    // eslint-disable-next-line sonarjs/cognitive-complexity
    SearchReservation() {
      this.searchCheckoutInvoicesAction({
        guest_name: this.guest_name,
        start_date: this.date_from != null ? this.date_from : null,
        end_date: this.date_to != null ? this.date_to : this.date_from != '' ? this.date_from : null,
        res_no_from: this.res_no_from,
        res_no_to: this.res_no_to != null ? this.res_no_to : this.res_no_from,
        room_id: this.room.room_id,
        checked_out: 1,
      })

    },
    selectreservation(reservation) {
      this.$router.push({ name: `selectedFolio-folio`, params: { folio: reservation.id } })
    },
    alert() {
      this.$swal.fire({
        icon: 'error',
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        title: 'Please Enter right data',
        timer: 2000,
        showConfirmButton: false,

      })
    },

  },
  // eslint-disable-next-line vue/component-api-style
  created() {
    this.getRoomsAction()
    this.searchCheckoutInvoicesAction({
      checked_out: 1,
      start_date: moment().format("YYYY-MM-DD"),
      end_date: moment().format("YYYY-MM-DD"),
    })
  },

}
</script>

<style scoped>
.v-selection-control__input input {
  position: absolute;
  block-size: 50%;
  inline-size: 50%;
  inset-block-start: 9px;
  inset-inline-start: 9px;
  opacity: 1;
}

.custom-combobox .v-select-list {
  max-block-size: 50px;

  /* Set your desired fixed height here */
  overflow-y: auto;

  /* Enable vertical scrolling if the content exceeds the height */
}

.hover-pointer:hover {
  cursor: pointer;
}
</style>
