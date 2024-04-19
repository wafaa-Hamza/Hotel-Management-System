<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { avatarText } from '@/@core/utils/formatters'
import {
  betweenValidator,
  requiredValidator,
  integerValidator,
  lengthValidator,
} from '@validators'

// headers
const headers_en = [
  {
    title: 'meal_code',
    key: 'meal_code',
  },
  {
    title: 'name',
    key: 'name',
  },

  {
    title: 'price',
    key: 'price',
  },
  {
    title: 'ledger_id',
    key: 'ledger_id',
  },

  {
    title: 'meal type',
    key: 'meal_type',
  },


  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

// headers
const headers_ar = [
  {
    title: 'meal_code',
    key: 'meal_code',
  },
  {
    title: 'name_loc',
    key: 'name_loc',
  },

  {
    title: 'price',
    key: 'price',
  },
  {
    title: 'ledger_id',
    key: 'ledger_id',
  },
  {
    title: 'meal type',
    key: 'meal_type',
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
    <VCardText class="AddRow" style="display: flex;justify-content: space-between">
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
      <VCol cols="6" md="4" >
        <VDialog v-model="dialog" width="1024" scroll-strategy="none">
          <template #activator="{ props }">
            <VBtn v-bind="props">
              {{ $t('Add meal') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>

              <span class="text-h5">{{ $t('Add meal') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="meal_code" :label="$t('meal_code')"  persistent-placeholder type="text" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="meal_name" :label="$t('meal_name')"  type="text" persistent-placeholder />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="meal_name_loc" :label="$t('meal_name_loc')" type="text" persistent-placeholder
                    />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="price" :label="$t('price')" persistent-placeholder type="number" />
                  </VCol>


                  <VCol cols="12" sm="6" md="6">
                    <VCombobox v-model="ledger_id" :label="$t('Ledger id')" :items="Ledgers"   item-title="name"  item-color="customColorValue"  />
                  </VCol>

                  <VCol cols="12" sm="6" md="6">
                    <VCombobox v-model="mealtype" :label="$t('meal type')" :items="MeallType"    item-color="customColorValue"  />
                  </VCol>

                </VRow>
              </VContainer>
            </VCardText>
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
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="meals"
      :search="search"
      :items-per-page="10"
    >


      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn color="primary" >
            <VRow>


              <VDialog v-model="item.dialog3" width="1024" scroll-strategy="none">

                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item.raw?item.raw:item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                    <VCardTitle>
                    <span class="text-h5">Update Meal</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="meal_code_edit"   :label="$t('meal code')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="name_edit"  :label="$t('Meal Name')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="name_loc_edit"  :label="$t('Meal name loc')" type="text" />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="price_edit"   :label="$t('price')" persistent-hint
                                      type="number" />
                        </VCol>


                        <VCol cols="12" sm="6" md="6">
                          <VCombobox v-model="ledger_id_edit" :items="Ledgers"  :label="$t('Ledger id')" item-title="name"   item-color="customColorValue"   />
                         </VCol>

                        <VCol cols="12" sm="6">
                          <VCombobox v-model="meal_type_edit" :items="MeallType" :label="$t('meal type edit')"  item-color="customColorValue"
                          />
                        </VCol>



                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="item.dialog3 = false">
                      {{$t('Close')}}
                    </VBtn>
                    <VBtn color="blue-darken-1" variant="text" @click="updateUser">
                      {{ $t('Update')}}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>

            </VRow>
          </VBtn>
          <VBtn  @click="Delete(item.raw?item.raw.id:item.id)" color="red" style="background: red;color: white">
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


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      meals: [],
      meal_code_edit: '',
      name_edit: '',
      name_loc_edit: '',
      price_edit: '',
      ledger_id_edit: '',
      meal_type_edit: '',
      meal_code: '',
      meal_name: '',
      meal_name_loc: '',
      price: '',
      ledger_id: '',
      Ledgers: [],
      URL: window.location.origin,

      MeallType:['BF','LN','DI','IF','SU','OT'],
      mealtype:''
    }
  },




  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllData()
    this.getLedger()
  },


  // eslint-disable-next-line vue/component-api-style
  methods: {

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
        title: `${this.meal_name} Added successfully`,
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
            title: `${this.meal_name} Deleted successfully`,
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
        title: `${this.meal_name} updateed successfully`,
        color: 'mediumpurple',
        timer: 5000,
      })
    },

    getAllData() {
      axios
        .get(`${this.URL}/api/meals`)

        .then(response => (this.meals = response.data))
    },

    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/meals`,
          {
            meal_code: this.meal_code,
            name: this.meal_name,
            name_loc: this.meal_name_loc,
            price: this.price,
            ledger_id: this.ledger_id.id,
            meal_type: this.mealtype,
          },
        )


        this.getAllData()
        this.dialog = false
        this.insertAlert()
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
        .delete(`${this.URL}/api/meals/${this.itemid}`)
        .then(response => (this.Rooms = response.data))
      this.getAllData()
    },

    async Updates(Getdata) {
      this.itemid = Getdata



      this.meal_code_edit= Getdata.meal_code
      this.name_edit= Getdata.name
      this.name_loc_edit= Getdata.name_loc
      this.price_edit= Getdata.price
      this.ledger_id_edit= Getdata.ledger.name
      this.meal_type_edit= Getdata.meal_type


    },

    async updateUser() {
      try {
        const user = await axios.put(
          `${this.URL}/api/meals/${this.itemid.id}`,
          {
            meal_code: this.meal_code_edit,
            name: this.name_edit,
            name_loc: this.name_loc_edit,
            price: this.price_edit,
            ledger_id: this.ledger_id_edit.id,
            meal_type: this.meal_type_edit,
          },
        )


        this.getAllData()
        this.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }

    },

    getLedger(){
      axios.get(`${this.URL}/api/ledger`).then(res=>(this.Ledgers=res.data))
    },

  },

}
</script>

