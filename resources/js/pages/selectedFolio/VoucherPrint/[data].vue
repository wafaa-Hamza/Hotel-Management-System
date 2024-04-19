<script setup>
// 👉 Print Invoice
const printInvoice = () => {
  window.print()
}
</script>

<template>

  <div style="padding: 2%;">

    <VRow>
      <VCol
        cols="12"
        md="9"
      >
        <div style="width: 65%;">
          <VCol>
            <tr>
              <td class="pe-16 pb-3">
                {{localSetting.name}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">

                {{localSetting.address}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">

                {{ localSetting.email}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">

                {{localSetting.hotel_date}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">

                {{localSetting.city}}
              </td>
            </tr>
          </VCol>
        </div>
        <hr class="my-1">
        <VCard>
          <VCardText>
            <div class="mx-auto text-center">
              <h6 class="text-sm font-weight-semibold me-1">
                {{ $t('Vat No') }} : <b>{{ guests.vat_no }}</b>
              </h6>
            </div>
          </VCardText>
        </VCard>
        <hr class="my-1" />
        <VCard style="text-align: center;padding: 3%;display: flex;justify-content: space-around">
          <div style="width: 65%">
            <h2 style="display: inline">
              {{ $t('Copy of TAX Invoice') }}
            </h2>

            <h3
              style="width: 50px;height: 50px;display: inline;background: black;margin: 0 2%;padding: 2% 4%;color: white">

              {{ AllVoucher.trans_no }}
            </h3>
          </div>
          <div style="width: 25%;">
            <b style="display: inline;"><b>Date : 06/05/202</b><br> <b>Time: 16:19</b></b>
          </div>
        </VCard>
        <hr class="my-1">
        <!-- 👉 Payment Details -->


        <VCard style="padding: 1%;">
          <VCol>
            <tr>
              <td class="pe-16 pb-3">
                {{ $t('Folio no') }} :
              </td>

              <h4>{{ guests.folio_no }}</h4>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{ $t('Room number') }} :
              </td>
              <h4>{{ rooms.rm_name_en }}</h4>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{ $t('Corporate') }} :
              </td>

              <h4>{{ guests.company_name }}</h4>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{ $t('Vat No') }}:
              </td>

              <h4>{{ guests.vat_no }}</h4>
            </tr>
          </VCol>
        </VCard>

        <hr class="my-3">

        <VCard style="padding: 1%;">
          <tr>
            <td class="pe-16 pb-3">
              {{ $t('Amount') }} :
            </td>

            <h4>{{ AllVoucher.payment_amount }}</h4>
          </tr>
          <tr>
            <td class="pe-16 pb-3">
              {{ $t('Payment Type') }} :
            </td>

            <h4>{{ guests.way_of_payment }}</h4>
          </tr>
          <tr>
            <td class="pe-16 pb-3">
              {{ $t('trans No') }}:
            </td>

            <h4>{{ AllVoucher.trans_no }}</h4>
          </tr>

          <tr>
            <td class="pe-16 pb-3">
              {{ $t('For') }}:
            </td>
            <h4>{{ $t('for it') }}</h4>
          </tr>
        </VCard>



        <hr class="my-1">

        <VCard>
          <div style="display: flex;width: 100%;justify-content: space-around;">
            <div class="text-center">
              <b>المستخدم الرئيسي</b>
              <hr>
              <b>{{ $t('Cashier') }}</b>
            </div>

            <div class="text-center">
              <b>{{ profile.first_name }} {{ profile.last_name }} </b>
              <hr>
              <b>{{ $t('Guest') }}</b>
            </div>
          </div>
        </VCard>
      </VCol>

      <VCol cols="6" md="3" class="d-print-none">
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
              {{ $t('Send Invoice') }}
            </VBtn>
            <VBtn block variant="tonal" color="secondary" class="mb-2">
              {{ $t('Download') }}
            </VBtn>

            <VBtn block class="mb-2" @click="printInvoice">
              {{ $t('Print') }}
            </VBtn>


            <VBtn
              block
              color="secondary"
              variant="tonal"
              class="mb-2"
            >
              {{ $t('Edit Invoice') }}
            </VBtn>

            <!-- 👉  Add Payment trigger button  -->

            <VBtn
              block
              prepend-icon="tabler-currency-dollar"
              variant="tonal"
              color="secondary"
            >
              {{ $t('Add Payment') }}
            </VBtn>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios";

export default {
  data() {
    return {


      URL: window.location.origin,
      AllVoucher: [],
      guests: [],
      Print_Voucher: [],
      rooms: [],
      profile: [],

      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
    }
  },
  computed: {

  },
  mounted() {
    this.getVoucher()

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

    getVoucher() {
      axios
        .post(`${this.URL}/api/voucherPrinting`, {

          transaction_id: this.$route.params.data,
        })
        .then(response => (this.Print_Voucher = response.data.PrintVoucher, this.AllVoucher = response.data.voucherPrinting[0], this.rooms = response.data.voucherPrinting[0].room, this.guests = this.AllVoucher.guest, this.profile = this.AllVoucher.guest.profile))
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
