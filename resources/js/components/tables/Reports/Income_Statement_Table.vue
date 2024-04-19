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
        <div style="display: flex;width: 40%;justify-content: space-between;float: inline-end;">
          <VCol>
            <AppDateTimePicker v-model="dates" :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
              :placeholder="$t('date')" />
          </VCol>
          <VCol style="display: flex;justify-content: space-around;">
            <VBtn @click="getIncome">
              {{ $t('Search') }}
            </VBtn>


            <VBtn @click="printInvoice">
              {{ $t('print') }}
            </VBtn>

          </VCol>
        </div>
      </VCard>
      <br>



    </VCol>
    <VCard v-show="Visability">
      <div id="element-to-print" style="padding: 0 2%;">
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
          <h6>{{ localSetting.name }}</h6>
          <h6>MSS00174</h6>
        </div>
        <VRow>
          <VCol cols="12" sm="12" md="12">
            <VCol style="border: 1px solid black;border-radius: 10px;text-align: center;" cols="12" sm="12" md="12">
              <h5 style="position: absolute;">
                Hotel Date : {{ localSetting.hotel_date }}
              </h5>
              <h2>Income Statement</h2>
            </VCol>
            <br>
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Description
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Amount
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      %
                    </th>

                  </tr>
                </thead>
                <tbody v-for="(item, itemObjKey) in InCome" :key="itemObjKey" style="color: black;height: 20%;text-align: center">
                  <tr class="text-center" style="border: none;background: lightslategray;">
                    <td style="border-bottom: 1px solid;">{{ item.name }}</td>
                    <td style="border-bottom: 1px solid;">
                      <p v-for="TransAmount in item.transactions_led_cat">{{ TransAmount.charge_amount_by_ledCat }}</p>
                    </td>
                    <td style="border-bottom: 1px solid;">
                      <p v-for="TransPer in item.transactions_led_cat">{{ TransPer.payment_amount_by_ledCat }}</p>
                    </td>


                  </tr>
                  <tr v-for="ledger in item.ledgers" class="text-center">
                    <td style="border-bottom: 1px solid;">{{ ledger.name }}</td>
                    <td style="border-bottom: 1px solid;">
                      <p v-for="TransAmountChild in ledger.transactions">{{ TransAmountChild.charge_amount }}</p>
                    </td>
                    <td style="border-bottom: 1px solid;">
                      <p v-for="TransPerChild in ledger.transactions">{{ TransPerChild.payment_amount }}</p>
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
        <div style="display: flex;justify-content: space-between;margin-top: 5%;">
          <h5> {{ formattedDate }}</h5>
          <h6>Printed By : {{ userData.firstname }} {{ userData.lastname }}</h6>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      URL: window.location.origin,
      InCome: [],
      dates: '',
      dataCount: 0,
      hotel_date: '',
      Visability: false,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }
  },
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
    getIncome() {
      axios.post(`${this.URL}/api/income-statement-report`, {

        hotel_date: this.dates,
      }).then(res => (this.InCome = res.data.income_statement, this.dataCount = res.data.income_statement.length))
      this.Visability = true
    },

    async printInvoice() {
      await this.getIncome()

      window.print()
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
