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
      <VCard class="d-print-none">
        <br>

        <div style="float: right;width: 55%;display: flex;justify-content: space-between;">
          <div style="width: 100%;display: flex;justify-content: space-between;">


            <v-text-field
              v-model="year"
              :label="$t('Year')"
              style="width: 60%;"
              type="number"
            />
            <VSpacer />
          </div>
<VCol>
          <VBtn
            @click="getCurrent"
          >
            {{ $t('Search') }}
          </VBtn>
    </VCol>
          <VCol>
            <VBtn @click="printInvoice"
            >
              {{ $t('print') }}
            </VBtn>

          </VCol>
        </div>
        <br><br><br>
      </VCard>
      <br>



    </VCol>
    <VCard  v-show="Visability"  style="padding-bottom:2% ">
      <br>
      <div
        id="element-to-print"
        style="padding: 0 2%"
      >
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%">
          <h6>{{localSetting.name}}</h6>
          <h6>MSS00174</h6>
        </div>
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
                {{ $t('Hotel Date') }} : {{localSetting.hotel_date}}
              </h5>
              <h2>Occupancy By Month (Gregorain)</h2>
            </VCol>
            <br>

            <div style="display:flex;justify-content: space-between">
              <h4 style="color: blue;display: inline-block">
                {{$t('for the date of')}} # 2023
              </h4>

            </div>          <VDivider />
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
                    {{$t('Month')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Room name')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('VAC')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('OCC')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('OCC %')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('H.Use')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('CMP')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('O.Ord')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('O.Inv')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Rate Code')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('O.Sales')}}
                  </th>
                  <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('ARR')}}
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr
                  v-for="item in occupancy"  style="height: 20px;text-align: center"
                >

                  <td style="border-bottom: 1px solid;"><h3>{{ item.Month_Data }}</h3></td>


                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.Rooms}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.VAC}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.OCC}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.OCCPercentage}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.HUse}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.CMP}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.OOrd}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.OInv}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.Rate}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.OSales}}</h4></td>
                  <td style="border-bottom: 1px solid;"><h4 v-for="occ in item.Occupancy_ByMonth">{{occ.ARR}}</h4></td>

                </tr>
                <tr>
                  <td></td>
                  <td >

                  </td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">
                    <b>{{ VAC }}</b>
                  </td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">
                  <b>{{ OCC }}</b>
                  </td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">
                 <b>{{ OCCPercentage }}</b>
                  </td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">{{HUse}}</td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">{{CMP}}</td>
                  <td style="box-shadow: 0px 3px 3px 0px;text-align: center">{{OOrd}}</td>
                  <td style="box-shadow: 0px 2px 2px 0px;text-align: center">
                    <b>{{ OInv }}</b>
                  </td>
                  <td style="box-shadow: 0px 2px 2px 0px;text-align: center">
                    <b>{{ Rate }}</b>
                  </td>
                  <td style="box-shadow: 0px 2px 2px 0px;text-align: center">
                    <b>{{ OSales }}</b>
                  </td>
                  <td style="box-shadow: 0px 2px 2px 0px;text-align: center">
                    <b>{{ ARR }}</b>
                  </td>

                </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>

        <div style="display: flex;justify-content: space-between;margin-top: 5%">
          <h5> {{formattedDate }}</h5>
          <h6>Printed By :  {{ userData.firstname }} {{userData.lastname}}</h6>

        </div>
      </div>
    </VCard>

    </div>
</template>
<script>
import axios from "@axios"
export default {
  // eslint-disable-next-line vue/component-api-style
  data () {
    return {
      Visability:false,
      URL : window.location.origin,
      occupancy:[],
      year:'',
      VAC:0,
      ARR:0,
      OSales:0,
      Rate:0,
      CMP:0,
      HUse:0,
      OInv:0,
      OOrd:0,
      OCCPercentage:0,
      OCC:0,

      tot_sum_ARR:[],
      i:0,
      key_value:[],
      dataCount: 0,
      myData:[],
      userData:[],
      AllSetData:[],
      SettingData:[],
      localSetting:[],
      currentDate: new Date()
    }
  },

  mounted() {
    this.getCurrent()
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

    getCurrent() {
      axios.post(`${this.URL}/api/occupancy-by-month`,{
        year:this.year=2023,
      }).then(res => (
        this.occupancy = res.data.Data_of_Occupancy_ByMonth,
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.VAC += Number(ele.VAC)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.OCC += Number(ele.OCC)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.OCCPercentage += Number(ele.OCCPercentage)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.OOrd += Number(ele.OOrd)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.OInv += Number(ele.OInv)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.HUse += Number(ele.HUse)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.CMP += Number(ele.CMP)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.Rate += Number(ele.Rate)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.OSales += Number(ele.OSales)}),
        this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity).forEach((ele,value)=> {this.ARR += Number(ele.ARR)}),
        this.tot_sum_ARR = this.occupancy.map(ele=>ele.Occupancy_ByMonth).flat(Infinity)


      ))
      this.Visability = true
    },

    async  printInvoice(){
      await this.getCurrent()

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
