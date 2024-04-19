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
            <VBtn style="float: inline-end;" @click="getInhouse">
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
                Hotel Date : {{ localSetting.hotel_date }}
              </h5>
              <h2>{{ $t('In House Guest') }}</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                {{ $t('for the date of') }} : {{ $t('from') }} {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
              </h4>

            </div>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Room name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Guest Name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Folio no') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('C/In') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('C/Out') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Nights') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('def pax') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Rate Code') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Group Name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('RC No') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Booking Voucher') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, itemObjKey) in InHouse" :key="itemObjKey"  style="height: 30px;text-align: center">
                    <td style="border-bottom: 1px solid;" v-if="item.room">{{ item.room.rm_name_en }}</td>
                    <td style="border-bottom: 1px solid;" v-if="item.profile">{{ item.profile.first_name }} {{ item.profile.last_name }}</td>

                    <td style="border-bottom: 1px solid;" >{{ item.id }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.in_date }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.out_date }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.no_of_nights }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.no_of_pax }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.rate_code?.rate_code }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.trans_no }}</td>
                    <td style="border-bottom: 1px solid;" >{{ item.charge_amount }}</td>

                    <td style="border-bottom: 1px solid;" >{{ item.ref_no }}</td>
                  </tr>
                  <tr>
                    <td   v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b>{{ $t('Room Count') }} => {{ dataCount }}</b>
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
          <h6><b style="color: red;">Note * Checked out rooms</b><br><br>{{ formattedDate }}</h6>
          <h6>{{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
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
      InHouse: [],
      start_dates: '',
      end_dates: '',
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

    getInhouse() {
      axios.post(`${this.URL}/api/Inhouse-guests-report`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
      }).then(res => (this.InHouse = res.data.inHouse_guests_report, this.dataCount = res.data.inHouse_guests_report.length))
      this.Visability = true
    },
    async printInvoice() {
      await this.getInhouse()

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
