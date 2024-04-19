<script setup>

// Store
import { useInvoiceStore } from '@/views/apps/invoice/useInvoiceStore';

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

    <VCard v-show="Visability" style="padding-bottom: 2%;">
      <br>
      <div id="element-to-print" style="padding: 0 2%;">
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
          <h6>{{ localSetting.name }}</h6>
          <h6>MSS00174</h6>
        </div>
        <VRow>
          <VCol cols="12" sm="12" md="12">
            <VCol cols="12" sm="12" md="12" style="display: flex; justify-content: space-around;">
              <div style="padding: 2%;border: 1px solid black;border-radius: 10px;">
                <h5 v-if="RateChange">
                  {{ $t('Date') }} & {{ $t('Time') }} :{{ RateChange.hotel_date }}
                </h5>

              </div>
              <div style="padding: 2%;border: 1px solid black;border-radius: 10px;text-align: center;">

                <h2>{{ $t('Rate Changed') }}</h2>
              </div>
              <div style="padding: 2%;border: 1px solid black;border-radius: 10px;text-align: center;">
                <h5>
                  {{ $t('Hotel Date') }} : {{ localSetting.hotel_date }}
                </h5>
              </div>
            </VCol>

            <hr />
            <VCol cols="12" sm="12" md="12" style="display: flex; justify-content: space-around;">
              <div>
                <h3 v-if="profile">
                  {{ $t('Guest name') }} : {{ profile.first_name }}
                </h3>
                <br>
                <h3 v-if="profile">
                  {{ $t('Family name') }} : {{ profile.last_name }}
                </h3>
                <br>
                <h3 v-if="guest">
                  {{ $t('Pax') }} : {{ guest.no_of_pax }}
                </h3>
              </div>

              <div>
                <h3 v-if="guest">
                  {{ $t('In Date') }} : {{ guest.in_date }}
                </h3>
                <br>
                <h3 v-if="guest">
                  {{ $t('Out Date') }} : {{ guest.out_date }}
                </h3>
                <br>
                <h3 v-if="guest">
                  {{ $t('Folio') }} : {{ guest.folio_no }}
                </h3>
              </div>
            </VCol>
            <hr />

            <VDivider />
            <VCol style="display: flex;padding: 2px;" cols="12" sm="12" md="12">

              <VTable>
                <thead>
                  <tr>

                    <th class="text-left">

                    </th>
                    <th class="text-left">
                      {{ $t('Rate Code') }}
                    </th>
                    <th class="text-left">
                      {{ $t('Rate') }}
                    </th>
                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <td>
                      <h3>Current Rate</h3>
                    </td>
                    <td>{{ rate_code_from ? rate_code_from.rate_code : '---' }}</td>
                    <td>{{ RateChange ? RateChange.from_rm_rate : '---' }}</td>
                  </tr>
                  <tr>
                    <td>
                      <h3>Change To</h3>
                    </td>
                    <td>{{ rate_code_to ? rate_code_to.rate_code : '---' }}</td>
                    <td>{{ RateChange ? RateChange.to_rm_rate : '---' }}</td>
                  </tr>
                  <tr>
                    <td>
                      <h3>Reason</h3>
                    </td>
                    <td>
                      {{ RateChange ? RateChange.raeson : '---' }}
                    </td>


                  </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>
        <h1 style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;" v-if="dataCount == 0">
          {{ $t('No Data Selected') }}
        </h1>
        <br><br>
        <VCard>
          <div style="display: flex;width: 100%;justify-content: space-around;">
            <div class="text-center">
              <b> {{ userData.firstname }} {{ userData.lastname }}</b>
              <hr>
              <b>{{ $t('Changed By') }}</b>
            </div>


          </div>
        </VCard>
      </div>
    </VCard>


    <VCol cols="6" md="3" style="margin: 2% auto;" class="d-print-none">
      <VCard>
        <VCardText>


          <VBtn block class="mb-2" @click="printInvoice">
            Print
          </VBtn>

        </VCardText>
      </VCard>
    </VCol>

  </div>
</template>


<script>
import axios from "@axios";

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: false,
      URL: window.location.origin,
      RateChange: [],
      RateChangeData: [],
      guest: [],
      rate_code_from: [],
      rate_code_to: [],
      profile: [],
      Rate_id: '',
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

    this.getRateChange()
    this.getAllRoomView()
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
    getAllRoomView() {

      axios
        .get(`api/ratechange`)
        .then(response => (this.RateChangeData = response.data))
    },
    getRateChange() {
      axios.post(`${this.URL}/api/rate-change-print`, {
        id: this.$route.params.data,
      }).then(res => (this.RateChange = res.data.Change_Rate_Print[0],
        this.guest = res.data.Change_Rate_Print[0].guest,
        this.profile = res.data.Change_Rate_Print[0].guest.profile,
        this.rate_code_from = res.data.Change_Rate_Print[0].rate_code_from[0],
        this.rate_code_to = res.data.Change_Rate_Print[0].rate_code_to[0],
        this.dataCount = res.data.Change_Rate_Print.length))
      this.Visability = true
    },
    printInvoice() {
      window.print()
    },

  },
}
</script>

<style>
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

  .layout-vertical-nav {
    display: none;
    inline-size: 0;
  }

  .layout-navbar {
    display: none;
  }
}
</style>

<route lang="yaml">
meta:
layout: blank
</route>
