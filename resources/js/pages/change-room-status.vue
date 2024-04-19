<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VRow>
        <VCol cols="12" sm="6" md="3">
          <VCombobox v-model="filter_status" :label="$t('Filter by status')" :items="room_statuses"
            :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" clearable />
        </VCol>
        <VCol cols="12" sm="6" md="3">
          <VCombobox v-model="floor" :label="$t('Filter by floor')" :items="floors"
            :item-title="$i18n.locale === 'en' ? 'floor_name' : 'floor_name_loc'" clearable />
        </VCol>

        <VCol cols="12" sm="6" md="2"> <label style="font-weight: bold;font-size: larger;" class="float-end mt-1">Changed status :</label></VCol>
        <VCol cols="12" sm="6" md="4">

          <VCombobox v-model="room_status" :label="$t('Room status')" :items="room_statuses"
            :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" clearable />
          
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn color="blue-darken-1" variant="text" @click="selectallrooms">
            select all
          </VBtn>
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn color="blue-darken-1" variant="text" @click="selected_rooms = []">
            unselect all
          </VBtn>
        </VCol>
        <br>
        <br>
        <br>
        <VCol cols="12" sm="6" md="12">

          <VTable height="250" fixed-header>
            <thead>
              <tr>
                <th>
                  {{ $t('select') }}
                </th>

                <th>
                  {{ $t('Room no') }}
                </th>
                <th>
                  {{ $t('Room type') }}
                </th>
                <th>
                  {{ $t('Floor') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for=" ( item, index ) in filteredrooms " :key="item.id">
                <td>
                  <VCheckbox v-model="selected_rooms[index]" :true-value="item.id" />
                </td>
                <td :style="{ backgroundColor: getRoomColor(index) }">
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
      </VRow>
      <VRow v-if="room_statuses.length !== 0">
        <VCol cols="12" sm="6" md="1">
          <VChip :color="room_statuses[0].color" variant="elevated" label size="x-large">

          </VChip> {{ room_statuses[0].status_code }}
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VChip :color="room_statuses[1].color" variant="elevated" label size="x-large">

          </VChip>{{ room_statuses[1].status_code }}
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VChip :color="room_statuses[2].color" variant="elevated" label size="x-large">

          </VChip>{{ room_statuses[2].status_code }}
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VChip :color="room_statuses[3].color" variant="elevated" label size="x-large">

          </VChip> {{ room_statuses[3].status_code }}
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VChip :color="room_statuses[4].color" variant="elevated" label size="x-large">

          </VChip> {{ room_statuses[4].status_code }}
        </VCol>
        <VCol cols="12" sm="6" md="6"></VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="updateRoomStatus" :disabled="disable_submit">
            Update
          </VBtn>
        </VCol>
      </VRow>
    </VCard>
    <VSnackbar v-model="isSnackbarVisible" :timeout="2000" location="top">
      Please select one room atleast
    </VSnackbar>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import moment from 'moment'
import { useRoomStore } from "../stores/RoomStore"
import { mapStores, mapActions } from "pinia"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selected_rooms: [],
      room_status: null,
      final_rooms: [],
      disable_submit: true,
      isSnackbarVisible: false,
      colors: [],
      filter_status: null,
      floor: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(
      useRoomStore,
    ),

    rooms() {
      return this.roomStore.rooms
    },
    floors() {
      return this.roomStore.floors
    },
    // eslint-disable-next-line vue/return-in-computed-property
    room_statuses() {
      return this.roomStore.room_statuses.filter(room_status => {
        return (
          room_status.status_code === 'VACL' ||
          room_status.status_code === 'VADI' ||
          room_status.status_code === 'BLCL' ||
          room_status.status_code === 'BLDI' ||
          room_status.status_code === 'BLOC'
        )
      })

    },
    // eslint-disable-next-line vue/return-in-computed-property
    filteredrooms() {
      if (this.filter_status != null && this.floor != null) {
        return this.rooms.filter(room => {
          return (
            room.fo_status + room.hk_stauts == this.filter_status.status_code &&
            room.floors.id === this.floor.id
          )
        })
      }
      // eslint-disable-next-line sonarjs/no-identical-conditions
      else if (this.filter_status != null && this.floor == null) {
        return this.rooms.filter(room => {
          return (
            room.fo_status + room.hk_stauts == this.filter_status.status_code
          )
        })
      }
      else if (this.filter_status == null && this.floor != null) {
        return this.rooms.filter(room => {
          return (
            room.floors.id === this.floor.id
          )
        })
      }
      else {
        return this.rooms
      }
    },

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    room_status() {
      if (this.room_status != null) {
        this.disable_submit = false
      }
      else {
        this.disable_submit = true
      }
    },

  },


  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction", 'getRoomStatusAction', 'getFloorsAction']),
    selectallrooms() {
      this.selected_rooms = this.filteredrooms.map(item => item.id)
    },
    getRoomColor(index) {
      let status = this.filteredrooms[index].fo_status + this.filteredrooms[index].hk_stauts

      for (let index = 0; index < this.room_statuses.length; index++) {
        if (this.room_statuses[index].status_code === status) {
          return this.room_statuses[index].color
        }
      }



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
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRoomStatusAction()
    this.getFloorsAction()
  },
}
</script>
