<template>
  <div style="margin-top: 5%;">

    <div style="display: flex;width: 45%;justify-content: space-between;float: inline-end;">
      <VRow justify="">
        <div style="display: flex;width: 65%;justify-content: space-between;">
          <VSelect item-color="customColorValue" v-model="pageSize" :label="$t('row')" :items="pageSizes"
            :value="pageSize" variant="solo" style="width: 18%;" />
          <VSpacer />

          <VTextField v-model="search" type="text" label="Search Roles" style="width: 70%;" />
        </div>
        <VSpacer />
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'tax')">
              Add taxes
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">Add New Taxes</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="tax_name" label="tax name" persistent-placeholder type="text" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="tax_name_loc" label="tax name loc" persistent-placeholder type="text" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="tax_percent" label="tax percent" persistent-placeholder type="number"
                      :disabled="disablePercentage" min="0" max="100" max-length="3" @input="toggleAmount" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="tax_amount" label="tax amount" persistent-placeholder type="number"
                      :disabled="disableAmount" min="0" @input="togglePercent" />
                  </VCol>
                </VRow>
              </VContainer>
            </VCardText>
            <VCardActions>
              <VSpacer />
              <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                Close
              </VBtn>
              <VBtn color="blue-darken-1" variant="text" @click="Add">
                Submit
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VRow>
    </div>


    <div style="display: flex;width: 14%;justify-content: space-between;float: inline-start;">
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

    <VTable style="table-layout: fixed;">
      <thead>
        <tr>
          <th>
            num of taxes
          </th>
          <th>
            Tax Name
          </th>
          <th>
            Tax per
          </th>
          <th>
            Tax Amount
          </th>
          <th v-if="$can('delete', 'tax')">
            Delete
          </th>
          <th v-if="$can('edit', 'tax')">
            Update
          </th>
        </tr>
      </thead>


      <tbody>
        <tr v-for="(item, index) in filterData" :key="item.id">
          <td> {{ ++index }}</td>

          <td>{{ item.name }}</td>

          <td v-if="item.per != null">
            {{ item.per }}
          </td>
          <td v-else>
            <VChip style="color: red;">
              <VIcon start icon="mdi-account-circle-outline" />
              Not found
            </VChip>
          </td>

          <td v-if="item.amount != null">
            {{ item.amount }}
          </td>
          <td v-else>
            <VChip style="color: red;">
              <VIcon start icon="mdi-account-circle-outline" />
              Not found
            </VChip>
          </td>

          <td style="table-layout: fixed;" v-if="$can('delete', 'tax')">
            <VBtn color="red" style="background: red;color: white;" @click="Delete(item.id)">
              <VIcon icon="mdi-delete" style="font-size: 135%;" />
            </VBtn>
          </td>

          <td v-if="$can('edit', 'tax')">
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
                      <span class="text-h5">Update Feature data</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="tax_name" label="Name edit" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="tax_name_loc" label="Name loc edit" />
                          </VCol>


                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="tax_percent" label="Percent edit" type="number"
                              :disabled="disablePercentage" min="0" max="100" max-length="3" @input="toggleAmount" />
                          </VCol>



                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="tax_amount" label="Amount edit" type="number" :disabled="disableAmount"
                              min="0" @input="togglePercent" />
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text" @click="dialog3 = false">
                        Close
                      </VBtn>
                      <VBtn color="blue-darken-1" variant="text" @click="updateUser">
                        Update
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
      <caption> {{ $t('List Of Data') }}( {{ count }} )</caption>
    </VTable>
    <div style="float: inline-end;">
      <VPagination v-if="pageInfo" v-model="pageInfo.current_page" :length="Math.ceil(pageInfo.total / pageSize)"
        total-visible="7" :size="43" :next="pageInfo.next_page_url" :per-page="pageInfo.per_page"
        @click="nextPage(pageInfo.per_page, pageInfo.current_page)" />
    </div>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  name: "TaxTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      Tax: [],
      tax_name: '',
      tax_name_loc: '',
      tax_percent: '',
      tax_amount: '',
      disablePercentage: false,
      disableAmount: false,
      editFlag: false,
      taxid: '',
      tax_id: '',

      tax_name_edit: '',
      tax_name_loc_edit: '',
      tax_percent_edit: '',
      tax_amount_edit: '',

      count: 0,
      perPage: 10,
      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 5,
      pageSizes: [5, 10, 15, 20, 25, 30],


    }
  },



  // eslint-disable-next-line vue/component-api-style
  computed: {
    filterData() {
      return this.pagi.filter(user => {
        return user.name.includes(this.search) ||
          user.per.includes(this.search)


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
    this.getAllTaxes()
    this.getAllPaginate()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {

    toggleAmount() {
      (this.tax_percent || this.tax_percent > 0) ? this.disableAmount = true : this.disableAmount = false
      this.tax_percent > 100 ? this.tax_percent = 100 : ''
      this.tax_percent < 0 ? this.tax_percent = 0 : ''
    },

    togglePercent() {
      (this.tax_amount || this.tax_amount > 0) ? this.disablePercentage = true : this.disablePercentage = false
      this.tax_amount < 0 ? this.tax_amount = 0 : ''
    },

    async getAllPaginate() {

      axios
        .get(`api/taxPagination/${this.pageSize}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data,
              this.pageInfo = response.data
            )
          }
        })

    },

    nextPage(page, query) {

      axios
        .get(`api/taxPagination/${page}?page=${query}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data,
              this.pageInfo = response.data
            )
          }
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

    save(date) {
      this.$refs.menu.save(date)
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

    getAllTaxes() {
      axios
        .get('api/tax')
        .then(response => (this.Tax = response.data))
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

      pdfMake.createPdf(docDefinition).download()


    },


    async Add() {
      try {
        const user = await axios.post(
          "api/tax",
          {
            name: this.tax_name,
            name_loc: this.tax_name_loc,
            per: this.tax_percent == '' ? null : this.tax_percent,
            amount: this.tax_amount == '' ? null : this.tax_amount,
            formula: "a + b",
            extra: "extra information",
            accept_per: true,
          },
        )



        this.getAllPaginate()

        this.dialog = false
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },

    async Delete(GetId) {
      this.tax_id = GetId
      this.DeleteAlert()
    },

    deleteData() {
      axios
        .delete(`api/tax/${this.tax_id}`)
        .then(response => (this.Tax = response.data))

      this.getAllTaxes()
    },


    async Updates(Getdata) {

      this.taxid = Getdata

      this.tax_name = Getdata.name
      this.tax_name_loc = Getdata.name_loc
      this.tax_percent = Getdata.per
      this.tax_amount = Getdata.amount
      this.tax_percent ? this.disableAmount = true : this.disableAmount = false
      this.tax_amount ? this.disablePercentage = true : this.disablePercentage = false


    },


    async updateUser() {


      try {
        const user = await axios.put(
          `api/tax/${this.taxid.id}`,
          {
            name: this.tax_name,
            name_loc: this.tax_name_loc,
            per: this.tax_percent,
            amount: this.tax_amount,
            formula: "a + b",
            extra: "extra information",
            accept_per: true,

          },
        )

        console.log(user)

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
</style>
