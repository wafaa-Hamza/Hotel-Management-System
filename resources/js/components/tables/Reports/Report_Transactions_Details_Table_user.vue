<script setup>

// Store
import { useInvoiceStore } from '@/views/apps/invoice/useInvoiceStore'

const invoiceListStore = useInvoiceStore()
const route = useRoute()
const invoiceData = ref()
const paymentDetails = ref()

// 👉 fetchInvoice
invoiceListStore.fetchInvoice(Number(route.params.id)).then(response => {
  invoiceData.value = response.data.invoice
  paymentDetails.value = response.data.paymentDetails
}).catch(error => {
  console.log(error)
})


</script>


<template>
  <div>


    <VCol cols="12" md="12">
      <VCard class="d-print-none">
        <br>
        <div
          style="display: flex;width: 70%;align-content: space-between;justify-content: space-between;float: inline-end;">
          <VCol>
            <AppDateTimePicker v-model="start_dates" :placeholder="$t('Start date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol>
            <AppDateTimePicker v-model="end_dates" :placeholder="$t('end date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>


          <VCol>

            <VCombobox v-model="Users_id" :items="Users" :label="$t('user id')" item-title="firstname"
              style="width: 160px;"  item-color="customColorValue" >


            </VCombobox>
          </VCol>

          <VCol style="display: flex;justify-content: space-between;">
            <VBtn @click="getDetails">
              {{ $t('Search') }}
            </VBtn>


            <VBtn @click="printInvoice">
              {{ $t('print') }}
            </VBtn>
          </VCol>

        </div>
        <br><br><br>
      </VCard>
      <br>

      <VCard v-show="Visability" style="padding-bottom: 2%;" id="printable-content">
        <br>
        <div style="padding: 0 2%;">
          <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
            <h6>{{ localSetting.name }}</h6>
            <h6>MSS00174</h6>
          </div>
          <VRow>
            <VCol cols="12" sm="12" md="12">
              <VCol style="border: 1px solid black;border-radius: 10px;text-align: center;" cols="12" sm="12" md="12">
                <h5 style="position: absolute;">
                  {{ $t('Hotel Date') }} : {{ localSetting.hotel_date }}
                </h5>
                <h2>{{ $t('Transaction Details') }}</h2>
                <h5 style="color: green;">
                  For (All Ledger Code)
                </h5>
              </VCol>
              <br>
              <h4 style="color: blue;">
                <div style="display: flex;justify-content: space-between;">
                  <h4 style="display: inline-block;color: blue;">

                    {{ $t('for the date of') }} : {{ $t('from') }} {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
                  </h4>


                </div>
              </h4>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style=" width: 100%;  color: black;font-size: 12px;">
                  <thead style=" color: black;font-size: 12px;">
                    <tr>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Room name') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Folio no') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Hotel Date') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('User Name') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Voucher') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Amount') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Description') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Guest Name') }}
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in Details" class="text-center" style="height: 20px;border-right: 1px solid black;">
                      <td style="border-bottom: 1px solid;">{{ item.room.rm_name_en }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.guest.id }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.hotel_date }}</td>
                      <td style="border-bottom: 1px solid;"> {{ item.created_by.firstname }} {{ item.created_by.lastname
                      }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.trans_no }}</td>
                      <td style="border-bottom: 1px solid;">{{
                        item.charge_amount == null ? item.payment_amount : item.charge_amount }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.description }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.guest.profile.first_name }} {{
                        item.guest.profile.last_name }}</td>
                    </tr>
                    <tr>
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> {{ $t('Room Count') }} => {{ dataCount }}</b>
                      </td>
                      <td />
                      <td />
                      <td />
                      <td />
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>{{ $t('Sum') }} => {{ tot_sum_charge }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">

            {{ $t('No Data Selected') }}
          </h1>
          <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h5> {{ formattedDate }}</h5>
            <h6>
              {{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
          </div>
        </div>
      </VCard>


    </VCol>
  </div>
</template>
<script>

import axios from "@axios"

import jsPDF from "jspdf"


export default {


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: false,
      URL: window.location.origin,
      Details: [],
      Users: [],
      date: '',
      in: '',
      out: '',
      start_dates: '',
      end_dates: '',
      Users_id: '',
      dataCount: 0,
      tot_sum_charge: '',
      doc: '',
      content: '',
      Ledgers: [],
      led: null,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      $refs: null,
      SumCharge: [],
      SumsCharge: 0,
      SumsPayment: 0,
    }

  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getUsers()
    const storedData = localStorage.getItem('userData');
    if (storedData) {
      this.myData = JSON.parse(storedData);
      this.userData = this.myData.user
    }
    const SettingData = localStorage.getItem('keyinfo');
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData);
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
  },
  computed: {
    formattedDate() {
      const day = String(this.currentDate.getDate()).padStart(2, '0');
      const month = String(this.currentDate.getMonth() + 1).padStart(2, '0'); // Note: Month is zero-based
      const year = this.currentDate.getFullYear();
      const date = this.currentDate.toLocaleDateString('en-GB');
      const time = this.currentDate.toLocaleTimeString();
      return `${year}-${month}-${day} ${time}`;
    }
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {

    downloadpdf(type) {
      var con = document.getElementById('printable-content')
      var pdf = new jsPDF()
      window.open(pdf.output(type))

      pdf.save()
    },
    getUsers() {
      axios
        .get(`${this.URL}/api/user`)
        .then(response => this.Users = response.data)
    },
    getDetails() {
      axios.post(`${this.URL}/api/transactions-details-by-user`, {
        start_date: this.start_dates,
        end_date: this.end_dates,
        user_id: this.Users_id.id,
      }).then(res => (this.Details = res.data.data.transactions_details_by_user, this.dataCount = res.data.data.transactions_details_by_user.length, this.tot_sum_charge = res.data.data.tot_sum_charge
      ))

      this.Visability = true
    },

    async printInvoice() {

      await this.getDetails();
      //
      setTimeout(function () {



        window.print();


      }, 1000);
    }
  },

}
</script>
<style lang="scss">
@media print {
  .v-application {
    background: none !important;
  }

  @page {
    margin: 0;
    size: auto;

    @bottom-left {
      content: "Page" counter(page) " of " counter(pages);
    }
  }

  .layout-page-content,
  .v-row,
  .v-col-md-9 {
    padding: 0;
    margin: 0;
  }

  .product-buy-now {
    display: none;
  }

  .v-navigation-drawer,
  .layout-vertical-nav,
  .app-customizer-toggler,
  .layout-footer,
  .layout-navbar,
  .layout-navbar-and-nav-container {
    display: none;
  }

  .v-card {
    box-shadow: none !important;

    .print-row {
      flex-direction: row !important;
    }
  }

  .layout-content-wrapper {
    padding-inline-start: 0 !important;
  }
}

/* width */
::-webkit-scrollbar {
  block-size: 10px;
  inline-size: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  border-radius: 10px;
  box-shadow: inset 0 0 5px grey;
}

/* Handle */
::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: mediumpurple;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, 60%);
}
</style>
