<script setup>
import {
  alphaDashValidator,
  alphaValidator,
  betweenValidator,
  confirmedValidator,
  emailValidator,
  integerValidator,
  lengthValidator,
  passwordValidator,
  regexValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div style="float: right;width: 45%;display: flex;justify-content: space-between;">
      <VRow justify="">
        <div style="width: 65%;display: flex;justify-content: space-between">
          <VSelect  item-color="customColorValue" v-model="pageSize" :label="$t('row')" :items="pageSizes" :value="pageSize" variant="solo"
                   style="width: 18%;" />
          <VSpacer />
        </div>
        <VSpacer />
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }" v-if="$can('create', 'user')">
            <VBtn v-bind="props">
              {{ $t('Add Users') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">User Profile {{ $t('Add') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="firstname" :label="$t('first name')"
                                :rules="[requiredValidator]" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="lastname" :label="$t('last name')"
                                :rules="[requiredValidator]" />
                  </VCol>
                  <VCol cols="12" sm="6" md="6">
                    <VTextField v-model="phonenumber" :label="$t('phone')"
                                :rules="[requiredValidator, regexValidator(phonenumber, '^([0-9]+)$')]"/>
                  </VCol>

                  <VCol cols="12" sm="6">
                    <VCombobox item-color="customColorValue" v-model="role" :items="roles" item-title="name" :label="$t('Role')"  :rules="[requiredValidator]" />
                  </VCol>
                  <VCol cols="12">
                    <VTextField v-model="email" :label="$t('Email')" required persistent-placeholder
                                :rules="[requiredValidator, emailValidator]" />
                  </VCol>
                  <VCol cols="12">
                    <VTextField v-model="password" :label="$t('Password')" type="password" required persistent-placeholder
                                :rules="[requiredValidator]" />
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
        export
      </VBtn>
      <VBtn class="btn" @click="Downpdf">
        PDF
      </VBtn>
    </div>

    <br>
    <br>
    <br>
    <!--<p v-for="i in pagi"> {{ i }}</p>-->
    <VTable>
      <thead>
      <tr>
        <th>
          {{ $t('firstname') }}
        </th>
        <th>
          {{ $t('lastname') }}
        </th>
        <th>
          {{ $t('phone') }}
        </th>
        <th>
          {{ $t('email') }}
        </th>
        <th>
          {{ $t('role') }}
        </th>

        <th v-if="$can('edit', 'user')">
          {{ $t('Update') }}
        </th>
        <th v-if="$can('delete', 'user')">
          {{ $t('Delete') }}
        </th>
      </tr>
      </thead>
      <tbody>

      <tr v-for="item in getusers" :key="item.id">
        <td>{{ item.firstname }}</td>
        <td>{{ item.lastname }}</td>
        <td>{{ item.phonenumber }}</td>
        <td>{{ item.email }}</td>
        <td>
          <VChip class="ma-2" color="primary" variant="outlined">
            {{ item.role.name }}
            <VIcon end icon="mdi-account-outline" />
          </VChip>
        </td>

         <td v-if="$can('edit', 'user')">
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
                    <span class="text-h5">Update User Profile {{ $t('Add') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="firstname_edit" :label="$t('first name edit')"  />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="lastname_edit" :label="$t('last name')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="6">
                          <VTextField v-model="phonenumber_edit" :label="$t('phone')"  type="number" />
                        </VCol>

                        <VCol cols="12" sm="6">
                          <VCombobox item-color="customColorValue" v-model="role_edit" :items="roles" item-title="name" :label="$t('Role')" :rules="[requiredValidator]" />
                        </VCol>
                        <VCol cols="12">
                          <VTextField v-model="email_edit" :label="$t('Email')" required />
                        </VCol>
                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="item.dialog3 = false">
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn color="blue-darken-1" variant="text" @click="updateUser(item)">
                      {{ $t('Update') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
        </td>
        <td v-if="$can('delete', 'user')">
          <VBtn color="red" style="background: red;color: white" @click="Delete(item.id)">
            <VIcon icon="mdi-delete"   style="font-size: 135%"/>
          </VBtn>
        </td>
      </tr>
      </tbody>
      <br>

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

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      name: "user_table",
      roles: [],
      dialog: false,
      dialog3: false,
      getusers: [],
      userid: null,
      firstname: null,
      lastname: null,
      phonenumber: ref(null),
      email: ref(null),
      role: null,
      password: ref(null),
      firstname_edit: null,
      lastname_edit: null,
      phonenumber_edit: null,
      email_edit: null,
      role_edit: null,
      password_edit: null,
      count: 0,
      perPage: 10,
      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 10,
      pageSizes: [5, 10, 15, 20, 25, 30],
      URL: window.location.origin,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    filterData() {
      // eslint-disable-next-line sonarjs/no-identical-expressions
      return this.pagi.filter(user => {
        return user.firstname.includes(this.search) ||
          user.lastname.includes(this.search) ||
          user.phonenumber.includes(this.search)||
          user.email.includes(this.search)
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
    this.getAllUsers()
    this.getAllPaginate()
    this.getRoles()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getRoles() {
      axios
        .get(`${this.URL}/api/getAllRoles`)
        .then(response => {
          if (response.status == 200) {
            this.roles = response.data.data
          }
        })
    },
    async getAllPaginate() {
      axios
        .get(`${this.URL}/api/userPagination/${this.pageSize}`)
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
        .get(`${this.URL}/api/userPagination/${page}?page=${query}`)
        .then(response => {
          if (response.status == 200) {
            (this.pagi = response.data.data,
                this.pageInfo = response.data
            )
          }
        })
    },
    getAllUsers() {
      axios
        .get(`${this.URL}/api/user`)
        .then(res => {

          this.getusers = res.data
        })
    },
    insertAlert() {
      const Toast = this.$swal.mixin({
        toast: true,
        position: 'top-end',
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
            position: 'top-end',
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
        position: 'top-end',
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
          `${this.URL}/api/user`,
          {
            firstname: this.firstname,
            lastname: this.lastname,
            phonenumber: this.phonenumber,
            email: this.email,
            password: this.password,
            role: this.role.id,
            language: 'en',
          },
        )

        this.getAllUsers()
        this.dialog = false
        this.insertAlert()
      } catch (e) {
        console.log(e)
      }
    },
    async Delete(GetId) {
      this.userid = GetId
      this.DeleteAlert()
    },
    deleteData() {
      console.log(this.userid)
      axios
        .delete(`${this.URL}/api/user/${this.userid}`)
        .then(response => (this.getusers = response.data))
      this.getAllPaginate()
    },
    async Updates(Getdata) {
      this.userid = Getdata
      console.log(Getdata)
      this.firstname_edit = Getdata.firstname
      this.lastname_edit = Getdata.lastname
      this.phonenumber_edit = Getdata.phonenumber
      this.email_edit = Getdata.email
      this.password_edit = Getdata.password
      this.role_edit = Getdata.role
    },
    async updateUser(item) {
      try {
        const user = await axios.put(
          `${this.URL}/api/user/${this.userid.id}`,
          {
            firstname: this.firstname_edit,
            lastname: this.lastname_edit,
            phonenumber: this.phonenumber_edit,
            email: this.email_edit,
            role: this.role_edit.id,
            language: 'en',
          },
        )

        this.getAllUsers()
        item.dialog3 = false
        this.UpdateAlert()
      } catch (e) {
        console.log(e)
      }
    },
  },
}
</script>

<route lang="yaml">
meta:
action: read
subject: user
</route>

