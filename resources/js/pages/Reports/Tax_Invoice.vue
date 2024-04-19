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
  <div >
    <VRow>
      <VCol
        cols="12"
        md="9"
      >
          <!-- SECTION Header -->
        <VCard>
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row">
            <!-- 👉 Left Content -->
            <div class="ma-sm-4">
              <div class="d-flex align-center mb-6">
                <!-- 👉 Logo -->
                <VNodeRenderer
                  :nodes="themeConfig.app.logo"
                  class="me-3"
                />

                <!-- 👉 Title -->
                <h6 class="font-weight-bold text-xl">
                  {{ themeConfig.app.title }}
                </h6>
              </div>

<!--              tenant        -->


              <!-- 👉 Address -->
              <p class="mb-0">
                {{Tax_invoice.name}}
              </p>
              <p class="mb-0">
                {{Tax_invoice.phone}}
              </p>
              <p class="mb-0">
                {{Tax_invoice.email}}
              </p>
            </div>

            <!-- 👉 Right Content -->
            <div class="mt-4 ma-sm-4">
              <!-- 👉 Invoice ID -->
              <h6 class="font-weight-medium text-xl mb-6">
                {{Tax_invoice.name_loc}}
              </h6>

              <!-- 👉 Issue Date -->
              <p class="mb-2">
                <span></span>
                <span class="font-weight-semibold">{{Tax_invoice.name_loc}}</span>
              </p>
              <!-- 👉 Issue Date -->
              <p class="mb-2">
                <span></span>
                <span class="font-weight-semibold">{{profile.address}}</span>
              </p>

              <!-- 👉 Due Date -->
              <p class="mb-2">
                <span></span>
                <span class="font-weight-semibold">{{profile.city}} </span>
              </p>
            </div>
          </VCardText>
          <!-- !SECTION -->
          <h4 style="text-align: center" class="my-1" >VAT Number : رقم التعريف الضريبي : {{Tax_invoice.vat_no}} </h4>
        </VCard>

        <VDivider  class="my-1" />
          <VCard style="text-align: center;padding: 3%;">
            <div>
              <h2 style="display: inline">Copy of TAX Invoice</h2>

            <h3 style="width: 50px;height: 50px;display: inline;background: black;margin: 0 2%;padding: 2% 4%;color: white">3070</h3>
            </div>
          </VCard>
        <VDivider  class="my-1" />
          <!-- 👉 Payment Details -->

          <VCardText class="d-flex justify-space-between flex-wrap flex-column flex-sm-row print-row">




              <VCard>
                <VCol>
                <tr>
                  <td class="pe-16 pb-3">
                    Folio :
                  </td>

                    <h4>{{ windows.guest_id }}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Room Number :
                  </td>
                    <h4>{{room.rm_name_en}}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Room Type:
                  </td>

                    <h4>{{roomtype.rm_type_name}}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Corporate :
                  </td>

                    <h4>{{company.company_name}}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Cust. Vat No:
                  </td>

                    <h4>{{print_invoice.vat_no}}</h4>

                </tr>
                </VCol>
              </VCard>




              <VCard>
                <VCol>
                <tr>
                  <td class="pe-16 pb-3">
                    Check-In Date:
                  </td>
                  <td style="float: right">
                    <h4>{{print_invoice.in_date}}</h4>
                  </td>
                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Check-Out Date:
                  </td>

                    <h4>{{print_invoice.out_date}}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Pax:
                  </td>

                    <h4>{{print_invoice.no_of_pax}}</h4>

                </tr>
                <tr>
                  <td class="pe-16 pb-3">
                    Room Rate:
                  </td>

                    <h4>{{print_invoice.rm_rate}}</h4>

                </tr>

                <tr>
                  <td class="pe-16">
                    SWIFT Code:
                  </td>
                     <h4>five</h4>

                </tr>
                </VCol>
              </VCard>

          </VCardText>
<VCard>
  <VCol style="color: black;display: flex;justify-content: space-around">

   <i> Guest Name : {{profile.first_name}} {{profile.last_name}} </i>

    <i> Issue Date : {{ currentDate }} </i>

  </VCol>
</VCard>
        <VDivider  class="my-1" />
        <VCard>
          <!-- 👉 Table -->

          <VTable>
            <thead>
            <tr>
              <th scope="col" class="text-center">
                Date & time
              </th>
              <th scope="col" class="text-center">
                Room No
              </th>
              <th class="text-center">
                Description
              </th>
              <th class="text-center">
                Reference
              </th>
              <th class="text-center">
                Charge
              </th>
              <th class="text-center">
                Payment
              </th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="item in transactions" class="text-center">

              <td >
                {{item.hotel_date}}
              </td>
              <td>
                {{item.room_id}}
              </td>
              <td>
                {{item.description}}
              </td>
              <td>
                {{item.trans_no}}
              </td>
              <td>
                {{item.charge_amount}}
              </td>
              <td>
                {{item.payment_amount}}
              </td>
            </tr>
            </tbody>

          </VTable>
        </VCard>
          <VDivider class="my-1" />

        <VCard>
          <!-- Total -->
          <VCardText class="d-flex justify-space-between flex-column flex-sm-row print-row">
            <div class="my-2 mx-auto">

              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-qrcode" width="150" height="150" viewBox="0 0 24 24" stroke-width="1.2" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                <path d="M7 17l0 .01" />
                <path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                <path d="M7 7l0 .01" />
                <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                <path d="M17 7l0 .01" />
                <path d="M14 14l3 0" />
                <path d="M20 14l0 .01" />
                <path d="M14 14l0 3" />
                <path d="M14 20l3 0" />
                <path d="M17 17l3 0" />
                <path d="M20 17l0 3" />
              </svg>

            </div>

            <div class="my-2 mx-sm-16">
              <table>
                <tr>
                  <td class="text-end">
                    <div class="me-16">
                      <p class="mb-4">
                        Total Charge :
                      </p>
                      <p class="mb-4">
                        Total F&B :
                      </p>
                      <p class="mb-4">
                        MunTAX 2.5% :
                      </p>
                      <p class="mb-4">
                        VAT 15% :
                      </p>
                      <p class="mb-4">
                        Grand Total :
                      </p>
                      <p class="mb-4">
                        Balance :
                      </p>
                    </div>
                  </td>

                  <td class="font-weight-semibold">

                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>

                    </p>
                  </td>
                  <td class="font-weight-semibold">

                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>
                    </p>
                    <p class="mb-4">
                      <b class="mx-6">$154.25</b>

                    </p>
                  </td>

                </tr>
              </table>

            </div>
          </VCardText>
          <div style="width: 100%;display: flex;justify-content: space-around">

            <div class="text-center">
              <b>المستخدم الرئيسي</b>
              <hr>
              <b>Cashier</b>
            </div>

            <div class="text-center">
              <b>{{profile.first_name}} {{profile.last_name}} </b>
              <hr>
              <b>Guest</b>
            </div>
          </div>
        </VCard>
          <VDivider class="my-1" />
        <VCard>
          <VCardText>
            <div class="mx-auto text-center">
              <h6 class="text-sm font-weight-semibold me-1">
                direct payment 2 single beds total the guests 5 and total price 707.25 riyal

              </h6>
             </div>
          </VCardText>
        </VCard>
      </VCol>

      <VCol
        cols="6"
        md="3"
        class="d-print-none"
      >
        <VCard>
          <VCardText>
            <!-- 👉 Send Invoice Trigger button -->

            <VBtn
              block
              prepend-icon="tabler-send"
              variant="tonal"
              color="secondary"
              class="mb-2"
            >
              Send Invoice
            </VBtn>
            <VBtn
              block
              variant="tonal"
              color="secondary"
              class="mb-2"
            >
              Download
            </VBtn>

            <VBtn
              block
              class="mb-2"
              @click="printInvoice"
            >
              Print
            </VBtn>

            <VBtn
              block
              color="secondary"
              variant="tonal"
              class="mb-2"
            >
              Edit Invoice
            </VBtn>

            <!-- 👉  Add Payment trigger button  -->
            <VBtn
              block
              prepend-icon="tabler-currency-dollar"
              variant="tonal"
              color="secondary"
            >
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
import { mapActions } from "pinia"

export default {
  data() {
    return {
      currentDate: ''
    };
  },
  computed:{

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
    this.getTaxInvoice()
    this.getCurrentDate()
  },
  methods:{
    ...mapActions(useGeneralStore, ['getTaxInvoice']),
    getCurrentDate() {
      const date = new Date();
      this.currentDate = date.toDateString()
    },
    printInvoice(){
      window.print()
    }
  }
}
</script>
<style lang="scss">
@media print {
  .v-application {
    background: none !important;
  }

  @page { margin: 0; size: auto; }

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
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: mediumpurple;
  border-radius: 10px;
}
/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, .6);  ;
}
</style>
