
<template>
<div>
  <VCol>
    <div>
      <span v-for="(item, index) in Racks.status" :key="item.id">

        <VChip id="chip" :style="{ background: item.color }">
          <b>{{ item.code }} ({{ item.count }}) </b>
          <VTooltip activator="parent" location="top"> {{ item.name }}</VTooltip>
        </VChip>
      </span>

    </div>
  </VCol>



  <div style="margin: 2% 0;">
    <VRow>
      <VCol>
        <div style="display: flex;width: 80%;justify-content: space-around;float: inline-end;">

          <VCombobox v-model="roomType_selected" :items="roomType" :label="$t('room type')" item-title="rm_type_name"
                     item-value="id" persistent-placeholder clearable  item-color="customColorValue"  />
          <VSpacer />
          <VBtn class="btn" @click="del">
            {{ $t('Remove') }}
          </VBtn>
        </div>
      </VCol>
    </VRow>
    <br>
    <br>
    <br>
    <VCol>
      <VTable id="element-to-pdf" style="table-layout: fixed;">
        <thead>
        <tr>
          <th v-for="(item, index) in Racks.floorData" :key="item.id" class="text-center floorTr">
            {{ item.floor_name }}
          </th>
        </tr>
        </thead>


        <tbody>
        <tr style="text-align: center;">
          <td v-for="(item, index) in Racks.floorData" :key="item.id" class="Tdloop">
            {{ item.guest_details }}
            <div v-for="(roomdata, index1) in item.rooms"
                 style="border: 2px solid black;margin: 3px 0;font-weight: bold;"
                 :style="{ backgroundColor: roomdata.color_status }" @dblclick="GoToPages(roomdata)">

              {{ roomdata.roomName }}
              <span>
                  <VMenu>
                    <template #activator="{ props }">
                      <VBtn v-bind="props" variant="outline">
                        ...
                      </VBtn>
                    </template>
                    <VList>

                      <VListItemTitle @click="getRoomHistory(roomdata.room_id), history_dialog = true"
                                      style="padding: 3%;cursor: pointer;text-align: center;">
                        <h4>history</h4>
                      </VListItemTitle>
                    </VList>
                  </VMenu>
                </span>
            </div>
          </td>
        </tr>
        </tbody>
      </VTable>


    </VCol>

    <VDialog v-model="history_dialog" width="1300">
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol cols="12" sm="6" md="9">
              <span>{{ $t("Room history") }}</span>
            </VCol>
          </VRow>
        </VCardTitle>
        <VCardText>
          <div class="mb-5 mt-2">
            <VRow>
              <VCol cols="12" sm="6" md="12">
                <VTable height="200">
                  <thead>
                  <tr>
                    <th>
                      {{ $t("Guest Name") }}
                    </th>
                    <th>
                      {{ $t("Reservation No.") }}
                    </th>
                    <th>
                      {{ $t("In date") }}
                    </th>
                    <th>
                      {{ $t("Out date") }}
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="room_history.length === 0">
                    <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
                  </tr>
                  <tr v-else v-for="item in room_history">
                    <td> {{ item.profile.first_name + ' ' + item.profile.last_name }} </td>
                    <td> {{ item.res_no ? item.res_no : ' ' }} </td>
                    <td> {{ item.in_date }} </td>
                    <td> {{ item.out_date }} </td>
                  </tr>
                  </tbody>
                </VTable>
              </VCol>
            </VRow>
          </div>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn @click="history_dialog = false">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

  </div>
</div>
</template>

<script>
import { useRoomStore } from "@/stores/RoomStore"
import axios from "@axios"


export default {
  name: "Rack",
  props: {
    roomDataId: Array
  },

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selected_room: [],
      history_dialog: false,
      search: '',
      dialog: false,
      Racks: [],
      Status: [],
      roomType_selected: '',
      room_Type: '',
      roomType: [],
      URL: window.location.origin,

      room_history: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    history_dialog() {
      if (this.history_dialog == false) {
        this.selected_room = null
        this.room_history = []
      }
    },
    selected_room() {

      if (this.selected_room != '' && this.selected_room != null) {
        this.getRoomHistory()
      }
    },

    roomType_selected() {
      this.room_Type = this.roomType_selected.id,
        this.getRack(this.room_Type)

    },

  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getRack()
    this.getroom_type()
    this.getRoomHistory()
    const middleOfPage = window.innerHeight / 2;

    // Scroll to the middle of the page
    window.scrollTo({
      top: middleOfPage,
      behavior: 'smooth', // Optional: Add smooth scrolling animation
    })


  },

  // eslint-disable-next-line vue/component-api-style
  methods: {

    getRack(id) {
      axios
        .post(`${this.URL}/api/rack`, {
          room_type_id: id,
        })
        .then(response => (this.Racks = response.data.data))
    },
    getroom_type() {
      axios
        .get(`${this.URL}/api/room_types`)
        .then(response => (this.roomType = response.data))
    },



    getRoomHistory(room_id) {

      axios
        .post(`${this.URL}/api/roomGuest-history`, {
          room_id: room_id,
        })
        .then(response => (this.room_history = response.data.room_guestHistory))
    },

    del() {
      this.roomType_selected = ''
    },
    print1(id) {
      window.print()
    },

    GoToPages(RoomData) {
      // console.log('RoomData',RoomData)
      // go to  selectedFolio page with guest_id

      if (RoomData.status.startsWith('OC')) {
        const routeData = {
          name: 'selectedFolio-folio',
          params: { folio: RoomData.guest_details.id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')
      }

      // go to  reservation page with reservation_id

      if (RoomData.status.startsWith('BL')) {
        const routeData = {
          name: 'reservation-update-reservation',
          params: { reservation: RoomData.reservation.id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')
      }

      // go to  reservation page with Room_id

      if (RoomData.status.startsWith('VA')) {
        const roomStore = useRoomStore();
         const roomDataId = RoomData;

        console.log('VA2',roomDataId.room_id)

        roomStore.setRoomId(roomDataId);


        this.$router.push({ name: 'create-reservation' });


      }

    }

  },
}
</script>

<style>
.Tdloop {
  vertical-align: text-top;
}

.v-table--density-default>.v-table__wrapper>table>tbody>tr>td,
.v-table--density-default>.v-table__wrapper>table>thead>tr>td,
.v-table--density-default>.v-table__wrapper>table>tfoot>tr>td {
  padding: 2px;
  margin: 0;
  block-size: 48px;
}

#chip {
  padding: 20px;
  border-radius: 10px;
  float: inline-start;
  margin-block: 7% 1%;
  margin-inline: 5px;
  max-inline-size: 7%;
  text-align: center;
}

.floorTr {
  box-shadow: 0 0 7px 10px #eee inset;
  font-weight: bold;
}
</style>
