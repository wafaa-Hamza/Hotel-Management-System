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
      <VCard class="d-print-none" v-if="show">
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
            <VBtn @click="getDaily" style="float: inline-end;">
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
              <h2>Daily Sales Summary By Date</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                {{ $t('for the date of') }} : {{ $t('from') }} {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
              </h4>

            </div>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <VCard style="display: flex;justify-content: space-around;border: 1px solid;">
                <b>Date</b>
                <b>Actual</b>
                <b>Occupancy</b>
                <b>Sales</b>
              </VCard>

              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Gregorian') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Hijri') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('C/In') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('C/Out') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('def pax') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Room name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      %
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('def pax') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Room') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('F & B') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('com') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Laundry') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Others') }}
                    </th>
                    <th class="text-left">
                      {{ $t('Total') }}
                    </th>
                  </tr>
                </thead>
                <tbody v-for="(item, itemObjKey) in daily_sales" :key="itemObjKey">
                  <tr v-for="daily in item"  style="height: 20px;text-align: center">
                    <td style="border-bottom: 1px solid;">{{ daily.hotel_date }}</td>
                    <td style="border-bottom: 1px solid;"></td>
                    <td style="border-bottom: 1px solid;">{{ daily.act_chkin_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.act_chkout_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.guest_inhouse_pax }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.oc_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.occupancyPer }}</td>
                    <td v-if="daily.sale[0].Acco > 0"  style="border-bottom: 1px solid;">{{ daily.guest_inhouse_pax / daily.sale[0].Acco }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].Acco }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].fb }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].com }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].lun }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].other }}</td>
                    <td style="border-bottom: 1px solid;">{{ daily.sale[0].total }}</td>
                  </tr>


                </tbody>
                <br>
                <tr>
                  <td></td>
                  <td></td>
                  <td> </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>

                  <td style="box-shadow: 0 4px 10px 0;text-align: center;">{{ Acco }}</td>
                  <td style="box-shadow: 0 4px 10px 0;text-align: center;">{{ FB }}</td>
                  <td style="box-shadow: 0 4px 10px 0;text-align: center;">{{ com }}</td>
                  <td style="box-shadow: 0 3px 10px 0;text-align: center;">{{ laund }}</td>
                  <td style="box-shadow: 0 3px 10px 0;text-align: center;">{{ other }}</td>
                  <td style="box-shadow: 0 3px 10px 0;text-align: center;">{{ totals }}</td>
                </tr>
              </table>
            </VCol>
          </VCol>
        </VRow>
        <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
          {{ $t('No Data Selected') }}
        </h1>
        <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
          <h5> {{ formattedDate }}</h5>
          <h6> {{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
          <h6> ElementA</h6>
        </div>
      </div>
    </VCard>

  </div>
</template>

<script>


import axios from "@axios"

export default {
  props: {
    start: {
      type: Date,
      require: true
    },
    end: {
      type: Date,
      require: true
    },
    propMethod: {
      type: Function,
      required: true,
    },
    show: {
      type: Boolean,
      default: true,
    },
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: false,
      URL: window.location.origin,
      daily_sales: [],
      sales: [],
      // daily_sales:[],
      start_dates: '',
      end_dates: '',
      dataCount: 0,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      FB: 0,
      laund: 0,
      com: 0,
      other: 0,
      Acco: 0,
      totals: 0,
      test: []

    }
  },
  watch: {
    start(startDateWatch) {
      this.start_dates = startDateWatch
    },
    end(EndDateWatch) {
      this.end_dates = EndDateWatch
    },

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

    getDaily() {
      this.$emit('function-selected', () => {
        console.log('Function selected!');
      });
      axios.post(`${this.URL}/api/daily-sales-report`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
      }).then(res => (this.daily_sales = res.data.daily_sales, this.dataCount = res.data.daily_sales.length, this.sales = this.daily_sales.flat().map((ele) => ele.sale).flat(),
        this.sales.forEach((ele, key) => { this.Acco += ele.Acco, this.FB += ele.fb, this.laund += ele.lun, this.com += ele.com, this.totals += ele.total, this.other += ele.other })
        //   res.data.forEach((value, index)=>{this.test=value
        //   // ,this.laund+=value.sale[0].lun
        //     // ,this.com+=value.sale[0].com,this.other+=value.sale[0].other,this.totals+=value.sale[0].total
        // })
      ))
      this.Visability = true,

        this.$emit('getAllDaily')
    },

    printInvoice() {
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
