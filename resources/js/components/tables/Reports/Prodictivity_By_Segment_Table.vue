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
        <div style="display: flex;width: 65%;justify-content: space-between;float: inline-end;">
          <VCol>
            <AppDateTimePicker v-model="start_dates" placeholder="Start date"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol>
            <AppDateTimePicker v-model="end_dates" placeholder="end date"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>

          <VCol>
            <VBtn @click="getDetails" style="float: inline-end;">
              search
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
                Hotel Date :{{ localSetting.hotel_date }}
              </h5>
              <h2>Prodictivity By Segment</h2>

            </VCol>
            <br>
            <h4 style="color: blue;">
              <div style="display: flex;justify-content: space-between;">
                <h4 style="display: inline-block;color: blue;">
                  for the date of : From {{ start_dates }} to : {{ end_dates }}
                </h4>

              </div>
            </h4>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Segment
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Rooms
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Night
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Totla Rate
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Totla F&B
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Pax
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Avg Room Rate
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Avg Room Rate Per Pax
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Avg FB
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Avg FB Per Pax
                    </th>
                  </tr>
                </thead>
                <tbody v-for="items in Details">
                  <tr
                    style="display: inline;border-left: 10px groove steelblue;color: black;font-size: 100%;font-style: normal;font-weight: bold;">
                    <b style="height: 20%;text-align: center;margin-left: 2%;">{{ items[0].Date }}</b> </tr>
                  <tr v-for="item in items" :key="item.id">
                    <td style="border-bottom: 1px solid;">{{ item.market_segment.name }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.Rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.no_of_nights }}</td>
                    <td style="border-bottom: 1px solid;"> {{ parseFloat(item.Total_Room_Rate).toFixed(2) }}</td>
                    <td style="border-bottom: 1px solid;">{{ parseFloat(item.FB).toFixed(2) }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.no_of_pax }}</td>
                    <td style="border-bottom: 1px solid;">{{ parseFloat(item.Avg_Room_Rate).toFixed(2) }}</td>
                    <td style="border-bottom: 1px solid;">{{ parseFloat(item.Avg_Room_Rate_Per_Pax).toFixed(2) }}</td>
                    <td style="border-bottom: 1px solid;">{{ parseFloat(item.Avg_FB).toFixed(2) }}</td>
                    <td style="border-bottom: 1px solid;">{{ parseFloat(item.Avg_FB_Per_Pax).toFixed(2) }}</td>
                  </tr>
                  <tr>
                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b> Room Count => {{ dataCount }}</b>
                    </td>
                    <td />
                    <td />

                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b>Sum => {{ parseFloat(tot_rate).toFixed(2) }}</b>
                    </td>
                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b>Sum => {{ tot_fb }}</b>
                    </td>
                    <td />
                  </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>
        <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
          No Data Selected
        </h1>
        <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
          <h5> {{ formattedDate }}</h5>
          <h6>Printed By : {{ userData.firstname }} {{ userData.lastname }}</h6>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script>
import axios from "@axios"
import { tr } from "vuetify/locale"


export default {


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: false,
      URL: window.location.origin,
      Details: [],
      date: '',
      in: '',
      out: '',
      start_dates: '',
      end_dates: '',
      company_id: null,
      dataCount: 0,
      tot_sum_charge: 0,
      tot_fb: 0,
      tot_rate: 0,
      country: [],
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
      const day = String(this.currentDate.getDate()).padStart(2, '0')
      const month = String(this.currentDate.getMonth() + 1).padStart(2, '0') // Note: Month is zero-based
      const year = this.currentDate.getFullYear()
      const date = this.currentDate.toLocaleDateString('en-GB')
      const time = this.currentDate.toLocaleTimeString()
      return `${year}-${month}-${day} ${time}`
    }
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {

    getDetails() {
      axios.post(`${this.URL}/api/ProductivityByMarket_Segments`, {
        start_date: this.start_dates = '2023-06-07',
        end_date: this.end_dates = '2023-11-07',
      }).then(res => (this.Details = res.data.ProductivityByMarket_Segments.filter(arr => arr.length > 0), this.country = res.data.country, this.dataCount = res.data.length,
        this.Details.forEach((key, value) => { this.tot_fb += key.FB, this.tot_rate += key.Total_Room_Rate })
      ))
      this.Visability = true
    },
    async printInvoice() {
      await this.getDetails()
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
