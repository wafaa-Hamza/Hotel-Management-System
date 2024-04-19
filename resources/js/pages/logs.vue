

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VForm ref="refForm">
        <VRow>

          <VCol cols="12" sm="6" md="2">
            <AppDateTimePicker v-model="start_date" :label="$t('Start date')" clearable
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <AppDateTimePicker v-model="end_date" :label="$t('End date')" clearable
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VCombobox v-model="user" :label="$t('User')" :items="users" item-title="firstname" class="mt-6" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VCombobox v-model="room" :label="$t('Room')" :items="rooms"
              :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'" clearable class="mt-6" />
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VTextField v-model="folio" :label="$t('Reservation No.')" type="number" class="mt-6" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="1" class="mt-6">
            <VBtn @click="clear">
              {{ $t('Reset') }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="1" class="mt-6">
            <VBtn type="submit" @click.prevent="getLogs">
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
                    {{ $t('Date/Time') }}
                  </th>
                  <th>
                    {{ $t('User') }}
                  </th>
                  <th>
                    {{ $t('Description') }}
                  </th>

                  <th>
                    {{ $t('Change From') }}
                  </th>
                  <th>
                    {{ $t('Change To') }}
                  </th>
                  <th>
                    {{ $t('Folio') }}
                  </th>

                </tr>
              </thead>
              <tbody>
                <tr v-if="logs.length === 0">
                  <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
                </tr>
                <tr v-else v-for=" ( item, index ) in logs " :key="index">
                  <td>
                    {{ item.date }}
                  </td>
                  <td>
                    {{ item.causer != null ? item.causer.firstname + ' ' + item.causer.lastname : null }}
                  </td>
                  <td>
                    {{ item.description }}
                  </td>
                  <td v-if="item.event == 'updated' && item.properties?.old != undefined">
                    <template v-for="([item, index]) in Object.entries(item.properties?.old)" :key="index">
                      {{ $t(item) + ': ' + index }}
                      <br>
                    </template>
                  </td>
                  <td v-else>

                  </td>
                  <td v-if="item.event == 'updated'">
                    <span v-for="([item, index]) in Object.entries(item.properties.attributes)" :key="index">
                      {{ $t(item) + ': ' + index }}
                      <br>
                    </span>
                  </td>
                  <td v-else>

                  </td>
                  <td>
                    {{ item.log_name == 'Reservation' ? item.subject_id : null }}
                  </td>


                </tr>
              </tbody>
            </VTable>
          </VCol>
        </VRow>
      </VForm>
    </VCard>
    <VSnackbar v-model="isSnackbarVisible" :timeout="2000" location="top">
      {{ $t('Please select Enter In date and Out date') }}
    </VSnackbar>
  </div>
</template>

<script>
import AppDateTimePicker from '@/@core/components/app-form-elements/AppDateTimePicker.vue'
import axiosIns from "@axios"
import moment from 'moment'
import { mapActions, mapStores } from "pinia"
import Swal from 'sweetalert2'
import { useRoomStore } from "../stores/RoomStore"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      transactionDialog: false,
      logs: [],
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
      users: [],
      user: null,
      folio: null,
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
    open() {
      console.log('mohamed')
    },
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction", "getRoomStatusAction", "getFloorsAction"]),
    async submitForm() {
      const isValid = await this.$refs.refForm.validate()
      if (isValid.valid) {
        this.getLogs()
      }
    },
    getLogs() {
      if (this.folio == null && this.room == null) {
        axiosIns.post(`${window.location.origin}/api/getActivityByFilter`, {
          start_date: this.start_date,
          end_date: this.end_date != null ? this.end_date : this.start_date,
          user_id: this.user != null ? this.user.id : null,
          type: "Normal",
        })
          .then(res => {
            console.log(res.data)
            this.logs = res.data
          })
          .catch(err => {
            console.log(err)
          })
      }
      if (this.folio != null && this.folio != '') {
        axiosIns.post(`${window.location.origin}/api/getActivityByFilter`, {
          start_date: this.start_date,
          end_date: this.end_date != null ? this.end_date : this.start_date,
          user_id: this.user != null ? this.user.id : null,
          id: this.folio,
          type: "Reservation",
        })
          .then(res => {
            this.logs = res.data
          })
          .catch(err => {
            console.log(err)
          })
      }
      if (this.room != null && this.room != '') {
        axiosIns.post(`${window.location.origin}/api/getActivityByFilter`, {
          start_date: this.start_date,
          end_date: this.end_date != null ? this.end_date : this.start_date,
          user_id: this.user != null ? this.user.id : null,
          id: this.room.id,
          type: "Room",
        })
          .then(res => {
            this.logs = res.data
            console.log(this.logs)
          })
          .catch(err => {
            console.log(err)
          })
      }

    },
    getAllusersAction() {
      axiosIns.get(`${window.location.origin}/api/users`)
        .then(res => {
          this.users = res.data
        })
        .catch(err => {
        })
    },
    clear() {
      this.start_date = moment().format("YYYY-MM-DD")
      this.end_date = moment().format("YYYY-MM-DD")
      this.user = null
      this.folio = null
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
    this.getRoomsAction()
    this.getAllusersAction()
    this.start_date = moment().format("YYYY-MM-DD")
    this.end_date = moment().format("YYYY-MM-DD")
    this.getLogs()
  },
  components: { AppDateTimePicker }
}
</script>


