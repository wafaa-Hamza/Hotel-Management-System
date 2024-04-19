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

    <VCol cols="12" md="12">
      <VCard class="d-print-none">
        <br>
        <div style="display: flex;width: 50%;justify-content: space-between;float: inline-end;">


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

          <VCol>

            <VBtn @click="getCashierSummary" style="float: inline-end;">
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
                  {{ $t('Hotel Date') }} :{{ localSetting.hotel_date }}
                </h5>
                <h2>{{ $t('cashier close summay') }}</h2>
              </VCol>
              <br>
              <div style="display: flex;justify-content: space-between;">
                <h4 style="display: inline-block;color: blue;">
                  {{ $t('for the date of') }} : {{ $t('from') }} {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
                </h4>

              </div>

              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style="width: 100%">
                  <thead style=" color: black;font-size: 12px;">                    <tr>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('No.') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Name') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Start') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Stop') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Cash') }}

                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Credit Card') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Wire Transfer') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Others') }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr v-for="(item, itemObjKey) in Summary" :key="itemObjKey"  style="height: 20px;">
                      <td style="border-bottom: 1px solid;">{{ ++itemObjKey }}</td>
                      <td style="border-bottom: 1px solid;">{{ item.firstname }}{{ item.lastname }}</td>
                      <td style="border-bottom: 1px solid;">
                        <p v-for="items in item.cashiers">{{ items.open }}</p>
                      </td>
                      <td style="border-bottom: 1px solid;">
                        <p v-for="items in item.cashiers">{{ items.close }}</p>
                      </td>
                      <td style="border-bottom: 1px solid;">
                        <b v-for="tran_item in item.transactions">
                          <p v-if="tran_item.payment_type_id === 1">{{ tran_item.total_payment_amount }}
                          </p>
                        </b>
                      </td>

                      <td style="border-bottom: 1px solid;">
                        <b v-for="tran_item in item.transactions">
                          <p v-if="tran_item.payment_type_id === 2">{{ tran_item.total_payment_amount }}
                          </p>
                        </b>
                      </td>
                      <td style="border-bottom: 1px solid;">
                        <b v-for="tran_item in item.transactions">
                          <p v-if="tran_item.payment_type_id === 3">{{ tran_item.total_payment_amount }}
                          </p>
                        </b>
                      </td>
                      <td style="border-bottom: 1px solid;">
                        <b v-for="tran_item in item.transactions">
                          <p
                            v-if="tran_item.payment_type_id !== 1 && tran_item.payment_type_id !== 2 && tran_item.payment_type_id !== 3">
                            {{ tran_item.total_payment_amount }}
                          </p>
                        </b>
                      </td>
                    </tr>
                    <tr>
                      <td style="box-shadow: 0 5px 8px 0;text-align: center;" v-if="dataCount > 0">
                        <b> {{ $t('Room Count') }} => {{ dataCount }}</b>
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;" v-if="dataCount == 0">
            {{ $t('No Data Selected') }}</h1>

          <div style="display: flex;justify-content: space-between;margin-top: 5%;" v-if="dataCount > 0">
            <h5> {{ formattedDate }}</h5>
            <h6>{{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
          </div>
        </div>
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
      Summary: [],
      start_dates: '',
      end_dates: '',
      user_id: '',
      dataCount: 0,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      Users: [],

      Users_id: '',
    }
  },
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
    getUsers() {
      axios
        .get(`${this.URL}/api/user`)
        .then(response => this.Users = response.data)
    },
    getCashierSummary() {
      axios.post(`${this.URL}/api/Tot_Payment_Of_Cashier_Summary`, {
        Date_From: this.start_dates,
        Date_To: this.end_dates,
        user_id: this.Users_id,
      }).then(res => (this.Summary = res.data.Total_Summary_Payment, this.dataCount = res.data.Total_Summary_Payment.length))
      this.Visability = true
    },
    async printInvoice() {
      await this.getCashierSummary();

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
