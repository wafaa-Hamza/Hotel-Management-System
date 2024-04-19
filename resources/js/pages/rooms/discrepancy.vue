<script setup>


</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>

    <VCard>
      <VContainer>


        <VRow>
          <VCol cols="12" sm="6" md="4">
            <VCombobox v-model="Floor_id" :label="$t('Floor name')"  :items="Floors"
                       :item-title="$i18n.locale == 'en' ? 'floor_name' : 'floor_name_loc'"  />
          </VCol>




          <VCol cols="12" sm="6" md="3">
            <VBtn class="float-end" type="submit"
                  @click="getAllData">
              {{ $t('Get All Rooms') }}
            </VBtn>
          </VCol>
        </VRow>

        <br>


        <VTable class="mt-5" height="300px">
          <thead>
          <tr>
            <th>
              {{ $t("Room no") }}
            </th>

            <th>
              {{ $t("Old Status") }}
            </th>
            <th>
              {{ $t("New Status") }}
            </th>
          </tr>
          </thead>
          <tbody v-if="AllRoomsByFloor">
          <tr v-for="(item, floorIndex) in AllRoomsByFloor" :key="floorIndex">

            <td >
              <p v-for="(roomItem, roomIndex) in item" :key="roomIndex"
                 style="margin: 12% 0%">{{ roomItem.rm_name_en }}  </p>
            </td>
            <td>
              <p v-for="(roomItem, roomIndex) in item" :key="`status_${roomIndex}`"
                 style="margin: 12% 0%">{{ roomItem.fo_status }}{{ roomItem.hk_stauts }}</p>
            </td>

            <VCol cols="12" sm="6" md="6">
              <p  v-for="(roomItem, roomIndex) in item" :key="`combobox_${roomIndex}`">  <VCombobox
                v-model="statusSelected[roomIndex]"
                :label="$t('Status')"
                :items="AllStatus"
                item-title="status_code"
                style="margin: 12% 0%"
              /> </p>
            </VCol>

          </tr>
          </tbody>
        </VTable>

        <VCol style="display: flex;justify-content: space-around">
           <VBtn class="float-end mb-5" type="submit"
                @click="AddStatus">
            {{ $t('Submit') }}
          </VBtn>
          <VBtn class="float-end mb-5" type="submit"
                @click="update" >
            {{ $t('update') }}
          </VBtn>
        </VCol>
      </VContainer>
    </VCard>
    <VDialog v-model="dialog" width="1224" height="610"
     >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Discripancy</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VTable class="mt-5"  fixed-header>
              <thead >
              <tr >
                <th>

                  {{ $t("Solved") }}
                </th>
                <th>
                  {{ $t("Room") }}
                 </th>

                <th>
                  {{ $t("hotel_date") }}
                </th>
                <th>
                  {{ $t("Status") }}
                </th>
                <th>
                  {{ $t("created by") }}
                </th>
                <th>
                  {{ $t("update") }}
                </th>
              </tr>
              </thead>

              <tbody v-for="item in Alldiscrepancy"  >

              <tr>

                <td >

                  <p  v-if="item.solved_by ===1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled" width="44" height="44" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" stroke-width="0" fill="green" />
                    </svg>
                  </p>
                  <p  v-if="item.solved_by !==1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" stroke-width="0" fill="red" />
                    </svg>
                  </p>
                </td>

                <td >
                  {{ item.room_id }}
                </td>
                <td >
                  {{ item.hotel_date }}
                </td>
                <td >
                  {{ item.status_fo }}{{ item.status_hk }}
                </td>
                <td >
                  {{ item.created_by }}
                </td>
                <td >

            <div v-if="item.solved_by !==1">
                  <VBtn @click="updateStatus(item)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
             </div>
            <div v-else>
                  <VBtn @click="updateStatus(item)" disabled
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
            </div>
                </td>
              </tr>


<!--              <tr v-else style="background: green;border-radius: 10px">-->

<!--                <td >-->
<!--                  {{ item.room_id }}-->
<!--                </td>-->
<!--                <td >-->
<!--                  {{ item.hotel_date }}-->
<!--                </td>-->
<!--                <td >-->
<!--                  {{ item.status_fo }}{{ item.status_hk }}-->
<!--                </td>-->
<!--                <td >-->
<!--                  {{ item.created_by }}-->
<!--                </td>-->
<!--                <td >-->
<!--                  <VBtn @click="updateStatus(item)"-->
<!--                  >-->
<!--                    <VIcon icon="mdi-file-edit-outline" />-->
<!--                  </VBtn>-->
<!--                </td>-->



<!--              </tr>-->
              </tbody>
             </VTable>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
            Close
          </VBtn>

        </VCardActions>
      </VCard>
    </VDialog>

  </div>
</template>

<script>
import { ref } from 'vue';

import axios from '@axios'
import Swal from 'sweetalert2'

export default {
  props: {
    AllRoomsByFloor: Array, // Assuming AllRoomsByFloor is an array of arrays
    AllStatus: Array
  },
  setup(props) {
    // Create a reactive object to store v-model values
    const statusSelected = ref({});

    // Check if AllRoomsByFloor is defined and is an array
    if (Array.isArray(props.AllRoomsByFloor)) {
      // Initialize v-model values for each room
      props.AllRoomsByFloor.forEach(floorItems => {
        if (Array.isArray(floorItems)) {
          floorItems.forEach((roomItem, roomIndex) => {
            statusSelected.value[roomIndex] = null;
          });
        }
      });
    }

    return {
      statusSelected,
    };
  },
  name: 'discrepancy',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      dialog: false,
      AllRooms:[],
      Alldiscrepancy:[],
      AllRoomsByFloor:[],
      Floorses:[],
      Floors:[],
      AllStatus:[],
      Floor_id:'',
      status_old:'',
      Status_selected:'',
      room_to:'',
      URL: window.location.origin,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllData()
    this.getAllFloors()
    this.getAllStatus()
    this.AddStatus()
    this.getAlldiscrepancy()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    UpdateAlert() {
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
        title: 'Status Updated successfully',
        color: 'green',
        timer: 5000,
      })
    },
    getAllStatus() {
      axios
        .get(`${this.URL}/api/room-status`)

        .then(response => (this.AllStatus = response.data.data))
    },
    getAlldiscrepancy() {
      axios
        .get(`${this.URL}/api/discrepancy`)

        .then(response => (this.Alldiscrepancy = response.data.data))
    },
    getAllFloors() {
      axios
        .get(`${this.URL}/api/floor`)
        .then(res => {

          this.Floors = res.data

        })
    },

    getAllData() {
      axios.post(`${this.URL}/api/Floor_by_rooms`, {
        floor_id: this.Floor_id.id,
        // eslint-disable-next-line vue/no-mutating-props
      }).then(response => (this.AllRooms = response.data.floor_with_rooms, this.AllRoomsByFloor = response.data.floor_with_rooms.map((ele)=>ele.rooms)))

    },


    AddStatus(){

      let roomlength =this.AllRoomsByFloor.flat(Infinity)
      for (let i =0;i<=roomlength.length-1;i++){

        this.status_old = this.AllRoomsByFloor.flat(Infinity)[i].fo_status.concat(this.AllRoomsByFloor.flat(Infinity)[i].hk_stauts)


        if(this.status_old === this.statusSelected[i].status_code){


         }else {
          const firstTwoCharacters = this.statusSelected[i].status_code.substring(0, 2)

          const lastTwoCharacters = this.statusSelected[i].status_code.substring(this.statusSelected[i].status_code.length - 2)

          axios.post(`${this.URL}/api/discrepancy`, {
            room_id: this.AllRoomsByFloor.flat(Infinity)[i].id,
            status_fo: firstTwoCharacters,
            status_hk: lastTwoCharacters,
          })
        }

      }
      this.getAlldiscrepancy()


    },
    update(){
      this.dialog = true
    },
    updateStatus(data){

      axios.post(`${this.URL}/api/discrepancy/${data.id}`, {
        room_id: data.room_id,
        status_fo: data.status_fo,
        status_hk: data.status_hk,
        _method: 'PUT',
      })
      this.getAlldiscrepancy()
  this.UpdateAlert()
    },
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

.last-row-td {
  background-color: beige;
  /* Add any other styles you want for the last row here */
}

.Solved{
  background: green;
}
.notsolved{
  background: white;
}

</style>
