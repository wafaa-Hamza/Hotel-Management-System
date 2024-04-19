<script setup>
import {
  requiredValidator,
} from '@validators'

const form = ref()
const refform = ref()
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VRow>
      <VCol cols="12" sm="6" md="5">
        <VTextField v-model="search" type="text" label="Search" />
      </VCol>
      <VCol cols="12" sm="6" md="5" />
      <VCol cols="12" sm="6" md="2">
        <VDialog v-model="dialog" width="1024" persistent z-index="1000">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'ledger expenses')">
              {{ $t('Create New Expense') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Expenses') }}</span>
            </VCardTitle>
            <VForm ref="form">
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name" label="Name" clearable :rules="[requiredValidator]" />
                    </VCol>

                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="amount" label="Amount" type="number" clearable :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VCombobox v-model="category" :label="$t('Category')" :items="categories" item-title="name"
                        item-value="category" :menu-props="{ maxHeight: '200px' }" clearable
                        :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" sm="6" md="2">
                      <VDialog v-model="categoryDialog" width="1024" z-index="1000">
                        <template #activator="{ props }">
                          <VBtn v-bind="props">
                            {{ $t('Create Category') }}
                          </VBtn>
                        </template>
                        <VCard>
                          <VForm ref="refform">
                            <VCardTitle>
                              <span class="text-h5">{{ $t('Category') }}</span>
                            </VCardTitle>

                            <VCardText>
                              <VContainer>
                                <VRow>
                                  <VCol cols="12" sm="6" md="6">
                                    <VTextField v-model="category_name" label="Category name" clearable
                                      :rules="[requiredValidator]" />
                                  </VCol>
                                  <VCol cols="12" sm="6" md="6">
                                    <VTextField v-model="category_name_loc" label="Category name loc" clearable
                                      :rules="[requiredValidator]" />
                                  </VCol>
                                </VRow>
                              </VContainer>
                            </VCardText>
                            <VCardActions>
                              <VSpacer />
                              <VBtn color="blue-darken-1" variant="text" @click="categoryDialog = false">
                                {{ $t('Close') }}
                              </VBtn>
                              <VBtn color="blue-darken-1" variant="text"
                                @click.prevent="refform?.validate().then(res => { res.valid ? createCategory() : null })">
                                {{ $t('Submit') }}
                              </VBtn>
                            </VCardActions>
                          </VForm>
                        </VCard>
                      </VDialog>
                    </VCol>
                    <VCol cols="12" sm="6" md="4">
                      <VTextField v-model="reference" label="Reference" clearable :rules="[requiredValidator]" />
                    </VCol>

                    <VCol cols="12" sm="6" md="12">
                      <VTextarea v-model="description" label="Description" clearable :rules="[requiredValidator]" />
                    </VCol>

                    <VCol cols="6" sm="12">
                      <VFileInput ref="fileInput" show-size label="Upload file" clearable />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="dialog = false, errors = null">
                  Close
                </VBtn>
                <VBtn color="blue-darken-1" variant="text"
                  @click.prevent="form?.validate().then(res => { res.valid ? createExpense() : null })">
                  Submit
                </VBtn>
              </VCardActions>
            </VForm>
          </VCard>
        </VDialog>
      </VCol>
    </VRow>



    <br>
    <br>
    <br>

    <VTable>
      <thead>
        <tr>
          <th>
            {{ $t('Name') }}
          </th>
          <th>
            {{ $t('Date') }}
          </th>
          <th>
            {{ $t('Amount') }}
          </th>
          <th>
            {{ $t('Expense category') }}
          </th>
          <th>
            {{ $t('Reference') }}
          </th>
          <th>
            {{ $t('Description') }}
          </th>
          <th v-if="$can('edit', 'ledger expenses')">
            {{ $t('Show') }}
          </th>
          <th v-if="$can('delete', 'ledger expenses')">
            {{ $t('Delete') }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item) in expenses" :key="item.id">
          <td>{{ item.name }}</td>
          <td>{{ item.hotel_date }}</td>
          <td>{{ item.amount }}</td>
          <td>{{ item.exp_ledger.name }}</td>
          <td>{{ item.reference }}</td>
          <td>{{ item.description }}</td>
          <td v-if="$can('edit', 'ledger expenses')">
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="item.dialogVisible" width="1024" persistent z-index="1000">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(item)">
                      <VIcon icon="mdi-eye" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">{{ $t('Expense') }}</span>
                    </VCardTitle>
                    <VForm ref="form">
                      <VCardText>
                        <VContainer>
                          <VRow>
                            <VCol cols="12" sm="6" md="6">
                              <VTextField v-model="name" label="Name" clearable :rules="[requiredValidator]"/>
                            </VCol>

                            <VCol cols="12" sm="6" md="6">
                              <VTextField v-model="amount" label="Amount" type="number" clearable :rules="[requiredValidator]"/>
                            </VCol>
                            <VCol cols="12" sm="6" md="6">
                              <VCombobox v-model="category" :label="$t('Category')" :items="categories" item-title="name"
                                         item-value="category" :menu-props="{ maxHeight: '200px' }" clearable :rules="[requiredValidator]"/>
                            </VCol>
                            <VCol cols="12" sm="6" md="6">
                              <VTextField v-model="reference" label="Reference" clearable :rules="[requiredValidator]"/>
                            </VCol>

                            <VCol cols="12" sm="6" md="12">
                              <VTextarea v-model="description" label="Description" clearable :rules="[requiredValidator]"/>
                            </VCol>


                            <VCol cols="6" sm="12">
                              <VFileInput ref="fileInput" show-size label="Upload file" :rules="rules" clearable />
                            </VCol>

                            <VCol cols="6" sm="2">
                              <button @click.prevent="downloadFile(item)" v-if="item.file!=null">
                                {{ $t('Download File') }}
                              </button>
                            </VCol>
                          </VRow>
                        </VContainer>
                      </VCardText>
                      <VCardActions>
                        <VSpacer />
                        <VBtn color="blue-darken-1" variant="text" @click="item.dialogVisible = false, this.clear()">
                          Close
                        </VBtn>
                        <VBtn color="blue-darken-1" variant="text"
                              @click.prevent="form[0]?.validate().then(res => { res.valid ? updateExpense(item) : null })">
                          Update
                        </VBtn>
                      </VCardActions>
                    </VForm>
                  </VCard>
                </VDialog>
              </VRow>
            </VBtn>
          </td>
          <td v-if="$can('delete', 'ledger expenses')">
            <VBtn color="red" style="background: red;color: white" @click="Delete(item.id)">
              <VIcon icon="mdi-delete"   style="font-size: 135%"/>
            </VBtn>
          </td>
        </tr>
      </tbody>
      <br>
    </VTable>
    <div style="float: right">
      <VPagination v-model="page" :length="10" :total-visible="7" :data="users" @pagination-change-page="getPosts" />
    </div>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import { useGeneralStore } from '../stores/GeneralStore'
import { mapStores, mapActions } from 'pinia'

export default {
  name: "Expenses",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      page: 1,
      dialog: false,
      dialog3: false,
      URL: window.location.origin,
      date: null,
      name: null,
      amount: null,
      category: null,
      reference: null,
      description: null,
      fileInput: null,
      category_name: null,
      category_name_loc: null,
      categoryDialog: false,
      expense_id: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore),
    categories() {
      return this.generalStore.expense_categories
    },
    expenses() {
      return this.generalStore.expenses
    },
    filterData() {
      return this.tickets.filter(ticket => ticket.includes(this.search))
    },
    hasErrors() {
      return this.errors !== null
    },
    hasupdateErrors() {
      return this.update_errors !== null
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    dialog() {
      if (!this.dialog) {
        this.clear()
      }
    },


  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllExpensesAction()
    this.getAllExpensesCategoreisAction()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['getAllExpensesAction', 'getAllExpensesCategoreisAction']),
    clear() {
      this.name = null
      this.date = null
      this.amount = null
      this.category = null
      this.description = null
      this.reference = null
    },
    downloadFile(item) {
      const fileUrl = item.file

      // Replace with the actual file path
      axiosIns.get(fileUrl, { responseType: 'blob' })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')

          link.href = url
          link.setAttribute('download', item.file)// Replace with the desired file name and extension
          document.body.appendChild(link)
          link.click()
        })
        .catch(error => {
          console.error('Error downloading file:', error)
        })
    },
    async createCategory() {
      await axiosIns.post(`${this.URL}/api/expensesLedger`, {
        name: this.category_name,
        name_loc: this.category_name_loc,
        gl_acount: 1,
      }).then(
        res => {
          if (res.status != undefined) {
            this.getAllExpensesCategoreisAction()
            this.categoryDialog = false
            this.insertAlert('New exepnse category has been created')
          }

        },
      ).catch(
        err => {
          console.log(err)

        },
      )
    },
    async createExpense() {
      const formData = new FormData()
      if (this.$refs.fileInput.files[0] != undefined) {
        formData.append('file', this.$refs.fileInput.files[0])
      }

      formData.append('name', this.name)
      formData.append('amount', this.amount)
      formData.append('reference', this.reference)
      formData.append('description', this.description)
      formData.append('exp_ledger_id', this.category.id)
      await axiosIns.post(`${this.URL}/api/expenses`, formData).then(
        res => {
          if (res.status != undefined) {
            this.getAllExpensesAction()
            this.dialog = false
            this.insertAlert('New exepnse has been created')
          }
        },
      ).catch(
        err => {
          console.log(err)

        },
      )
    },
    async updateExpense(item) {
      const formData = new FormData()
      if (this.$refs.fileInput[0].files[0] != undefined)
        formData.append('file', this.$refs.fileInput[0].files[0])
      formData.append('name', this.name)
      formData.append('amount', this.amount)
      formData.append('reference', this.reference)
      formData.append('description', this.description)
      formData.append('exp_ledger_id', this.category.id)
      formData.append('_method', 'PUT')
      await axiosIns.post(`${this.URL}/api/expenses/${item.id}`, formData).then(
        res => {
          if (res.status != undefined) {
            this.getAllExpensesAction()
            this.insertAlert('Expense updated')
            this.dialogVisible = false
            this.clear()
          }
        },
      ).catch(
        err => {
          console.log(err)

        },
      )
    },

    Delete(GetId) {
      this.expense_id = GetId
      this.DeleteAlert()
    },
    deleteData() {
      axiosIns
        .delete(`${this.URL}/api/expenses/${this.expense_id}`)
        .then(res => {
          this.getAllExpensesAction()
        }).catch(
          err => {
            console.log(err)
          },
        )

    },

    Updates(Getdata) {
      this.name = Getdata.name
      this.date = Getdata.hotel_date
      this.amount = Getdata.amount
      this.category = Getdata.exp_ledger
      this.description = Getdata.description
      this.reference = Getdata.reference

    },
    insertAlert(message) {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: message,
        timer: 2000,
      })
    },
    DeleteAlert() {
      Swal.fire({
        icon: 'error',
        title: 'Delete this Expense?',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Delete`,
      }).then(result => {
        if (result.isDenied) {
          this.deleteData()
        }
      })
    },

  },
}
</script>


<route lang="yaml">
  meta:
    action: view
    subject: ledger expenses
  </route>
