<script setup>
import { requiredValidator } from "@validators"
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard>
      <VContainer>
        <VRow>
          <VCol cols="12" sm="6" md="9">
            <VTextField v-model="reservation_number" :label="$t('Reservation number')" @keydown.enter="SearchReservation"
              clearable />
          </VCol>

          <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
            {{ $t("Please enter data") }}
          </VSnackbar>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="Clear">
              {{ $t("Clear") }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VBtn @click="SearchReservation">
              {{ $t("Search Reservation") }}
            </VBtn>
          </VCol>
        </VRow>
      </VContainer>
    </VCard>

    <VCard class="mt-6" :disabled="noProfileSelected || locked">
      <VCardTitle>
        <VRow>
          <VCol cols="12" sm="6" md="4">
            <span> {{ $t("Rooms") }}</span>
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <span>{{ $t("Total PAX") }}: {{ no_of_pax }} </span>
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <span>{{ $t("Total Rooms") }}: {{ rows }} </span>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VBtn @click="autoAssign">
              {{ $t("Auto assign rooms") }}
            </VBtn>
          </VCol>
        </VRow>
      </VCardTitle>
      <VCardText class="mt-3">
        <VTable class="table-container" height="300px">
          <thead>
            <tr>
              <th>
                {{ $t("Room no") }}
              </th>
              <th>
                {{ $t("Room type") }}
              </th>
              <th>
                {{ $t("Rate code") }}
              </th>
              <th>
                {{ $t("PAX") }}
              </th>
              <th>
                {{ $t("Rate") }}
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-if="flattenedRows.length === 0">
              <tr>
                <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
              </tr>
            </template>
            <template v-else v-for="(item, index) in flattenedRows" :key="index">

              <tr>
                <td style="display: flex;padding-top: 2%;">
                  <AppCombobox v-model="room[index]" :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'"
                    item-value="room" style="width: 150px;" readonly />
                  <div>
                    <VIcon class="ml-2 mr-2 mt-2" @click="selectRoom(index, group_room_type[index].id)"
                      @focus="setTouchedField(index)">
                      tabler-ballpen
                    </VIcon>
                    <VIcon class="mt-2" @click="removeRoom(index, group_room_type[index].id)"
                      @focus="setTouchedField(index)">
                      tabler-trash
                    </VIcon>
                  </div>
                </td>
                <td>
                  <AppCombobox v-model="group_room_type[index]" style="width: 150px;" readonly
                    :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'" />
                </td>
                <td>
                  <AppCombobox v-model="group_rate_code[index]" :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                    style="width: 150px;" readonly />
                </td>
                <td>
                  <VTextField v-model="pax[index]" type="number" style="width: 100px;" min="1" readonly />
                </td>
                <td>
                  <VTextField v-model="rate[index]" type="number" style="width: 100px;" min="1" readonly />
                </td>

              </tr>
            </template>
          </tbody>
        </VTable>
        <VBtn class="mt-8 float-end mb-5" @click="submitAllRooms">
          {{ $t("Submit") }}
        </VBtn>
      </VCardText>
    </VCard>
    <VDialog v-model="roomDialog" width="1024">
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol cols="12" sm="6" md="6">
              <span class="text-h5">{{ $t("Select Room") }}</span>
            </VCol>
          </VRow>
        </VCardTitle>
        <VCardText>
          <VTable height="600">
            <thead>
              <tr>
                <th>
                  {{ $t("Room number") }}
                </th>
                <th>
                  {{ $t("Room name") }}
                </th>
                <th>
                  {{ $t("Number of pax") }}
                </th>
                <th>
                  {{ $t("Floor") }}
                </th>
                <th>
                  {{ $t("Room type") }}
                </th>
                <th>
                  {{ $t("Selected") }}
                </th>
                <th>
                  {{ $t("Select") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in filterData" :key="item.id">
                <td>{{ item.id }}</td>
                <td>
                  <p v-if="$i18n.locale === 'en'">
                    {{ item.rm_name_en }}
                  </p>
                  <p v-else>
                    {{ item.rm_name_loc }}
                  </p>
                </td>
                <td>{{ item.rm_max_pax }}</td>

                <td>
                  <p v-if="$i18n.locale === 'en'">
                    {{ item.floors.floor_name }}
                  </p>
                  <p v-else>
                    {{ item.floors.floor_name_loc }}
                  </p>
                </td>
                <td>
                  <p v-if="$i18n.locale === 'en'">
                    {{ item.room_type.rm_type_name }}
                  </p>
                  <p v-else>
                    {{ item.room_type.rm_type_name_loc }}
                  </p>
                </td>
                <td>
                  <VChip class="ma-2">
                    <VIcon v-if="item.selected" icon="mdi-check" color="success" />
                    <VIcon v-if="!item.selected" icon="mdi-close-octagon" color="error" />
                  </VChip>
                </td>
                <td>
                  <VBtn color="primary" :disabled="item.selected" @click="submitRoom(item)">
                    <VIcon icon="mdi-check" />
                  </VBtn>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>
          <VBtn color="blue-darken-1" variant="text" class="float-right" @click="roomDialog = false">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script>
import axios from "@axios"
import moment from "moment"
import { mapActions, mapStores } from "pinia"
import Swal from "sweetalert2"
import { useGeneralStore } from "../stores/GeneralStore"
import { useGuestStore } from "../stores/GuestStore"
import { useRatecodeStore } from "../stores/RatecodeStore"
import { useRoomStore } from "../stores/RoomStore"
import { useExtrasStore } from "../stores/ExtrasStore"


export default {
  name: "block-rooms",
  // eslint-disable-next-line vue/component-api-style
  data() {

    return {
      reservation_number: null,
      rooms: [],
      rooms_checked: [],
      locked: null,
      extra_amount: null,
      selected_extra: null,
      flattenedRows: [],
      rooms_array: [],
      changedIndexes: [],
      room_type_filter: null,
      selected_room_index: null,
      roomDialog: false,
      room: [],
      pax: [],
      rate: [],
      group_rate_code: [],
      group_room_type: [],
      no_of_pax: 0,
      updated: [],
      groupGuest: [],
      reservation: [],
      guest_id: [],
      rows: 0,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(
      useRatecodeStore,
      useRoomStore,
      useGuestStore,
      useGeneralStore,
      useExtrasStore,
    ),
    filterData() {
      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == this.room_type_filter)

      return filteredRoomType ? filteredRoomType.rooms : []
    },
    available_roomtypes() {
      return this.guestStore.available_rooms
    },
    rate_codes() {
      return this.ratecodeStore.rate_codes
    },

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    // eslint-disable-next-line sonarjs/cognitive-complexity
    reservation() {
      if (this.reservation != null) {
        this.getAvaliableRooms(this.reservation.in_date, this.reservation.out_date)
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    available_roomtypes() {
      if (this.available_roomtypes != null) {
        this.groupGuests = this.reformChildrenReservation()
        this.flattenedRows = this.groupGuests.length
        this.rows = this.groupGuests.length
        for (let index = 0; index < this.groupGuests.length; index++) {
          this.group_room_type[index] = this.available_roomtypes.find(roomType => roomType.id == this.groupGuests[index].roomType.id)
          this.pax[index] = this.groupGuests[index].no_of_pax
          this.no_of_pax += this.groupGuests[index].no_of_pax
          this.rate[index] = this.groupGuests[index].rm_rate
          this.total_room_rate += this.rate[index]
          if (this.groupGuests[index].rateCode != null) {
            this.selected_rate_code = this.rate_codes.find(item => item.id == this.groupGuests[index].rateCode.id)
          }
          this.group_rate_code[index] = this.selected_rate_code
          this.selected_rate_code = null
          this.room[index] = this.groupGuests[index].room
          this.guest_id[index] = this.groupGuests[index].id
          if (this.room[index] != null) {
            for (let i = 0; i < this.available_roomtypes.length; i++) {
              if (this.available_roomtypes[i].rooms.length != 0) {
                for (let z = 0; z < this.available_roomtypes[i].rooms.length; z++) {
                  if (this.available_roomtypes[i].rooms[z].id == this.room[index].id) {
                    this.available_roomtypes[i].rooms[z].selected = true
                  }
                }
              }
            }
          }
        }
        //this.calculateTotalRoomRate()
        this.calculateTotalPax()
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    autoAssign() {
      for (let index = 0; index < this.flattenedRows; index++) {
        if (this.room[index] == null) {
          const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == this.group_room_type[index].id)
          for (let i = 0; i < filteredRoomType.rooms.length; i++) {
            if (!filteredRoomType.rooms[i].selected) {
              this.room[index] = filteredRoomType.rooms[i]
              filteredRoomType.rooms[i].selected = true
              break
            }
          }
        }
      }
    },
    calculateTotalRoomRate() {
      this.total_room_rate = 0
      for (let i = 0; i < this.flattenedRows; i++) {
        this.total_room_rate += parseInt(this.rate[i])
      }
    },
    calculateTotalPax() {
      this.no_of_pax = 0
      for (let i = 0; i < this.flattenedRows; i++) {
        this.no_of_pax += parseInt(this.pax[i])
      }
    },
    reformChildrenReservation() {
      const mergedArray = [...this.reservation.guestGroup, ...this.reservation.guestGroupNullRateCode]

      mergedArray.sort((a, b) => a.id - b.id)

      return mergedArray
    },
    setTouchedField(index) {
      this.updated[index] = 1
    },
    async SearchReservation() {
      const response = await this.getSelectedGroupReseravtion(this.reservation_number)

      if (response.data.message == 'not found') {

        this.$alert('No group found', false)
      }
      else {
        this.reservation = response.data.data[0]
      }
    },
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction"]),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      "CreateGroupReservationAction",
      'updateGroupReservation',
      'getGroupReservation',
      'deleteReservation',
      'getSelectedGroupReseravtion',
      'getAvaliableRooms',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    selectRoom(index, id) {
      if (id != null) {
        this.room_type_filter = id
        this.roomDialog = true
        this.selected_room_index = index
      }

    },
    removeRoom(room_index, id) {
      if (this.room[room_index] != null) {
        const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == id)
        let room_found = 0
        for (let index = 0; index < filteredRoomType.rooms.length; index++) {
          if (filteredRoomType.rooms[index].id == this.room[room_index].id) {
            filteredRoomType.rooms[index].selected = false
            room_found = 1
          }
        }
        if (room_found == 0) {
          filteredRoomType.rooms.push(this.room[room_index])
        }
        this.room[room_index] = null
      }
    },
    submitRoom(room) {
      this.roomDialog = false
      this.room[this.selected_room_index] = room

      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == this.room_type_filter)
      for (let index = 0; index < filteredRoomType.rooms.length; index++) {
        if (filteredRoomType.rooms[index].id == room.id) {
          filteredRoomType.rooms[index].selected = true
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    async submitAllRooms() {
      this.group_guest = []
      for (let index = 0; index < this.groupGuests.length; index++) {
        this.group_guest.push({
          guest_id: this.groupGuests[index].id,
          updated: 1,
          profile_id: this.reservation.profile.id,
          room_type_id: this.group_room_type[index].id,
          rm_rate: this.rate[index],
          no_of_pax: this.pax[index],
          room_id: this.room[index] != null ? this.room[index].id : null,
          rate_code_id: this.group_rate_code[index] != null ? this.group_rate_code[index].id : null,
          group_code: this.reservation.group_code,
          res_no: this.reservation.res_no,
        })
      }
      const response = await this.updateGroupReservation({ groupGuest: this.group_guest })

      if (response.status != undefined) {
        this.$alert('Room blocked successfully', true)
        this.updated = []
        this.SearchReservation()
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    Clear() {
      this.flattenedRows = []
      this.rooms_array = []
      this.changedIndexes = []
      this.room = []
      this.pax = []
      this.rate = []
      this.group_rate_code = []
      this.group_room_type = []
      this.no_of_pax = 0
      this.groupGuest = []
      this.rows = 0
      this.reservation_number = null
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
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    checkinfunction() {
      this.GuestCheckinAction(this.reservation.id)
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getRateCodesAction()

  },
}
</script>

<style scoped>
.flex-grow-1 {
  flex-grow: 0 !important;
}
</style>


