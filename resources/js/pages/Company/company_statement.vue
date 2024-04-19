<script setup>
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard class="pl-5 pr-5 pt-2 pb-5">
      <VRow>
        <VCol cols="12" sm="6" md="3">
          <VCombobox v-model="company" :label="$t('Company')" :items="companies"
                     :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" clearable />
        </VCol>

        <VCol cols="12" sm="6" md="3">
          <AppDateTimePicker v-model="in_date" :label="$t('Start date')"
                             :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>
        <VCol cols="12" sm="6" md="3">
          <AppDateTimePicker v-model="out_date" :label="$t('End date')"
                             :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>
        <VCol cols="12" sm="6" md="1"></VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="clear">
            {{ $t("Remove") }}
          </VBtn>
        </VCol>
        <VCol cols="12" sm="6" md="1">
          <VBtn @click="searchCompanyStatement" :disabled="disable_submit">
            {{ $t("Search") }}
          </VBtn>
        </VCol>
      </VRow>



      <VTable class="mt-8" fixed-header height="250">
        <thead>
        <tr>
          <th>
            {{ $t('no') }}
          </th>
          <th>
            {{ $t('Date') }}
          </th>
          <th>
            {{ $t('Description') }}
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
            {{ $t('Reference') }}
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="results.length === 0">
          <td colspan="8" style="text-align: center;">{{ $t('No data available') }}</td>
        </tr>
        <tr v-else v-for="(item, index) in results" :key="index">
          <td>{{ item.id }}</td>
          <td>
            {{ moment(item.trans_date).format("YYYY-MM-DD") }}

          </td>
          <td> {{ item.description }}</td>
          <td> {{ item.debit_amount }}</td>
          <td>{{ item.credit_amount }}</td>

          <td> {{ calculateBalance(index) }}</td>

          <td>{{ item.ref_no }}</td>


        </tr>
        </tbody>
      </VTable>
      <VRow class="pt-10">
        <VCol cols="12" sm="6" md="4">
          <VTextField v-model="debit_total" :label="$t('Total debit')" readonly />
        </VCol>

        <VCol cols="12" sm="6" md="4">
          <VTextField v-model="credit_total" :label="$t('Total credit')" readonly />
        </VCol>
        <VCol cols="12" sm="6" md="4">
          <VTextField v-model="balance" :label="$t('Balance')" readonly />
        </VCol>
      </VRow>
    </VCard>
  </div>
</template>

<script>
import axiosIns from "@axios"
import moment from 'moment'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      companies: [],
      in_date: '',
      out_date: '',
      results: [],
      company: null,
      balance: 0.00,
      debit_total: 0.00,
      credit_total: 0.00,
      disable_submit: true,
      bal: 0.00,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {



  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    company() {
      if (this.company != null && this.in_date != null && this.out_date != null) {
        this.disable_submit = false
      }
      else {
        this.disable_submit = true
      }
    },
    in_date() {
      if (this.company != null && this.in_date != null && this.out_date != null) {
        this.disable_submit = false
      }
      else {
        this.disable_submit = true
      }
    },
    out_date() {
      if (this.company != null && this.in_date != null && this.out_date != null) {
        this.disable_submit = false
      }
      else {
        this.disable_submit = true
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.GetCompanyProfiles()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
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
    searchCompanyStatement() {
      axiosIns
        .post(`${window.location.origin}/api/companystatemen_dates`, {
          startDate: this.in_date,
          endDate: this.out_date,
          company_id: this.company.id,
        }, {
          headers: {
            "Authorization": "Bearer " + localStorage.getItem("accessToken"),
            "Accept": "application/json",
          },
        })
        .then(res => {
          this.results = res.data.CompanyARStatement
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

    Updates(Getdata) {
      this.company_profile_id_edit = Getdata.id;
      this.company_code_edit = Getdata.company_code;
      this.company_name_edit = Getdata.company_name;
      this.company_name_loc_edit = Getdata.company_name_loc;
      this.city_edit = Getdata.city;
      this.address_edit = Getdata.address;
      this.phone_1_edit = Getdata.phone_1;
      this.phone_2_edit = Getdata.phone_2;
      this.mobile_edit = Getdata.mobile;
      this.fax_edit = Getdata.fax;
      this.email_edit = Getdata.email;
      this.p_o_box_edit = Getdata.p_o_box;
      this.zip_number_edit = Getdata.zip_number;
      this.tax_no_edit = Getdata.tax_no;
      this.contact_person_edit = Getdata.contact_person;
      this.position_edit = Getdata.position;
      this.contact_phone_edit = Getdata.contact_phone;
      this.contact_mobile_edit = Getdata.contact_mobile;
      this.max_credit_limit_edit = Getdata.max_credit_limit;
      this.current_balance_edit = Getdata.current_balance;
      this.def_rate_code_edit = Getdata.def_rate_code;
      this.country_id_edit = Getdata.country;
    },
    async UpdateCompanyProfile() {
      const data = {
        company_code: this.company_code_edit,
        company_name: this.company_name_edit,
        company_name_loc: this.company_name_loc_edit,
        city: this.city_edit,
        address: this.address_edit,
        phone_1: this.phone_1_edit,
        phone_2: this.phone_2_edit,
        mobile: this.mobile_edit,
        fax: this.fax_edit,
        email: this.email_edit,
        p_o_box: this.p_o_box_edit,
        zip_number: this.zip_number_edit,
        tax_no: this.tax_no_edit,
        contact_person: this.contact_person_edit,
        position: this.position_edit,
        contact_phone: this.contact_phone_edit,
        contact_mobile: this.contact_mobile_edit,
        max_credit_limit: this.max_credit_limit_edit,
        current_balance: this.current_balance_edit,
        def_rate_code: this.def_rate_code_edit,
        country_id: this.country_id_edit.id,
      };
      await axiosIns.put(`${window.location.origin}/api/companyProfile/${this.company_profile_id_edit}`, data, {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("accessToken"),
        },
      }).then(res => {
        this.GetCompanyProfiles();
        this.dialog3 = false;
        this.UpdateAlert();
      }).catch(err => {
        console.log(err);
        this.update_errors = err.response.data.errors;
      });
    },
    insertAlert() {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Company profile has been created",
        showConfirmButton: false,
        timer: 2000,
      });
    },

  },
}
</script>
