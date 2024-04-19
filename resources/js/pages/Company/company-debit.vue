<script setup>
import {
  integerValidator,
  requiredValidator,
} from '@validators'

const form = ref()
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-5 pb-5">
      <VForm ref="form">
        <VRow>
          <VCol cols="12" sm="6" md="6" >
            <VCombobox v-model="company" :label="$t('Company')" :items="companies"
                       :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" clearable
                       :rules="[requiredValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="6">
            <AppDateTimePicker v-model="date" :label="$t('Date')" clearable :rules="[requiredValidator]"
                               :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
          </VCol>
          <VCol cols="12" sm="6" md="6">
            <VTextarea v-model="amount" :label="$t('Amount')" rows="2" clearable
                       :rules="[requiredValidator, integerValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VCombobox v-model="way_of_payment" :label="$t('Way of payment*')" :items="payments" item-title="name"
                       item-value="code" :rules="[requiredValidator]" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="ref_no" :label="$t('Ref no')" clearable :rules="[requiredValidator, integerValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="12">
            <VTextarea v-model="description" :label="$t('Description')" auto-grow clearable
                       :rules="[requiredValidator]" />
          </VCol>
          <VCol cols="12" sm="6" md="10"></VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="reset">
              {{ $t("Reset") }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click.prevent="form?.validate().then(res => { res.valid ? submitDebit() : null })">
              {{ $t("Submit") }}
            </VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCard>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import moment from 'moment'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      date: null,
      ref_no: null,
      description: null,
      way_of_payment: null,
      no: null,
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
      payments: [
        {
          name: "cash",
          code: "CASH",
        },
        {
          name: "cards",
          code: "CARD",
        },
        {
          name: "City ledger",
          code: "CL",
        },
        {
          name: "Complimentary",
          code: "COMP",
        },
        {
          name: "House Use",
          code: "HUSU",
        },
      ],
      total_payment: 0.00,
      vouchers: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {



  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.GetCompanyProfiles()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    async submitDebit() {
      try {
        await axiosIns.post(`${window.location.origin}/api/debit`,
          {
            company_id: this.company.id,
            trans_date: this.date,
            trans_type: 'DRN',
            ref_no: this.ref_no,
            description: this.description,
            debit_amount: this.amount,
            credit_amount: 0,
            paid_amount: 0,
          },
        ).then(res => {
          if (res.status != undefined) {
            this.reset()
            this.insertAlert()
          }
        })
      } catch (e) {
        console.log(e)
      }
    },

    GetCompanyProfiles() {
      axiosIns
        .get(`${window.location.origin}/api/companyProfile`, {
          headers: {
            "Authorization": "Bearer " + localStorage.getItem("accessToken"),
            "Accept": "application/json",
          },
        })
        .then(res => {
          this.companies = res.data
        }).catch(err => {
        console.log(err)
      })
    },
    reset() {
      this.date = null
      this.ref_no = null
      this.description = null
      this.way_of_payment = null
      this.no = null
      this.amount = null
      this.$refs.form.reset()
    },

    insertAlert() {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Debit has been recorded",
        showConfirmButton: false,
        timer: 2000,
      });
    },

  },
}
</script>
