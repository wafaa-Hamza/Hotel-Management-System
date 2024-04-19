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

    <VCard>
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
          <VBtn @click="getReservation" style="float: inline-end;">
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
    <VCol cols="12" md="12">

      <VCard v-show="Visability" style="padding-bottom: 2%;">
        <br>
        <div id="element-to-print" style="padding: 0 2%;">
          <div style="display: flex;justify-content: space-between;margin-bottom: 2%;">
            <h6>Ekono by Leva Airport Hotel</h6>
            <h6>MSS00174</h6>
          </div>
          <VRow>
            <VCol cols="12" sm="12" md="12">
              <VCol style="border: 1px solid black;border-radius: 10px;text-align: center;" cols="12" sm="12" md="12">
                <h5 style="position: absolute;">
                  {{ $t('Hotel Date') }} : 27/04/2023
                </h5>
                <h2>Reservation Overview</h2>
              </VCol>
              <br>

              <div style="display: flex;justify-content: space-between;">
                <h4 style="display: inline-block;color: blue;">
                  {{ $t('for the date of') }} : {{ $t('from') }} {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
                </h4>
                <VBtn class="btn-danger" @click="down">
                  down
                </VBtn>
              </div>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <VTable>
                  <thead>
                    <tr>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Res No') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Arrival Date') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Departure date') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Guest Name') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Referance') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Status') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('NOR') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Arrival Date') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Amount') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Arrival Date') }}
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        {{ $t('Balance') }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, itemObjKey) in PaymentVoucher" :key="itemObjKey">
                      <td>{{ item.transaction_collection }}</td>
                      <td>{{ item.guest.id }}</td>
                      <td>{{ item.room.rm_name_en }}</td>
                      <td>{{ item.voided_at }}</td>

                      <td>{{ item.guest.profile.first_name }}</td>
                      <td>{{ item.description }}</td>
                      <td>{{ item.guest.profile.first_name }}</td>
                      <td>{{ item.description }}</td>
                      <td>180</td>
                      <td>64</td>
                      <td>21</td>

                    </tr>
                    <tr v-if="dataCount > 0">
                      <td style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> {{ $t('Room Count') }} => {{ dataCount }}</b>
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td style="box-shadow: 0 5px 4px 0;text-align: center;">
                        <b>250</b>
                      </td>
                      <td style="box-shadow: 0 5px 4px 0;text-align: center;">
                        <b>1245</b>
                      </td>
                      <td style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>2360</b>
                      </td>
                    </tr>
                  </tbody>
                </VTable>
              </VCol>
            </VCol>
          </VRow>
          <h1 style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;" v-if="dataCount == 0">{{ $t('No Data
                      Selected')}}</h1>
          <div style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h6><b style="color: red;">Note * Checked out rooms</b><br><br>30/04/2023 12:24:16</h6>
            <h6>{{ $t('Printed By') }} : MASASOFT</h6>
          </div>
        </div>
      </VCard>
    </VCol>



  </div>
</template>
<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      URL: window.location.origin,
      PaymentVoucher: [],
      Visability: false,
      start_dates: '',
      end_dates: '',
      dataCount: 0,

      desserts: [
        {
          name: 'Frozen Yogurt',
          calories: 159,
        },
        {
          name: 'Ice cream sandwich',
          calories: 237,
        },
      ],
    }
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    getReservation() {
      axios.post(`${this.URL}/api/payment-voucher-list-report`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
      }).then(res => (this.PaymentVoucher = res.data.payment_voucher_list, this.dataCount = res.data.payment_voucher_list.length))
      this.Visability = true
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
