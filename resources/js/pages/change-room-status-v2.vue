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
        <VCol cols="12" sm="6" md="3">
          <VCombobox v-model="filter_status" :label="$t('Filter by status')" :items="room_statuses" :item-title="
            $i18n.locale === 'en' ? 'name' : 'name_loc'" clearable />
        </VCol>
        <VCol cols="12" sm="6" md="3">
          <VCombobox v-model="floor" :label="$t('Filter by floor')" :items="floors" :item-title="
            $i18n.locale === 'en' ? 'floor_name' : 'floor_name_loc'" clearable />
        </VCol>




        <VExpansionPanels v-model="master_panel">
          <VRow class="pl-5 pr-5 pt-5 pb-5">
            <VCol v-for="(item, index) in filteredrooms" :key="item.id" cols="12" sm="6" md="4">

              <VExpansionPanel v-model="panel[index]" >
                <VExpansionPanelTitle :style="{ backgroundColor: getRoomColor(index) }">
                  Room no: {{ $i18n.locale === 'en' ?
                    item.rm_name_en : item.rm_name_loc }}
                </VExpansionPanelTitle>
                <VExpansionPanelText>
                  <span class="pt-5">
                    Room type: {{ $i18n.locale === 'en' ?
                      item.room_type.rm_type_name
                      : item.room_type.rm_type_name_loc }} <span class="float-end"> floor: {{ $i18n.locale === 'en' ?
    item.floors.floor_name :
    item.floors.floor_name_loc }}</span></span>
                  <VRow v-if="room_statuses.length !== 0" class="mt-2">
                    <VCol cols="12" sm="6" md="6">
                      <VBtn :color="room_statuses[0].color"
                        @click="updateRoomStatus(item.id, room_statuses[0].status_code), master_panel = []">
                        {{ $i18n.locale === 'en' ? room_statuses[0].name : room_statuses[0].name_loc }}
                      </VBtn>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VBtn :color="room_statuses[1].color"
                        @click="updateRoomStatus(item.id, room_statuses[1].status_code), master_panel = []">
                        {{ $i18n.locale === 'en' ? room_statuses[1].name : room_statuses[1].name_loc }}
                      </VBtn>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VBtn :color="room_statuses[2].color"
                        @click="updateRoomStatus(item.id, room_statuses[2].status_code), master_panel = []">
                        {{ $i18n.locale === 'en' ? room_statuses[2].name : room_statuses[2].name_loc }}
                      </VBtn>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VBtn :color="room_statuses[3].color"
                        @click="updateRoomStatus(item.id, room_statuses[3].status_code), master_panel = []">
                        {{ $i18n.locale === 'en' ? room_statuses[3].name : room_statuses[3].name_loc }}
                      </VBtn>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VBtn :color="room_statuses[4].color"
                      @click="updateRoomStatus(item.id, room_statuses[4].status_code), master_panel = []">
                      {{ $i18n.locale === 'en' ? room_statuses[4].name : room_statuses[4].name_loc }}
                    </VBtn>
                  </VCol>
                </VRow>
              </VExpansionPanelText>
              <!--  <VCard>

                  <VCardTitle :style="{ backgroundColor: getRoomColor(index) }">Room no: {{ $i18n.locale === 'en' ?
                    item.rm_name_en : item.rm_name_loc }}</VCardTitle>
                  <VCardSubtitle><span style="font-weight: bold;">Room type: {{ $i18n.locale === 'en' ?
                    item.room_type.rm_type_name :
                    item.room_type.rm_type_name_loc }}</span> <span class="float-end" style="font-weight: bold;"> floor:
                      {{ $i18n.locale === 'en' ? item.floors.floor_name : item.floors.floor_name_loc }}</span>
                  </VCardSubtitle>

                  <VCardText>
                    <VRow v-if="room_statuses.length !== 0">
                      <VCol cols="12" sm="6" md="6">
                        <VBtn :color="room_statuses[0].color"
                          @click="updateRoomStatus(item.id, room_statuses[0].status_code)">
                          {{ $i18n.locale === 'en' ? room_statuses[0].name : room_statuses[0].name_loc }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VBtn :color="room_statuses[1].color"
                          @click="updateRoomStatus(item.id, room_statuses[1].status_code)">
                          {{ $i18n.locale === 'en' ? room_statuses[1].name : room_statuses[1].name_loc }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VBtn :color="room_statuses[2].color"
                          @click="updateRoomStatus(item.id, room_statuses[2].status_code)">
                          {{ $i18n.locale === 'en' ? room_statuses[2].name : room_statuses[2].name_loc }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VBtn :color="room_statuses[3].color"
                          @click="updateRoomStatus(item.id, room_statuses[3].status_code)">
                          {{ $i18n.locale === 'en' ? room_statuses[3].name : room_statuses[3].name_loc }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VBtn :color="room_statuses[4].color"
                          @click="updateRoomStatus(item.id, room_statuses[4].status_code)">
                                                                                    {{ $i18n.locale === 'en' ? room_statuses[4].name : room_statuses[4].name_loc }}
                                                                                  </VBtn>
                                                                                </VCol>

                                                                              </VRow>
                                                                            </VCardText>
                                                                        </VCard> -->
              </VExpansionPanel>

            </VCol>
          </VRow>
        </VExpansionPanels>
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
      panel: [],
      master_panel: [],
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
    updateRoomStatus(index, status_code) {
      const firstTwoLetters = status_code.substring(0, 2)
      const lastTwoLetters = status_code.substring(2)

      axiosIns.post(`${window.location.origin}/api/change-room-status`, {
        fo_status: firstTwoLetters,
        hk_status: lastTwoLetters,
        rooms: [index],
      })
        .then(res => {
          this.insertAlert('Room status updated successfully')
          this.getRoomsAction()
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
      const Toast = this.$swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer)
          toast.addEventListener('mouseleave', this.$swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: 'success',
        title: message,
        color: 'green',
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
