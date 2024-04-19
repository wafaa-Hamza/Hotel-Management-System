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


    <VCol
      cols="12"
      md="12"
    >

      <VCard style="padding-bottom:2% ">
        <br>
        <div
          id="element-to-print"
          style="padding: 0 2%"
        >
          <div style="display: flex;justify-content: space-between;margin-bottom: 2%">
            <h6>{{localSetting.name}}</h6>
            <h6>MSS00174</h6>

          </div>
          <div style="display:flex;justify-content: flex-end">

            <VBtn @click="printInvoice"
            >
              {{ $t('print') }}
            </VBtn>


          </div>
          <br>
          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="12"
            >
              <VCol
                style="border: 1px solid black;border-radius: 10px;text-align: center"
                cols="12"
                sm="12"
                md="12"
              >
                <h5 style="position: absolute">
                  Hotel Date : {{localSetting.hotel_date}}
                </h5>
                <h2>High Balance</h2>
              </VCol>
              <br>


              <VDivider />
              <br>
              <VCard v-show="Visability">
                <VCol
                  style="padding: 2px"
                  cols="12"
                  sm="12"
                  md="12"
                >
                  <table style=" width: 100%;  color: black;font-size: 12px;">
                    <thead style=" color: black;font-size: 12px;">
                    <tr>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Room
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Folio
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Guest Name
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        IN
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        OUT
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Rate
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Pay Type
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Charge
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Payment
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Balance
                      </th>
                    </tr>
                    </thead>
                    <tbody v-for="(Balance,itemObjKey) in High_Balance"
                           :key="itemObjKey">
                    <tr

                    >

                      <td style="border-bottom: 1px solid;">{{ Balance[0].room.rm_name_en }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].profile.id }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].profile.first_name }} {{ Balance[0].profile.last_name }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].in_date }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].out_date }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].rm_rate }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].way_of_payment }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].transactions_sum_charge_amount }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[0].transactions_sum_payment_amount }}</td>
                      <td style="border-bottom: 1px solid;">{{ Balance[1].Balance }}</td>
                    </tr>

                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>

                    </tr>
                    </tbody>
                  </table>

                </VCol>
                <h1
                  v-if="dataCount == 0"
                  style="text-align: center;box-shadow: 5px 4px 8px 0px;padding: 1%"
                >
                  No Data Selected
                </h1>
              </VCard>
            </VCol>
          </VRow>
          <div style="display: flex;justify-content: space-between;margin-top: 5%">
            <h5> {{formattedDate }}</h5>
            <h6>Printed By :  {{ userData.firstname }} {{userData.lastname}}</h6>
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
  data () {
    return {
      Visability: true,
      URL : window.location.origin,
      High_Balance:[],
      start_dates:'',
      end_dates:'',
      dataCount: 0,
      myData:[],
      userData:[],
      AllSetData:[],
      SettingData:[],
      localSetting:[],
      currentDate: new Date(),
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
  mounted() {
    this.getBalance()
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
  methods:{
    getBalance() {
      axios.get(`${this.URL}/api/high-balance-report`,{
      }).then(res =>(this.High_Balance = res.data.Guests_With_Balance.map((ele)=>ele),this.dataCount = res.data.Guests_With_Balance.length))
      this.Visability = true
    },
    async printInvoice(){
      await this.getBalance()

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
