<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'

const rules = [files => !files  || !files.some(file => file.size > 5e6) || 'Please insert only 5 files each file size should be less than 5 MB!']
</script>


<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div style="float: right;width: 45%;display: flex;justify-content: space-between;">
      <VRow justify="">
        <div style="width: 65%;display: flex;justify-content: space-between">
          <VSelect item-color="customColorValue" :label="$t('row')" :items="['10', '20', '30', '40']" variant="solo" style="width: 18%;" />
          <VSpacer />
          <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 70%;" />
        </div>
        <VSpacer />
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'ticket')">

              {{$t('Create Ticket')}}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5"> {{$t('Ticket details')}}</span>
            </VCardTitle>

            <VCardText>
              <VContainer>
                <div v-if="hasErrors">
                  <div v-for="(error, key) in errors" :key="key">
                    <VAlert closable close-label="Close Alert" class="mb-5">
                      {{ error[0] }}
                    </VAlert>
                  </div>
                </div>
                <VRow>
                  <VCol cols="12" sm="6" md="12">
                    <VTextField v-model="title"  :label="$t('Title')" :rules="[requiredValidator]" />
                  </VCol>
                  <VCol cols="12" sm="6" md="12">
                    <VTextarea v-model="message"  :label="$t('Message')" :rules="[requiredValidator]" />
                  </VCol>

                  <VCol cols="6" sm="12">
                    <VFileInput ref="fileInput" show-size multiple  :label="$t('Upload file')" :rules="rules" />
                  </VCol>
                </VRow>
              </VContainer>
            </VCardText>
            <VCardActions>
              <VSpacer />
              <VBtn color="blue-darken-1" variant="text" @click="dialog = false, errors = null">
                Close
                {{$t('export')}}
              </VBtn>
              <VBtn color="blue-darken-1" variant="text" @click.prevent="CreateTicket($event)">
                Submit
                {{$t('export')}}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
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

            {{$t('Title')}}
          </th>
          <th >

            {{$t('Message')}}
          </th>
          <th v-if="$can('edit', 'ticket')">

            {{$t('Show')}}
          </th>
          <th v-if="$can('delete', 'ticket')">

            {{$t('Delete')}}
          </th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,index) in tickets" :key="item.id">
          <td>{{ ++index}}</td>
          <td>{{ item.title }}</td>
          <td>{{ item.message }}</td>

          <td v-if="$can('edit', 'ticket')">
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="item.dialogVisible" width="1024">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(item)">
                      <VIcon icon="mdi-eye" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">{{$t('My Ticket')}}</span>
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
                            <VTextField v-model="title_edit" disabled:label="$t('Title')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="12">
                            <VTextarea v-model="message_edit" disabled :label="$t('Message')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="6" sm="12">
                            <div v-for="files in item.ticket_files">
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
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VRow>
            </VBtn>
          </td>
          <td v-if="$can('delete', 'ticket')">
            <VBtn  @click="Delete(item.id)" color="red" style="background: red;color: white">
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
import axiosIns from "axios"
import Swal from 'sweetalert2'


export default {
  name: "create-ticket",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      page: 1,
      dialog: false,
      dialog3: false,
      username: '',
      title: '',
      message: '',
      priority: 'low',
      status: 'open',
      is_resolved: '',
      is_locked: '',
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
      errors: null,
      update_errors: null,
      isSnackbarVisible: ref(false),
      ticket_id: '',
      URL : window.location.origin,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
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
  mounted() {
    this.getAlltickets()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {


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
    async CreateTicket(event) {


      

      const formData = new FormData()
      const files = this.$refs.fileInput.files
      for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i])

      }
      formData.append('title', this.title)
      formData.append('message', this.message)
      formData.append('priority', this.priority)
      formData.append('status', this.status)




      await axiosIns.post(`${this.URL}/api/tickets`, formData, {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          'Content-Type': 'application/*',
        },
      }).then(
        res => {

          this.getAlltickets()
          this.dialog = false
          this.insertAlert()



        },
      ).catch(
        err => {
          console.log(err)
          this.errors = err
        },
      )
    },


    async Delete(GetId) {
      this.ticket_id = GetId
      this.DeleteAlert()
    },
    deleteData() {
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

      this.ticket_id = Getdata.id
      this.title_edit = Getdata.title
      this.message_edit = Getdata.message

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

  },
}
</script>
