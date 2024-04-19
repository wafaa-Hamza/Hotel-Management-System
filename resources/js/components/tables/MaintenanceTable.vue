<script setup>
import { requiredValidator } from "@validators"

const date = ref('')
const refform = ref()
</script>


<template>
  <div style="margin-top: 5%;">

    <Breadcrumb class="d-print-none"></Breadcrumb>
    <div style="display: flex;width: 100%;justify-content: space-between;float: inline-end;">
      <div style="display: flex;width: 62%;">
        <VCol cols="3">
          <AppDateTimePicker v-model="in_date_filter" :label="$t('In date*')" :rules="[requiredValidator]" />
        </VCol>
        <VCol cols="3">
          <AppDateTimePicker v-model="out_date_filter" :label="$t('out date*')" :rules="[requiredValidator]" />
        </VCol>
        <VCol cols="4" sm="4" md="4">
          <VSelect item-color="customColorValue" v-model="status_data_filter" :items="statuses" :label="$t('status')" />
        </VCol>
        <VCol cols="2" sm="2" md="2">
          <VBtn @click="getAllData">Filter</VBtn>
        </VCol>
      </div>
      <div style="width: 35%;"><br>
        <VRow>
          <div style="display: flex;width: 65%;justify-content: space-between;">


            <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 70%;" />
          </div>
          <VSpacer />
          <VDialog v-model="dialog" width="1024" z-index="1000" scroll-strategy="none">
            <template #activator="{ props }">
              <VBtn v-if="$can('create', 'maintenance')" v-bind="props">
                {{ $t('Add maintenance') }}
              </VBtn>
            </template>
            <VCard>
              <VCardTitle>
                <span class="text-h5">{{ $t('Add maintenance') }}</span>
              </VCardTitle>
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="6" sm="6" md="6">
                      <VCombobox v-model="room_id" :items="roomsData" item-title="rm_name_en"
                        item-value="roomType_selected" :label="$t('Room name')" item-color="customColorValue" />
                    </VCol>

                    <VCol cols="3" sm="3" md="3">
                      <VTextField v-model="rm_type_selected.rm_type_name" readonly :label="$t('Room type')" />
                    </VCol>
                    <VCol cols="3" sm="3" md="3">
                      <VTextField v-model="Floor_selected.floor_name" readonly :label="$t('Floor')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VSelect item-color="customColorValue" v-model="status_data" :items="statuses"
                        :label="$t('status')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="4">
                      <VSelect item-color="customColorValue" v-model="maintenancetype_data" :items="maintenancetype"
                        item-title="name" item-value="id" :label="$t('maintenancetype')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="2">
                      <VDialog v-model="typeDialog" width="1024" z-index="1000" scroll-strategy="none">
                        <template #activator="{ props }">
                          <VBtn v-bind="props">
                            {{ $t('Create type') }}
                          </VBtn>
                        </template>
                        <VCard>
                          <VForm ref="refform">
                            <VCardTitle>
                              <span class="text-h5">{{ $t('maintenance type') }}</span>
                            </VCardTitle>

                            <VCardText>
                              <VContainer>
                                <VRow>
                                  <VCol cols="12" sm="6" md="6">
                                    <VTextField v-model="maintenance_type_name" :label="$t('maintenance name')"
                                      clearable :rules="[requiredValidator]" />
                                  </VCol>
                                  <VCol cols="12" sm="6" md="6">
                                    <VTextField v-model="maintenance_type_name_loc" :label="$t('maintenance name loc')"
                                      clearable :rules="[requiredValidator]" />
                                  </VCol>
                                </VRow>
                              </VContainer>
                            </VCardText>
                            <VCardActions>
                              <VSpacer />
                              <VBtn color="blue-darken-1" variant="text" @click="typeDialog = false">
                                {{ $t('Close') }}
                              </VBtn>
                              <VBtn color="blue-darken-1" variant="text"
                                @click.prevent="refform?.validate().then(res => { res.valid ? addmaintenancetype() : null })">
                                {{ $t('Submit') }}
                              </VBtn>
                            </VCardActions>
                          </VForm>
                        </VCard>
                      </VDialog>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextarea v-model="discribtions" cols="12" sm="6" md="6" :label="$t('Auto Grow')"
                        persistent-placeholder auto-grow style="height: 10%;" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">

                      <VTextField v-model="Date_time" :label="$t('Date & time')" readonly :rules="[requiredValidator]"
                        @click="isDialogDate = !isDialogDate" />
                    </VCol>
                    <VDialog v-model="isDialogDate" width="300">

                      <DialogCloseBtn @click="isDialogDate = false" />

                      <VCard style="padding: 20px;margin: auto;">
                        <AppDateTimePicker v-model="Date_time" :label="$t('Date & time')" type="date"
                          :config="{ altFormat: 'Y-m-d l', dateFormat: 'Y-m-d', inline: true }" disabled="disabled"
                          :rules="[requiredValidator]" />
                        <VCardText class="d-flex flex-wrap gap-3">
                          <VSpacer />

                          <VBtn @click="isDialogDate = false">
                            ok
                          </VBtn>
                        </VCardText>
                      </VCard>
                    </VDialog>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text">
                  {{ $t('Close') }}
                </VBtn>
                <VBtn color="blue-darken-1" variant="text" @click="Add">
                  {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
        </VRow>
      </div>
    </div>

    <br>
    <br>

    <br>
    <br>
    <br>

    <VTable style="table-layout: fixed;">
      <thead>
        <tr>
          <th>
            {{ $t('Num') }}
          </th>
          <th>
            {{ $t('room_name') }}
          </th>

          <th>
            {{ $t('room_type_name') }}
          </th>
          <th>
            {{ $t('floor_name') }}
          </th>

          <th>
            {{ $t('maintenance_type_id') }}
          </th>
          <th>
            {{ $t('description') }}
          </th>
          <th>
            {{ $t('exp_ready_date') }}
          </th>
          <th>
            {{ $t('main_status') }}
          </th>

          <th v-if="$can('edit', 'maintenance')">
            {{ $t('Update') }}
          </th>
        </tr>
      </thead>


      <tbody>
        <tr v-for="(item, index) in Maintenance" :key="item.id" style="text-align: start;">
          <td> {{ ++index }}</td>


          <td>
            {{ item.room?.rm_name_en }}
          </td>
          <td>
            {{ item.room?.room_type.rm_type_name }}
          </td>
          <td>
            {{ item.room?.floors.floor_name }}
          </td>



          <td>{{ item.maintenance_type_id }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.exp_ready_date }}</td>

          <td>
            <VChip class="ma-2" style="color: mediumpurple;">
              <VIcon start icon="mdi-account-circle-outline" />
              {{ item.main_status }}
            </VChip>
          </td>

          <td v-if="$can('edit', 'maintenance')">
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="item.dialog3" width="1024" scroll-strategy="none">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(item)">
                      <VIcon icon="mdi-file-edit-outline" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">
                        {{ $t('Update maintenance') }}</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="6" sm="6" md="6">
                            <VCombobox v-model="roomType_selected_edit" :items="roomsData" item-title="rm_name_en"
                              item-value="roomType_selected" :label="$t('Room name')" item-color="customColorValue" />
                          </VCol>
                          <VCol cols="3" sm="3" md="3">
                            <VCombobox v-model="rm_edit_id" :items="roomType_data" :label="$t('Room type name')"
                              item-title="rm_type_name" item-color="customColorValue" />
                          </VCol>
                          <VCol cols="3" sm="3" md="3">
                            <VCombobox v-model="floor_edit_id" :items="floorsType" :label="$t('floor name')"
                              item-title="floor_name" item-color="customColorValue" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextarea v-model="discribtions_edit" cols="12" sm="6" md="6" style="height: 10%;" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VSelect item-color="customColorValue" v-model="maintenancetype_data_edit"
                              :items="maintenancetype" item-title="name" item-value="id"
                              :label="$t('maintenancetype')" />
                            <br>
                            <VSelect item-color="customColorValue" v-model="status_data_edit" :items="statuses"
                              :label="$t('status')" />
                            <br>
                            <VTextField v-model="Date_time_edit" cols="6" sm="3" md="3" :label="$t('Date & time')"
                              type="date" />
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                        {{ $t('Close') }}
                      </VBtn>
                      <VBtn color="blue-darken-1" variant="text" @click="updateUser">
                        {{ $t('Submit') }}
                      </VBtn>
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VRow>
            </VBtn>
          </td>
        </tr>
      </tbody>
      <br>
    </VTable>
    <div style="float: inline-end;">
      <VPagination v-if="pageInfo" v-model="pageInfo.current_page" :length="Math.ceil(pageInfo.total / pageSize)"
        total-visible="7" :size="43" :next="pageInfo.next_page_url" :per-page="pageInfo.per_page"
        @click="nextPage(pageInfo.per_page, pageInfo.current_page)" />
    </div>

  </div>
</template>

<script>
import axios from "@axios"

export default {
  name: "MaintenanceTable",


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      maintenance_type_name: null,
      in_date_filter: null,
      out_date_filter: null,
      status_data_filter: null,
      maintenance_type_name_loc: null,
      typeDialog: false,
      search: '',
      dialog: false,
      dialog3: false,
      Maintenance: [],
      roomsData: [],
      roomType_data: [],
      itemid: '',
      floor_id: '',
      rm_typet_id: '',
      itemid_edit: '',
      room_id: '',
      rm_id: '',
      room_type: '',
      floors: '',
      allroom: '',
      room_type_select_edit: '',
      room_key_options_edit: '',
      room_active_edit: '',
      rm_type_selected: [],
      Floor_selected: [],

      roomType_selected: [],
      room_selected: '',
      roomType_selected_edit: '',
      roomfloor_selected: '',
      All_room_selected: '',

      maintenancetype: [],
      discribtions: '',
      discribtions_edit: '',
      maintenancetype_data: '',
      maintenancetype_data_edit: '',
      roomType_selected_edit_id: '',
      floor_edit_id: '',
      rm_edit_id: '',
      isDialogDate: false,
      URL: window.location.origin,

      status_data: '',
      status_data_edit: '',
      statuses: ['pending', 'finished', 'closed'],

      floorsType: [],
      Date_time: '',
      Date_time_edit: '',

      count: 0,
      perPage: 10,
      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 5,
      pageSizes: [5, 10, 15, 20, 25, 30],

    }
  },

  // eslint-disable-next-line vue/component-api-style
  computed: {
    filterData() {
      return this.pagi.filter(user => user.id.includes(this.search))
    },
  },

  // eslint-disable-next-line vue/component-api-style
  watch: {
    pageSize() {
      this.getAllPaginate()
    },

    room_id(room_data) {
      this.rm_type_selected = room_data.room_type
      this.Floor_selected = room_data.floors
    },

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllData()
    this.getFloors()
    this.getRooms()
    this.getRoomsTypes()
    this.getAllPaginate()
    this.getmaintenancetype()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllPaginate() {

      axios
        .get(`api/mainTenancePagination/${this.pageSize}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data.data,
              this.pageInfo = response.data.data
            )

          }
        })

    },
    nextPage(page, query) {
      axios
        .get(`api/mainTenancePagination/${page}?page=${query}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.pagination,
              this.pageInfo = response.data.data
            )

          }
        })
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
            title: `Data  Deleted successfully`,
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

    // getAllData() {
    //   axios
    //     .post(`api/maintenances-date`)
    //
    //     .then(response => (this.Maintenance = response.data.data))
    // },
    getAllData() {
      axios
        .post(`${this.URL}/api/maintenances-date`, {
          main_status: this.status_data_filter,

          start_date: this.in_date_filter,
          end_date: this.out_date_filter,
        })
        .then(response => (
          this.Maintenance = response.data.data, console.log(response)
        ))
    },
    async getmaintenancetype() {
      axios
        .get(`api/maintenance-type`)
        .then(response => (this.maintenancetype = response.data.data))
    },
    async addmaintenancetype() {
      axios
        .post(`api/maintenance-type`, {
          name: this.maintenance_type_name,
          name_loc: this.maintenance_type_name_loc,
        })
        .then(response => {
          if (response.status != undefined) {
            this.$alert('New maintenance type created', true)
            this.typeDialog = false
            this.getmaintenancetype()
          }
        })
    },
    async getRooms() {
      axios
        .get(`api/rooms`)
        .then(response => (this.roomsData = response.data.data))
    },
    async getRoomsTypes() {
      axios
        .get(`api/room_types`)
        .then(response => (this.roomType_data = response.data))
    },
    async getFloors() {
      axios
        .get(`api/floor`)
        .then(response => (this.floorsType = response.data))
    },
    async Add() {
      console.log(this.status_data)
      this.dialog = false
      try {
        const user = await axios.post(
          `api/maintenance`,
          {
            room_id: this.room_id.id,
            description: this.discribtions,
            main_status: this.status_data,
            exp_ready_date: this.Date_time,
            maintenance_type_id: this.maintenancetype_data,
          },

        )

        this.discribtions = ''
        this.status_data = ''
        this.Date_time = ''
        this.maintenancetype_data = ''
        this.room_id = ''

        this.getAllData()
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }


    },

    async Updates(Getdata) {
      this.itemid = Getdata


      this.roomType_selected_edit = Getdata.room?.rm_name_en,
        this.roomType_selected_edit_id = Getdata.room_id,
        this.status_data_edit = Getdata.main_status,
        this.rm_edit_id = Getdata.room?.room_type.rm_type_name,
        this.floor_edit_id = Getdata.room?.floors.floor_name,
        this.Date_time_edit = Getdata.exp_ready_date,
        this.maintenancetype_data_edit = Getdata.maintenance_type_id,
        this.discribtions_edit = Getdata.description


    },
    async updateUser() {

      try {
        const user = await axios.put(
          `api/maintenance/${this.itemid.id}`,
          {
            room_id: this.roomType_selected_edit_id,
            maintenance_type_id: this.maintenancetype_data_edit,
            description: this.discribtions_edit,
            main_status: this.status_data_edit,
            exp_ready_date: this.Date_time_edit,



          },
        )


        this.getAllData()
        this.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }



    },

  },
}
</script>

<style>
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

.v-textarea .v-field__field {
  --v-input-control-height: var(--v-textarea-control-height);

  block-size: 10.6em;
}
</style>
