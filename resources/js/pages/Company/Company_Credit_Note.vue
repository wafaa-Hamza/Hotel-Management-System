<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VRow>
        <VCol cols="3" sm="3" md="3">
          <VCombobox v-model="company" :label="$t('Company')" :items="companies" :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" clearable />
        </VCol>
        <VCol cols="3" sm="3" md="3">
          <VCombobox v-model="way_of_payment" :label="$t('Way of payment*')" :items="payments" item-title="name"
                     item-value="code" :rules="[requiredValidator]" />
        </VCol>

        <!--        <VCol cols="6" sm="6" md="3">-->
        <!--          <VCombobox v-model="trans_type_data" :label="$t('trans_type')" :items="trans_type"-->
        <!--                      />-->
        <!--        </VCol>-->
        <VCol cols="3" sm="3" md="3">
          <VTextField v-model="ref_no" :label="$t('Ref no.')" />

        </VCol>

        <VCol cols="5" sm="5" md="5">
          <VTextarea v-model="amount" :label="$t('Amount')" rows="2" />
          <br>
          <AppDateTimePicker  :label="$t('Trans Date')" v-model="trans_date"
                              :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
        </VCol>

        <VCol cols="7" sm="7" md="7" >
          <VTextarea v-model="description" :label="$t('Description')" auto-grow  rows="10"/>
        </VCol>

        <VCol cols="12" sm="6" md="5">
          <VRadioGroup v-model="type_radio" inline>
            <VRadio label="Auto earlier to recent" value="e" density="compact" />
            <VRadio label="Auto recent to earlier" value="r" density="compact" />
            <VRadio label="Manual" value="m" density="compact" />
          </VRadioGroup>
        </VCol>

        <VCol cols="12" sm="6" md="6"></VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="updateVouchers" :disabled="disable_submit">
            {{ $t("Apply") }}
          </VBtn>
        </VCol>
      </VRow>



      <VTable class="mt-8" fixed-header height="250">
        <thead>
        <tr>
          <th>
            {{ $t('Date') }}
          </th>
          <th>
            {{ $t('Invoice no') }}
          </th>
          <th>
            {{ $t('Folio no') }}
          </th>
          <th>
            {{ $t('Guest/Group name') }}
          </th>
          <th>
            {{ $t('Amount') }}
          </th>
          <th>
            {{ $t('Paid') }}
          </th>
          <th>
            {{ $t('Balance') }}
          </th>
          <th>
            {{ $t('payment') }}
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
          <td>{{ item.paid_amount }}</td>
          <td> {{ item.balance }}</td>
          <td>
            <VTextField v-model="payment[index]" :label="$t('payment')" :readonly="!manual" />
          </td>
        </tr>
        </tbody>
      </VTable>
      <VRow class="pt-10">

        <VCol cols="12" sm="6" md="5">
          <VTextField v-model="total_payment" :label="$t('Total payment')" readonly />
        </VCol>
        <VCol cols="12" sm="6" md="5"></VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="reset">
            {{ $t("Reset") }}
          </VBtn>
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="submitVoucher" :disabled="disable_submit">
            {{ $t("Submit") }}
          </VBtn>
        </VCol>
      </VRow>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"
import Swal from 'sweetalert2'
import moment from 'moment'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      manual: false,
      description: null,
      way_of_payment: null,
      trans_date: null,
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
      Way_id: [],
      payments: [],
      total_payment: 0.00,
      vouchers: [],
      invoices: [],
      ref_no: null,
      trans_type:['REC','DRN'],
      trans_type_data:null

    }
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    payment: {
      // eslint-disable-next-line sonarjs/cognitive-complexity
      handler(newCodes, oldCodes) {
        let total = null
        for (let index = 0; index < this.vouchers.length; index++) {
          if (this.payment[index] != null && this.payment[index] != '' && this.payment[index] >= 1) {
            total += parseInt(this.payment[index])
          }
        }
        this.total_payment = total
      },
      deep: true, // Watch for nested changes within the array
    },
    company() {
      if (this.company != null) {
        this.searchCompanyincoives()
      }
    },

    type_radio() {
      if (this.type_radio == 'm') {
        this.manual = true
      }
      else {
        this.manual = false
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.GetCompanyProfiles()
    this.Getpayment()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    submitVoucher() {
      if (this.total_payment > this.amount) {
        this.warnAlert()
      }
      else {
        this.invoices = []

        for (let index = 0; index < this.vouchers.length; index++) {
          if(this.payment[index]){
            this.invoices.push({
              invoice_no: this.vouchers[index].invoice_no,
              Paid_amount: this.payment[index],
            })
          }


        }
        console.log(this.trans_type_data)
        axios
          .post(`${window.location.origin}/api/credit`, {
            company_id: this.company.id,
            ref_no: this.ref_no,
            description: this.description,
            debit_amount: 0,
            credit_amount: this.amount,
            invoices: this.invoices,
            trans_date: this.trans_date,

          }, {
            headers: {
              "Authorization": "Bearer " + localStorage.getItem("accessToken"),
              "Accept": "application/json",
            },
          })
          .then(res => {
            this.insertAlert('Data has been recorded')
            this.vouchers = []
          }).catch(err => {
          console.log(err)
        })
      }

    },
    updateVouchers() {
      if (this.type_radio == 'e') {
        this.earlierToRecent()
      }
      else if (this.type_radio == 'r') {
        this.recentToEarlier()
      }
    },
    earlierToRecent() {
      let amount = this.amount
      this.payment = []
      for (let index = 0; index < this.vouchers.length; index++) {
        if (this.vouchers[index].balance <= amount) {
          this.payment[index] = this.vouchers[index].balance
          amount -= this.vouchers[index].balance
        }
        else {
          this.payment[index] = amount
          break
        }

      }
    },
    recentToEarlier() {
      let amount = this.amount
      this.payment = []

      for (let index = this.vouchers.length - 1; index >= 0; index--) {
        if (this.vouchers[index].balance <= amount) {
          this.payment[index] = this.vouchers[index].balance
          amount -= this.vouchers[index].balance
        }
        else {
          this.payment[index] = amount
          break
        }

      }
    },
    reset() {

    },
    calculateBalance(index) {
      let balance = 0
      let total_debit = 0
      let total_credit = 0
      for (let i = 0; i <= index; i++) {
        const currentItem = this.results[i]

        balance += currentItem.debit_amount - currentItem.credit_amount
        total_debit += parseFloat(currentItem.debit_amount)
        total_credit += parseFloat(currentItem.credit_amount)
        this.debit_total = total_debit
        this.credit_total = total_credit
        this.balance = balance
      }

      return balance


    },
    Getpayment() {
      axios
        .get(`${window.location.origin}/api/payment`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
            'Accept': 'application/json',
          },
        })
        .then(res => {
          this.payments = res.data
          console.log(res.data)
        }).catch(
        err => {
          console.log(err)
        },
      )
    },

    GetCompanyProfiles() {
      axios
        .get(`${window.location.origin}/api/companyProfile`, {
          headers: {
            "Authorization": "Bearer " + localStorage.getItem("accessToken"),
            "Accept": "application/json",
          },
        })
        .then(res => {
          this.companies = res.data
          console.log(this.companies)
        }).catch(err => {
        console.log(err)
      })
    },
    searchCompanyincoives() {
      axios
        .post(`${window.location.origin}/api/company-invoices`, {
          company_id: this.company.id,
        }, {
          headers: {
            "Authorization": "Bearer " + localStorage.getItem("accessToken"),
            "Accept": "application/json",
          },
        })
        .then(res => {
          this.vouchers = res.data
          console.log(res.data)
        }).catch(err => {
        console.log(err)
      })
    },
    clear() {
      this.company = null
      this.in_date = null
      this.out_date = null
      this.results = []
      this.debit_total = null
      this.credit_total = null
      this.balance = null

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
