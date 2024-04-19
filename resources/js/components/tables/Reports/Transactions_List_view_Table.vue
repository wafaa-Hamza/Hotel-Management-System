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
      <VCard class="d-print-none" style="height: 260px;">

        <div style="display: flex;width: 100%;float: inline-end;">
          <VCol cols="12" md="12">
            <VCol cols="4" md="4" style="display: inline-block;">
              <AppDateTimePicker v-model="start_dates" :placeholder="$t('Start date')"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />

            </VCol>
            <VCol cols="4" md="4" style="display: inline-block;">
              <AppDateTimePicker v-model="end_dates" :placeholder="$t('end date')"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />

            </VCol>



            <VCol cols="4" md="4" style="display: inline-block;">
              <VCombobox v-model="room_no" :items="rooms" item-title="rm_name_en" :label="$t('Room name')" multiple  item-color="customColorValue" >
                <template #selection="{ item }">
                  <VChip>
                    {{ item.title }}
                  </VChip>
                </template>
              </VCombobox>

            </VCol>
            <br><br>
            <VCol cols="4" md="4" style="display: inline-block;">
              <VCombobox v-model="user_id" :items="users" item-title="firstname" :label="$t('User Name')" multiple  item-color="customColorValue" >

                <template #selection="{ item }">
                  <VChip>
                    {{ item.title }}
                  </VChip>
                </template>
              </VCombobox>

            </VCol>

            <VCol cols="4" md="4" style="display: inline-block;">
              <VTextField v-model="voucher_no" :label="$t('Voucher')">


              </VTextField>

            </VCol>



            <VCol cols="4" md="4" style="display: inline-block;">
              <VTextField v-model="folio_no" :label="$t('Folio no')">

              </VTextField>

            </VCol>
            <br><br>

            <VCol cols="4" md="4" style="display: inline-block;">
              <VCombobox v-model="ledgers_id" :items="Ledgers" item-title="name" :label="$t('Ledger id')" multiple
                :disabled="payment_type_id.length > 0"  item-color="customColorValue" >

                <template #selection="{ item }">
                  <VChip>
                    {{ item.title }}
                  </VChip>
                </template>
              </VCombobox>

            </VCol>
            <VCol cols="4" md="4" style="display: inline-block;">
              <VCombobox v-model="payment_type_id" :items="payment" item-title="name" :label="$t('Payment')" multiple
                :disabled="ledgers_id.length > 0"  item-color="customColorValue" >
                <template #selection="{ item }">
                  <VChip>
                    {{ item.title }}
                  </VChip>
                </template>
              </VCombobox>

            </VCol>
            <VCol cols="4" md="4" style="display: inline-block;">

              <VCol cols="12" md="12" style="display: flex;align-items: flex-start;justify-content: space-around;">
                <VBtn @click="printInvoice">
                  {{ $t('print') }}
                </VBtn>

                <VBtn @click="getDetails">
                  {{ $t('Search') }}
                </VBtn>
              </VCol>
            </VCol>
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
                {{ $t('Hotel Date') }} :{{ localSetting.hotel_date }}
              </h5>
              <h2>Transactions List view</h2>

            </VCol>
            <br>
            <br>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Date') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Folio no') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Room name') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('ledger') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('payment') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Description') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Charge') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Payment') }}
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      {{ $t('Voucher') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in list_view.Transaction_list_view" class="text-center"  style="height: 20px;text-align: center">

                    <td style="border-bottom: 1px solid;">{{ item.hotel_date }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.guest.id }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.room.rm_name_en }}</td>
                    <td style="border-bottom: 1px solid;">
                      <p v-if="item.ledger">{{ item.ledger.name }}</p>
                      <p v-if="!item.ledger">______</p>

                    </td>
                    <td style="border-bottom: 1px solid;">
                      <p v-if="item.ledger">______</p>
                      <p v-if="!item.ledger">{{ item.payment_type.name }}</p>
                    </td>
                    <td style="border-bottom: 1px solid;">{{ item.description }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.charge_amount }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.payment_amount }}</td>
                    <td style="border-bottom: 1px solid;">{{ item.trans_no }}</td>
                  </tr>

                  <tr>
                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b> Room Count => {{ dataCount }}</b>
                    </td>
                    <td />
                    <td />
                    <td />
                    <td />
                    <td />
                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b>Sum => {{ parseFloat(SumsCharge).toFixed(3) }}</b>
                    </td>
                    <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                      <b>Sum => {{ parseFloat(SumsPayment).toFixed(2) }}</b>
                    </td>
                  </tr>
                </tbody>

              </table>
            </VCol>
            <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
              {{ $t('No Data Selected') }}
            </h1>
            <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
              <h5> {{ formattedDate }}</h5>
              <h6>{{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
            </div>
          </VCol>

        </VRow>

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
      list_view: [],
      date: '',
      in: '',
      out: '',
      start_dates: '',
      end_dates: '',
      folio_no: '',
      voucher_no: '',
      user_id: [],
      payment_type_id: [],
      room_no: [],
      ledgers_id: [],
      dataCount: 0,
      tot_sum_charge: 0,
      Ledgers: [],
      rooms: [],
      payment: [],
      users: [],
      Voucher: [],
      disablePay: false,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      SumsCharge: 0,
      SumsPayment: 0,
    }

  },
  watch: {
    payment_type_id(newVal) {
      if (newVal.length > 0) {
        // Disable ledgers_id when a selection is made in payment_type_id
        this.ledgers_id = [];
      }
    },

    ledgers_id(newVal) {
      if (newVal.length > 0) {
        // Disable ledgers_id when a selection is made in payment_type_id
        this.payment_type_id = [];
      }
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {

    this.getLedger()
    this.getRoomsr()
    this.getPayment()
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
      axios.get(`${this.URL}/api/user`).then(res => (this.users = res.data))
    },
    getPayment() {
      axios.get(`${this.URL}/api/payment`).then(res => (this.payment = res.data))
    },
    getLedger() {
      axios.get(`${this.URL}/api/ledger`).then(res => (this.Ledgers = res.data))
    },
    getRoomsr() {
      axios.get(`${this.URL}/api/rooms`).then(res => (this.rooms = res.data.data))
    },

    getDetails() {

      axios.post(`${this.URL}/api/transaction-list-view`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
        folio_no: this.folio_no,
        voucher_no: this.voucher_no,
        user_id: this.user_id.map((ele) => ele.id),
        room_no: this.room_no.map((ele) => ele.id),
        ledger_id: this.ledgers_id.map((ele) => ele.id),
        payment_type_id: this.payment_type_id.map((ele) => ele.id),
      }).then(res => (this.list_view = res.data.All_data, this.dataCount = res.data.All_data.Transaction_list_view.length,
        this.list_view.Transaction_list_view.forEach((value, index) => { this.SumsCharge += value.charge_amount, this.SumsPayment += value.payment_amount })
      ))
      this.Visability = true
    },

    async printInvoice() {
      await this.getDetails()

      window.print()
    },
    chan() {
      document.getElementById('led').style.display = "none"
      console.log('text')
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
