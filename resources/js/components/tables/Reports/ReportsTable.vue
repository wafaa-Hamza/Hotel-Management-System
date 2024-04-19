<script setup>

// Store
import { useInvoiceStore } from '@/views/apps/invoice/useInvoiceStore'
const { width: windowWidth } = useWindowSize()
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

    <VCard class="d-print-none">
      <br>
      <div style="float: right;width: 70%;display: flex;justify-content: space-between;align-content: space-between;">
        <VCol>
          <AppDateTimePicker v-model="Dates" :placeholder="$t('Date')" />
        </VCol>

        <VCol style="display: flex;justify-content: space-around">
          <VBtn @click="getReport">
            {{ $t('Search') }}
          </VBtn>


          <VBtn @click="printInvoice">
            {{ $t('print') }}
          </VBtn>
        </VCol>

      </div>
      <br><br><br>
    </VCard>
    <div id="element-to-print" style="padding: 0 2%">
      <br />
      <div style="display: flex;justify-content: space-between;margin-bottom: 2%">
        <h6>{{ localSetting.name }}</h6>

      </div>
      <VRow>
        <VCol cols="9" sm="9" md="9">
          <VCol style="border: 1px solid black;border-radius: 10px;text-align: center" cols="12" sm="12" md="12">
            <h2>Guest Ledger (Trail Balance)</h2>
            <h5>Hotel Date :{{ localSetting.hotel_date }}</h5>

          </VCol>
          <VDivider />
          <VCol style="border: 1px solid black;border-radius: 10px;padding: 2px" cols="12" sm="12" md="12">
            <table style=" width: 100%;  color: black;font-size: 12px;">
              <thead>
                <tr>
                  <th class="text-center">
                    Code
                  </th>
                  <th class="text-center">
                    Description
                  </th>
                  <th class="text-center">
                    Amount
                  </th>
                </tr>
                <th class="text-left" style="background: #BDBDBD;font-size;: 130%;padding:1%">
                  Charge
                </th>
              </thead>

              <tbody v-for="items in Report_charge">
                <tr v-if="items.transactions_led_cat_sum_charge_amount != null">
                  <td class="text-left"><b>{{ items.name }}</b></td>
                  <td></td>
                 </tr>
                <tr v-for="item in items.ledgers" :key="item.name" class="text-center">
                  <td v-if="items.transactions_led_cat_sum_charge_amount != null">{{ item.code }}</td>
                  <td v-if="items.transactions_led_cat_sum_charge_amount != null">{{ item.name }}</td>
                  <td>{{item.transactions_sum_charge_amount}}
                  </td>
                </tr>
              </tbody>
            </table>
            <h4 style="background: #BDBDBD;padding: 10px"  :style="$i18n.locale=='en'?'direction: rtl':'direction: ltr'" >{{ $t('Total Charge') }} : {{ Report_charge_ledger.transactions_sum_charge_amount }}</h4>

            <table style=" width: 100%;  color: black;font-size: 12px;">
              <thead>
                <tr>
                  <th class="text-left" style="background: #BDBDBD;font-size;: 130%;padding:1%">
                    Taxes
                  </th>
                </tr>
              </thead>
              <tbody>

                <tr v-for="items in Guest_ledger_taxes" class="text-center">
                  <td>-------</td>
                  <td>{{ items.name }}</td>
                  <td> {{ parseFloat(items.transactions_taxes_sum_amount).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
            <h4 style="background: #BDBDBD;padding: 10px"  :style="$i18n.locale=='en'?'direction: rtl':'direction: ltr'">{{$t('Total Taxes')}} : {{ parseFloat(taxes_amount).toFixed(3) }}</h4>
            <table style=" width: 100%;  color: black;font-size: 12px;">
              <thead>
                <tr>
                  <th class="text-left" style="background: #BDBDBD;font-size;: 130%;padding:1%">
                    Payment
                  </th>

                </tr>
              </thead>
              <tbody v-for="items in Report_payment">
                 <tr v-if="items.transactions_sum_payment_amount != null">
                  <td class="text-left"><b>{{ items.name }}</b></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr v-for="item in items.payment_types" :key="item.name" class="text-center">
                  <td v-if="items.transactions_sum_payment_amount">{{ item.code }}</td>
                  <td v-if="items.transactions_sum_payment_amount">{{ item.name }}</td>
                  <td v-if="items.transactions_sum_payment_amount">{{ item.transactions_sum_payment_amount }}</td>
                </tr>
              </tbody>
            </table>
            <h4 style="background: #BDBDBD;padding: 10px;" :style="$i18n.locale=='en'?'direction: rtl':'direction: ltr'">{{$t('Total Payment')}} :  {{sum(extractedValues)}}</h4>

          </VCol>
          <VDivider />

          <VCol style="border: 1px solid black;border-radius: 10px;display: flex;justify-content: space-between" cols="12"
            sm="12" md="12">


            <VCol>
              <p style="display: flex;justify-content: space-between">
              <h6>City Ledger Payments :</h6> <b>{{ AllReportData.city_ledger_payment }}</b></p>
              <p style="display: flex;justify-content: space-between">
              <h6>Reservation Balance :</h6> <b>{{ AllReportData.reservation_balance }}</b></p>
              <p style="display: flex;justify-content: space-between">
              <h6>Company Balance :</h6> <b>{{ AllReportData.company_balance }}</b></p>
            </VCol>

          </VCol>

        </VCol>
        <VSpacer />
        <VCol style="border: 1px solid black;border-radius: 10px;height: 130px;text-align: start" sm="3" md="3">

          <p style="border-bottom: 1px solid black">Pre Balance : {{ AllReportData.pre_balance }}</p>
          <p style="border-bottom: 1px solid black">Today Balance : {{ AllReportData.today_balance }}</p>
          <p>Current Great Balance : {{ AllReportData.current_guest_ledger }}</p>

        </VCol>
      </VRow>

    </div>

  </div>
</template>

<script>
import axios from "@axios"
import { sum } from "@antfu/utils"


export default {
  // eslint-disable-next-line vue/component-api-style
  data() {

    return {
      Report_charge: [],
      Report_payment: [],
      AllReportData: [],
      Report: [],
      URL: window.location.origin,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      Report_charge_ledger: 0,
      Report_payments: 0,
      taxes_amount: 0,
      Report_payment_trans: [],
      Guest_ledger_taxes: [],
      currentDate: new Date(),
      tot_sum_charge: 0,
      tot_sum_payment: [],
      extractedValues: 0,
      currentPayvalue: 0,
      Dates: ''
    }

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
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
    float() {
      return float
    },
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
    sum,


    getReport() {
      axios.post(`${this.URL}/api/guest_ledger_report`, {
        date: this.Dates,
      }).then(res => (this.Report_charge = res.data.Guest_ledger_charges,
        this.Report_payment = res.data.Guest_ledger_payments,
        this.Guest_ledger_taxes = res.data.Guest_ledger_taxes,
        this.AllReportData = res.data,
        this.Report_charge_ledger = res.data.Guest_ledger_charges
          .map(ele => ele.ledgers)
          .flat().reduce((previousValue,currentValue)=>currentValue.transactions_sum_charge_amount+previousValue.transactions_sum_charge_amount),
        this.taxes_amount = res.data.Guest_ledger_taxes
          .reduce((currentValue,previousValue)=> currentValue.transactions_taxes_sum_amount+previousValue.transactions_taxes_sum_amount),
        this.tot_sum_payment = res.data.Guest_ledger_payments.map(ele=>ele.transactions_sum_payment_amount),
      this.extractedValues = this.tot_sum_payment.filter(value => value !== null).map(Number)

      ),


      )
    },

    async printInvoice() {

      await this.getReport();

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

