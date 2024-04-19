<script setup>
import {
  requiredValidator,
} from '@validators'
import { VDataTable } from 'vuetify/labs/VDataTable'

const form = ref()

const headers_en = [

  {
    title: "oord type",
    key: 'oord_type',
  },
  {
    title: "Room name",
    key: 'room.rm_name_en',
  },
  {
    title: "start date",
    key: 'start_date',
  },
  {
    title: "end date",
    key: 'end_date',
  },
  {
    title: "notes",
    key: 'notes',
  },
  {
    title: "created by",
    key: 'created_by.firstname',
  },
  {
    title: "is return",
    key: 'is_return',
  },
  {
    title: "return by",
    key: 'return_by.firstname',
  },
  {
    title: "return date",
    key: 'return_date',
  },

  {
    title: 'Update',
    key: 'update',
  },
  {
    title: 'Return',
    key: 'actions',
  },
]

// const headers_ar = [
//   {
//     title: "oord type",
//     key: 'oord_type',
//   },
//   {
//     title: "Room name",
//     key: 'room.rm_name_en',
//   },
//   {
//     title: "start date",
//     key: 'start_date',
//   },
//   {
//     title: "end date",
//     key: 'end_date',
//   },
//   {
//     title: "notes",
//     key: 'notes',
//   },
//   {
//     title: "created by",
//     key: 'created_by',
//   },
//   {
//     title: "is return",
//     key: 'is_return',
//   },
//   {
//     title: "return by",
//     key: 'return_by',
//   },
//   {
//     title: "return date",
//     key: 'return_date',
//   },
//   {
//     title: 'Update',
//     key: 'update',
//   },
//   {
//     title: 'Return',
//     key: 'actions',
//   },
// ]
</script>

<template>
  <div>
    <Breadcrumb />
    <VCardText class="AddRow">
      <VCol
        cols="6"
        md="4"
      >
        <AppTextField
          v-model="search"
          density="compact"
          :placeholder="$t('Search')"
          append-inner-icon="tabler-search"
          single-line
          hide-details
          dense
          outlined
        />
      </VCol>
      <VCol
        cols="6"
        md="4"
      >
        <VBtn @click="$router.push(`Store`)">
          {{ $t('Add New Oorder Service') }}
        </VBtn>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable
      :headers="headers_en"
      :items="OordServ"
      :search="search"
      :items-per-page="10"
    >
      <template #item.return_date="{ item }">
        <VLabel>{{ item.props.title.return_date }}</VLabel>
      </template>
      <template #item.update="{ item }">
        <VBtn
          color="primary"
          @click="Update(item.raw?item.raw:item)"
        >
          {{ $t('Update') }}
        </VBtn>
      </template>
      <template #item.actions="{ item }">
        <VBtn
          v-if="!item.props.title.is_return"
          color="primary"
          @click="returnOORD(item)"
        >
          {{ $t('Return') }}
        </VBtn>
      </template>
      <template #item.is_return="{ item }">
        <VChip class="ma-2">
          <VIcon
            v-if="item.props.title.is_return"
            icon="mdi-check"
            color="success"
          />
          <VIcon
            v-if="!item.props.title.is_return"
            icon="mdi-close-octagon"
            color="error"
          />
        </VChip>
      </template>
    </VDataTable>

    <VDialog
      v-model="updateDialog"
      width="1024"
      persistent
      scroll-strategy="none"
      z-index="1000"
    >
      <VCard>
        <VCardTitle>
          <span class="text-h5">{{ $t('Update OORD') }}</span>
        </VCardTitle>
        <VForm ref="form">
          <VCardText>
            <VContainer>
              <VRow>
                <VCol
                  cols="12"
                  sm="6"
                  md="4"
                >
                  <AppDateTimePicker
                    v-model="end_date_edit"
                    label="End date"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                    clearable
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VTextarea
                    v-model="note_edit"
                    label="Note"
                    clearable
                    :rules="[requiredValidator]"
                  />
                </VCol>
              </VRow>
            </VContainer>
          </VCardText>
          <VCardActions>
            <VSpacer />
            <VBtn
              color="blue-darken-1"
              variant="text"
              @click="updateDialog = false"
            >
              Close
            </VBtn>
            <VBtn
              color="blue-darken-1"
              variant="text"
              @click.prevent="form?.validate().then(res => { res.valid ? updateOORD() : null })"
            >
              Submit
            </VBtn>
          </VCardActions>
        </VForm>
      </VCard>
    </VDialog>
  </div>
</template>

<script>
import router from "@/router"
import axios from "@axios"
import Swal from 'sweetalert2'

export default {
  name: "OordServiceTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      oord_id: null,
      updateDialog: false,
      search: '',
      dialog: false,
      dialog3: false,
      dialog4: false,
      OordServ: [],
      roomsData: [],
      roomType_data: [],
      itemid: '',
      itemid_edit: '',
      room_id: '',
      rm_id: '',
      room_type: '',
      floors: '',
      allroom: '',
      room_type_select_edit: '',
      room_key_options_edit: '',
      room_active_edit: '',
      roomType_selected: [],
      room_selected: '',
      roomType_selected_edit: '',
      roomfloor_selected: '',
      All_room_selected: '',
      discribtions: '',
      discribtions_edit: '',
      maintenancetype_data: '',
      maintenancetype_data_edit: '',
      roomType_selected_edit_id: '',
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
      URL: window.location.origin,
      settings: null,
    }
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllData()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    // GoToStore() {
    //
    //   router.push({ name: 'OordService-Store' })
    //
    // },
    Update(item) {
      this.updateDialog = true
      this.oord_id = item.props.title.id
      this.end_date_edit = item.props.title.end_date
      this.note_edit = item.props.title.notes
    },
    updateOORD() {
      axios.put(`${this.URL}/api/Oord_services/${this.oord_id}`,
        {
          end_date: this.end_date_edit,
          note: this.note_edit,
        })
        .then(response => {
          this.updateDialog = false
          this.getAllData()
          this.alert('OORD updated successfully', true)
        })
    },
    returnOORD(item) {
      axios.put(`${this.URL}/api/return-oord/${item.props.title.id}`,
        {
          is_return: 1,
          return_date: this.settings.hotel_date,
        })
        .then(response => {
          this.getAllData()
          this.alert('OORD returned successfully', true)
        })
    },
    getAllData() {
      axios
        .get(`${this.URL}/api/Oord_services`)
        .then(response => (this.OordServ = response.data.data))
    },



    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: type ? 'success' : 'error',
        title: message,
      })
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

.AddRow {
  display: flex;
  flex: 1 1 auto;
  flex-wrap: wrap;
  justify-content: right;
  margin: -12px;
}
</style>
