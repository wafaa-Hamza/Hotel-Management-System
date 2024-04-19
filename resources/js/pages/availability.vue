<script setup>
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VRow>
        <VCol cols="12" sm="6" md="5">
          <AppDateTimePicker v-model="start_date" :label="$t('start date')"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>
        <VCol cols="12" sm="6" md="5">
          <AppDateTimePicker v-model="end_date" :label="$t('end date')"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
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
                  {{ $t('Higry Date') }}
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
              <tr v-if="availabilty.length === 0" style="padding: 2%">
                <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
              </tr>
              <tr v-else v-for=" ( item, index ) in availabilty " :key="index">
                <td style="width: 6.5%;padding: 0;margin: 0">
                  {{ item.HDate }}
                </td>
                <td>
                  {{ item.HigryHDate }}
                </td>
                <td>
                  {{ item.total }}
                </td>
                <td>
                 <VBtn @click="Arrival(item.HDate)" variant="text">{{ item.arrival }}</VBtn>
                </td>
                <td>
                  <VBtn @click="Out(item.HDate)" variant="text">  {{ item.out }} </VBtn>
                </td>
                <td>
                  <VBtn @click="OC_Data(item.HDate)" variant="text"> {{ item.oc }}</VBtn>
                </td>
                <td>
                  {{ item['%'].toFixed(2) }} %
                </td>
                <td>
                  <VBtn @click="VA_Date(item.HDate)" variant="text"> {{ item.va }}</VBtn>
                </td>
                <template v-if="availabilty.length !== 0">
                  <td v-for=" ( itemm ) in item.availablityRoomTypeCount " :key="itemm.id">
                    <VBtn @click="RType(itemm,item.HDate)" variant="text"> {{ itemm.countOfType }}</VBtn>
                  </td>
                </template>
              </tr>
            </tbody>
          </VTable>
        </VCol>
      </VRow>

    </VCard>
    <VSnackbar v-model="isSnackbarVisible" :timeout="2000" location="top">
      Please select Enter In date and Out date
    </VSnackbar>
    <VDialog
      v-model="Arrival_Dialog"
      class="v-dialog-xl"
    >


      <!-- Dialog close btn -->
      <DialogCloseBtn @click="Arrival_Dialog = !Arrival_Dialog" />

      <!-- Dialog Content -->
      <VCard title="">
        <h1 style="padding: 1% 3%">Arrival {{ Guest.length }}</h1>




                <VCardText>
                  <VTable id="element-to-pdf" height="300">
                    <thead>
                    <tr>
                      <th>
                        {{ $t('no.') }}
                      </th>
                      <th>
                        {{ $t('room name') }}
                      </th>
                      <!--  <th>
                        {{ $t('Folio no') }}
                      </th> -->
                      <th>
                        {{ $t('Guest Name') }}
                      </th>
                      <th>
                        {{ $t('Guest mobile') }}
                      </th>
                      <th>
                        {{ $t('Arrival Date') }}
                      </th>
                      <th>
                        {{ $t('Due Out Date') }}
                      </th>
                      <th>
                        {{ $t('Res.No') }}
                      </th>
                      <th>
                        {{ $t('Charge') }}
                      </th>
                      <th>
                        {{ $t('Payment') }}
                      </th>
                      <th>
                        {{ $t('balance') }}
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="Guest.length === 0">
                      <td colspan="12" style="text-align: center;">{{ $t('No data available') }}</td>
                    </tr>
                    <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                        class="hover-pointer">
                      <td>
                        {{ item.id }}
                      </td>
                      <td>
                        <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }}-->
                        {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                        : null
                        }}

                      </td>
                      <!--  <td>
                        {{ item.folio_no }}
                      </td> -->
                      <td>
                        {{ item.profile.first_name }}
                      </td>
                      <td>
                        {{ item.profile.mobile }}
                      </td>
                      <td>
                        {{ item.in_date }}
                      </td>
                      <td>
                        {{ item.out_date }}
                      </td>
                      <td>
                        {{ item.res_no }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_payment_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>


      </VCard>

    </VDialog>
    <VDialog
      v-model="Out_Dialog"
      class="v-dialog-xl"
    >


      <!-- Dialog close btn -->
      <DialogCloseBtn @click="Out_Dialog = !Out_Dialog" />

      <!-- Dialog Content -->
      <VCard   >

        <h1 style="padding: 1% 3%">Out {{ Guest.length }}</h1>



                <VCardText>
                  <VTable id="element-to-pdf" height="300">
                    <thead>
                    <tr>
                      <th>
                        {{ $t('no.') }}
                      </th>
                      <th>
                        {{ $t('room name') }}
                      </th>
                      <!--  <th>
                        {{ $t('Folio no') }}
                      </th> -->
                      <th>
                        {{ $t('Guest Name') }}
                      </th>
                      <th>
                        {{ $t('Guest mobile') }}
                      </th>
                      <th>
                        {{ $t('Arrival Date') }}
                      </th>
                      <th>
                        {{ $t('Due Out Date') }}
                      </th>
                      <th>
                        {{ $t('Res.No') }}
                      </th>
                      <th>
                        {{ $t('Charge') }}
                      </th>
                      <th>
                        {{ $t('Payment') }}
                      </th>
                      <th>
                        {{ $t('balance') }}
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="Guest.length === 0">
                      <td colspan="12" style="text-align: center;">{{ $t('No data available') }}</td>
                    </tr>
                    <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                        class="hover-pointer">
                      <td>
                        {{ item.id }}
                      </td>
                      <td>
                        <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }}-->
                        {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                        : null
                        }}

                      </td>
                      <!--  <td>
                        {{ item.folio_no }}
                      </td> -->
                      <td>
                        {{ item.profile.first_name }}
                      </td>
                      <td>
                        {{ item.profile.mobile }}
                      </td>
                      <td>
                        {{ item.in_date }}
                      </td>
                      <td>
                        {{ item.out_date }}
                      </td>
                      <td>
                        {{ item.res_no }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_payment_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>





      </VCard>

    </VDialog>
    <VDialog
      v-model="OC_Dialog"
      class="v-dialog-xl"
    >


      <!-- Dialog close btn -->
      <DialogCloseBtn @click="OC_Dialog = !OC_Dialog" />

      <!-- Dialog Content -->
      <VCard >
        <h1 style="padding: 1% 3%">OC {{ Guest.length }}</h1>




                <VCardText>
                  <VTable id="element-to-pdf" height="300">
                    <thead>
                    <tr>
                      <th>
                        {{ $t('no.') }}
                      </th>
                      <th>
                        {{ $t('room name') }}
                      </th>
                      <!--  <th>
                        {{ $t('Folio no') }}
                      </th> -->
                      <th>
                        {{ $t('Guest Name') }}
                      </th>
                      <th>
                        {{ $t('Guest mobile') }}
                      </th>
                      <th>
                        {{ $t('Arrival Date') }}
                      </th>
                      <th>
                        {{ $t('Due Out Date') }}
                      </th>
                      <th>
                        {{ $t('Res.No') }}
                      </th>
                      <th>
                        {{ $t('Charge') }}
                      </th>
                      <th>
                        {{ $t('Payment') }}
                      </th>
                      <th>
                        {{ $t('balance') }}
                      </th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                        class="hover-pointer">
                      <td>
                        {{ item.id }}
                      </td>
                      <td>
                        <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }}-->
                        {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                        : null
                        }}

                      </td>
                      <!--  <td>
                        {{ item.folio_no }}
                      </td> -->
                      <td>
                        {{ item.profile.first_name }}
                      </td>
                      <td>
                        {{ item.profile.mobile }}
                      </td>
                      <td>
                        {{ item.in_date }}
                      </td>
                      <td>
                        {{ item.out_date }}
                      </td>
                      <td>
                        {{ item.res_no }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_payment_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>





      </VCard>

    </VDialog>
    <VDialog
      v-model="RoomType_Dialog"
      class="v-dialog-xl"
    >
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="RoomType_Dialog = !RoomType_Dialog" />

      <!-- Dialog Content -->
      <VCard  >
        <h1 style="padding: 1% 3%">Room Type {{ Guest.length }}</h1>




                <VCardText>
                  <VTable id="element-to-pdf"  >
                    <thead>
                    <tr>
                      <th class="text-center">
                        {{ $t('no.') }}
                      </th>
                      <th class="text-center">
                        {{ $t('room name') }}
                      </th>
                       <th class="text-center">
                        {{ $t('Room Type') }}
                      </th>



                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                        class="hover-pointer">
                      <td>
                        {{ item.id }}
                      </td>
                      <td><br>
                        <p  v-for="room in item.rooms">{{room.rm_name_en}}</p>

                      </td>
                       <td>
                        {{ item.rm_type_name }}
                      </td>


                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>


      </VCard>

    </VDialog>
    <VDialog
      v-model="Va_Dialog"
      class="v-dialog-xl"
    >
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="Va_Dialog = !Va_Dialog" />

      <!-- Dialog Content -->
      <VCard >
        <h1 style="padding: 1% 3%">VA {{ Guest.length }}</h1>




                <VCardText>
                  <VTable id="element-to-pdf" height="300">
                    <thead>
                    <tr>
                      <th>
                        {{ $t('no.') }}
                      </th>
                      <th>
                        {{ $t('room name') }}
                      </th>
                      <!--  <th>
                        {{ $t('Folio no') }}
                      </th> -->
                      <th>
                        {{ $t('Guest Name') }}
                      </th>
                      <th>
                        {{ $t('Guest mobile') }}
                      </th>
                      <th>
                        {{ $t('Arrival Date') }}
                      </th>
                      <th>
                        {{ $t('Due Out Date') }}
                      </th>
                      <th>
                        {{ $t('Res.No') }}
                      </th>
                      <th>
                        {{ $t('Charge') }}
                      </th>
                      <th>
                        {{ $t('Payment') }}
                      </th>
                      <th>
                        {{ $t('balance') }}
                      </th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                        class="hover-pointer">
                      <td>
                        {{ item.id }}
                      </td>
                      <td>
                        <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }}-->
                        {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                        : null
                        }}

                      </td>
                      <!--  <td>
                        {{ item.folio_no }}
                      </td> -->
                      <td>
                        {{ item.profile.first_name }}
                      </td>
                      <td>
                        {{ item.profile.mobile }}
                      </td>
                      <td>
                        {{ item.in_date }}
                      </td>
                      <td>
                        {{ item.out_date }}
                      </td>
                      <td>
                        {{ item.res_no }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_payment_amount }}
                      </td>
                      <td>
                        {{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>


      </VCard>

    </VDialog>
  </div>
</template>

<script>
import AppDateTimePicker from '@/@core/components/app-form-elements/AppDateTimePicker.vue'
import axiosIns from "@axios"
import moment from 'moment'
import { mapActions, mapStores } from "pinia"
import Swal from 'sweetalert2'
import { useRoomStore } from "../stores/RoomStore"
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      settingData: null,
      Arrival_Dialog:false,
      Out_Dialog:false,
      OC_Dialog:false,
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
      Guest: [],
      OC_Date:null,
      RoomType_Dialog:false,
      Va_Dialog:false,
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
  mounted() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction", "getRoomStatusAction", "getFloorsAction"]),

    RType(RType,Date){
      console.log('Room Type',Date)
      axiosIns.post(`/api/availability`, {

        "in_date": Date.slice(0,10),
        "out_date": Date.slice(0,10),
        "room_type_id": RType.IdOfType,

      }).then(res => {
        this.Guest = res.data.data
      })
      this.RoomType_Dialog = true
    },
    VA_Date(VA){
      console.log(VA)
      axiosIns.post(`/api/availability`, {

        "in_date": VA.slice(0,10),
        "out_date": VA.slice(0,10),


      }).then(res => {
        this.Guest = res.data.data
      })
      this.Va_Dialog = true
    },

    OC_Data(Date) {

       axiosIns.post(`/api/GetOcGuestByData`, {

          "date": Date.slice(0,10),

      }).then(res => {
        this.Guest = res.data.data
      })
      this.OC_Dialog=true
    } ,

    Out(Date) {
      axiosIns.post(`/api/guest_filter`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          checked_out: '0',
          "out_date+date": Date.slice(0,10),
        },
      }).then(res => {
        this.Guest = res.data.data.Guests_Filter

      })
      this.Out_Dialog=true
    },
    Arrival(Date) {

      axiosIns.post(`/api/guest_filter`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          is_checked_in: '0',
          is_reservation: 1,
          "in_date+date": Date.slice(0,10),
        }
      }).then(res => {
        this.Guest = res.data.data.Guests_Filter

      })
      this.Arrival_Dialog=true
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

    checkAvailabilty() {
      axiosIns.post(`${window.location.origin}/api/availabilityScreenData`, {
        in_date: this.start_date,
        out_date: this.end_date,
      })
        .then(res => {
          this.availabilty = res.data.data
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
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.start_date = this.SettingData.hotel_date
    this.end_date = moment(this.start_date).add(7, 'days').format("YYYY-MM-DD")
    this.checkAvailabilty()
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRoomStatusAction()
    this.getFloorsAction()
  },
  components: { AppDateTimePicker }
}
</script>


<style scoped>

/* width */
::-webkit-scrollbar {
  block-size: 10px;
  inline-size: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  border-radius: 10px;
  box-shadow: inset 0 0 5px grey;
}

/* Handle */
::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: mediumpurple;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, 60%);

}

</style>
