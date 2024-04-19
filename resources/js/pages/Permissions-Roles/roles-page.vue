<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers = [

  {
    title: `Role ID`,
    key: 'id',
  },
  {
    title: 'Roles name',
    key: 'name',
  },
  {
    title: 'Update',
    key: 'ok',
  },
]
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

          <VTextField v-model="search" type="text" label="Search Roles" style="width: 70%;" />
        </div>
        <VSpacer />
        <VDialog v-model="addDialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'roles')">
              {{ $t('Add Role') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add New Role') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol cols="12" sm="12" md="12">
                    <VTextField v-model="Roles_name" label="Roles name" persistent-placeholder type="text" />
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


    <br>
    <br>
    <br>

    <VDataTable :items="Roles" :search="search" :items-per-page="10" style="table-layout: fixed">
      <thead>
        <tr>
          <th>
            {{ $t('Role name') }}
          </th>
          <th v-if="$can('edit', 'roles')">
            {{ $t('Update permissions') }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in Roles" :key="item.id">

          <td>{{ item.name }}</td>
          <td v-if="$can('edit', 'roles')">
            <VBtn @click="update(item.raw?item.raw:item)">
              <VIcon icon="mdi-file-edit-outline" />
            </VBtn>
          </td>
        </tr>
      </tbody>
      <br>
    </VDataTable>
    <VDialog v-model="dialog" width="1024">
      <VCard>
        <VCardTitle>
          <span class="text-h5">Edit</span>
        </VCardTitle>
        <VCardText>
          <VTable height="500" fixed-header>
            <thead>
              <tr>
                <th>
                  <VCheckbox v-model="select_all" label="Module" true-value="1" false-value="0"
                    @update:modelValue="selectall" />
                </th>
                <th>
                  {{ $t('Permissions') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(permission, i) in permissions" :key="i">
                <td>
                  <VCheckbox v-model="select_all_subject[i]" true-value="1" false-value="0" :label="permission.subject"
                    @update:modelValue="selectallsubject(i)" />
                </td>

                <td>
                  <div style="display: flex;">
                    <template v-for="(action, index) in permission.action" :key="index">
                      <VCheckbox v-model="actions[i][index]" :label="action"
                        :true-value="action + '-' + permissions[i].subject" :false-value="false" class="mr-5" />
                    </template>
                  </div>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>

        <VCardActions>
          <VSpacer />

          <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
            Close
          </VBtn>

          <VBtn color="blue-darken-1" variant="text" @click.prevent="updateRole">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script>
import axios from "@axios"
import Swal from "sweetalert2"

export default {
  name: "RolesTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      permissions: [],
      actions: [],
      new_permissions: [],
      disable_submit: true,
      role_permissions: [],
      search: '',
      dialog: false,
      Roles: [],
      Rolesid: '',
      addDialog: false,
      Roles_name: '',
      select_all: "0",
      select_all_subject: [false],
      URL: window.location.origin,
      selected_role: null,
    }
  },

  // eslint-disable-next-line vue/component-api-style
  computed: {

  },

  // eslint-disable-next-line vue/component-api-style
  watch: {
    dialog() {
      if (this.dialog == false) {
        this.emptyCheckboxes()
        this.role_permissions = []
        this.selected_role = null
      }
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllRoles()
    this.getPermissions()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    update(role) {
      this.dialog = true
      this.getRolePermissions(role.id)
    },
    assignRolesPermissions() {
      for (let i = 0; i < this.permissions.length; i++) {
        for (let index = 0; index < this.permissions[i].action.length; index++) {
          this.role_permissions.some(permission =>
            permission.name === this.permissions[i].action[index] + '-' + this.permissions[i].subject) ? this.actions[i][index] = this.permissions[i].action[index] +
            '-'
            + this.permissions[i].subject : false
        }
      }
    },
    selectallsubject(i) {
      if (this.select_all_subject[i] == "1") {

        for (let index = 0; index < this.permissions[i].action.length; index++) {
          this.actions[i][index] = this.permissions[i].action[index] + '-' + this.permissions[i].subject
        }
      }
      else {
        for (let index = 0; index < this.permissions[i].action.length; index++) {
          this.actions[i][index] = false
        }
      }
    },
    selectall() {
      if (this.select_all == "1") {
        for (let i = 0; i < this.permissions.length; i++) {
          this.select_all_subject[i] = "1"
          this.selectallsubject(i)
        }
      }
      else {
        for (let i = 0; i < this.permissions.length; i++) {
          this.select_all_subject[i] = "0"
          this.selectallsubject(i)
        }
      }
    },
    updateRole() {
      for (let index = 0; index < this.permissions.length; index++) {
        for (let i = 0; i < this.permissions[index].action.length; i++) {
          if (this.actions[index][i] != false) {
            this.new_permissions.push(this.actions[index][i])
          }
        }
      }
      axios.post(`${this.URL}/api/assignPermissionRole/${this.selected_role}`, {
        permissionID: this.new_permissions,
      })
        .then(({ data }) => {
          this.dialog = false
          this.getAllRoles()
          this.insertAlert('Permissions has been updated')
        })
      this.new_permissions = []
    },
    getRolePermissions(id) {
      axios.post(`${this.URL}/api/getRoleById/${id}`)
        .then(({ data }) => {
          this.role_permissions = data.data[0].permissions
          this.assignRolesPermissions()
          this.selected_role = id
        })
    },
    emptyCheckboxes() {
      for (let i = 0; i < this.permissions.length; i++) {
        for (let j = 0; j < this.permissions[i].action.length; j++) {
          this.actions[i][j] = false // Set checkbox value to false
        }
      }
    },
    getPermissions() {
      axios.get(`${this.URL}/api/getPermissionBySubject`)
        .then(({ data }) => {
          this.permissions = data.data
          this.createActionsArray()
        })
    },
    createActionsArray() {
      this.actions = this.permissions.map(permission => {
        return new Array(permission.action.length).fill(false) // Initialize each checkbox as unchecked
      })
    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    getAllRoles() {
      axios
        .get(`${this.URL}/api/getAllRoles`)
        .then(response => (this.Roles = response.data.data))
    },
    async Add() {
      try {
        const user = await axios.post(
          `${this.URL}/api/storeRole`,
          {
            name: this.Roles_name,
          },
        )

        this.Roles_name = ''
        this.getAllRoles()
        this.addDialog = false
        this.insertAlert('New Role added')
      } catch (e) {
        console.log(e)
      }
    },
  },

}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: roles
  </route>
