<!-- eslint-disable vue/require-v-for-key -->
<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div style="float: right;width: 45%;display: flex;justify-content: space-between;">
      <VRow justify="">
        <div style="width: 65%;display: flex;justify-content: space-between">
          <VSelect item-color="customColorValue"
            v-model="pageSize"
            :label="$t('row')"
            :items="pageSizes"
            :value="pageSize"
            variant="solo"
            style="width: 18%;"
          />          <VSpacer />
          <VTextField v-model="search" type="text" label="Search" style="width: 70%;" />
        </div>
        <VSpacer />
      </VRow>
    </div>
    <div style="float: left;width: 14%;display: flex;justify-content: space-between">
      <VBtn class="btn" @click="exportExecl">
        {{$t('export')}}
      </VBtn>
      <VBtn class="btn" @click="Downpdf">
        {{$t('PDF')}}
      </VBtn>
    </div>

    <br>
    <br>
    <br>

    <VTable>
      <thead>
        <tr>
          <th >
            {{$t('Ticket number')}}
          </th>
          <th >

            {{$t('Username')}}
          </th>
          <th >

            {{$t('Title')}}
          </th>
          <th >

            {{$t('Priority')}}
          </th>
          <th >

            {{$t('status')}}
          </th>
          <th >

            {{$t('Resolved')}}
          </th>
          <th >

            {{$t('Locked')}}
          </th>
          <th >

            {{$t('Show and edit')}}
          </th>
          <th >

            {{$t('Delete')}}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,index) in filterData" :key="item.id">
          <td>{{ ++index }}</td>
          <td>{{ item.user.firstname + ' ' + item.user.lastname }}</td>
          <td>{{ item.title }}</td>
          <td>{{ item.priority }}</td>
          <td>{{ item.status }}</td>
          <td>
            <VChip class="ma-2">
              <VIcon v-if="item.is_resolved" icon="mdi-check" color="success" />
              <VIcon v-if="!item.is_resolved" icon="mdi-close-octagon" color="error" />
            </VChip>
          </td>
          <td>
            <VChip class="ma-2">
              <VIcon v-if="item.is_locked" icon="mdi-check" color="success" />
              <VIcon v-if="!item.is_locked" icon="mdi-close-octagon" color="error" />
            </VChip>
          </td>
          <td>
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="item.dialogVisible" width="1024">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(item)">
                      <VIcon icon="mdi-file-edit-outline" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">Show and update Ticket</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <div v-if="hasupdateErrors">
                          <div v-for="(error, key) in update_errors" :key="key">
                            <VAlert closable close-label="Close Alert" class="mb-5">
                              {{ error[0] }}
                            </VAlert>
                          </div>
                        </div>
                        <VRow>
                          <VCol cols="12" sm="6" md="12">
                            <VTextField v-model="title_edit" :label="$t('Title')" disabled :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="12">
                            <VTextarea v-model="message_edit" :label="$t('Message')" disabled :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="12">
                            <VTextarea v-model="comment"  :label="$t('Comment')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VSelect item-color="customColorValue" v-model="priority_edit" :items="priorities" item-title="name" item-value="name"
                                     :label="$t('Priority')" persistent-hint :rules="[requiredValidator]" />
                          </VCol>

                          <VCol cols="12" sm="6">
                            <VSelect item-color="customColorValue" v-model="status_edit" :items="statuses" item-title="name" item-value="name"
                                     :label="$t('status')" persistent-hint :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VCheckbox v-model="is_resolved_edit"  :label="$t('Is_resolved')" persistent-hint />
                          </VCol>
                          <VCol cols="12" sm="6">
                            <VCheckbox v-model="is_locked_edit"   :label="$t('Is_locked')" persistent-hint />
                          </VCol>

                          <VCol cols="6" sm="12">

                            <div v-for="files in item.ticket_files" :key="files.id">
                              <a :href="files.file" download>{{ files.file }}</a>
                              <br>
                            </div>
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text"
                            @click="item.dialogVisible = false, update_errors = null">
                        {{$t('Close')}}
                      </VBtn>
                      <VBtn color="blue-darken-1" variant="text" @click="updateticket">
                        {{$t('Update')}}
                      </VBtn>
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VRow>
            </VBtn>
          </td>
          <td>
            <VBtn  @click="Delete(item.id)" color="red" style="background: red;color: white">
              <VIcon icon="mdi-delete"   style="font-size: 135%"/>
            </VBtn>
          </td>
        </tr>
      </tbody>
      <br>
    </VTable>
    <div style="float: right">
      <VPagination
        v-if="pageInfo"
        v-model="pageInfo.current_page"
        :length="Math.ceil(pageInfo.total / pageSize)"
        total-visible="7"
        :size="43"
        :next="pageInfo.next_page_url"
        :per-page="pageInfo.per_page"
        @click="nextPage(pageInfo.per_page, pageInfo.current_page)"
      />    </div>
  </div>
</template>

<script>
import axiosIns from "axios"
import Swal from 'sweetalert2'
import axios from "@axios"


export default {
  name: "ticket",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      dialog3: false,
      priorities: ['high', 'normal', 'low'],
      statuses: ['open', 'closed'],
      tickets: [
        {
          id: '',
          user: {},
          title: '',
          message: '',
          priority: '',
          status: '',
          is_resolved: '',
          is_locked: '',
          ticket_files: [],
        },
      ],
      ticket: {
        id: '',
        firstname: '',
        lastname: '',
        username: '',
        title: '',
        message: '',
        priority: '',
        status: '',
        is_locked: '',
        is_resolved: '',
      },
      id_edit: '',
      title_edit: '',
      message_edit: '',
      priority_edit: '',
      status_edit: '',
      is_resolved_edit: '',
      is_locked_edit: '',
      comment: '',
      errors: null,
      update_errors: null,
      isSnackbarVisible: ref(false),
      count:0,

      pagi:[],
      page:2,
      pageInfo:null,
      totalPages: 4,
      pageSize: 5,
      perPage:10,

      pageSizes: [5, 10, 15,20,25,30],
      URL : window.location.origin,

    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    filterData() {
      return  this.pagi.filter(ticket => {
        return  ticket.title.includes(this.search)||
          ticket.message.includes(this.search)||
          ticket.priority.includes(this.search)

      })
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
    pageSize() {
      this.getAllPaginate()
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAlltickets()
    this.getAllPaginate()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getAllPaginate(){
      console.log(this.pageSize)
      axios
        .get(`${this.URL}/api/ticketsPagination/${this.pageSize}`)
        .then(response => {
          if (response.status == 200){
            (this.pagi = response.data.data,
                this.pageInfo = response.data
            )

          }
        })

    },
    nextPage(page, query) {
      console.log('nextPage', page, query)
      axios
        .get(`${this.URL}/api/ticketsPagination/${page}?page=${query}`)
        .then(response => {
          if (response.status == 200){
            (this.pagi = response.data.data,
                this.pageInfo = response.data
            )

          }
        })
    },


    getAlltickets() {
      axiosIns
        .get(`${this.URL}/api/tickets`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
            'Accept': 'application/json',
          },
        })
        .then(res => {

          this.tickets = res.data
        })
        .catch(
          err => {
            console.log(err)
          },
        )
    },

    Delete(GetId) {
      this.ticket_id = GetId
      this.DeleteAlert()
    },
    async deleteData() {
      axiosIns
        .delete(`${this.URL}/api/tickets/${this.ticket_id}`, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },
        })
        .then(res => {
          this.getAlltickets()
        }).catch(
          err => {
            console.log(err)
          },
        )

    },

    async Updates(Getdata) {
      console.log(Getdata.is_resolved)

      this.id_edit = Getdata.id
      this.title_edit = Getdata.title
      this.message_edit = Getdata.message
      this.priority_edit = Getdata.priority
      this.status_edit = Getdata.status
      this.is_resolved_edit = Getdata.is_resolved==0?false:true
      this.is_locked_edit = Getdata.is_locked==0?false:true
      this.comment = Getdata.comment
    },
    async updateticket() {
      const data = {
        title: this.title_edit,
        message: this.message_edit,
        priority: this.priority_edit,
        status: this.status_edit,
        is_resolved: this.is_resolved_edit,
        is_locked: this.is_locked_edit,
        comment: this.comment,
      }

      await axiosIns.put(`${this.URL}/api/tickets/${this.id_edit}`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      }).then(
        res => {
          this.getAllPaginate()
          this.dialog3 = false
          this.UpdateAlert()
        },
      ).catch(
        err => {
          this.update_errors = err.response.data.errors
        },
      )
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

    insertAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'New Ticket has been created',
        timer: 2000,
      })
    },
    DeleteAlert() {

      Swal.fire({
        icon: 'error',
        title: 'Delete this ticket?',
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
    UpdateAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'Ticket has been updated',
        showConfirmButton: true,
        timer: 2000,
      })
    },
  },
}
</script>
