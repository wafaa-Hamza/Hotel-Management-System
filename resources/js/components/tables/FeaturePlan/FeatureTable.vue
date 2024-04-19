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

          <VTextField v-model="search" type="text"   :label="$t('Search Feature')"  style="width: 70%;" />
        </div>
        <VSpacer />
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'feature')">
              Add Feature
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{$t('Add New feature')}}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="5" md="5">
                    <VTextField v-model="feature_name"  :label="$t('feature name')" persistent-placeholder type="text" />
                  </VCol>


                  <VCol cols="12" md="4" sm="4">
                    <VSelect item-color="customColorValue" v-model="period_type" :items="period_types" item-title="period type" item-value="id"
                      persistent-placeholder   :label="$t('period type')" />
                  </VCol>
                  <VCol cols="12" sm="3" md="3">
                    <VTextField v-model="periodicity"   :label="$t('period city')" type="number" persistent-placeholder />
                  </VCol>
                  <VCol cols="12" sm="4" md="4">
                    <VCheckbox v-model="consumable" :label="`consumable: ${consumable.toString()}`" />
                  </VCol>

                  <VCol cols="12" sm="4" md="4">
                    <VCheckbox v-model="post_paid" :label="`post paid: ${post_paid.toString()}`" />
                  </VCol>

                  <VCol cols="12" sm="4" md="4">
                    <VCheckbox v-model="quata" :label="`quota: ${quata.toString()}`" />
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
            {{ $t('no.') }}
          </th>
          <th>
            {{ $t('feature name') }}
          </th>
          <th>
            {{ $t('consumable') }}
          </th>
          <th>
            {{ $t('periodicity') }}
          </th>
          <th>
            {{ $t('periodicity_type') }}
          </th>
          <th>
            {{ $t('post paid') }}
          </th>
          <th>
            {{ $t('quata') }}
          </th>
          <th v-if="$can('edit', 'feature')">
            {{ $t('update') }}
          </th>
        </tr>
      </thead>


      <tbody>
        <tr v-for="(item, index) in filterData" :key="item.id">
          <td> {{ ++index }}</td>

          <td>{{ item.feature_name }}</td>
          <td>{{ item.consumable }}</td>
          <td>{{ item.periodicity_of_feature }}</td>
          <td>{{ item.periodicity_type }}</td>
          <td>{{ item.postpaid }}</td>
          <td>{{ item.quota }}</td>
          <td v-if="$can('edit', 'feature')">
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
                      <span class="text-h5">{{$t('Update Feature data')}}</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12" sm="5" md="5">
                            <VTextField v-model="feature_name_edit"  :label="$t('feature Name edit')" />
                          </VCol>
                          <VCol cols="12" md="4" sm="4">
                            <VSelect item-color="customColorValue" v-model="period_type_edit" :items="period_types" item-title="period type"
                              item-value="id" persistent-placeholder :label="$t('period type')"  />
                          </VCol>
                          <VCol cols="12" sm="3" md="3">
                            <VTextField v-model="periodicity_edit"  :label="$t('periodicity')" type="number" />
                          </VCol>

                          <VCol cols="12" sm="4" md="4">
                            <VCheckbox v-model="consumable_edit" :label="`consumable: ${consumable_edit.toString()}`" />
                          </VCol>
                          <VCol cols="12" sm="4" md="4">
                            <VCheckbox v-model="quata_edit" :label="`quota: ${quata_edit.toString()}`" />
                          </VCol>

                          <VCol cols="12" sm="4" md="4">
                            <VCheckbox v-model="post_paid_edit" :label="`post paid: ${post_paid_edit.toString()}`" />
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
                         {{ $t('Update') }}
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
      <caption>{{ $t('List Of Data') }}( {{ count }} )</caption>
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

export default {
  name: "FeatureTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      search: '',
      dialog: false,
      dialog3: false,
      Features: [],
      featureid: '',
      feature_id: '',
      period_types: ['Day', 'Week', 'Month', 'Year'],

      feature_name: '',
      period_type: '',
      consumable: true,
      periodicity: '',
      quata: true,
      post_paid: true,

      feature_name_edit: '',
      period_type_edit: '',
      consumable_edit: '',
      periodicity_edit: '',
      quata_edit: '',
      post_paid_edit: '',


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
      // eslint-disable-next-line sonarjs/no-identical-expressions
      return this.pagi.filter(user => {
        return user.feature_name.includes(this.search)
      })
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
    this.getAllFeature()
    this.getAllPaginate()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllPaginate() {
      axios
        .get(`${this.URL}/api/featurePagination/${this.pageSize}`)
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
        .get(`${this.URL}/api/featurePagination/${page}?page=${query}`)
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


    getAllFeature() {
      axios
        .get(`${this.URL}/api/feature`)
        .then(response => (this.Features = response.data.data))
    },


    exportExecl() {
      const XLSX = xlsx
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.aoa_to_sheet(this.framework1)

      XLSX.utils.book_append_sheet(workbook, worksheet, "framework")
      XLSX.writeFile(workbook, "users.xlsx")
    },


    Downpdf() {
      let docDefinition = {
        content: [
          {
            layout: 'lightHorizontalLines',
            table: {
              headerRows: 1,
              widths: ['*', "auto", 100, "*"],

              body: this.framework1,

            },
          },
        ],
      }

      // pdfMake.createPdf(docDefinition).open()
      pdfMake.createPdf(docDefinition).download()

      // pdfMake.createPdf(docDefinition).print()

    },


    async Add() {

      try {
        const user = await axios.post(
          `${this.URL}/api/feature`,
          {
            name: this.feature_name,
            consumable: this.consumable,
            quota: this.quata,
            postpaid: this.post_paid,
            periodicity: this.periodicity,
            periodicity_type: this.period_type,

          },

        )

        this.feature_name = ''
        this.period_type = ''
        this.consumable = true
        this.periodicity = ''
        this.quata = true
        this.post_paid = true

        this.getAllFeature()
        this.getAllPaginate()
        this.dialog = false
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },



    async Updates(Getdata) {

      this.featureid = Getdata


      this.feature_name_edit = Getdata.feature_name,
        this.periodicity_edit = Getdata.periodicity_of_feature,
        this.period_type_edit = Getdata.periodicity_type
      this.consumable_edit = Getdata.consumable == 0 ? false : true
      this.quata_edit = Getdata.quota == 0 ? false : true
      this.post_paid_edit = Getdata.post_paid == 0 ? false : true

    },


    async updateUser() {


      try {
        const user = await axios.put(
          `${this.URL}/api/feature/${this.featureid.feature_id}`,
          {
            name: this.feature_name_edit,
            periodicity: this.periodicity_edit,
            periodicity_type: this.period_type_edit,
            consumable: this.consumable_edit,
            quota: this.quata_edit,
            postpaid: this.post_paid_edit,

          },
        )


        this.getAllPaginate()
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
  background: rgba(147, 112, 219, .6);
  ;
}
</style>
