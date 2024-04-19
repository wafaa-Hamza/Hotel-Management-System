<script setup>

// Store
import { useInvoiceStore } from '@/views/apps/invoice/useInvoiceStore'
const { width: windowWidth } = useWindowSize()
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
    <VCol
      cols="12"
      md="12"
    >
      <VCard class="d-print-none">
        <br>
        <div style="display: flex;width: 70%;align-content: space-between;justify-content: space-between;float: inline-end;">
          <VCol>
            <AppDateTimePicker
              v-model="start_dates"
              :placeholder="$t('Start date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
            />
          </VCol>
          <VCol>
            <AppDateTimePicker
              v-model="end_dates"
              :placeholder="$t('end date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
            />
          </VCol>


          <VCol>
            <VCombobox
              v-model="Users_id"
              :items="Users"
              :label="$t('user id')"
              item-title="firstname"
              style="width: 160px;"
              item-color="customColorValue"
            />
          </VCol>
          <VCol>
            <VBtn @click="getManageReport">
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

    <VCard
      v-show="Visability"
      style="padding: 2%;"
    >
      <br>
      <div
        id="element-to-print"
        style="padding: 0 2%;"
      >
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
          <h6>{{ localSetting.name }}</h6>
          <h6>MSS00174</h6>
        </div>
        <VRow>
          <VCol
            cols="12"
            sm="12"
            md="12"
          >
            <VCol
              style="border: 1px solid black;border-radius: 10px;text-align: center;"
              cols="12"
              sm="12"
              md="12"
            >
              <h5 style="position: absolute;">
                Hotel Date : {{ localSetting.hotel_date }}
              </h5>
              <h2>Cashier Close</h2><br>
              <h2>For All Users</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                For the date of : {{ date }}
              </h4>
            </div>
            <VDivider />
            <VCol
              style="padding: 2px;"
              cols="12"
              sm="12"
              md="12"
            >
              <VTable>
                <thead>
                  <tr>
                    <th
                      class="text-center"
                      style="width: 150px;"
                    >
                      <h3>Cash</h3><br>
                      <VDivider /><br>
                      <tr style="display: flex;justify-content: space-between;">
                        <td style="width: 50%;padding: 5px;border-right: 3px solid black;float: inline-start;text-align: start;">
                          In
                        </td>
                        <td style="width: 50%;padding: 5px;float: inline-end;text-align: end;">
                          Out
                        </td>
                      </tr>
                    </th>
                    <th class="text-center">
                      <h4>Credit Cards</h4><br>
                      <VDivider /><br>
                      <tr style="display: flex;justify-content: space-around;">
                        <td>Amount</td>
                      </tr>
                    </th>
                    <th class="text-center">
                      <h1>CL</h1> <br>
                      <VDivider /> <br>
                      <tr style="display: flex;justify-content: space-around;">
                        <td>Amount</td>
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <h2>Transfers</h2><br>
                      <VDivider />
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        <td>Amount</td>
                      </tr>
                      <br>
                    </th>

                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Voucher
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Room
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Folio
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Guest Name
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Description
                      </tr>
                    </th>
                    <th class="text-center">
                      <br>
                      <tr style="display: flex;justify-content: space-around;">
                        Date Time
                      </tr>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="Cashier_data in Cashier">
                    <td v-if="Cashier_data.payment_type">
                      <div v-if="Cashier_data.payment_type.payment_modes_id == 1">
                        <p
                          v-if="Cashier_data.payment_type.type == 'CR'"
                          style="width: 50%;padding: 5px;border-right: 3px solid black;float: inline-start;text-align: start;"
                        >
                          {{ Cashier_data.payment_amount }}
                        </p>
                        <p
                          v-if="Cashier_data.payment_type.type == 'DR'"
                          style="width: 50%;float: inline-end;text-align: end;"
                        >
                          {{ Cashier_data.payment_amount }}
                        </p>
                      </div>
                    </td>

                    <td v-if="Cashier_data.payment_type">
                      <div
                        v-if="Cashier_data.payment_type.payment_modes_id == 2"
                        style="display: flex;justify-content: space-around;"
                      >
                        <p>{{ Cashier_data.payment_amount }}</p>
                      </div>
                    </td>



                    <td v-if="Cashier_data.payment_type">
                      <div
                        v-if="Cashier_data.payment_type.payment_modes_id == 4"
                        style="display: flex;justify-content: space-around;"
                      >
                        <p>{{ Cashier_data.payment_amount }}</p>
                      </div>
                    </td>

                    <td v-if="Cashier_data.payment_type">
                      <div
                        v-if="Cashier_data.payment_type.payment_modes_id == 3"
                        style="display: flex;justify-content: space-around;"
                      >
                        <p>{{ Cashier_data.payment_amount }}</p>
                      </div>
                    </td>


                    <td class="text-center">
                      {{ Cashier_data.trans_no }}
                    </td>
                    <td class="text-center">
                      {{ Cashier_data.room?.rm_name_en }}
                    </td>
                    <td class="text-center">
                      {{ Cashier_data.guest_id }}
                    </td>
                    <td class="text-center">
                      {{ Cashier_data.guest.profile?Cashier_data.guest.profile.first_name:'' }} {{ Cashier_data.guest.profile?Cashier_data.guest.profile.last_name:'' }}
                    </td>
                    <td class="text-center">
                      {{ Cashier_data.description }}
                    </td>
                    <td class="text-center">
                      {{ Cashier_data.created_at.split('T')[0] }}
                    </td>
                  </tr>


                  <tr>
                    <td style="display: flex;justify-content:space-around;align-items:center;box-shadow: 0 5px 1px 0;text-align: center;">
                      <b> {{ INcash }}</b> |
                      <b> {{ OUTcash }}</b>
                    </td>



                    <td style="box-shadow: 0 5px 1px 0;text-align: center;">
                      <b>{{ Credit }}</b>
                    </td>





                    <td style="box-shadow: 0 5px 1px 0;text-align: center;">
                      <b> {{ Tranfer }}</b>
                    </td>
                    <td style="box-shadow: 0 5px 1px 0;text-align: center;">
                      <b> {{ CLA }}</b>
                    </td>
                  </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>

        <br>
        <hr>
        <hr>
        <hr>
      </div>
      <br>

      <div style="display: flex;justify-content: space-between;margin-left: 3%;">
        <h4> <b>NET</b> : {{ INcash - OUTcash }}</h4>
      </div>
      <div style="display: flex;justify-content: space-between;margin-top: 5%;">
        <h5> {{ formattedDate }}</h5>
        <h6>Printed By : {{ userData.firstname }} {{ userData.lastname }}</h6>
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

      start_dates: '',
      end_dates: '',
      Users_id: '',
      dataCount: 0,

      Cashier: [],
      Users: [],
      myData: [],
      payment_modes_ids: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date(),
      Credit: 0,
      INcash: 0,
      OUTcash: 0,
      CLA: 0,
      Tranfer: 0,
      arres: [],
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
    },
  },


  mounted() {
    this.getUsers()

    const storedData = localStorage.getItem('userData')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.user
    }
    const SettingData = localStorage.getItem('keyinfo')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }

  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    getUsers() {
      axios
        .get(`${this.URL}/api/user`)
        .then(response => this.Users = response.data)
    },
    async getManageReport() {
      await  axios.post(`${this.URL}/api/Tot_Payment_Of_Cashier_Closer`, {
        user_id: this.Users_id,
        Date_From: this.start_dates,
        Date_To: this.end_dates,

      }).then(res => (
        this.Cashier = res.data.Total_Closer_Payment, this.cash =this.Cashier,
        this.Visability = true))
      this.Cash()
    },
    Cash(){


      const CRCashCard = this.Cashier.filter(payment =>
        payment.payment_type.payment_modes_id === 1 && payment.payment_type.type === "CR",
      )

      const DRCashCard = this.Cashier.filter(payment =>
        payment.payment_type.payment_modes_id === 1 && payment.payment_type.type === "DR",
      )


      const CreditCard = this.Cashier.filter(payment => payment.payment_type.payment_modes_id === 2)
      const CLCard = this.Cashier.filter(payment => payment.payment_type.payment_modes_id === 3)
      const TransferCard = this.Cashier.filter(payment => payment.payment_type.payment_modes_id === 4)



      const CRCashAmounts = CRCashCard.map(payment => payment.payment_amount)
      const DRCashAmounts = DRCashCard.map(payment => payment.payment_amount)
      const CreditCardAmounts = CreditCard.map(payment => payment.payment_amount)
      const CLAmounts = CLCard.map(payment => payment.payment_amount)
      const TranferAmounts = TransferCard.map(payment => payment.payment_amount)






      this.INcash = CRCashAmounts.reduce((previousValue, currentValue)=>{
        if (currentValue!== undefined && previousValue !== undefined){
          return currentValue+=previousValue
        }
      }, 0)
      this.OUTcash = DRCashAmounts.reduce((previousValue, currentValue)=>{
        if (currentValue!== undefined && previousValue !== undefined){
          return currentValue+=previousValue
        }
      }, 0)
      this.Credit = CreditCardAmounts.reduce((previousValue, currentValue)=>{
        if (currentValue!== undefined && previousValue !== undefined){
          return currentValue+=previousValue
        }
      }, 0)
      this.CLA = CLAmounts.reduce((previousValue, currentValue)=>{
        if (currentValue!== undefined && previousValue !== undefined){
          return currentValue+=previousValue
        }
      }, 0)
      this.Tranfer = TranferAmounts.reduce((previousValue, currentValue)=>{
        if(currentValue!== undefined && previousValue !== undefined){
          return currentValue+=previousValue
        }
      }, 0)


    },
    async printInvoice() {
      await this.getManageReport()
      window.print()
    },
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
