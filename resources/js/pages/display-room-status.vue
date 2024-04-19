<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'

const form = ref()
</script>

<template>
  <div>


    <Breadcrumb  class="d-print-none" ></Breadcrumb>

    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VForm ref="form">
        <VRow>
          <VCol cols="12" sm="6" md="3">
            <AppDateTimePicker v-model="start_date" :label="$t('Start date')" clearable :rules="[requiredValidator]"
                               :config="{ altInput: true,altFormat: 'Y-m-d l',dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <AppDateTimePicker v-model="end_date" :label="$t('End date')" clearable :rules="[requiredValidator]"
                               :config="{ altInput: true,altFormat: 'Y-m-d l',dateFormat: 'Y-m-d' }"/>
          </VCol>
          <VCol cols="12" sm="6" md="3" class="mt-6">
            <VCombobox v-model="room_type" :label="$t('Filter by room type')" :items="room_types"
              :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_loc'" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="3" class="mt-6">
            <VCombobox v-model="room" :label="$t('Filter by room')" :items="rooms"
              :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'" clearable />
          </VCol> 
          <br>
          <br>
          <br>
          <VCol cols="12" sm="6" md="12"   style="max-height: 400px; padding: 2%; overflow-y:scroll;">

            <VTable fixed-header>
              <thead>
                <tr>
                  <th style="width: 3%;">
                    {{ $t('Floor') }}
                  </th>

                  <th style="width: 3%;">
                    {{ $t('Room no') }}
                  </th>
                  <th style="width: 3%;">
                    {{ $t('Room type') }}
                  </th>
                  <th v-for=" ( item ) in dates_array " :key="item.id" style="width: 3%;">
                    {{ formatDate(item) }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for=" ( item, room_index ) in filteredrooms " :key="item.id" >
                  <td class="headcol">
                    <p v-if="$i18n.locale === 'en'">
                      {{ item.floors.floor_name }}
                    </p>
                    <p v-else>
                      {{ item.floors.floor_name_loc }}
                    </p>
                  </td>
                  <!-- :style="{ backgroundColor: getRoomColor(index) }" -->
                  <td>
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
                      {{ item.room_type.rm_type_name_loc }}
                    </p>
                  </td>
                  <td v-for="  item  in rooms_statuses " :key="item.id"
                    :style="{ backgroundColor: getRoomColor(item[room_index]) }">
                    {{ item[room_index] }}
                  </td>
                </tr>
              </tbody>
            </VTable>

          </VCol>
        </VRow>
        <VRow>
          <VCol cols="12" sm="6" md="10"></VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="reset">
              {{ $t('Reset') }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click.prevent="form?.validate().then(res => { res.valid ? getRoomsStatus() : null })" >
              {{ $t('Search') }}
            </VBtn>
          </VCol>
        </VRow>
      </VForm>
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
import AppDateTimePicker from '@/@core/components/app-form-elements/AppDateTimePicker.vue'

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
      start_date: '',
      end_date: '',
      rooms_statuses: [],
      dates_array: [],
      room: null,
      room_type: null,
      AllSetData: [],
      SettingData: [],
      localSetting: []
    }
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    room() {
      if (this.room != null) {
        this.room_type = null
        this.rooms_statuses = []
      }
      if (this.room == null && this.room_type == null) {
        this.rooms_statuses = []
      }
    },
    room_type() {
      if (this.room_type != null) {
        this.room = null
        this.rooms_statuses = []
      }
      if (this.room == null && this.room_type == null) {
        this.rooms_statuses = []
      }
    },
  },
  
  mounted() {
    //console.log('test')
   // console.log('getRoomsStatus',this.getRoomsStatus())

    const SettingData = localStorage.getItem('keyinfo');
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData);
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
     this.start_date=   this.localSetting.hotel_date



          const hotelDate = new Date(this.localSetting.hotel_date);
          // Add 7 days to the hotelDate
          hotelDate.setDate(hotelDate.getDate() + 7);

          this.end_date = hotelDate;


       
          
        setTimeout(this.getRoomsStatus(),2000)  

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
    // start_date(start){
    //   start = this.localSetting.hotel_date
    // }
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction",  "getFloorsAction" ,"getRoomStatusAction"]),

    selectallrooms() {
      this.selected_rooms = this.filteredrooms.map(item => item.id)
    },
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
    reset() {
      this.start_date = null
      this.end_date = null
      this.room = null
      this.room_type = null
      this.rooms_statuses = []
      this.dates_array = []
      this.$refs.form.reset()
    },
    getDates(startDate, endDate) {
      var dates = []
      var currentDate = new Date(startDate)

      while (currentDate <= endDate) {
        dates.push(new Date(currentDate))
        currentDate.setDate(currentDate.getDate() + 1)
      }

      return dates
    },
    formatDate(date) {
      return String(date.getDate()).padStart(2, '0')
    },
    getRoomsStatus() {
      var startDate = moment(this.start_date)
      var endDate = moment(this.end_date)
      this.dates_array = this.getDates(startDate, endDate)
      axiosIns.post(`${window.location.origin}/api/roomStatus`, {
        start_date: moment(this.start_date).format("YYYY-MM-DD"),
        end_date: moment(this.end_date).format("YYYY-MM-DD"),
        room_id: this.room != null ? this.room.id : null,
        roomTypeID: this.room_type != null ? this.room_type.id : null,
      })
        .then(res => {
          this.rooms_statuses = res.data
        })
        .catch(err => {
          console.log(err)
        })
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
  },
  components: { AppDateTimePicker }
}
</script>


