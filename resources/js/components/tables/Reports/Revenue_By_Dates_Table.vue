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
        <div style="display: flex;width: 55%;justify-content: space-between;float: inline-end;">


          <VCol>
            <AppDateTimePicker v-model="start_dates" :placeholder="$t('Start date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol>
            <AppDateTimePicker v-model="end_dates" :placeholder="$t('end date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol>
            <VBtn @click="getRevenue" style="float: inline-end;">
              {{ $t('Search') }}
            </VBtn>
          </VCol>
          <VCol>
            <VBtn @click="printInvoice">
              {{ $t('print') }}
            </VBtn>

          </VCol>
        </div>
        <br><br><br>
      </VCard>
      <br>



    </VCol>
    <VCard v-show="Visability" style="padding-bottom: 2%;">
      <br>
      <div id="element-to-print" style="padding: 0 2%;">
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
              <h2>Revenue By Dates</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                for the date of : From {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
              </h4>

            </div>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Date') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Accomodation') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('F & B') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Communication') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Laundry') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Other Revenues') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Total') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, itemObjKey) in revenue" :key="itemObjKey"  style="height: 20px;text-align: center">
                    <td style="border-bottom: 1px solid;">{{ item.date }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.Acco }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.fb }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.com }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.lun }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.other }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.total }}</td>
                  </tr>
                  <tr v-if="dataCount > 0">
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ $t('Rm Count') }} => {{ dataCount }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ Acco_Count }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ FB }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ com }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ lun }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ other }}
                    </td>
                    <td style="box-shadow: 0 5px 3px 0;">
                      {{ total }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>
        <h1 style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;" v-if="dataCount == 0">
          {{ $t('NoDataSelected') }}</h1>

        <div style="display: flex;justify-content: space-between;margin-top: 5%;" v-if="dataCount > 0">
          <h5> {{ formattedDate }}</h5>
          <h6> {{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
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
      Visability: false,
      URL: window.location.origin,
      revenue: [],
      start_dates: '',
      end_dates: '',
      dataCount: 0,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      rev_over: [],
      Acco_Count: 0,
      FB: 0,
      com: 0,
      lun: 0,
      other: 0,
      total: 0,
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

    getRevenue() {
      axios.post(`${this.URL}/api/revenue-by-dates-report`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
      }).then(res => (this.revenue = res.data, this.dataCount = res.data.length,
        this.revenue.forEach((ele, value) => { this.Acco_Count += ele.Acco, this.FB += ele.fb, this.com += ele.com, this.lun += ele.lun, this.other += ele.other, this.total += ele.total })))
      this.Visability = true
    },

    async printInvoice() {
      await this.getRevenue();

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
