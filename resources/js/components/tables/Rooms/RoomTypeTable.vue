<script setup>
import { avatarText } from '@/@core/utils/formatters'
import {
  betweenValidator,
  requiredValidator,
} from '@validators'
import { VDataTable } from 'vuetify/labs/VDataTable'

// headers
const headers_en = [
  {
    title: '#',
    key: 'id',
  },
  {
    title: 'code',
    key: 'rm_type_code',
  },
  {
    title: 'NAME',
    key: 'rm_type_name',
  },
  {
    title: 'name loc',
    key: 'rm_type_name_loc',
  },
  {
    title: 'pax',
    key: 'def_pax',
  },
  {
    title: 'no room',
    key: 'no_of_rooms',
  },
  {
    title: 'rentable',
    key: 'rm_type_rentable',
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
    title: 'code',
    key: 'rm_type_code',
  },
  {
    title: 'NAME',
    key: 'rm_type_name',
  },
  {
    title: 'name loc',
    key: 'rm_type_name_loc',
  },
  {
    title: 'pax',
    key: 'def_pax',
  },
  {
    title: 'no room',
    key: 'no_of_rooms',
  },
  {
    title: 'rentable',
    key: 'rm_type_rentable',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const resolveStatusVariant = rm_type_rentable => {
  if (rm_type_rentable === 0)
    return {
      color: 'error',
      text: 'none rentable',
    }
  else if (rm_type_rentable === 1)
    return {
      color: 'warning',
      text: 'rentable',
    }
}



 </script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
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
          tabindex="-1"
        />
      </VCol>
      <VCol
        cols="6"
        md="4"
      >
        <VDialog
          v-model="dialog"
          width="1024"
        >
          <template
            v-if="$can('create', 'roomtype')"
            #activator="{ props }"
          >
            <VBtn
              v-bind="props"
              @click="OpenDialog"
            >
              {{ $t('Add Room Type') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add Room Type') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                       ref="roomTypeCodeInput"
                      v-model="room_type_code"
                      :label="$t('room type code')"
                      persistent-placeholder
                       tabindex="1"
                       @keyup.enter="moveToNextInput('roomTypeCodeInput', 'roomTypeNameInput')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="roomTypeNameInput"
                      v-model="room_type_name"
                      :label="$t('room type name en')"
                      persistent-placeholder
                       @keyup.enter="moveToNextInput('roomTypeNameInput', 'roomTypeNameInputAr')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="roomTypeNameInputAr"

                      v-model="room_type_loc"
                      :label="$t('room type name ar')"
                      persistent-placeholder
                      type="text"
                      class="vText"
                      @keyup.enter="moveToNextInput('roomTypeNameInputAr', 'roomTypePax')"


                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="roomTypePax"

                      v-model="room_type_pax"
                      :label="$t('def pax')"
                      persistent-placeholder
                      type="number"
                      class="vText"
                      @keyup.enter="moveToNextInput('roomTypePax', 'roomTypePrice')"


                    />
                  </VCol>

                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="roomTypePrice"

                      v-model="room_type_price"
                      :label="$t('def price')"
                      persistent-placeholder
                      type="number"
                      class="vText"
                      @keyup.enter="moveToNextInput('roomTypePrice', 'roomTypeNumber')"


                    />
                  </VCol>


                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="roomTypeNumber"

                      v-model="room_type_number"
                      :label="$t('number of rooms')"
                      persistent-placeholder
                      type="number"
                      class="vText"
                      @keyup.enter="moveToNextInput('roomTypeNumber', 'monthly_rate')"


                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="monthly_rate"
                      v-model="monthly_rate"
                      :label="$t('monthly rate')"
                      persistent-placeholder
                      type="number"
                      class="vText"
                      @keyup.enter="moveToNextInput('monthly_rate', 'yearly_rate')"


                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      ref="yearly_rate"
                      v-model="yearly_rate"
                      :label="$t('yearly rate')"
                      persistent-placeholder
                      type="number"
                      class="vText"
                      @keyup.enter="moveToNextInput('yearly_rate', 'roomCheck')"


                    />
                  </VCol>


                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VCheckbox
                      ref="roomCheck"
                      v-model="room_type_rent"
                      :label="`room type rentable ${room_type_rent.toString()}`"


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
                @click="dialog = false"

              >
                {{ $t('Close') }}
              </VBtn>
              <VBtn
                color="blue-darken-1"
                variant="text"
                @click="Add"
              >
                {{ $t('Submit') }}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="Types"
      :search="search"
      :items-per-page="10"
    >
      <!-- full name -->
      <template #item.full_name="{ item }">
        <div class="d-flex align-center">
          <!-- avatar -->
          <VAvatar
            size="32"
            :color="item.raw.avatar ? '' : 'primary'"
            :class="item.raw.avatar ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.raw.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="item.raw.avatar"
              :src="item.raw.avatar"
            />
            <span v-else>{{ avatarText(item.raw.full_name) }}</span>
          </VAvatar>

          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.full_name }}</span>
            <small>{{ item.raw.post }}</small>
          </div>
        </div>
      </template>

      <!-- status -->
      <template #item.rm_type_rentable="{ item }">
        <VChip
          :color="resolveStatusVariant(item.raw.rm_type_rentable).color"
          size="small"
        >
          {{ resolveStatusVariant(item.raw.rm_type_rentable).text }}
        </VChip>
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn
            v-if="$can('edit', 'roomtype')"
            color="primary"
          >
            <VRow>
              <VDialog
                v-model="item.dialog3"
                width="1024"
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    @click="Updates(item.raw?item.raw:item)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('UPDATE New Room Type') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_code_edit"
                            :label="$t('room type code')"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_name_edit"
                            :label="$t('room type name en')"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_loc_edit"
                            :label="$t('room type name ar')"
                            type="text"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_pax_edit"
                            :label="$t('def pax')"
                            persistent-hint
                            type="number"
                          />
                        </VCol>

                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_price_edit"
                            :label="$t('def price')"
                            persistent-placeholder
                            type="number"
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="room_type_number_edit"
                            :label="$t('number of rooms')"
                            persistent-hint
                            type="number"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            ref="monthly_rate"
                            v-model="monthly_rate_edit"
                            :label="$t('monthly rate edit')"
                            persistent-placeholder
                            type="number"
                            class="vText"
                            @keyup.enter="moveToNextInput('monthly_rate_edit', 'yearly_rate_edit')"


                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            ref="yearly_rate"
                            v-model="yearly_rate_edit"
                            :label="$t('yearly rate edit')"
                            persistent-placeholder
                            type="number"
                            class="vText"
                            @keyup.enter="moveToNextInput('yearly_rate_edit', 'room_type_rent_edit')"


                          />
                        </VCol>

                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VCheckbox
                            v-model="room_type_rent_edit"
                            :label="`room type rentable: ${room_type_rent_edit.toString()}`"
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
                      @click="dialog3 = false"
                    >
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="updateUser"
                    >
                      {{ $t('Submit') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
          <VBtn
            v-if="$can('delete', 'roomtype')"
            color="red" style="background: red;color: white"
            @click="Delete(item.raw?item.raw.id:item.id)"
          >
            <VIcon icon="mdi-delete"   style="font-size: 135%"/>
          </VBtn>
        </div>
      </template>
    </VDataTable>
  </div>
</template>


<script>
import axios from "@axios"
import Swal from "sweetalert2"


export default {
  name: "RoomTypeTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      search: '',
      dialog: false,
      dialog3: false,
      Types: [],
      typeid: '',
      id: '',

      room_type_code: ref(''),
      room_type_name: '',
      room_type_loc: '',
      room_type_pax: '',
      room_type_price: '',
      room_type_number: '',
      room_type_rent: true,
      monthly_rate:'',
      yearly_rate:'',


      room_type_code_edit: ref(''),
      room_type_name_edit: '',
      room_type_loc_edit: '',
      room_type_pax_edit: '',
      room_type_price_edit: '',
      room_type_number_edit: '',
      room_type_rent_edit: '',
      monthly_rate_edit: '',
      yearly_rate_edit: '',

      count: 0,
      perPage: 10,
      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 5,
      pageSizes: [5, 10, 15, 20, 25, 30],
      URL: window.location.origin,


    }
  },


  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllTypes()
 
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    moveToNextInput(currentRef, nextRef) {
      if (this.$refs[nextRef] && this.$refs[nextRef].$el) {
        this.$refs[nextRef].$el.querySelector('input').focus()
      }
    },

    getAllTypes() {
      axios
        .get(`${this.URL}/api/room_types`)
        .then(res => {
          this.Types = res.data
        })
    },
    OpenDialog() {

      this.dialog = true
      this.$nextTick(() => {
        this.$refs.roomTypeCodeInput.focus()
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
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: message,
        showConfirmButton: false,
        timer: 2000,
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
            title: `Data Deleted successfully`,
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

     Add() {
     axios.post(
          `/api/room_types`,
          {
            rm_type_code: this.room_type_code,
            rm_type_name: this.room_type_name,
            rm_type_name_loc: this.room_type_loc,
            def_pax: this.room_type_pax,
            def_price: this.room_type_price,
            no_of_rooms: this.room_type_number,
            rm_type_rentable: this.room_type_rent,
            sort_order: 1,
            scth_type_code: 2,
            def_rate_code: 'qwwq',

            monthly_rate: this.monthly_rate,

            yearly_rate: this.yearly_rate,
          }
        ).then(res => {

       if (res.status === 200) {
         this.getAllTypes();
         this.dialog = false;
         this.insertAlert();
         this.room_type_code = ref('');
         this.room_type_name = '';
         this.room_type_loc = '';
         this.room_type_pax = '';
         this.room_type_price = '';
         this.room_type_number = '';
         this.room_type_rent = true;
       }
     })
    },

    async Delete(GetId) {
      this.typeid = GetId
      this.DeleteAlert()
    },


    deleteData() {
      axios
        .delete(`${this.URL}/api/room_types/${this.typeid}`)
        .then(response => (this.Types = response.data.data))
      this.getAllTypes()
    },



    async Updates(Getdata) {

      this.typeid = Getdata

      this.room_type_code_edit = Getdata.rm_type_code
      this.room_type_name_edit = Getdata.rm_type_name
      this.room_type_loc_edit = Getdata.rm_type_name_loc
      this.room_type_pax_edit = Getdata.def_pax
      this.room_type_price_edit = Getdata.def_price
      this.room_type_number_edit = Getdata.no_of_rooms
      this.monthly_rate_edit = Getdata.monthly_rate
      this.yearly_rate_edit = Getdata.yearly_rate
      this.room_type_rent_edit = Getdata.rm_type_rentable === 0 ? false : true
    },


    async updateUser() {
      try {
        const user = await axios.put(

          `${this.URL}/api/room_types/${this.typeid.id}`,
          {

            rm_type_code: this.room_type_code_edit,
            rm_type_name: this.room_type_name_edit,
            rm_type_name_loc: this.room_type_loc_edit,
            def_pax: this.room_type_pax_edit,
            def_price: this.room_type_price_edit,
            no_of_rooms: this.room_type_number_edit,
            rm_type_rentable: this.room_type_rent_edit,
            monthly_rate: this.monthly_rate_edit,
            yearly_rate: this.yearly_rate_edit,


            sort_order: 1,
            scth_type_code: 2,
            def_rate_code: 'qwwq',
          },
        )

        this.getAllTypes()
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
