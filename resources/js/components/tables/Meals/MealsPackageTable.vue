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
    title: 'package code',
    key: 'package_code',
  },
  {
    title: 'name',
    key: 'name',
  },


  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

// headers
const headers_ar = [
  {
    title: 'package code',
    key: 'package_code',
  },
  {
    title: 'name_loc',
    key: 'name_loc',
  },



  {
    title: 'ACTIONS',
    key: 'actions',
  },
]
</script>

<template>
  <div>
    <Breadcrumb  class="d-print-none"  />
    <VCardText
      class="AddRow"
      style="display: flex;justify-content: space-between"
    >
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
              {{ $t('Add meal package') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add meal package') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="4"
                    sm="4"
                    md="4"
                  >
                    <VTextField
                      v-model="Package_code"
                      :label="$t('Package code')"
                      persistent-placeholder
                      type="text"
                    />
                  </VCol>
                  <VCol
                    cols="4"
                    sm="4"
                    md="4"
                  >
                    <VTextField
                      v-model="meal_name"
                      :label="$t('meal_name')"
                      type="text"
                      persistent-placeholder
                    />
                  </VCol>
                  <VCol
                    cols="4"
                    sm="4"
                    md="4"
                  >
                    <VTextField
                      v-model="meal_name_loc"
                      :label="$t('meal_name_loc')"
                      type="text"
                      persistent-placeholder
                    />
                  </VCol>


                  <VCol >
                    <VSelect item-color="customColorValue"
                      v-model="MealId"
                      :items="formattedMeals"
                      :label="$t('Meals id')"
                      item-title="formattedTitle"
                      item-value="id"
                      chips
                      multiple
                      closable-chips
                    />
                  </VCol>    <VCol
                  cols="3"
                  md="3"


                >
                  <VTextField
                    v-model="meal_price"
                    readonly
                    :label="$t('Total Price')"
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
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="meals"
      :search="search"
      :items-per-page="10"
    >
      <template #item.meal="{ item }">
        <div
          v-for="mealItem in item.props.title.meal"
          :key="mealItem.id"
          style="display: inline-block"
        >
          <VChip
            class="ma-2 w-55"
            style="color:blue;padding: 15px"
          >
            {{ mealItem.meal_type }}
          </VChip>
        </div>
      </template>
      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
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
                    <span class="text-h5">Update Meal Package</span>
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
                            v-model="package_code_edit"
                            :label="$t('Package code')"
                          />
                        </VCol>

                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="name_edit"
                            :label="$t('meal_name')"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="name_loc_edit"
                            :label="$t('meal_name_loc')"
                            type="text"
                          />
                        </VCol>


                        <VCol>
                          <VSelect item-color="customColorValue"
                            v-model="meal_type_edit"
                            :items="formattedMeals"
                            :label="$t('meal type edit')"
                            item-title="formattedTitle"
                            item-value="id"
                            chips
                            multiple
                            closable-chips
                          />


                        </VCol>
                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCol
                    cols="6"
                    md="6"
                    class="mx-auto"
                    style="padding: 0;margin: 0"
                  >
                    <VTextField
                      v-model="meal_price"
                      readonly
                      :label="$t('Total Price')"
                      type="text"
                    />
                  </VCol>
                  <VCardActions>
                    <VSpacer />
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="item.dialog3 = false"
                    >
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="updateUser"
                    >
                      {{ $t('Update') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
          <VBtn
            color="red"
            style="background: red;color: white"
            @click="Delete(item.raw?item.raw.id:item.id)"
          >
            <VIcon
              icon="mdi-delete"
              style="font-size: 135%"
            />
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
      meal_price: null,
      doubleData: [],
      search: '',
      dialog: false,
      dialog3: false,
      meals: [],
      Package_code_edit: '',
      name_edit: '',
      name_loc_edit: '',
      price_edit: '',
      ledger_id_edit: '',
      meal_type_edit: [],
      Package_code: '',
      meal_name: '',
      meal_name_loc: '',
      price: '',
      ledger_id: '',
      Meals: [],
      URL: window.location.origin,
      MealsType: [],
      mealtype: [],

      MealId: [],
      All: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    // eslint-disable-next-line vue/no-dupe-keys,vue/return-in-computed-property
    MealsTypeId() {
      for (let i = 0; i <= this.MealsType.length - 1; i++) {

        this.doubleData.push(...[this.MealsType[i].name + ' | ' + this.MealsType[i].price])

      }

    },

    // eslint-disable-next-line vue/return-in-computed-property

    formattedMeals() {
      // console.log('this.MealsType',this.MealsType.map(meal => ({
      //   ...meal,
      //
      // })))
      return this.MealsType.map(meal => ({
        ...meal,
        formattedTitle: `${meal.name} - ${meal.price}$`,

      }))

    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    meal_type_edit() {
      // Handle the change in meal_type_edit here
      this.updateMealPrice()
    },
    MealId() {
      // Handle the change in meal_type_edit here
      this.AddMealPrice()
    },



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
        .get(`${this.URL}/api/meal-packages`)

        .then(response => (this.meals = response.data))
    },

    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/meal-packages`,
          {
            package_code: this.Package_code,
            name: this.meal_name,
            name_loc: this.meal_name_loc,
            meal_id: this.MealId,

          },
        )

          this.Package_code='',
          this.meal_name='',
          this.meal_name_loc='',
            this.MealId=null,
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
        .delete(`${this.URL}/api/meal-packages/${this.itemid}`)
        .then(response => (this.Rooms = response.data))
      this.getAllData()
    },
    async AddMealPrice() {
      this.meal_price = 0
      // Your existing logic to update meal_price based on meal_type_edit
      this.meal_price = this.MealId
        .map(id => this.formattedMeals.find(meal => meal.id === id))
        .map(meal => meal.price)
        .reduce((prev, curr) => prev + curr, 0)
    },
    async updateMealPrice() {
      // Your existing logic to update meal_price based on meal_type_edit
      this.meal_price = this.meal_type_edit
        .map(id => this.formattedMeals.find(meal => meal.id === id))
        .map(meal => meal.price)
        .reduce((prev, curr) => prev + curr, 0)
    },
    async Updates(Getdata) {
      console.log('this.meal_type_edit', Getdata.meal)

      this.itemid = Getdata


      this.package_code_edit= Getdata.package_code
      this.name_edit= Getdata.name
      this.name_loc_edit= Getdata.name_loc


      this.meal_type_edit= Getdata.meal.map(ele=>ele.id)



    },

    async updateUser() {
      console.log( 'meal_id:', this.meal_type_edit)
      try {
        const user = await axios.put(
          `${this.URL}/api/meal-packages/${this.itemid.id}`,
          {
            package_code: this.package_code_edit,
            name: this.name_edit,
            name_loc: this.name_loc_edit,

            meal_id: this.meal_type_edit.map(ele=>ele),


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
      axios.get(`${this.URL}/api/meals`).then(res=>(this.MealsType=res.data))
    },


  },

}
</script>

