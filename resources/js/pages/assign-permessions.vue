<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VRow>
        <VCol cols="12" md="4" sm="4" />
        <VCol cols="12" md="4" sm="4" class="mb-10 mt-5">
          <VCombobox v-model="user" :label="$t('User')" :items="users" item-title="firstname"
            :menu-props="{ maxHeight: '200px' }" clearable />
        </VCol>
      </VRow>
      <div>
        <VTable height="500" fixed-header>
          <thead>
            <tr>
              <th>
                <VCheckbox v-model="select_all" :label="$t('Module')" true-value="1" false-value="0"
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
                      :true-value="action + '-' + permissions[i].subject" :false-value="false" class="mr-5"
                      @update:modelValue="select(i, index)" />
                  </template>
                </div>
              </td>
            </tr>
          </tbody>
        </VTable>
      </div>

      <VRow>
        <VCol cols="12" sm="4" />
        <VCol cols="12" sm="4" class="mb-5">
          <VBtn block type="submit" :disabled="disable_submit" @click="submit" v-if="$can('edit', 'assignpermissions')">

            {{ $t('update') }}
          </VBtn>
        </VCol>
      </VRow>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"
import { ABILITY_TOKEN } from '@casl/vue'
import { mapActions, mapStores } from 'pinia'
import Swal from "sweetalert2"
import { useGeneralStore } from '../stores/GeneralStore'

export default {
  // eslint-disable-next-line vue/component-api-style
  inject: {
    $ability: { from: ABILITY_TOKEN },
  },

  // eslint-disable-next-line vue/component-api-style
  data() {

    return {
      user: null,
      Role: [],
      permissions: [],
      actions: [],
      new_permissions: [],
      disable_submit: true,
      user_permissions: [],
      select_all: "0",
      select_all_subject: [false],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore),
    users() {
      return this.generalStore.users
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    user() {
      if (this.user != null && this.user != '') {
        this.disable_submit = false
        this.getUserPermissions()
        this.select_all_subject = []
        this.select_all = "0"
      }
      else {
        this.user_permissions = []
        this.emptyCheckboxes()
        this.disable_submit = true
      }
    },

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPermissions()
    this.getAllusersAction()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['getAllusersAction']),

    assignUserPermissions() {
      for (let i = 0; i < this.permissions.length; i++) {
        for (let index = 0; index < this.permissions[i].action.length; index++) {
          this.user_permissions.some(permission =>
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
    emptyCheckboxes() {
      for (let i = 0; i < this.permissions.length; i++) {
        for (let j = 0; j < this.permissions[i].action.length; j++) {
          this.actions[i][j] = false // Set checkbox value to false
        }
      }
    },
    getUserPermissions() {
      axios.get(`${window.location.origin}/api/getUserPermissionByID/${this.user.id}`)
        .then(({ data }) => {
          this.createActionsArray()
          this.user_permissions = data.data
          this.assignUserPermissions()
        })
    },
    submit() {
      for (let index = 0; index < this.permissions.length; index++) {
        for (let i = 0; i < this.permissions[index].action.length; i++) {
          if (this.actions[index][i] != false) {
            this.new_permissions.push(this.actions[index][i])
          }
        }
      }
      console.log(this.new_permissions)
      axios.post(`${window.location.origin}/api/updateUserPermissions/${this.user.id}`, {
        permissionName: this.new_permissions,
      })
        .then(({ data }) => {
          this.insertAlert('Permissions has been updated')
        })
      this.new_permissions = []
    },
    getPermissions() {
      axios.get(`${window.location.origin}/api/getPermissionBySubject`)
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
  },
}
</script>
<!-- 
<route lang="yaml">
  meta:
    action: view
    subject: assignpermissions
  </route> -->
