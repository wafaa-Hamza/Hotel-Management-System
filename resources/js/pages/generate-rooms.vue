<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
const refForm = ref()
const form = ref()

</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>

    <VCard>
      <VContainer>
        <VForm ref="refForm">
          <VRow>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="from_floor" :label="$t('From floor')" :items="to_floors" :rules="[requiredValidator]"
                :item-title="$i18n.locale == 'en' ? 'floor_name' : 'floor_name_loc'" clearable />
            </VCol>
            <VCol cols="12" sm="3" md="3">
              <VTextField v-model="room_from" :label="$t('Room no from')" type="number" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="3" md="3">
              <VTextField v-model="room_to" :label="$t('Room no to')" type="number" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="1">
              <VBtn @click="Clear">
                {{ $t('Clear') }}
              </VBtn>
            </VCol>
            <VCol cols="12" sm="6" md="1">
              <VBtn class="float-end" type="submit"
                @click.prevent="refForm?.validate().then(res => { res.valid ? generateRooms() : null })">
                {{ $t('Generate') }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
        <br>
        <br>
        <br>
        <br>

        <VRow v-if="rooms_count != 0">
          <VCol cols="12" sm="6" md="4">
            <VCombobox v-model="selected_room_type" :items="room_types"
              :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'" clearable
              :label="$t('Room type')" />
          </VCol>
          <VCol cols="12" sm="6" md="4">
            <VCombobox v-model="selected_room_view" :items="room_views"
              :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'" clearable :label="$t('Room view')" />
          </VCol>
        </VRow>
        <VForm ref="form">
          <VTable class="mt-5" height="300px">
            <thead>
              <tr>
                <th>
                  {{ $t("Select") }}
                </th>
                <th>
                  {{ $t("Room no") }}
                </th>
                <th>
                  {{ $t("Room type") }}
                </th>
                <th>
                  {{ $t("Room view") }}
                </th>
                <th>
                  {{ $t("Floor") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="rooms_count == 0">
                <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
              </tr>
              <tr v-else v-for="(item, index) in rooms_count" :key="index">
                <td>
                  <VBtn @click="roomSelected(index)">
                    <VIcon icon="tabler-edit" size="22" />
                  </VBtn>
                </td>
                <td>
                  <Vlabel v-model="room[index]" readonly>{{ room[index] }} </Vlabel>
                </td>
                <td>
                  <VCombobox v-model="room_type[index]" :rules="[requiredValidator]" readonly
                    :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'" />
                </td>
                <td>
                  <VCombobox v-model="room_view[index]" readonly
                    :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'" />
                </td>
                <td>
                  {{ $i18n.locale == 'en' ? from_floor.floor_name : from_floor.floor_name_loc }}
                </td>
              </tr>
            </tbody>
          </VTable>
          <VBtn class="float-end mb-5" type="submit"
            @click.prevent="form?.validate().then(res => { res.valid ? submitRooms() : null })">
            {{ $t('Submit') }}
          </VBtn>
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
      selected_room_view: null,
      selected_room_type: null,
      selected: [],
      pax: [],
      room_type: [],
      room: [],
      rooms_count: 0,
      room_to: null,
      room_from: null,
      from_floor: null,
      SettingData: null,
      refForm: ref(),
      room_view: [],
      room_views: [],
      rooms: [],
      to_floors: [],
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
    room_types() {
      return this.roomStore.room_types
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchCashierCloser', 'searchCheckoutInvoicesAction', 'getAllusersAction', 'getReservationstatusesAction', 'getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'searchReservationsAction']),
    ...mapActions(useRoomStore, ['getRoomsAction', 'getRoomTypesAction']),

    roomSelected(index) {

      if (this.room_type[index] != null || this.room_view[index] != null) {
        this.room_type[index] = null
        this.room_view[index] = null
      }
      else {
        this.room_type[index] = this.selected_room_type
        this.room_view[index] = this.selected_room_view
      }


    },
    async getAllRoomView() {
      await axios.get(`${window.location.origin}/api/viewGarden`).then(response =>
        (this.room_views = response.data.data))

    },
    Clear() {
      this.from_floor = null
      this.room_from = null
      this.room_to = null
      this.rooms_count = 0
      this.room_view = []
      this.room_type = []
      this.room = []

    },
    generateRooms() {
      this.rooms_count = this.room_to - (this.room_from - 1)
      for (let index = 0; index < this.rooms_count; ++index) {
        this.room[index] = parseInt(this.room_from) + parseInt(index)
        this.room_type[index] = null
        this.room_view[index] = null
      }
    },
    async submitRooms() {
      let rooms_array = []
      for (let index = 0; index < this.rooms_count; index++) {
        rooms_array.push({
          "room_name_en": this.room[index],
          "room_name_loc": this.room[index],
          "room_type_id": this.room_type[index]?.id,
          "room_max_pax": this.room_type[index]?.def_pax,
          "floor_id": this.from_floor.id,
          "room_phone_no": this.room[index],
          "room_phone_ext": this.room[index],
          "room_key_code": this.room[index],
          "room_key_options": this.room[index],
          "view_id": this.room_view?.id,
          "fo_status": "VA",
          "hk_stauts": "CL",
          "room_active": "1",
          "sort_order": "1",
        })
      }
      await axios.post(`${window.location.origin}/api/storeMoreOneRoom`,
        {
          rooms: rooms_array,
        })
        .then(response => {
          if (response.status == 200) {
            this.$alert('rooms created successfully', true)
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
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getFloorsWithoutRooms()
    this.getRoomTypesAction()
    this.getAllRoomView()
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
