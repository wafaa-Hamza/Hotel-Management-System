
<script setup>
import {
  requiredValidator,
} from '@validators'

const refForm = ref()
const form = ref()

 const menusVariant = [
  'warning',
]

const items = [
  {
    title: 'Option 1',
    value: 'Option 1',
  },
  {
    title: 'Option 2',
    value: 'Option 2',
  },
  {
    title: 'Option 3',
    value: 'Option 3',
  },
]
</script>
 
<template>
  <div style="margin-top: 5%">

    <Breadcrumb></Breadcrumb>
    <VCard>
      <VCardText>
        <VRow>
          <VCol cols="12" sm="6" md="5">
            <VTextField v-model="search" type="text" label="Search" />
          </VCol>
          <VCol cols="12" sm="6" md="5" />
          <VCol cols="12" sm="6" md="2">
            <VDialog v-model="dialog" width="1024" persistent z-index="1000">
              <template #activator="{ props }">
                <VBtn v-bind="props" v-if="$can('create', 'extra')">
                  {{ $t('Create New Extra') }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <span class="text-h5">{{ $t('Extras') }}</span>
                </VCardTitle>
                <VForm ref="refForm">
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol cols="12" sm="6" md="4">
                          <VTextField v-model="name"  :label="$t('Name')"  :rules="[requiredValidator]" />
                        </VCol>
                        <VCol cols="12" sm="6" md="4">
                          <VTextField v-model="name_loc" :label="$t('Name loc')"   :rules="[requiredValidator]" />
                        </VCol>
                        <VCol cols="12" sm="6" md="4">
                          <VCombobox v-model="ledger" :label="$t('Ledger')"  :items="ledgers" item-title="name"
                            :rules="[requiredValidator]" />
                        </VCol>
                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="dialog = false, errors = null">
                       {{ $t('Close') }}
                    </VBtn>
                    <VBtn type="submit" color="blue-darken-1" variant="text"
                      @click.prevent="refForm?.validate().then(res => { res.valid ? createExtra() : null })">
                       {{ $t('Submit') }}
                    </VBtn>
                  </VCardActions>
                </VForm>
              </VCard>
            </VDialog>
          </VCol>
        </VRow>
        <br>
        <br>

        <VTable>
          <thead>
            <tr>
              <th>
                {{ $t('Name') }}
              </th>
              <th>
                {{ $t('Name loc') }}
              </th>

              <th v-if="$can('edit', 'extra')">
                Show
              </th>
              <th v-if="$can('delete', 'extra')">
                Delete
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="extras === undefined">
              <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
            </tr>
            <tr v-else v-for="(item) in extras" :key="item.id">
              <td>{{ item.name }}</td>
              <td>{{ item.name_loc }}</td>

              <td v-if="$can('edit', 'extra')">


                <VRow>
                  <VDialog v-model="item.dialogVisible" width="1024" persistent z-index="1000">
                    <template #activator="{ props }">
                      <VBtn v-bind="props" @click="Updates(item)">
                        <VIcon icon="mdi-eye" />
                      </VBtn>
                    </template>
                    <VCard>
                      <VCardTitle>
                        <span class="text-h5">Extras</span>
                      </VCardTitle>
                      <VForm ref="form">
                        <VCardText>
                          <VContainer>
                            <VRow>
                              <VCol cols="12" sm="6" md="4">
                                <VTextField v-model="name"    :label="$t('Name')" :rules="[requiredValidator]" />
                              </VCol>
                              <VCol cols="12" sm="6" md="4">
                                <VTextField v-model="name_loc"   :label="$t('Name loc')" :rules="[requiredValidator]" />
                              </VCol>
                              <VCol cols="12" sm="6" md="4">
                                <VCombobox v-model="ledger"    :label="$t('Ledger')" :items="ledgers" item-title="name"
                                           :rules="[requiredValidator]" />
                              </VCol>
                            </VRow>
                          </VContainer>
                        </VCardText>
                        <VCardActions>
                          <VSpacer />
                          <VBtn color="blue-darken-1" variant="text" @click="item.dialogVisible = false, this.clear()">
                            {{ $t('Close') }}
                          </VBtn>
                          <VBtn type="submit" color="blue-darken-1" variant="text"
                                @click.prevent="form[0]?.validate().then(res => { res.valid ? updateExtras(item) : null })">
                            {{ $t('Update') }}
                          </VBtn>
                        </VCardActions>
                      </VForm>
                    </VCard>
                  </VDialog>
                </VRow>
              </td>
              <td v-if="$can('delete', 'extra')">
                <VBtn color="red" style="background: red;color: white" @click="Delete(item.id)">
                  <VIcon icon="mdi-delete"   style="font-size: 135%"/>
                </VBtn>
              </td>
            </tr>
          </tbody>
          <br>
        </VTable>
      </VCardText>
    </VCard>
    <VDialog
    v-model="dialog4"
    width="200" 
  >
 
    <VCard class="text-center">
          one
<br><br>
          two
          <br><br>
          three
    </VCard>
  </VDialog>
  
  <VBtn  @click.right.prevent="dialog4=true">open</VBtn>
     <!-- <div class="demo-space-x">
      <VMenu
      v-for="(menu, index) in menusVariant"
      :key="menu"
      v-model="menuStates[index]"
    >
      <template #activator="{ on }">
        <VBtn
          :color="menu"
          v-on="on"
           @click.right.prevent="toggleMenu(index)"
        >
          {{ menu }}
        </VBtn>
      </template>

      <VList :items="items" style="position:relative;left:450px;top:490px" />
    </VMenu>
    </div> -->
   </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import { useExtrasStore } from '../stores/ExtrasStore'
import { useLedgerStore } from '../stores/LedgerStore'
import { mapStores, mapActions } from 'pinia'

export default {
  name: "Extras",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      menusVariant: ['warning'],
      items: [
        {
          title: 'Option 1',
          value: 'Option 1',
        },
        {
          title: 'Option 2',
          value: 'Option 2',
        },
        {
          title: 'Option 3',
          value: 'Option 3',
        },
      ],
      menuStates: [false], // Initially, all menus are closed
      form: ref(),
      result: null,
      search: '',
      page: 1,
      dialog: false,
      dialog3: false,
      dialog4:false,
      URL: window.location.origin,
      date: null,
      name: null,
      name_loc: null,
      amount: null,
      category: null,
      reference: null,
      description: null,
      fileInput: null,
      category_name: null,
      category_name_loc: null,
      categoryDialog: false,
      extra_id: null,
      ledger: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useExtrasStore, useLedgerStore),
    extras() {
      return this.extrasStore.extras
    },
    ledgers() {
      return this.ledgerStore.ledgers
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
    this.getExtras()
    this.getLedgers()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: { 
    toggleMenu(index) {
      this.menuStates[index] = !this.menuStates[index];
    },
    ...mapActions(useExtrasStore, ['getExtras', 'addExtra', 'editExtra', 'deleteExtra']),
    ...mapActions(useLedgerStore, ['getLedgers']),
    clear() {
      this.name = null
      this.name_loc = null
      this.ledger = null
    },

    async createExtra() {
      const response = await this.addExtra({
        name: this.name,
        name_loc: this.name_loc,
        ledger_id: this.ledger.id,
      })

      if (response.status != undefined) {
        this.alert('Extra has been created successfully', true)
        this.clear()
        this.dialog = false
      }
    },
    async updateExtras(item) {
      const response = await this.editExtra({
        name: this.name,
        name_loc: this.name_loc,
        ledger_id: this.ledger.id,
      }, item.id)

      if (response.status != undefined) {
        this.alert('Extra has been updated successfully', true)
        this.clear()
        this.dialogVisible = false
      }
    },

    Delete(GetId) {
      this.extra_id = GetId
      this.DeleteAlert()
    },
    deleteData() {
      this.deleteExtra(this.extra_id)

    },

    Updates(Getdata) {

      this.name = Getdata.name
      this.name_loc = Getdata.name_loc
      this.ledger = Getdata.ledger

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
        title: 'Delete this Extra?',
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
    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: type ? 'success' : 'error',
        title: message,
      })
    },
  },
}
</script>



<!-- <route lang="yaml">
  meta:
    action: view
    subject: ledger expenses
  </route> -->
