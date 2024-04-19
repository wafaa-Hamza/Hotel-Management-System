<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VRow>
        <VCol cols="12" sm="6" md="5">
          <AppDateTimePicker v-model="start_date" :label="$t('start date')"
                             :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
        </VCol>
        <VCol cols="12" sm="6" md="5">
          <AppDateTimePicker v-model="end_date" :label="$t('end date')"
                             :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
        </VCol>
        <VCol cols="12" sm="6" md="1" class="mt-6">
          <VBtn @click="clear">
            {{ $t('Reset') }}
          </VBtn>
        </VCol>
        <VCol cols="12" sm="6" md="1" class="mt-6">
          <VBtn @click="checkAvailabilty">
            {{ $t('Search') }}
          </VBtn>
        </VCol>


        <br>
        <br>
        <br>
        <VCol cols="12" sm="6" md="12">

          <VTable fixed-header>
            <thead>
              <tr>
                <th>
                  {{ $t('Date') }}
                </th>

                <th>
                  {{ $t('Total') }}
                </th>
                <th>
                  {{ $t('Arrival') }}
                </th>
                <th>
                  {{ $t('Out') }}
                </th>
                <th>
                  {{ $t('OC') }}
                </th>
                <th>
                  {{ $t('%') }}
                </th>
                <th>
                  {{ $t('VA') }}
                </th>
                <th v-if="availabilty.length !== 0" v-for=" ( item ) in availabilty[0].availablityRoomTypeCount "
                  :key="item.id">
                  {{ item.nameOfType }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="availabilty.length === 0">
                <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
              </tr>
              <tr v-else v-for=" ( item, index ) in availabilty " :key="index">
                <td>
                  {{ item.HDate }}
                </td>
                <td>
                  {{ item.total }}
                </td>
                <td>
                  {{ item.arrival }}
                </td>
                <td>
                  {{ item.out }}
                </td>
                <td>
                  {{ item.oc }}
                </td>
                <td>
                  {{ item['%'] }}
                </td>
                <td>
                  {{ item.va }}
                </td>
                <template v-if="availabilty.length !== 0">
                  <td v-for=" ( itemm ) in item.availablityRoomTypeCount " :key="itemm.id">
                    {{ itemm.countOfType }}
                  </td>
                </template>
              </tr>
            </tbody>
          </VTable>
        </VCol>
      </VRow>
      <!--     <VRow v-if="room_statuses.length !== 0">
        <VCol cols="12" sm="6" md="11"></VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="getRoomsStatus">
            Search
          </VBtn>
        </VCol>
      </VRow> -->
    </VCard>
    <VSnackbar v-model="isSnackbarVisible" :timeout="2000" location="top">
      Please select Enter In date and Out date
    </VSnackbar>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import moment from 'moment'
import { useRoomStore } from "../stores/RoomStore"
import { mapStores, mapActions } from "pinia"
import AppDateTimePicker from '@/@core/components/app-form-elements/AppDateTimePicker.vue'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      availabilty: [],
      selected_rooms: [],
      room_status: null,
      final_rooms: [],
      disable_submit: true,
      isSnackbarVisible: false,
      colors: [],
      filter_status: null,
      floor: null,
      start_date: null,
      end_date: null,
      rooms_statuses: [],
      dates_array: [],
      room: null,
      room_type: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useRoomStore),
    rooms() {
      return this.roomStore.rooms
    },
    room_types() {
      return this.roomStore.room_types.filter(room => {
        return room.id != 1
      })
    },
    floors() {
      return this.roomStore.floors
    },
    // eslint-disable-next-line vue/return-in-computed-property
    room_statuses() {
      return this.roomStore.room_statuses
    },
    // eslint-disable-next-line vue/return-in-computed-property
    filteredrooms() {
      if (this.room != null) {
        return this.rooms.filter(room => {
          return room.id == this.room.id
        })
      }
      else if (this.room_type != null) {
        return this.rooms.filter(room => {
          return room.room_type.id == this.room_type.id
        })
      }
      else {
        return this.rooms
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction", "getRoomStatusAction", "getFloorsAction"]),

    getRoomColor(index) {
      let status
      if (index != 'OO' && index != 'OS') {
        status = index + 'CL'
      }
      else {
        status = index
      }
      for (let index = 0; index < this.room_statuses.length; index++) {
        if (this.room_statuses[index].status_code === status) {
          return this.room_statuses[index].color
        }
      }
    },

    checkAvailabilty() {
      axiosIns.post(`${window.location.origin}/api/availabilityScreenData`, {
        in_date: this.start_date,
        out_date: this.end_date,
      })
        .then(res => {
          this.availabilty = res.data.data
          console.log(this.availabilty)
        })
        .catch(err => {
          console.log(err)
        })
    },
    clear() {
      this.start_date = moment().format("YYYY-MM-DD")
      this.end_date = moment().format("YYYY-MM-DD")
      this.availabilty = []
    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      });
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRoomStatusAction()
    this.getFloorsAction()
    this.start_date = moment().format("YYYY-MM-DD")
    this.end_date = moment().format("YYYY-MM-DD")
  },
  components: { AppDateTimePicker }
}
</script>


