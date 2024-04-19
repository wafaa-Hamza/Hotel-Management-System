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

          <div style="display: flex;width: 100%;justify-content: space-between;">
            <VCol>

              <AppDateTimePicker v-model="startDate" placeholder="Start date"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol>
              <AppDateTimePicker v-model="endDate" placeholder="end date"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>

          </div>
          <VCol>
            <VBtn @click="getHistoryForcast">
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
    <VCard style="padding: 2% 0;">
      <div id="element-to-print" style="padding: 0 2%;">

        <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
          <h6>{{ localSetting.name }}</h6>
          <h6>MSS00174</h6>
        </div>


        <VCol style="border: 1px solid black;border-radius: 10px;text-align: center;" cols="12" sm="12" md="12">
          <h5 style="position: absolute;">Hotel Date : {{ localSetting.hotel_date }}</h5>
          <h2>History_Forcast</h2>
        </VCol>
        <br>

        <div style="display: flex;justify-content: space-between;">
          <h4 style="display: inline-block;color: blue;">for the date of : From {{ startDate }} to : {{ endDate }}</h4>
        </div>
        <VDivider />
        <div style="display: flex;width: 100%;justify-content: space-around;border: 1px solid black;">
          <h4 class="text-left">
            Date
          </h4>

          <h4 class="text-left">
            Actual
          </h4>
          <h4 class="text-left">
            Occupancy
          </h4>
          <h4 class="text-left">
            Sales
          </h4>

        </div>
        <table style="border: 1px;width: 100%">
          <thead style=" color: black;font-size: 12px;">


            <tr>
              <th class="text-center" style="border-bottom: 2px solid;">
                Gregorian
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Total Room
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                O.O.O
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                PAX
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Room
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                %
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Rate / Rm
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Rate / pax
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Room
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                F & B
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Phone
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Laundry
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Other
              </th>
              <th class="text-center" style="border-bottom: 2px solid;">
                Total
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in pasts">
              <td style="border-bottom: 1px solid;">{{ item.date }}</td>
              <td style="border-bottom: 1px solid;">{{ item.Total_Rooms }}</td>
              <td style="border-bottom: 1px solid;">{{ item.OoRooms }}</td>
              <td style="border-bottom: 1px solid;">{{ item.pax }}</td>
              <td style="border-bottom: 1px solid;">{{ item.oc_rooms }}</td>
              <td style="border-bottom: 1px solid;">{{ item.RoomPercentage }}</td>
              <td style="border-bottom: 1px solid;">{{ item.rateRoom }}</td>
              <td style="border-bottom: 1px solid;">{{ item.ratePax }}</td>
              <td style="border-bottom: 1px solid;">{{ item.Acco }}</td>
              <td style="border-bottom: 1px solid;">{{ item.fb }}</td>
              <td style="border-bottom: 1px solid;">{{ item.com }}</td>
              <td style="border-bottom: 1px solid;">{{ item.lun }}</td>
              <td style="border-bottom: 1px solid;">{{ item.other }}</td>
              <td style="border-bottom: 1px solid;">{{ item.total }}</td>
            </tr>
            <tr>
              <td style="box-shadow: 0 5px 8px 0;text-align: center;" v-if="dataCount > 0"><b>Room Count =>
                  {{ dataCount }}</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="box-shadow: 0 5px 8px 0;text-align: center;" v-if="dataCount > 0"><b>Sum =>
                  {{ tot_sum_charge }}</b></td>
            </tr>
          </tbody>


        </table>


      </div>
    </VCard>


  </div>
</template>
<script>
import axios from "@axios"

export default {
  data() {
    return {
      Visability: false,
      pasts: [],
      startDate: [],
      endDate: [],
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }
  },
  // computed:{
  //
  //   ...mapStores(useGeneralStore),
  //   pasts(){
  //     return this.generalStore.pasts
  //   },
  //
  // },
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
  // eslint-disable-next-line vue/component-api-style
  methods: {
    // ...mapActions(useGeneralStore, ['getHistoryForcast']),

    getHistoryForcast() {
      axios.post(`${window.location.origin}/api/history-and-forecast`, {
        startDate: this.startDate,
        endDate: this.endDate,
      }).then(res => {
        (this.pasts = res.data.History_And_Forecast)


      })
      // this.Visability = true
    },

    async printInvoice() {
      await this.getHistoryForcast()
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
