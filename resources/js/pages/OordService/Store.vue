<script setup>
const inlineRadio = ref('radio-1')
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VCardTitle>
        <span class="text-h5">Add New Service Room</span>
      </VCardTitle>
      <VCardText>
        <VCol cols="7" sm="7" md="7" style="margin: auto">
          <VRadioGroup v-model="inlineRadio" inline>
            <VRadio label="By Room" value="radio-1" @click="ChangeToFloor" />
            <VSpacer />
            <VRadio label="By Floor" value="radio-2" @click="ChangeToRoom" />
          </VRadioGroup>
        </VCol>

        <VCol cols="12">
          <VRow>
            <VCol cols="6" sm="6" md="6">
              <VCol cols="12" sm="12" md="12">
                <VCombobox v-model="roomType_selected" :items="Rooms" item-title="rm_name_en" :disabled="disableRoomData"
                  label="Room" />

              </VCol>
              <br>

              <VCol cols="12" sm="12" md="12">
                <VCombobox v-model="OordType" :items="oord_type" item-title="oord_type" label="oord type" />
                <br>
              </VCol>
              <VCol cols="12" sm="12" md="12" style="display: flex">
                <VCol>

              <AppDateTimePicker v-model="Start_data" label="Start Date"
                  :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData?.hotel_date).subtract(1,'days').format('YYYY-MM-DD')}]  , dateFormat: 'Y-m-d l' }"
                  />
                </VCol>
                <VCol>

                  <AppDateTimePicker v-model="End_data" label="End Date" 
                  :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData?.hotel_date).format('YYYY-MM-DD')}]  ,dateFormat: 'Y-m-d l' }"  />

                </VCol>
              </VCol>
              <VCol cols="12" sm="12" md="12">
                <VTextarea v-model="notes" cols="12" sm="6" md="6" label="Note" auto-grow style="height: 20%" />
              </VCol>
            </VCol>


            <VSpacer />
            <VCol cols="6">
              <VCol cols="12" sm="12" md="12">
                <VCombobox v-model="FloorType_selected" :items="FloorData" item-title="floor_name"
                  item-value="FloorType_selected" :disabled="disableFloorData" label="Floor" />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VBtn class="ml-4" @click="selectall">
                  {{ $t('select all') }}
                </VBtn>
                <VBtn class="ml-4" @click="checked = []">
                  {{ $t('unselect all') }}
                </VBtn>
              </VCol>

              <VTable style="table-layout: fixed;direction: ltr;" height="300">
                <thead>
                  <tr>
                    <th>
                      {{ $t('Num') }}
                    </th>


                    <th>
                      Room
                    </th>
                    <th>
                      Room Type
                    </th>
                    <th>
                      Select
                    </th>
                  </tr>
                </thead>


                <tbody id="DisplayTable">
                  <tr v-for="(roooma, index) in rooms" :key="roooma.id">
                    <td>{{ roooma.id }}</td>

                    <td>
                      {{ roooma.rm_name_en }}
                    </td>
                    <td>
                      {{ roooma.room_type.rm_type_name }}
                    </td>
                    <td>
                      <VCheckbox v-model="checked[index]" label="Check" :true-value="roooma.id" :false-value="false" />
                    </td>
                  </tr>
                </tbody>
              </VTable>

            </VCol>
          </VRow>
        </VCol>
      </VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn variant="tonal" @click="add(checked)" :disabled="disableRoomData">
          Submit By Room
        </VBtn>
        <VSpacer />
        <VBtn variant="tonal" @click="add1(checked)" :disabled="disableFloorData">
          Submit By Floor
        </VBtn>
        <VSpacer />
        <VBtn variant="tonal" @click="BackToProfile">
          Back to Oord Service page
        </VBtn>
        <VSpacer />
      </VCardActions>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"
import moment from 'moment'

export default {
  name: "Store",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      SettingData:null,
      disableRoomData: false,
      disableFloorData: true,
      oord_type: ['Out Of Order', 'Out Of Service'],
      roomType_selected: '',
      Rooms: [],
      FloorData: [],
      room_Type: [],
      Floors: [],
      Floor: [],
      OordType: '',
      Start_data: '',
      End_data: '',
      notes: '',
      URL: window.location.origin,
      FloorType_selected: [],
      room_Type_data: [],
      room_data: [],
      rooms: '',
      room_type_data: [],
      checked: [],
      room_id_array: [],
      ok: '',
    }

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    roomType_selected() {
      this.room_Type = this.roomType_selected.roomType
      this.Floors = this.roomType_selected.floors

    },
    FloorType_selected() {
      this.rooms = this.FloorType_selected.rooms
      this.room_data = this.FloorType_selected


    },

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }

    this.getRooms()
    this.getFloors()
    

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    selectall() {

      this.checked = this.rooms.map(item => item.id)

    },
    insertAlert() {
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
        title: 'Data Added successfully',
        color: 'green',
        timer: 5000,
      })
    },
    ChangeToFloor() {
      this.disableRoomData = false
      this.disableFloorData = true
      document.getElementById('DisplayTable').style.display = "none"
    },
    ChangeToRoom() {
      this.disableFloorData = false
      this.disableRoomData = true
      document.getElementById('DisplayTable').style.display = ""

    },
    getRooms() {
      axios
        .get(`${this.URL}/api/rooms`)
        .then(response => (this.Rooms = response.data.data))
    },
    getFloors() {
      axios
        .get(`${this.URL}/api/floor`)
        .then(response => (this.FloorData = response.data))

    },

      add() {
      try {

        this.room_id_array.push(this.roomType_selected.id)




        const user =  axios.post(
          `${this.URL}/api/Oord_services`,
          {
            room_id: this.room_id_array,
            return_date: '2024-04-06',
            return_by: '0',
            is_return: '0',
            oord_type: this.OordType === "Out Of Order" ? "OO" : "OS",
            start_date: this.Start_data,
            end_date: this.End_data,
            notes: this.notes,
          },
        )



        // this.room_id_array=''
          router.push('/oordservice/oordservice')
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },

     add1(checked) {


      try {
        const user =   axios.post(
          `${this.URL}/api/Oord_services`,
          {
            room_id: checked,
            oord_type: this.OordType === "Out Of Order" ? "OO" : "OS",
            return_date: '2024-04-06',
            return_by: '0',
            is_return: '0',
            start_date: this.Start_data,
            end_date: this.End_data,
            notes: this.notes,
          },
        )

        this.insertAlert()
          router.push('/oordservice/oordservice')

      } catch (e) {
        console.log(e)
      }
    },
    BackToProfile() {
      this.$router.go(-1)
    },
  },

}
</script>
