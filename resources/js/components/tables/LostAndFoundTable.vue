<script setup>
const now = new Date()
const date = ref('')


const currentMonth = now.toLocaleString('default', { month: '2-digit' })
const currentYear = now.getFullYear()
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers_En = [

  {
    title: "description",
    key: 'description',
  },
  {
    title: "found date",
    key: 'found_date',
  },
  {
    title: "found_by",
    key: 'found_by',
  },
  {
    title: "delivery_status",
    key: 'delivery_status',
  },
  {
    title: "delivery_date",
    key: 'delivery_date',
  },
  {
    title: "delivery_to",
    key: 'delivery_to',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const headers_Ar = [

  {
    title: "description",
    key: 'description',
  },
  {
    title: "found date",
    key: 'found_date',
  },
  {
    title: "found_by",
    key: 'found_by',
  },
  {
    title: "delivery_status",
    key: 'delivery_status',
  },
  {
    title: "delivery_date",
    key: 'delivery_date',
  },
  {
    title: "delivery_to",
    key: 'delivery_to',
  },
  {
    title: 'العمليات',
    key: 'actions',
  },
]
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none"  />
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
          persistent
          scroll-strategy="none"
        >
          <template #activator="{ props }">
            <VBtn
              v-if="$can('create', 'lostandfound')"
              v-bind="props"
            >
              {{ $t('Add Lost And Found') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add Lost And Found') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="12"
                    sm="6"
                  >
                    <VSelect item-color="customColorValue"
                      v-model="Guests_selected"
                      :items="doubleData"
                      item-title="profile.first_name"
                      item-value="id"
                      :label="$t('Guests')"
                    />
                  </VCol>


                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="found_by"
                      :label="$t('found_by')"
                      persistent-placeholder
                      type="text"
                    />
                  </VCol>

                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextarea
                      v-model="description"
                      cols="12"
                      sm="6"
                      md="6"
                      :label="$t('Descriptions')"
                      persistent-placeholder
                      auto-grow
                      style="height: 10%;"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >


                    <VTextField  v-model="found_date" :label="$t('Found Date')"    readonly

                              @click="isDialogDate = !isDialogDate"/>
                  </VCol>
                  <VDialog
                    v-model="isDialogDate"
                    width="300"

                  >

                    <DialogCloseBtn @click="isDialogDate = false" />

                    <VCard style="margin: auto;padding: 20px">
                      <AppDateTimePicker v-model="found_date" :label="$t('found date')" type="date"
                                         :config="{  altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',inline:true }"
                                         disabled="disabled"
                                        />
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
      :items="LostGuest"
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
                scroll-strategy="none"
              >
                <template #activator="{ props }">
                  <div
                    v-if="$can('edit', 'lostandfound')"
                    v-bind="props"
                    @click="Updates(item.raw?item.raw:item)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </div>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('UPDATE Bussiness names') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol
                          cols="12"
                          sm="6"
                        >
                          <VSelect item-color="customColorValue"
                            v-model="delivery_by"
                            :items="users"
                            item-title="firstname"
                            item-value="id"
                            :label="$t('Delivery By')"
                          />
                          <br>
                          <VTextField
                            v-model="delivery_to"
                            :label="$t('Delivery To')"
                            persistent-placeholder
                            type="text"
                          />
                        </VCol>

                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <AppDateTimePicker
                            v-model="delivery_date"
                            :label="$t('Delivery Date')"
                            :config="{ inline: true, altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
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
        </div>
      </template>
    </VDataTable>
    {{ guestId }}
  </div>
</template>

<script>
import axios from "@axios"


export default {
  name: "LostGuestTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      doubleData: [],
      search: '',
      dialog: false,
      dialog3: false,
      LostGuest: [],
      itemid: '',





      description: '',
      found_date: '',
      found_by: '',
      delivery_status: 'pending',
      delivery_date: '',
      delivery_to: '',
      delivery_by: '',
      Guests_selected: '',


      isDialogDate:false,

      users: [],
      AllGuests: [],
      AllGuestsInHouse: [],


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
  computed: {
    guestId(){
      for (let i = 0;i<=this.AllGuestsInHouse.length-1;i++){
        // console.log(this.AllGuestsInHouse[i].profile)
        this.doubleData.push(...[this.AllGuestsInHouse[i].id + ' | ' +this.AllGuestsInHouse[i].profile.first_name])
      }

    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getUsers()
    this.getAllGuest()
    this.getGuestInHouse()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllGuest() {

      axios
        .get(`api/lost-and-found`)
        .then(response => (this.LostGuest = response.data.data))

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






    async Add() {
      try {
        const user = await axios.post(
          `${this.URL}/api/lost-and-found`,
          {
            guest_id: this.Guests_selected.split(' |')[0],
            description: this.description,
            found_date: this.found_date,
            found_by: this.found_by,
            delivery_status: 'pending',
          },
        )

        this.Guests_selected = '',
        this.description = '',
        this.found_date = '',
        this.found_by = '',

        this.getAllGuest()
        this.dialog = false
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },



    async Updates(Getdata) {
      this.itemid = Getdata

      this.delivery_to = Getdata.delivery_to
      this.delivery_by = Getdata.delivery_by
      this.delivery_date = Getdata.delivery_date
    },


    async updateUser() {


      try {
        const user = await axios.put(
          `${this.URL}/api/lost-and-found/${this.itemid.id}`,
          {
            // room_name_en: this.room_name_edit,

            delivery_status: 'closed',
            delivery_to: this.delivery_to,
            delivery_by: this.delivery_by,
            delivery_date: this.delivery_date,


          },
        )


        this.getAllGuest()
        this.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }

    },

    async getUsers() {
      axios
        .get(`${this.URL}/api/users`)
        .then(response => (this.users = response.data))
    },


    async getGuestInHouse() {
      axios
        .get(`${this.URL}/api/guestInhouse`)
        .then(response => (this.AllGuestsInHouse = response.data.data))
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
