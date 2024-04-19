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
        <div style="display: flex;width: 55%;align-items: center;justify-content: space-between;float: inline-end;">

          <div style="display: flex;width: 100%;justify-content: space-between;">

            <VCol>
              <AppDateTimePicker v-model="hotel_date" :placeholder="$t('Hotel Date')"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />

            </VCol>
          </div>
          <VBtn @click="getOccupancy">
            {{ $t('Search') }}
          </VBtn>

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
              <h2>Occupancy Month to Date</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                {{ $t('for the date of') }} # 2023
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
                      {{ $t('Room name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('VAC') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('OCC') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('OCC %') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('H.Use') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('CMP') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('O.Ord') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('O.Inv') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Rate Code') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('O.Sales') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('ARR') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, itemObjKey) in occupancy_by_mtd" :key="itemObjKey"  style="height: 20px;text-align: center">
                    <td style="border-bottom: 1px solid;">{{ item.hotel_date }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.total_room }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.va_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.oc_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.OCCPercentage }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.house_use_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.comp_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.oo_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.os_rooms }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.total_rate_room }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.OSales }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.ARR }}</td>



                  </tr>
                  <br>
                  <tr>
                    <td></td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">
                      <b>{{ dataCount }}</b>
                    </td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">
                      <b>{{ tot_sum_va_rooms }}</b>
                    </td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">
                      <b>{{ tot_sum_oc_rooms }}</b>
                    </td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">
                      <b>{{ tot_sum_OCCPercentage }}</b>
                    </td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">{{ tot_sum_house_use_rooms }}</td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">{{ tot_sum_comp_rooms }}</td>
                    <td style="box-shadow: 0 3px 3px 0;text-align: center;">{{ tot_sum_oo_rooms }}</td>
                    <td style="box-shadow: 0 2px 2px 0;text-align: center;">
                      <b>{{ tot_sum_os_rooms }}</b>
                    </td>
                    <td style="box-shadow: 0 2px 2px 0;text-align: center;">
                      <b>{{ tot_sum_total_rate_room }}</b>
                    </td>
                    <td style="box-shadow: 0 2px 2px 0;text-align: center;">
                      <b>{{ tot_sum_OSales }}</b>
                    </td>
                    <td style="box-shadow: 0 2px 2px 0;text-align: center;">
                      <b>{{ tot_sum_ARR }}</b>
                    </td>

                  </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>
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
      Visability: false,
      URL: window.location.origin,
      occupancy_by_mtd: [],
      hotel_date: '',
      tot_sum_oc_rooms: 0,
      tot_sum_ARR: 0,
      tot_sum_OSales: 0,
      tot_sum_total_rate_room: 0,
      tot_sum_os_rooms: 0,
      tot_sum_oo_rooms: 0,
      tot_sum_comp_rooms: 0,
      tot_sum_house_use_rooms: 0,
      tot_sum_OCCPercentage: 0,
      tot_sum_va_rooms: 0,
      dataCount: 0,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }
  },
  mounted() {
    this.getOccupancy()
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

    getOccupancy() {
      axios.post(`${this.URL}/api/occupancy-by-mtd`, {
        hotel_date: this.hotel_date,
      }).then(res => (this.occupancy_by_mtd = res.data.Occupancy_ByMonthTD, this.dataCount = res.data.Occupancy_ByMonthTD.length,
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_oc_rooms += ele.oc_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_va_rooms += ele.va_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_OCCPercentage += Number(ele.OCCPercentage) }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_house_use_rooms += ele.house_use_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_comp_rooms += ele.comp_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_oo_rooms += ele.oo_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_os_rooms += ele.os_rooms }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_total_rate_room += ele.total_rate_room }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_OSales += ele.OSales }),
        this.occupancy_by_mtd.forEach((ele, value) => { this.tot_sum_ARR += ele.ARR })
      ))
      this.Visability = true
    },

    async printInvoice() {
      await this.getOccupancy()

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
