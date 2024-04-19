<script setup>
import {
  requiredValidator,
  MoreThanZero

} from '@validators'

const refForm = ref()
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="filter w-50">
        <div style="flex-basis: 20%;" class="ml-4">
          <VSelect item-color="customColorValue" :items="['10', '20', '30', '40']" variant="solo" :label="$t('row')" />
        </div>

        <div style="flex-basis: 50%;" class="ml-4">
          <VTextField v-model="search" type="text" :label="$t('Search')" />
        </div>

        <div>
          <VDialog v-model="dialog" width="1024">
            <template #activator="{ props }" v-if="$can('create', 'ledger')">
              <VBtn class="ml-4" v-bind="props" @click="clearForm">
                {{ $t('Add Ledger') }}
              </VBtn>
            </template>

            <VCard>
              <VCardTitle>
                <span class="text-h5">{{ editFlag ? 'Edit' : 'Add New' }} {{ $t('Ledger') }}</span>
              </VCardTitle>
              <VForm ref="refForm">
                <VCardText>
                  <VContainer>
                    <VRow class="mb-4">
                      <VCol cols="12" sm="12" md="12">
                        <VTextField v-model="ledger_code" :label="$t('ledger code')" persistent-placeholder type="text" />
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VTextField v-model="ledger_name" :label="$t('Ledger Name')" persistent-placeholder type="text"
                          :rules="[requiredValidator]" />
                      </VCol>

                      <VCol cols="12" sm="6" md="6">
                        <VTextField v-model="ledger_name_loc" :label="$t('Ledger Name Loc')" persistent-placeholder
                          type="text" :rules="[requiredValidator]" />
                      </VCol>
                    </VRow>

                    <VRow class="mb-4">
                      <VCol cols="12" sm="6" md="6">


                        <VRadioGroup v-model="ledger_type" :rules="[requiredValidator]">
                          <VRadio :label="$t('Credit')" value="Credit" />
                          <VRadio :label="$t('Debit')" value="Debit" />
                        </VRadioGroup>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VSelect item-color="customColorValue" v-model="ledger_category" :label="$t('Ledger Category')" :items="ledgerCats"
                          item-title="name" item-value="id" persistent-placeholder :rules="[requiredValidator]" />
                      </VCol>
                    </VRow>


                    <VRow class="mb-4">
                      <VCol cols="12" sm="6" md="6">
                        <VSelect item-color="customColorValue" v-model="include_tax" :items="taxes" :label="$t('Included Taxes')" multiple
                          hint="Pick your included taxes" persistent-hint persistent-placeholder item-title="name"
                          item-value="id" chips />
                      </VCol>

                      <VCol cols="12" sm="6" md="6">
                        <VSelect item-color="customColorValue" v-model="exclude_tax" :items="taxes" :label="$t('Excluded Taxes')" multiple
                          hint="Pick your excluded taxes" persistent-hint persistent-placeholder item-title="name"
                          item-value="id" chips />
                      </VCol>



                      <VCol cols="12" sm="6" md="6">
                        <VTextField v-model="gl_account" :label="$t('Gl account')" persistent-placeholder
                                    type="number" :rules="[MoreThanZero]" min="0" />
                      </VCol>

                    </VRow>
                  </VContainer>
                </VCardText>

                <VCardActions>
                  <VSpacer />

                  <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                    {{ $t('Close') }}
                  </VBtn>

                  <VBtn type="submit" color="blue-darken-1" variant="text"
                    @click.prevent="refForm?.validate().then(res => { res.valid ? submitLedger() : null })">
                    {{ $t('Submit') }}
                  </VBtn>
                </VCardActions>
              </VForm>
            </VCard>
          </VDialog>
        </div>
      </div>
    </div>

    <VTable>
      <thead>
        <tr>
          <th>
            {{ $t('code') }}
          </th>
          <th>
            {{ $t('Name') }}
          </th>
          <th>{{ $t('Type') }}</th>
          <th>{{ $t('Category') }}</th>
          <th>{{ $t('Included Taxes') }}</th>
          <th>{{ $t('Excluded Taxes') }}</th>
          <th v-if="$can('edit', 'ledger')">{{ $t('Update') }}</th>
          <th v-if="$can('delete', 'ledger')">{{ $t('Delete') }}</th>
        </tr>
      </thead>
      <tbody>

        <tr v-for="ledger in filteredLedgers" :key="ledger.id">
          <td>
            {{ ledger.code }}
          </td>
          <td v-if="$i18n.locale === 'en'">
            {{ ledger.name }}
          </td>
          <td v-else>
            {{ ledger.name_loc }}
          </td>
          <td v-if="ledger.type">
            {{ ledger.type }}
          </td>
          <td v-else>
            -
          </td>
          <td v-if="ledger.ledger_cat_id">
            {{ ledger.ledger_cat_id }}
          </td>
          <td v-else>
            -
          </td>
          <td v-if="ledger.inctaxes">
            <span v-for="inctax in ledger.inctaxes" :key="inctax.id">
              <VChip class="ma-2">
                {{ inctax.name }}
              </VChip>
              <br v-if="inctax.id % 3 === 0">
            </span>
          </td>
          <td v-else>
            -
          </td>
          <td v-if="ledger.taxes">
            <span v-for="extax in ledger.taxes" :key="extax.id">
              <VChip class="ma-2">
                {{ extax.name }}
              </VChip>
              <br v-if="extax.id % 3 === 1">
            </span>
          </td>
          <td v-else>
            -
          </td>
          <td v-if="$can('edit', 'ledger')">
            <VBtn @click="update(ledger)">
              <VIcon icon="mdi-file-edit-outline" />
            </VBtn>
          </td>
          <td v-if="$can('delete', 'ledger')">
            <VBtn @click="del(ledger.id)" color="red" style="background: red;color: white">
              <VIcon icon="mdi-delete"   style="font-size: 135%"/>
            </VBtn>
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<script>
import { useTaxesStore } from '@/stores/TaxesStore'
import { useLedgerStore } from '@/stores/LedgerStore'
import { mapStores, mapActions } from 'pinia'
import Swal from 'sweetalert2'
export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      dialog: false,
      editFlag: false,
      ledger_name: '',
      ledger_name_loc: '',
      ledger_type: 'Credit',
      include_tax: [],

      gl_account: 0,
      exclude_tax: [],
      ledger_category: '',
      ledger_id: '',
      ledger_code: '',
       included_tax_id_forEdit: [],
      excluded_tax_id_forEdit: [],
    }
  },

  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useTaxesStore, useLedgerStore),

    taxes() {
      return this.taxesStore.taxes
    },

    ledgerCats() {
      return this.ledgerStore.ledgerCats
    },

    ledgers() {
      return this.ledgerStore.ledgers
    },

    filteredLedgers() {
      return this.ledgers.filter(ledger => {
        return ledger.name.toLowerCase().includes(this.search) ||
          ledger.name_loc.toLowerCase().includes(this.search) ||
          ledger.type.toLowerCase().includes(this.search)
      })
    },
  },
  // eslint-disable-next-line vue/component-api-style
  created() {
    this.editFlag = false
    this.getTaxes()
    this.getledgerCats()
    this.getLedgers()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useTaxesStore, ['getTaxes']),
    ...mapActions(useLedgerStore, ['getledgerCats', 'addLedger', 'getLedgers', 'editLedger', 'deleteLedger']),

    clearForm() {
      this.ledger_name = ''
      this.ledger_name_loc = ''
      this.ledger_type = 'Credit'
      this.include_tax = []
      this.exclude_tax = []
      this.ledger_category = ''
    },

    async submitLedger() {
      if (!this.editFlag) {

        const response = await this.addLedger({

          name: this.ledger_name,
          name_loc: this.ledger_name_loc,
          type: this.ledger_type,
          inctaxes: this.include_tax,
          taxes: this.exclude_tax,
          ledger_cat_id: this.ledger_category,

          gl_account: this.gl_account,
          code: this.ledger_code,
        })
        if (response.status != undefined) {
          this.dialog = false
          this.clearForm()
          this.alert('Ledger has been created', true)
        }
      } else {
        if (typeof this.include_tax[0] == 'object' && this.include_tax) {
          this.included_tax_id_forEdit = this.include_tax.map(tax => tax.id)
        } else {
          this.included_tax_id_forEdit = this.include_tax
        }

        if (typeof this.exclude_tax[0] == 'object' && this.exclude_tax) {
          this.excluded_tax_id_forEdit = this.exclude_tax.map(tax => tax.id)
        } else {
          this.excluded_tax_id_forEdit = this.exclude_tax
        }

        const response = await this.editLedger({
          code:this.ledger_code,
          name: this.ledger_name,
          name_loc: this.ledger_name_loc,
          type: this.ledger_type,
          inctaxes: this.included_tax_id_forEdit,
          taxes: this.excluded_tax_id_forEdit,
          ledger_cat_id: this.ledger_category,
        }, this.ledger_id)
        if (response.status != undefined) {
          this.dialog = false
          this.editFlag = false
          this.clearForm()
          this.alert('Ledger has been updated', true)
        }
      }
    },

    update(ledger) {
      this.editFlag = true
      this.dialog = true
      this.ledger_id = ledger.id
      this.ledger_name = ledger.name
      this.ledger_name_loc = ledger.name_loc
      this.ledger_type = ledger.type
      this.include_tax = ledger.inctaxes
      this.exclude_tax = ledger.taxes
      this.ledger_category = ledger.ledger_cat_id
      this.ledger_code = ledger.code
    },

    del(id) {
      this.$swal.fire({
        icon: 'error',
        title: 'Do you want to Delete',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Delete`,
      }).then(result => {

        if (result.isDenied) {
          this.deleteLedger(id)

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
        icon: type ? 'success' : 'danger',
        title: message,
      })
    },
  },


}
</script>

<style scoped>
.filter {
  display: flex;
}
</style>


<route lang="yaml">
  meta:
    action: view
    subject: ledger
  </route>
