<script setup>
import { avatarText } from '@/@core/utils/formatters'
import { VDataTable } from 'vuetify/labs/VDataTable'

// headers
const headers_en = [

  {
    title: 'NAME',
    key: 'floor_name',
  },
  {
    title: 'name loc',
    key: 'floor_name_loc',
  },

  {
    title: 'no room',
    key: 'no_of_rooms',
  },
  {
    title: 'active',
    key: 'active',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

// headers
const headers_ar = [

  {
    title: 'NAME',
    key: 'floor_name',
  },
  {
    title: 'name loc',
    key: 'floor_name_loc',
  },

  {
    title: 'no room',
    key: 'no_of_rooms',
  },
  {
    title: 'active',
    key: 'active',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const resolveStatusVariant = active => {
  if (active === 0)
    return {
      color: 'error',
      text: 'Not Active',
    }
  else if (active === 1)
    return {
      color: 'success',
      text: 'Active',
    }
}
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCardText class="AddRow">
      <VCol cols="6" md="4">
        <AppTextField v-model="search" density="compact" :placeholder="$t('Search')" append-inner-icon="tabler-search"
          single-line hide-details dense outlined />
      </VCol>
      <VCol cols="6" md="4">
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }" v-if="$can('create', 'floor')">
            <VBtn v-bind="props">
              {{ $t('Add Floors') }}
            </VBtn>
          </template>

          <VCard>
            <VCardTitle>
              <span class="text-h5">Add New Floor</span>
            </VCardTitle>
            <VForm ref="form">
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="floor_name" label="floor name"  persistent-placeholder type="text" @keyup.enter="goNext($event.target)"/>
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="floor_name_loc" label="اسم الطابق" persistent-placeholder type="text"  @keyup.enter="goNext($event.target)" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="no_of_rooms"  :label="$t('number of rooms')" persistent-placeholder type="number"
                                @keyup.enter="goNext($event.target)"/>
                  </VCol>



                  <VCol cols="12" sm="6" md="6">
                    <VCheckbox v-model="active" :label="`Floor active: ${active.toString()}`" />
                  </VCol>
                </VRow>
              </VContainer>
            </VCardText>
            </VForm>
            <VCardActions>
              <VSpacer />
              <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                 {{$t('Close')}}
              </VBtn>
              <VBtn color="blue-darken-1" variant="text" @click="Add">
                 {{$t('Submit')}}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable :headers="$i18n.locale === 'en' ? headers_en : headers_ar" :items="Floors" :search="search" :items-per-page="10">
      <!-- full name -->
      <template #item.full_name="{ item }">
        <div class="d-flex align-center">
          <!-- avatar -->
          <VAvatar size="32" :color="item.avatar ? '' : 'primary'"
            :class="item.avatar ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.avatar ? 'tonal' : undefined">
            <VImg v-if="item.avatar" :src="item.avatar" />
            <span v-else>{{ avatarText(item.raw?item.raw.full_name:item.full_name) }}</span>
          </VAvatar>

          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text--primary text-truncate">{{ item.full_name }}</span>
            <small>{{ item.post }}</small>
          </div>
        </div>
      </template>

      <!-- status -->
      <template #item.active="{ item }">
        <VChip :color="resolveStatusVariant(item.raw?item.raw.active:item.active).color" size="small">
          {{ resolveStatusVariant(item.active).text }}
        </VChip>
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn color="primary" v-if="$can('edit', 'floor')">
            <VRow>
              <VDialog v-model="item.dialog3" width="1024">
                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>

                <VCard>
                  <VCardTitle>
                    <span class="text-h5">UPDATE New Floor</span>
                  </VCardTitle>
                  <VForm ref="form">
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="floor_name_edit" label="floor type name"  type="text"  @keyup.enter="goNext($event.target)"
                            persistent-placeholder />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="floor_name_loc_edit" label="اسم الطابق" type="text"  @keyup.enter="goNext($event.target)"
                            persistent-placeholder />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="no_of_rooms_edit" :label="$t('number of rooms')"  persistent-placeholder  @keyup.enter="goNext($event.target)"
                            type="number" />
                        </VCol>




                        <VCol cols="12" sm="6" md="6">
                          <VCheckbox v-model="active_edit" persistent-placeholder
                            :label="`Floor active: ${active_edit.toString()}`" />
                        </VCol>
                      </VRow>
                    </VContainer>
                  </VCardText>
                  </VForm>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="item.dialog3 = false">
                      {{$t('Close')}}
                    </VBtn>
                    <VBtn color="blue-darken-1" variant="text" @click="updateUser">
                       {{$t('Submit')}}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>

          <VBtn @click="Delete(item.raw.id)" v-if="$can('delete', 'floor')" color="red" style="background: red;color: white">
            <VIcon icon="mdi-delete"   style="font-size: 135%"/>
          </VBtn>
        </div>
      </template>
    </VDataTable>

  </div>
</template>

<script>
import axios from "@axios"


export default {
  name: "FloorTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      Floors: [],
      floorid: '',
      id: '',
      perPage: 10,
      floor_name: '',
      floor_name_loc: '',
      no_of_rooms: '',
      sort_order: '',
      active: true,
      count: 0,

      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 5,

      pageSizes: [5, 10, 15, 20, 25, 30],


      floor_name_edit: '',
      floor_name_loc_edit: '',
      no_of_rooms_edit: '',
      sort_order_edit: '',
      active_edit: '',

      URL: window.location.origin,


    }
  },






  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllFloors()
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
    // goNext(elem) {
    //   const currentIndex = Array.from(elem.form.elements).indexOf(elem);
    //
    //   const nextIndex = currentIndex < elem.form.elements.length - 1 ? currentIndex + 1 : 0;
    //
    //   const nextElem = elem.form.elements[nextIndex];
    //
    //   if (nextElem) {
    //     nextElem.focus();
    //   }
    // },
    getAllFloors() {
      axios
        .get(`${this.URL}/api/floor`)
        .then(res => {

          this.Floors = res.data

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

    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/floor`,
          {
            floor_name: this.floor_name,
            floor_name_loc: this.floor_name_loc,
            no_of_rooms: this.no_of_rooms,
            // sort_order: this.sort_order,
            active: this.active,
          },
        )

        this.floor_name = ''
        this.floor_name_loc = ''
        this.no_of_rooms = ''
        // this.sort_order = ''
        this.active = true

        this.dialog = false


        this.getAllFloors()
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },


    async Delete(GetId) {
      this.floorid = GetId
      console.log('GetId',GetId)
      console.log('this.floorid',this.floorid)
      this.DeleteAlert()
    },


    deleteData() {

      axios
        .delete(`${this.URL}/api/floor/${this.floorid}`)
        .then(response => (this.Floors = response.data.data))
      this.getAllFloors()
    },

    async Updates(Getdata) {

      this.floorid = Getdata
      this.floor_name_edit = Getdata.floor_name
      this.floor_name_loc_edit = Getdata.floor_name_loc
      this.no_of_rooms_edit = Getdata.no_of_rooms
      // this.sort_order_edit = Getdata.sort_order
      this.active_edit = Getdata.active == 0 ? false : true



    },

    async updateUser() {
      try {
        const user = await axios.put(

          `${this.URL}/api/floor/${this.floorid.id}`,
          {


            floor_name: this.floor_name_edit,
            floor_name_loc: this.floor_name_loc_edit,
            no_of_rooms: this.no_of_rooms_edit,
            // sort_order: this.sort_order_edit,
            active: this.active_edit,

          },

        )

        this.getAllFloors()
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

.AddRow {
  display: flex;
  flex: 1 1 auto;
  flex-wrap: wrap;
  justify-content: right;
  margin: -12px;
}
</style>
