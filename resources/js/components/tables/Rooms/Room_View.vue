<script setup>
import { ref } from 'vue'
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers_En = [

  {
    title: 'Room View',
    key: 'name',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const headers_Ar = [
 
  {
    title: 'واجهة الغرفة',
    key: 'name_loc',
  },
  {
    title: 'العمليات',
    key: 'actions',
  },
]
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
          <template #activator="{ props }">
            <VBtn v-bind="props">
              {{ $t('Add Room View') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Room View') }}</span>
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
                      v-model="name"
                      :label="$t('room view name')"
                      persistent-placeholder
                      type="text"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="name_loc"
                      :label="$t('room view name_loc')"
                      persistent-placeholder
                      type="text"
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
      :headers="$i18n.locale === 'en' ? headers_En : headers_Ar"
      :items="Types"
      :search="search"
      :items-per-page="10"
    >
      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-2">
          <VBtn color="primary">
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
                    <span class="text-h5">{{ $t('UPDATE Room View') }}</span>
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
                            v-model="name_edit"
                            :label="$t('room view name edit')"
                            persistent-placeholder
                            type="text"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="name_loc_edit"
                            :label="$t('room view name loc edit')"
                            persistent-placeholder
                            type="text"
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


export default {
  name: "RoomView",

  // eslint-disable-next-line vue/component-api-style
  data(){
    return {
      search: '',
      dialog: false,
      dialog3: false,

      itemid: '',

      Types: [],
      typeid: '',



      name: '',
      name_loc: '',


      name_edit: '',
      name_loc_edit: '',

      URL: window.location.origin,

    }
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {

    this.getAllRoomView()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllRoomView(){

      axios
        .get(`${this.URL}/api/viewGarden`)
        .then(response => (this.Types=response.data.data))
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
    async  Add(){

      try {
        const user = await axios.post(
          `${this.URL}/api/viewGarden`,
          {
            name: this.name,
            name_loc: this.name_loc,

          },
        )

        this.name='',
        this.name_loc='',

        this.getAllRoomView()
        this.dialog = false
        this.insertAlert()
      } catch(e) {
        console.log(e)
      }
    },


    async Delete(GetId) {
      this.typeid = GetId
      this.DeleteAlert()
    },


    deleteData() {
      axios
        .delete(`${this.URL}/api/viewGarden/${this.typeid}`)
        .then(response => (this.Types = response))
      this.getAllRoomView()
    },

    async  Updates(Getdata){
      this.itemid = Getdata

      this.name_edit = Getdata.name
      this.name_loc_edit = Getdata.name_loc
    },


    async updateUser() {


      try {
        const user = await axios.put(
          `${this.URL}/api/viewGarden/${this.itemid.id}`,
          {
            // room_name_en: this.room_name_edit,


            name: this.name_edit,
            name_loc: this.name_loc_edit,


          },
        )

        this.getAllRoomView()
        this.dialog3 = false
        this.UpdateAlert()
      } catch(e) {
        console.log(e)
      }

    },




  },

}
</script>

<style>
/* width */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: mediumpurple;
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, .6);  ;
}
.AddRow{
  display: flex;
  flex-wrap: wrap;
  flex: 1 1 auto;
  margin: -12px;
  justify-content: right;

}
</style>
