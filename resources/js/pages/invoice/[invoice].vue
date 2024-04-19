<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

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
    <VRow>

      <VCol cols="12" md="9">
        <!-- SECTION Header -->
        <VCard>
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row">
            <!-- 👉 Left Content -->
            <div class="ma-sm-4">
              <div class="d-flex align-center mb-6">
                <!-- 👉 Logo -->
                <VNodeRenderer :nodes="themeConfig.app.logo" class="me-3" />

                <!-- 👉 Title -->
                <h6 class="font-weight-bold text-xl">
                  {{ themeConfig.app.title }}
                </h6>
              </div>




              <!-- 👉 Address -->
              <p class="mb-0">
                {{ localSetting.name }}
              </p>
              <p class="mb-0">
                {{ localSetting.phone }}
              </p>
              <p class="mb-0">
                {{ localSetting.email }}
              </p>
            </div>

            <!-- 👉 Right Content -->
            <div>
              <!-- 👉 Invoice ID -->
              <h6 class="font-weight-medium text-xl mb-6">
                {{ localSetting.name_loc }}
              </h6>

              <!-- 👉 Issue Date -->
              <p class="mb-2">
                <span />
                <span class="font-weight-semibold">{{ localSetting.name_loc }}</span>
              </p>
              <!-- 👉 Issue Date -->
              <p class="mb-2">
                <span />
                <span class="font-weight-semibold">{{ localSetting.address }}</span>
              </p>

              <!-- 👉 Due Date -->
              <p class="mb-2">
                <span />
                <span class="font-weight-semibold">{{ localSetting.city }} </span>
              </p>
            </div>
          </VCardText>
          <!-- !SECTION -->
          <h4 style="display: flex;width: 50%;justify-content: space-around;margin: auto;text-align: center;">
            <p>VAT Number </p>
            <p>{{ localSetting.vat_no }}</p>
            <p> رقم التعريف الضريبي </p>
          </h4>
        </VCard>

        <VDivider />
        <VCard style="padding: 1%;text-align: center;">
          <div>
            <h2 style="display: inline;">
              Guest id number
            </h2>

            <h3
              style="display: inline;width: 50px;height: 50px;padding: 1% 3%;margin: 0 2%;background: black;color: white;">
              {{ guests_id }}
            </h3>
          </div>
        </VCard>
        <VDivider />
        <!-- 👉 Payment Details -->

        <VCardText class="d-flex justify-space-between flex-wrap flex-column flex-sm-row print-row">
          <VCard>
            <VCol>
              <tr>
                <td class="pb-2">
                  Folio :
                </td>

                <h4>{{ guests_id }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Room Number :
                </td>
                <h4>{{ rooms }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Room Type:
                </td>

                <h4>{{ room_type_id }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Corporate :
                </td>

                <h4>{{ company_name }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Cust. Vat No:
                </td>

                <h4>{{ Invoices.vat_no ? Invoices.vat_no : 'null' }}</h4>
              </tr>
            </VCol>
          </VCard>

          <VCard>
            <VCol>
              <tr>
                <td class="pb-2">
                  Check-In Date:
                </td>
                <td style="float: inline-end;">
                  <h4>{{ Invoices.in_date }}</h4>
                </td>
              </tr>
              <tr>
                <td class="pb-2">
                  Check-Out Date:
                </td>

                <h4>{{ Invoices.out_date }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Pax:
                </td>

                <h4>{{ Invoices.no_of_pax }}</h4>
              </tr>
              <tr>
                <td class="pb-2">
                  Room Rate:
                </td>

                <h4>{{ Invoices.rm_rate }}</h4>
              </tr>


            </VCol>
          </VCard>
        </VCardText>
        <VCard style="text-align: center;">

          <b style="font-size: 200%;"> Guest Name : {{ ProfileData?.first_name }} {{ ProfileData?.last_name }} </b>


          <i style="margin: 1%  2% 0 0;float: inline-end;"> Issue Date : {{ currentDate }} </i>
        </VCard>
        <VDivider />
        <VCard>
          <!-- 👉 Table -->

          <table style="width: 100%;">
            <thead>
              <tr>

                <th scope="col" class="text-center" style="border-bottom: 2px solid rgb(177, 175, 175);">
                  Date & time
                </th>
                <th scope="col" class="text-center" style="border-bottom: 2px solid rgb(177, 175, 175);">
                  Room No
                </th>
                <th class="text-center" style="border-bottom: 2px solid rgb(177, 175, 175);">
                  Description
                </th>
                <th class="text-center" style="border-bottom: 2px solid black">
                  Ref

                </th>

                <th class="text-center" style="border-bottom: 2px solid rgb(177, 175, 175);">
                  Charge
                </th>
                <th class="text-center" style="border-bottom: 2px solid rgb(177, 175, 175);">
                  Payment
                </th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="item in TransData"
                class="text-center"
              >

                <td style="border-bottom: 2px solid gray">

                  {{item.created_at.substring(item.created_at.indexOf('T') + 1, item.created_at.indexOf('.'))}} {{item.hotel_date }}
                </td>
                <td style="border-bottom: 2px solid gray;border-right: 2px solid gray">
                  {{item.room.rm_name_en }}
                </td>
                <td style="border-bottom: 2px solid gray;border-right: 2px solid gray">
                  {{item.description}}
                </td>
                <td style="border-bottom: 2px solid gray;border-right: 2px solid gray">
                  {{item.trans_no}}
                </td>

                <td style="border-bottom: 2px solid gray;border-right: 2px solid gray">
                  {{ item.charge_amount }}
                </td>
                <td style="border-bottom: 2px solid gray;border-right: 2px solid gray">

                  {{ item.payment_amount }}
                </td>
              </tr>
            </tbody>
          </table>
        </VCard>
        <VDivider class="my-1" />

        <VCard>
          <!-- Total -->
          <VCardText class="d-flex justify-space-between flex-column flex-sm-row print-row">
            <qrcode-vue v-if="Qrcode !== null" :value="Qrcode" :size="size" level="H" />

            <div class="my-2 mx-sm-16">
              <table>
                <tr>
                  <td class="text-end">
                    <div class="me-16">
                      <p class="mb-4">
                        Total Charge : {{totalChargeAmount}}
                      </p>
<!--                      <p class="mb-4">-->
<!--                        Total F&B :-->
<!--                      </p>-->
                      <p class="mb-4">
                        MunTAX 2.5% :
                      </p>
                      <p class="mb-4">
                        VAT 15% :
                      </p>
                      <p class="mb-4">
                        Grand Total :{{totalChargeAmount}}
                      </p>
                      <p class="mb-4">
                        Total Payment : {{totalPaymentAmount}}
                      </p>
                      <p class="mb-4">
                        Balance : {{totalChargeAmount - totalPaymentAmount}}
                      </p>
                    </div>
                  </td>



                </tr>
              </table>
            </div>
          </VCardText>
          <div style="display: flex;width: 100%;justify-content: space-around;">
            <div class="text-center">
              <b>{{ userData?.firstname }} {{ userData?.lastname }}</b>
              <hr>
              <b>Cashier</b>
            </div>

            <div class="text-center">
              <b>{{ ProfileData?.first_name }} {{ ProfileData?.last_name }} </b>
              <hr>
              <b>Guest</b>
            </div>
          </div>
        </VCard>
        <VDivider class="my-1" />
<!--        <VCard>-->
<!--          <VCardText>-->
<!--            <div class="mx-auto text-center">-->
<!--              <h6 class="text-sm font-weight-semibold me-1">-->
<!--                direct payment 2 single beds total the guests 5 and total price 707.25 riyal-->
<!--              </h6>-->
<!--            </div>-->
<!--          </VCardText>-->
<!--        </VCard>-->
      </VCol>

      <VCol cols="6" md="3" class="d-print-none">
        <VCard>
          <VCardText>
            <!-- 👉 Send Invoice Trigger button -->

            <VBtn block prepend-icon="tabler-send" variant="tonal" color="secondary" class="mb-2">
              Send Invoice
            </VBtn>
            <VBtn block variant="tonal" color="secondary" class="mb-2">
              Download
            </VBtn>

            <VBtn block class="mb-2" @click="printInvoice">
              Print
            </VBtn>

            <VBtn block color="secondary" variant="tonal" class="mb-2">
              Edit Invoice
            </VBtn>

            <!-- 👉  Add Payment trigger button  -->
            <VBtn block prepend-icon="tabler-currency-dollar" variant="tonal" color="secondary">
              Add Payment
            </VBtn>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
    </div>
</template>

<script>
import { useGeneralStore } from '@/stores/GeneralStore'
import axios from "@axios"
import QrcodeVue from "qrcode.vue"

export default {
  data() {
    return {
      Qrcode:null,
       size: 200,
      currentDate: '',
      wind_id: Math.abs(parseInt(this.$route.params.invoice.match(/\-(\d+)/), 10)),
      guests_id: parseInt(this.$route.params.invoice.match(/^(\d+)-/), 10),
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      Invoices: [],
      URL: window.location.origin,
      rooms: '',
      company_name: '',
      room_type_id: '',
      TransData: [],
      ProfileData: [],
      TotalCharge:[],
      AllTotalCharge:null,
      test:[],
       totalChargeAmount : 0,
       totalPaymentAmount : 0
    }
  },
  components: {
    QrcodeVue,
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
    ...mapStores(useGeneralStore),
    Tax_invoice() {
      return this.generalStore.Tax_invoice
    },
    print_invoice() {
      return this.generalStore.print_invoice
    },
    Rooms() {
      return this.generalStore.Rooms
    },
    windows() {
      return this.generalStore.windows
    },
    transactions() {
      return this.generalStore.transactions
    },
    profile() {
      return this.generalStore.profile
    },
    room() {
      return this.generalStore.room
    },
    roomtype() {
      return this.generalStore.roomtype
    },
    company() {
      return this.generalStore.company
    },

  },
  mounted() {

    this.getInvoice()
    this.getCurrentDate()

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
  methods: {
    getInvoice() {
      axios.post(`${this.URL}/api/print-invoice`, {
        guest_id: this.guests_id,
        window_id: this.wind_id,
      }).then(res => (this.Invoices = res.data.data.print_invoice[0], this.dataCount = res.data.data.print_invoice.length,
        this.rooms = res.data.data.print_invoice[0].room.rm_name_en, this.company_name = res.data.data.print_invoice[0].company.company_name,
        this.room_type_id = res.data.data.print_invoice[0].room.room_type_id, this.TransData = res.data.data.print_invoice[0].window[0].transactions,
        this.ProfileData = res.data.data.print_invoice[0].profile,
            this.totalChargeAmount =  res.data.data.print_invoice[0].window[0].transactions.reduce((total, transaction) => total + (transaction.charge_amount || 0), 0),
            this.totalPaymentAmount =  res.data.data.print_invoice[0].window[0].transactions.reduce((total, transaction) => total + (transaction.payment_amount || 0), 0),
        this.Qrcode = res.data.data.qr_code
      ))
      this.Visability = true
    },
    getCurrentDate() {
      const date = new Date()

      this.currentDate = date.toDateString()
    },
    printInvoice() {
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

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";

.misc-email-input {
  margin-inline: auto;
  max-inline-size: 21.875rem;
  min-inline-size: 12.5rem;
}
</style>

<route lang="yaml">
meta:
  layout: blank
</route>
