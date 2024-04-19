<script setup>
import {
  floatValidator,
  requiredValidator
} from '@validators'

const form = ref()
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VForm ref="form">
        <VRow>
          <VCol cols="12" sm="6" md="6">
            <VCombobox v-model="ledger" :label="$t('Ledger')" :items="ledgers"
              :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" clearable :rules="[requiredValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="6">
            <VCombobox v-model="way_of_payment" :label="$t('Way of payment')" :items="payments" item-title="name"
              clearable :rules="[requiredValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="6">
            <VTextField v-model="amount" type="number" :label="$t('Amount')" rows="2"
              :rules="[requiredValidator, floatValidator]" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="6">
            <VTextarea v-model="description" :label="$t('Description')" rows="2" :rules="[requiredValidator]" clearable />
          </VCol>
        </VRow>



        <VTable class="mt-8" fixed-header height="250">
          <thead>
            <tr>
              <th>
                {{ $t('Time') }}
              </th>
              <th>
                {{ $t('Description') }}
              </th>
              <th>
                {{ $t('Amount') }}
              </th>
              <th>
                {{ $t('Voucher#') }}
              </th>
              <th>
                {{ $t('User') }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="vouchers.length === 0">
              <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
            </tr>
            <tr v-else v-for="(item, index) in vouchers" :key="index">
              <td>
                {{ moment(item.date).format("YYYY-MM-DD") }}
              </td>
              <td>{{ item.invoice_no }}</td>
              <td> {{ item.folio_no }}</td>
              <td> {{ item.company_profile.company_name }}</td>
              <td> {{ item.debit_amount }}</td>

            </tr>
          </tbody>
        </VTable>
        <VRow class="pt-10">
          <VCol cols="12" sm="6" md="5">
            <VTextField v-model="voucher_no" :label="$t('Voucher no.')" readonly />
          </VCol>
          <VCol cols="12" sm="6" md="5"></VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="clear">
              {{ $t("Reset") }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click.prevent="form?.validate().then(res => { res.valid ? submitDirectBill() : null })">
              {{ $t("Submit") }}
            </VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCard>
  </div>
</template>

<script>
import { useLedgerStore } from '@/stores/LedgerStore'
import axiosIns from "@axios"
import moment from 'moment'
import { mapActions, mapStores } from 'pinia'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      voucher_no: null,
      ledger: null,
      manual: false,
      description: null,
      way_of_payment: null,
      no: null,
      date: null,
      amount: null,
      payment: [],
      type_radio: 'e',
      companies: [],
      in_date: null,
      out_date: null,
      results: [],
      company: null,
      balance: 0.00,
      debit_total: 0.00,
      credit_total: 0.00,
      disable_submit: false,
      bal: 0.00,
      total_payment: 0.00,
      vouchers: [],
      invoices: [],
      ref_no: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useLedgerStore),
    ledgers() {
      return this.ledgerStore.ledgers
    },
    payments() {
      return this.ledgerStore.payments
    },

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getLedgers()
    this.getPaymentTypes()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useLedgerStore, ['getLedgers', 'getPaymentTypes']),
    async submitDirectBill() {
      await axiosIns
        .post(`${window.location.origin}/api/directBill`, {
          ledger_id: this.ledger != null ? this.ledger.id : null,
          payment_type_id: this.way_of_payment != null ? this.way_of_payment.id : null,
          description: this.description,
          amount: this.amount,
        })
        .then(res => {
          if (res.status != undefined) {
            this.insertAlert('Data has been recorded')
            this.disable_submit = true
            this.voucher_no = res.data.data.transaction_number
          }

        }).catch(err => {
          console.log(err)
        })
    },



    clear() {
      this.ledger = null
      this.way_of_payment = null
      this.description = null
      this.amount = null
      this.voucher_no = null
      this.$refs.form.reset()
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
    warnAlert() {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Total payment should not be greater than actual amount",
        showConfirmButton: false,
        timer: 2000,
      })
    },

  },
}
</script>
