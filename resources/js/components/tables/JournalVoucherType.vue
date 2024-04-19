<script setup>
import { avatarText } from '@/@core/utils/formatters'
import { VDataTable } from 'vuetify/labs/VDataTable'

// headers
const headers_en = [
  {
    title: '#',
    key: 'index',
  },
  {
    title: 'NAME',
    key: 'name',
  },
  {
    title: 'name loc',
    key: 'name_loc',
  },
  {
    title: 'Fyear',
    key: 'fyear',
  },

  {
    title: 'Voucher Type',
    key: 'voucher_type',
  },
  {
    title: 'is_opening',
    key: 'status',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const headers_ar = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'NAME',
    key: 'name',
  },
  {
    title: 'name loc',
    key: 'name_loc',
  },
  {
    title: 'Fyear',
    key: 'fyear',
  },

  {
    title: 'Voucher Type',
    key: 'voucher_type',
  },
  {
    title: 'is_opening',
    key: 'status',
  },
  {
    title: 'العمليات',
    key: 'actions',
  },
]
const resolveStatusVariant = status => {
  if (status === 1)
    return {
      color: 'primary',
      text: 'opend',
    }
  else if (status === 0)
    return {
      color: 'warning',
      text: 'not opened',
    }
  else if (status === 3)
    return {
      color: 'error',
      text: 'Rejected',
    }
  else if (status === 4)
    return {
      color: 'info',
      text: 'Applied',
    }
  else
    return {
      color: 'success',
      text: 'Professional',
    }
}
const addIndexToItems = (items) => {
  return items.map((item, index) => {
    return { index: index + 1, ...item }
  })
}
</script>

<template>


  <div>

    <Breadcrumb class="d-print-none"></Breadcrumb>

    <VCardText class="AddRow">

      <VCol cols="6" md="4">
        <AppTextField v-model="search" density="compact" :placeholder="$t('Search')" append-inner-icon="tabler-search"
          single-line hide-details dense outlined />

      </VCol>
      <VCol cols="6" md="4">
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props">
              {{ $t('Add Voucher type') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add Voucher type') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>

                  <VCol cols="12" sm="5" md="5">
                    <VTextField v-model="JournalName" persistent-placeholder :label="$t('Journal Name en')" />
                  </VCol>
                  <VCol cols="12" sm="5" md="5">
                    <VTextField v-model="JournalName_loc" persistent-placeholder type="text"
                      :label="$t('Journal Name ar')" />
                  </VCol>

                  <VCol cols="12" sm="5" md="5">
                    <VTextField v-model="Fyear" :placeholder="$t('Fyear')" />

                  </VCol>

                  <VCol cols="12" sm="5" md="5">
                    <VCombobox item-color="customColorValue" v-model="Vpucher_type" :label="$t('Vpucher_type')"
                      persistent-placeholder :items="Vpucher_types" />
                  </VCol>


                  <VCol cols="12" sm="2" md="2">
                    <VCheckbox v-model="is_open" :label="`is_open: ${is_open.toString()}`" />
                  </VCol>
                </VRow>
              </VContainer>
            </VCardText>
            <VCardActions>
              <VSpacer />
              <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                {{ $t('Close') }}
              </VBtn>
              <VBtn color="blue-darken-1" variant="text" @click="Add">
                {{ $t('Submit') }}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCol>

    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable :headers="$i18n.locale === 'en' ? headers_en : headers_ar" :items="addIndexToItems(Types)"
      :search="search" :items-per-page="10">
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
            <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.full_name }}</span>
            <small>{{ item.raw.post }}</small>
          </div>
        </div>
      </template>

      <!-- status -->
      <template #item.status="{ item }">
        <VChip :color="resolveStatusVariant(item.raw.is_opening).color" size="small">
          {{ resolveStatusVariant(item.raw.is_opening).text }}
        </VChip>
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn color="primary">
            <VRow>
              <VDialog v-model="item.dialog3" width="1024">
                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item.raw ? item.raw : item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('UPDATE Voucher type') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol cols="12" sm="5" md="5">
                          <VTextField v-model="JournalName_edit" :label="$t('Journal Name edit en')" />
                        </VCol>
                        <VCol cols="12" sm="5" md="5">
                          <VTextField v-model="JournalName_loc_edit" :label="$t('Journal Name edit ar')" />
                        </VCol>
                        <VCol cols="12" sm="5" md="5">
                          <VTextField v-model="fyear_edit" :label="$t('Year')" />
                        </VCol>

                        <VCol cols="12" sm="5" md="5">
                          <VCombobox item-color="customColorValue" v-model="voucher_type_edit" :items="Vpucher_types"
                            :label="$t('Voucher type')" />
                        </VCol>

                        <VCol cols="12" sm="2" md="2">
                          <VCheckbox v-model="is_opening_edit" :label="`is_open: ${is_open.toString()}`" />
                        </VCol>

                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="dialog3 = false">
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
          <VBtn color="red" style="background: red;color: white;" @click="Delete(item.raw ? item.raw.id : item.id)">
            <VIcon icon="mdi-delete" style="font-size: 135%;" />
          </VBtn>

        </div>
      </template>
    </VDataTable>



  </div>

</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      Types: [],
      typeid: '',
      id: '',

      is_open: '',
      JournalName: '',
      JournalName_loc: '',
      Fyear: '',
      is_opening_edit: '',
      Vpucher_types: ['JV', 'RVS', 'PVS'],

      Vpucher_type: '',



      JournalName_edit: '',
      JournalName_loc_edit: '',
      fyear_edit: '',
      room_type_pax_edit: '',
      voucher_type_edit: '',
      room_type_number_edit: '',
      room_type_rent_edit: '',


      URL: window.location.origin,


    }
  },



  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllTypes()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    getAllTypes() {
      axios
        .get(`${this.URL}/api/JournalVoucherType`)
        .then(res => {
          this.Types = res.data.data
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
          this.deleteData()
          Toast.fire({
            icon: 'success',
            title: `Data ${this.JournalName} Deleted successfully`,
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

    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/JournalVoucherType/`,
          {
            name: this.JournalName,
            name_loc: this.JournalName_loc,
            fyear: this.Fyear,
            is_opening: this.is_open,
            voucher_type: this.Vpucher_type,


          },
        )



        this.JournalName = ''
        this.JournalName_loc = ''
        this.Fyear = ''
        this.is_open = ''
        this.Vpucher_type = ''


        this.getAllTypes()
        this.dialog = false
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },

    async Delete(GetId) {
      this.typeid = GetId
      this.DeleteAlert()
    },

    deleteData() {
      axios
        .delete(`${this.URL}/api/JournalVoucherType/${this.typeid}`)
        .then(response => (this.Types = response.data))
      this.getAllTypes()
    },

    async Updates(Getdata) {

      this.typeid = Getdata

      this.JournalName_edit = Getdata.name
      this.JournalName_loc_edit = Getdata.name_loc
      this.fyear_edit = Getdata.Fyear

      this.is_opening_edit = Getdata.is_opening,
        this.voucher_type_edit = Getdata.voucher_type




    },

    async updateUser() {
      console.log(this.is_opening_edit)
      try {
        const user = await axios.put(

          `${this.URL}/api/JournalVoucherType/${this.typeid.id}`,
          {

            name: this.JournalName_edit,
            name_loc: this.JournalName_loc_edit,

            is_opening: this.is_opening_edit,
            voucher_type: this.voucher_type_edit,
          },
        )

        this.getAllTypes()
        this.dialog3 = false
        this.UpdateAlert()

        console.log(this.is_opening_edit)

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

.AddRow {
  display: flex;
  flex: 1 1 auto;
  flex-wrap: wrap;
  justify-content: right;
  margin: -12px;
}
</style>
