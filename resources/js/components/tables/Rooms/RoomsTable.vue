<script setup>
const checkboxOne = ref(true)

const capitalizedLabel = label => {
  const convertLabelText = label.toString()

  return convertLabelText.charAt(0).toUpperCase() + convertLabelText.slice(1)
}
import { avatarText } from '@/@core/utils/formatters'
import {
  betweenValidator,
  integerValidator,
  requiredValidator,
} from '@validators'
import { VDataTable } from 'vuetify/labs/VDataTable'

const form = ref()
const refform = ref()

// headers
const headers_en = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'rm_name_en',
    key: 'rm_name_en',
  },
  {
    title: 'room_type',
    key: 'room_type.rm_type_name',
  },

  {
    title: 'rm_max_pax',
    key: 'rm_max_pax',
  },
  {
    title: 'floors',
    key: 'floors.floor_name',
  },


  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

// headers
const headers_ar = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'NAME',
    key: 'rm_name_loc',
  },

  {
    title: 'room_type',
    key: 'room_type.rm_type_name',
  },
  {
    title: 'pax',
    key: 'rm_max_pax',
  },
  {
    title: 'no room',
    key: 'floors.no_of_rooms',
  },
  {
    title: 'floors',
    key: 'floors.floor_name',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCardText class="AddRow" style="display: flex;justify-content: space-between;">
      <VCol cols="6" md="4">
        <AppTextField v-model="search" density="compact" :placeholder="$t('Search')" append-inner-icon="tabler-search"
          single-line hide-details dense outlined />
      </VCol>
      <VCol cols="6" md="4" >
        <VDialog v-model="dialog" width="1024">
          <template v-if="$can('create', 'room')" #activator="{ props }">
            <VBtn v-bind="props" @click="OpenDialog">
              {{ $t('Add Room') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add Room') }}</span>
            </VCardTitle>
            <VForm ref="form">
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_name_en" label="Room Name" persistent-placeholder type="text"
                        :rules="[requiredValidator]" @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_name_loc" label="Room Name Loc" type="text" persistent-placeholder
                        :rules="[requiredValidator]" @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6">
                      <VSelect item-color="customColorValue" v-model="roomType_selected" clearable :items="roomType" item-title="rm_type_name"
                        item-value="id" :label="$t('Room Type')" :rules="[requiredValidator]"
                        @keyup.enter="goNext($event.target)" />
                    </VCol>

                    <VCol cols="12" sm="6">
                      <VSelect item-color="customColorValue" v-model="roomfloor_selected" :items="floorsType" item-title="floor_name" item-value="id"
                        :label="$t('Floor')" :rules="[requiredValidator]" @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_max_pax" label="Room max pax" type="number" persistent-placeholder
                        :rules="[requiredValidator, betweenValidator(room_max_pax, 1, 99), integerValidator]"
                        @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_phone_no" :label="$t('Room Phone No')" persistent-placeholder
                        type="number" @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_phone_ext" :label="$t('Room Phone Ext')" persistent-placeholder
                        type="number" @keyup.enter="goNext($event.target)" />
                    </VCol>

                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_key_code" :label="$t('Room Key Code')" persistent-placeholder type="text"
                        @keyup.enter="goNext($event.target)" />
                    </VCol>




                    <VCol cols="12" sm="6">
                      <VCombobox v-model="Room_View" :items="RoomView" item-title="name" item-value="id"
                        :label="$t('Room View')" @keyup.enter="goNext($event.target)"  item-color="customColorValue"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="room_key_options" :label="$t('Room Key Options')" persistent-placeholder
                        type="text" @keyup.enter="goNext($event.target)" />
                    </VCol>


                    <VCol cols="12" sm="6" md="6" >
                      <VCheckbox  v-model="room_connection" :label="`room connection: ${room_connection.toString()}`"
                                  @keyup.enter="goNext($event.target)" />
                    </VCol>

                    <VCol cols="12" sm="6" md="6">
                      <VCheckbox v-model="room_active" :label="`room Active: ${room_active.toString()}`"
                        @keyup.enter="goNext($event.target)" />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="dialog = false, errors = null">
                  {{ $t('Close') }}
                </VBtn>
                <VBtn color="blue-darken-1" variant="text"
                  @click.prevent="form?.validate().then(res => { res.valid ? Add() : null })">
                  {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VForm>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>

    <!-- 👉 Datatable  -->
    <VDataTable :headers="$i18n.locale === 'en' ? headers_en : headers_ar" :items="Roomes" :search="search"
      :items-per-page="10">
      <!-- full name -->
      <template #item.full_name="{ item }">
        <div class="d-flex align-center">
          <!-- avatar -->
          <VAvatar size="32" :color="item.raw.avatar ? '' : 'primary'"
            :class="item.raw.avatar ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.raw.avatar ? 'tonal' : undefined">
            <VImg v-if="item.raw.avatar" :src="item.raw.avatar" />
            <span v-else>{{ avatarText(item.raw.full_name) }}</span>
          </VAvatar>

          <div class="d-flex flex-column ms-3">
            <span class="dad-block font-weight-medium text--primary text-truncate">{{ item.raw.full_name }}</span>
            <small>{{ item.raw.post }}</small>
          </div>
        </div>
      </template>



      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn v-if="$can('edit', 'room')" color="primary">
            <VRow>
              <VDialog v-model="item.dialog3" width="1024">
                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item.raw?item.raw:item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5"> {{ $t('Update User Profile') }}</span>
                  </VCardTitle>
                  <VForm ref="form">
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_name_edit" :label="$t('Room Name')"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_name_loc_edit" :label="$t('Room Name Loc')"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>

                          <VCol cols="12" sm="6">
                            <VSelect item-color="customColorValue" v-model="room_type_select_edit" :items="roomType" item-title="rm_type_name"
                              item-value="id" :label="$t('Room Type')" @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6">
                            <VSelect item-color="customColorValue" v-model="floor_select_edit" :items="floorsType" item-title="floor_name"
                              item-value="id" :label="$t('Floor')" @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_max_pax_edit" :label="$t('Room max pax')" type="text"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_phone_no_edit" :label="$t('Room Phone No')" persistent-hint
                              type="number" @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_phone_ext_edit" :label="$t('Room Phone Ext')" type="number"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_key_code_edit" :label="$t('Room Key Code')" persistent-hint
                              type="number" @keyup.enter="goNext($event.target)" />
                          </VCol>




                          <VCol cols="12" sm="6">
                            <VSelect item-color="customColorValue" v-model="Room_View_edit" :items="RoomView" item-title="name" item-value="id"
                              :label="$t('Room View edit')" @keyup.enter="goNext($event.target)" />
                          </VCol>

                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="room_key_options_edit" :label="$t('Room Key Options')" persistent-hint
                              type="number" @keyup.enter="goNext($event.target)" />
                          </VCol>

                          <VCol cols="12" sm="6" md="6">
                            <VCheckbox v-model="room_connection_edit"
                              :label="`room connection edit: ${room_connection_edit.toString()}`"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VCheckbox v-model="room_active_edit"
                              :label="`room active edit: ${room_active_edit.toString()}`"
                              @keyup.enter="goNext($event.target)" />
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                  </VForm>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="item.dialog3 = false">
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn color="blue-darken-1" variant="text" @click="updateUser">
                      {{ $t('Update') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
          <VBtn v-if="$can('delete', 'room')" color="red" style="background: red;color: white;"
            @click="Delete(item.raw?item.raw.id:item.id)">
            <VIcon icon="mdi-delete" />
          </VBtn>
        </div>
      </template>
    </VDataTable>
  </div>
</template>


<script>
import axios from "@axios"


export default {
  name: "RoomsTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      name: "rooms",
      search: '',
      dialog: false,
      dialog3: false,
      Roomes: [],
      itemid: '',
      itemid_edit: '',
      room_id: '',

      room_id_edit: '',
      room_name_edit: '',
      room_name_loc_edit: '',
      room_max_pax_edit: '',
      room_phone_no_edit: '',
      room_phone_ext_edit: '',
      room_key_code_edit: '',
      room_connection_edit: '',
      room_status_edit: '',
      room_hk_status_edit: '',
      room_sort_edit: '',
      room_type_select_edit: '',
      floor_select_edit: '',
      room_key_options_edit: '',
      room_active_edit: '',
      Room_View_edit: '',
      room_type_rent_edit: '',
      room_type_number_edit: '',
      room_type_price_edit: '',
      room_type_pax_edit: '',
      room_type_loc_edit: '',
      room_type_name_edit: '',
      room_type_code_edit: '',
      room_type_name: '',
      room_type_loc: '',
      room_type_pax: '',
      room_type_price: '',
      room_type_number: '',
      room_type_rent: '',

      room_name_en: '',
      room_name_loc: '',
      room_max_pax: '',
      room_phone_no: '',
      room_phone_ext: '',
      room_key_code: ref(''),
      room_key_options: ref(''),
      room_connection: false,
      fo_status: 'VA',
      room_active: true,
      hk_stauts: 'CL',
      sort_order: '',
      roomType_selected: '',

      roomfloor_selected: '',
      Room_View: '',


      roomType: [],

      floorsType: [],
      RoomView: [],

      URL: window.location.origin,

    }
  },
  watch: {

    room_name_en(val) {
      this.room_name_loc = val
      this.room_phone_no = val
      this.room_phone_ext = val
      this.room_key_code = val
      this.room_key_options = val
    },

  },




  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllData()
    this.getFloors()
    this.getRoomsTypes()
    this.getAllRoomView()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)
      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },

    getAllRoomView() {
      axios
        .get(`${this.URL}/api/viewGarden`)
        .then(res => (this.RoomView = res.data.data))
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

    DeleteAlert() {

      this.$swal.fire({
        icon: 'error',
        title: 'Do you want to Delete',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Deleted`,
      }).then(result => {

        if (result.isDenied) {
          this.deleteData()

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
            title: `Data ${this.room_name_en} Deleted successfully`,
            color: 'red',
            timer: 10000,
          })
        }
      })
    },

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
        icon: 'info',
        title: 'updateed is successfully',
        color: 'mediumpurple',
        timer: 5000,
      })
    },

    getAllData() {
      axios
        .get(`${this.URL}/api/rooms`)

        .then(response => (this.Roomes = response.data.data))
    },

    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/rooms`,
          {
            room_name_en: this.room_name_en,
            room_name_loc: this.room_name_loc,
            room_max_pax: this.room_max_pax,
            room_phone_no: this.room_phone_no,
            room_phone_ext: this.room_phone_ext,
            room_type_id: this.roomType_selected,
            room_connection: this.room_connection,
            fo_status: 'VA',
            room_active: this.room_active,
            room_key_options: this.room_key_options,
            room_key_code: this.room_key_options,
            hk_stauts: 'CL',
            sort_order: this.sort_order,
            floor_id: this.roomfloor_selected,
            view_id: this.Room_View.id,
          },
        )

        this.room_name_en = ''
        this.room_name_loc = ''


        this.insertAlert()
        this.getAllData()

      } catch (e) {
        console.log(e)
      }
    },


    async Delete(GetId) {
      this.itemid = GetId
      this.DeleteAlert()
    },

    deleteData() {
      axios
        .delete(`${this.URL}/api/rooms/${this.itemid}`)
        .then(response => (this.Rooms = response.data))
      this.getAllData()
    },


    async Updates(Getdata) {

      this.itemid = Getdata

      this.room_id_edit = Getdata.id,
        this.room_name_edit = Getdata.rm_name_en,
        this.room_name_loc_edit = Getdata.rm_name_loc,
        this.room_max_pax_edit = Getdata.rm_max_pax,
        this.room_phone_no_edit = Getdata.rm_phone_no,
        this.room_phone_ext_edit = Getdata.rm_phone_ext,
        this.room_key_code_edit = Getdata.rm_key_code,
        this.room_connection_edit = Getdata.rm_connection == 0 ? false : true,
        this.room_status_edit = Getdata.fo_status,
        this.room_hk_status_edit = Getdata.hk_stauts,
        this.room_sort_edit = Getdata.sort_order,
        this.room_key_options_edit = Getdata.rm_key_options,
        this.room_active_edit = Getdata.rm_active == '0' ? false : true,
        this.room_type_select_edit = Getdata.room_type.id
      this.floor_select_edit = Getdata.floors.id
      this.Room_View_edit = Getdata.Room_View
    },


    async updateUser() {
      try {
        const user = await axios.put(
          `${this.URL}/api/rooms/${this.itemid.id}`,
          {
            room_name_en: this.room_name_edit,
            room_name_loc: this.room_name_loc_edit,
            room_max_pax: this.room_max_pax_edit,
            room_phone_no: this.room_phone_no_edit,
            room_phone_ext: this.room_phone_ext_edit,
            room_type_id: this.room_type_select_edit,
            fo_status: this.room_status_edit,
            room_active: this.room_active_edit == true ? 1 : 0,
            room_key_options: this.room_key_options_edit,
            room_key_code: this.room_key_code_edit,
            hk_stauts: this.room_hk_status_edit,
            sort_order: this.room_sort_edit,
            room_connection: this.room_connection_edit == true ? 1 : 0,
            floor_id: this.floor_select_edit,
            view_id: this.Room_View_edit,

          },
        )


        this.getAllData()
        this.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }

    },

    async getRoomsTypes() {
      axios
        .get(`${this.URL}/api/room_types`)
        .then(response => (this.roomType = response.data))
    },

    async getFloors() {
      axios
        .get(`${this.URL}/api/floor`)
        .then(response => (this.floorsType = response.data))
    },

  },

}
</script>
<style>
/*.v-selection-control__input input {*/
/*  cursor: pointer;*/
/*  position: absolute;*/
/*  left: 0;*/
/*  top: 10%;*/
/*  width: 43%;*/
/*  height: 84%;*/
/*  opacity: 1.1;*/
/*}*/
</style>
