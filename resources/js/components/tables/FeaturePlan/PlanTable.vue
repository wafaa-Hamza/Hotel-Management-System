<script setup>
import {
  lengthValidator,
  requiredValidator,
  integerValidator,
  betweenValidator,
} from '@validators'
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div style="float: right;width: 45%;display: flex;justify-content: space-between;">
      <VRow justify="">
        <div style="width: 65%;display: flex;justify-content: space-between">
          <VSelect item-color="customColorValue" v-model="pageSize" :label="$t('row')" :items="pageSizes" :value="pageSize" variant="solo"
            style="width: 18%;" />
          <VSpacer />

          <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 70%;" />
        </div>
        <VSpacer />
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'plan')">
              {{ $t('Add plan') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5"> {{ $t('Add New Plan') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="12" md="12">
                    <VTextField v-model="plan_name" :label="$t('plan Name')" persistent-placeholder type="text" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="periodicity_of_plan"  :label="$t('periodicity_of_plan')" type="number"
                      persistent-placeholder />
                  </VCol>

                  <VCol cols="12" md="6" sm="6">
                    <VSelect item-color="customColorValue" v-model="periodicity_type" :items="periodicity_types" item-title="periodicity type"
                      item-value="plan_id" persistent-placeholder label="periodicity type" />
                  </VCol>


                  <VCheckbox v-for="(item, index) in features" :key="item.id" v-model="features_type"
                    :label="item.feature_name" :value="item.feature_id"
                    style="width:150px; display: flex ;justify-content: space-around"
                    @input="saveFeature(item.feature_id)" />


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
      </VRow>
    </div>


    <div style="float: left;width: 14%;display: flex;justify-content: space-between">
      <VBtn class="btn" @click="exportExecl">
        {{ $t('export') }}
      </VBtn>
      <VBtn class="btn" @click="Downpdf">
        {{ $t('PDF') }}
      </VBtn>
    </div>

    <br>
    <br>
    <br>

    <VTable style="table-layout: fixed">
      <thead>
        <tr>
          <th>
            {{ $t('Num') }}
          </th>
          <th>
            {{ $t('plan name') }}
          </th>
          <th>
            {{ $t('periodicity of plan') }}
          </th>
          <th>
            {{ $t('periodicity type') }}
          </th>
          <th>
            {{ $t('feature name') }}
          </th>
          <th v-if="$can('edit', 'plan')">
            {{ $t('Update') }}
          </th>
          <th v-if="$can('delete', 'plan')">
            {{ $t('Delete') }}
          </th>
        </tr>
      </thead>


      <tbody>
        <tr v-for="(item, index) in filterData" :key="item.id">
          <td> {{ ++index }}</td>

          <td>{{ item.plan_name }}</td>
          <td>{{ item.periodicity_of_plan }}</td>
          <td>{{ item.periodicity_type }}</td>
          <br>

          <td v-for="(element, index) in item.features" :key="element.id" style="display: block;">
            <VChip class="ma-2" append-icon="mdi-star" style="color: gold;">
              <span style="color: black">{{ element.name }}</span>
            </VChip>
          </td>





          <td v-if="$can('edit', 'plan')">
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="item.dialog3" width="1024">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(item)">
                      <VIcon icon="mdi-file-edit-outline" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">Update User Profile</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12" sm="12" md="12">
                            <VTextField v-model="plan_name_edit"  :label="$t('plan Name')"  />
                          </VCol>

                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="periodicity_of_plan_edit"  :label="$t('periodicity_of_plan')" type="number" />
                          </VCol>

                          <VCol cols="12" md="6" sm="6">
                            <VSelect item-color="customColorValue" v-model="periodicity_type_edit" :items="periodicity_types"
                              item-title="periodicity type" item-value="plan_id" persistent-placeholder
                              label="periodicity type" />
                          </VCol>



                          <VCheckbox v-for="(item, index) in features" :key="item.id" v-model="features_type_edit"
                            :value="item.feature_id" style="width:150px; display: flex ;justify-content: space-around"
                            :label="item.feature_name" @input="saveFeature(item.feature_id)" />
                        </VRow>
                      </VContainer>
                    </VCardText>
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
          </td>

          <td style="table-layout: fixed " v-if="$can('delete', 'plan')">
            <VBtn  @click="Delete(item.plan_id)" color="red" style="background: red;color: white">
              <VIcon icon="mdi-delete"   style="font-size: 135%" />
            </VBtn>
          </td>
        </tr>
      </tbody>
      <br>
      <caption>{{ $t('List Of Data') }}( 1 )</caption>
    </VTable>
    <div style="float: right">
      <VPagination v-if="pageInfo" v-model="pageInfo.current_page" :length="Math.ceil(pageInfo.total / pageSize)"
        total-visible="7" :size="43" :next="pageInfo.next_page_url" :per-page="pageInfo.per_page"
        @click="nextPage(pageInfo.per_page, pageInfo.current_page)" />
    </div>
  </div>
</template>

<script>
import axios from "@axios"
import feature from "@/pages/Plans-Features/feature.vue"

// import xlsx from "xlsx/dist/xlsx.full.min"
// import pdfMake from "pdfmake/build/pdfmake"
// import pdfFonts from "pdfmake/build/vfs_fonts"
// import router from "@/router"
//
// pdfMake.vfs = pdfFonts.pdfMake.vfs

export default {
  name: "PlanTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      plans: [],
      features: [],
      planId: '',
      features_type: [],
      features_type_selected_ids: [],

      plan_id: '',

      grace_days: '',
      plan_name: '',
      periodicity_of_plan: '',

      periodicity_type: '',
      periodicity_types: ['Day', 'Week', 'Month', 'Year'],


      plan_name_edit: '',
      periodicity_of_plan_edit: '',
      periodicity_type_edit: '',
      features_type_edit: [],



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
  computed: {
    filterData() {

      return this.pagi.filter(user => user.plan_name.includes(this.search))
    },
  },

  // eslint-disable-next-line vue/component-api-style
  watch: {
    pageSize() {
      this.getAllPaginate()
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllplans()
    this.getFeature()
    this.getAllPaginate()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllPaginate() {
      axios
        .get(`${this.URL}/api/planPagination/${this.pageSize}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data,
              this.pageInfo = response.data.pagination
            )

          }
        })

    },

    nextPage(page, query) {
      axios
        .get(`${this.URL}/api/planPagination/${page}?page=${query}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data,
              this.pageInfo = response.data.pagination
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

    getAllplans() {
      axios
        .get(`${this.URL}/api/plan`)
        .then(response => (this.plans = response.data.data))
    },



    saveFeature(value) {
      this.features_type_selected_ids.push(value)
    },

    Add() {




      axios.post(
        `${this.URL}/api/plan`,
        {
          name: this.plan_name,
          periodicity: this.periodicity_of_plan,
          periodicity_type: this.periodicity_type,

        },
      ).then(res => {
        this.plan_id = res.data.data[0].plan_id

        axios.post(
          `${this.URL}/api/assosiative/${this.plan_id}`, {
          featureID: this.features_type_selected_ids,
        },
        )

        this.plan_name = ''
        this.periodicity_of_plan = ''
        this.periodicity_type = ''

        this.getAllPaginate()

      },

      )


      this.dialog = false,
        this.insertAlert()


    },

    async Delete(GetId) {
      this.planId = GetId
      this.DeleteAlert()
    },

    deleteData() {
      axios
        .delete(`${this.URL}/api/plan/${this.planId}`)
        .then(response => (this.plans = response.data.data))
      this.getAllPaginate()
    },

    async Updates(Getdata) {

      this.planId = Getdata
      console.log(Getdata)

      this.plan_name_edit = Getdata.plan_name
      this.periodicity_of_plan_edit = Getdata.periodicity_of_plan
      this.grace_days_edit = Getdata.grace_days
      this.periodicity_type_edit = Getdata.periodicity_type
      this.features_type_edit = Getdata.features_type_selected_ids


    },

    async updateUser() {

      try {
        const user = await axios.put(
          `${this.URL}/api/plan/${this.planId.plan_id}`,
          {
            name: this.plan_name_edit,
            periodicity: this.periodicity_of_plan_edit,
            grace_days: this.grace_days_edit,
            periodicity_type: this.periodicity_type_edit,
            featureID: this.features_type_edit,

          },
        )

        this.getAllPaginate()
        this.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }

    },

    async getFeature() {
      axios
        .get(`${this.URL}/api/feature`)
        .then(response => (this.features = response.data.data))

    },

  },

}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: plan
</route>

