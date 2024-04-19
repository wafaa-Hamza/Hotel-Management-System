<script setup>
import {
  requiredValidator,
} from '@validators'

const refForm = ref()
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard height="700">
      <h3 class="ml-6 mt-3 mb-5">
        {{$t('Chart of account') }}
      </h3>
      <VRow>
        <VCol cols="12" sm="6" md="12">
          <Treeselect v-model="value" :options="options" class="mb-10 ml-6 mr-6" always-open :searchable="false"
            :max-height="500" :show-count="true" :normalizer="normalizer"/>
        </VCol>

        <VCol cols="12" sm="6" md="12" style="position: absolute;top: 85%;">
          <VDialog v-model="createDialog" width="1024">
            <template #activator="{ props }">
              <VBtn v-bind="props" class="float-end mr-4" v-if="$can('create', 'chart of account')">
                {{ $t('Create') }}
              </VBtn>
            </template>
            <VCard>
              <VCardTitle>
                <span class="text-h5">{{ $t('Create new Child') }}</span>
              </VCardTitle>
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12" sm="6" md="12">
                      <VTextField v-model="selected_parent" :label="$t('Selected parent')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name" :label="$t('Name')" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name_loc" :label="$t('Name loc')" :rules="[requiredValidator]" />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="createDialog = false">
                  {{ $t('Close') }}
                </VBtn>
                <VBtn color="blue-darken-1" variant="text" @click="createChild">
                  {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
          <VDialog v-model="updateDialog" width="1024">
            <template #activator="{ props }">
              <VBtn v-bind="props" class="float-end mr-4" v-if="$can('edit', 'chart of account')">
                {{ $t('Update') }}
              </VBtn>
            </template>
            <VCard>
              <VCardTitle>
                <span class="text-h5">{{ $t('Update') }}</span>
              </VCardTitle>
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name" :label="$t('Name')" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name_loc" :label="$t('Name loc')" :rules="[requiredValidator]" />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="updateDialog = false">
                  {{ $t('Close') }}
                </VBtn>
                <VBtn color="blue-darken-1" variant="text" @click="update">
                  {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
        </VCol>
      </VRow>
    </VCard>
  </div>
</template>
    

<script>
import axios from '@axios'

import Swal from "sweetalert2"
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  name: "ChartAccount",
  components: {
    Treeselect,
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      name: null,
      name_loc: null,
      updateDialog: false,
      createDialog: false,
      value: null,
      options: null,
      selected_parent: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    value() {
    },
    updateDialog() {
      if (this.updateDialog) {
        if (this.value == null) {
          this.updateDialog = false
          this.DangerAlert('Please select a branch')
        } else {
          const selected_child = this.findCollectionById(this.options, this.value)

          this.name = selected_child.label
          this.name_loc = selected_child.account_name_loc
        }
      }
      else {
        this.name = null
        this.name_loc = null
      }
    },
    createDialog() {
      if (this.createDialog) {
        if (this.value == null) {
          this.createDialog = false
          this.DangerAlert('Please select a branch')
        } else {
          const selected_child = this.findCollectionById(this.options, this.value)
          if (selected_child.is_transaction == 1) {
            this.createDialog = false
            this.DangerAlert('Cannot create new child for this branch')
          }
          else {
            this.selected_parent = selected_child.label
          }
        }
      }
      else {
        this.name = null
        this.name_loc = null
        this.selected_parent = null
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getChartOfAccounts()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    getChartOfAccounts() {
      axios.get(`${window.location.origin}/api/ChartOfAccounts`)
        .then(res => {
          this.options = res.data.data
        }).catch(err => { })
    },
    createChild() {
      if (this.value != null) {
        axios.post(`${window.location.origin}/api/ChartOfAccounts`, {
          main_account_id: this.value,
          account_name: this.name,
          account_name_loc: this.name_loc,
        })
          .then(res => {
            this.getChartOfAccounts()
            this.insertAlert('New child added')
            this.createDialog = false
          }).catch(err => { })
      }

    },
    update() {
      if (this.value != null) {
        axios.put(`${window.location.origin}/api/ChartOfAccounts/${this.value}`, {
          account_name: this.name,
          account_name_loc: this.name_loc,
        })
          .then(res => {
            this.getChartOfAccounts()
            this.insertAlert('Child updated')
            this.updateDialog = false
          }).catch(err => { })
      }
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
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      });
    },
    findCollectionById(collections, id) {
      for (const collection of collections) {
        if (collection.id === id) {
          return collection
        }
        if (collection.children) {
          const foundCollection = this.findCollectionById(collection.children, id)
          if (foundCollection) {
            return foundCollection
          }
        }
      }

      return null
    },
    normalizer(node) {
      return {
        id: node.id,
        label: this.$i18n.locale === 'en'?node.label:node.account_name_loc,
        children: node.children,
      }
    },
  },
}
</script>

<style scoped>
.link-container {
  display: flex;
  flex-direction: column;
}

.link-container>div {
  flex-basis: 50%;
}
</style>


<route lang="yaml">
  meta:
    action: view
    subject: chart of account
  </route>
