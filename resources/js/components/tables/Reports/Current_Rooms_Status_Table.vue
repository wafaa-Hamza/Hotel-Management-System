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
          <VCol  cols="4"  md="4" style="display: inline-block">
            <VCombobox
              v-model="floor_id"
              :items="Floor"
              item-title="floor_name"
              :label="$t('Ledger id')"
              item-color="customColorValue"


            >

              <template #selection="{ item }">
                <VChip>
                  {{ item.title }}
                </VChip>
              </template>
            </VCombobox>

          </VCol>

          <VCol>
            <VBtn
              @click="getCurrent"
              style="float: right"
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
              <h2>Current Room</h2>
            </VCol>
            <br>

            <div style="display:flex;justify-content: space-between">
              <h4 style="color: blue;display: inline-block">
                {{$t('for the date of')}} : {{ $t('from') }}  {{ start_dates }} {{ $t('to') }} : {{ end_dates }}
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
                    {{$t('Room name')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Room Type')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Status')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{ $t('Reason') }}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Guest Name')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('phone')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Vacant Date')}}
                  </th>
                <th class="text-center" style="border-bottom: 2px solid;">
                    {{$t('Connected Rooms')}}
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr
                  v-for="(item,itemObjKey) in RoomStatus"
                  :key="itemObjKey"  style="height: 20px; text-align: center"
                >
                 <td style="border-bottom: 1px solid;">{{ item.rm_name_en }}</td>
                 <td style="border-bottom: 1px solid;">{{ item.room_type.rm_type_name }}</td>
                 <td style="border-bottom: 1px solid;">{{ item.fo_status }} {{ item.hk_stauts }}</td>
                 <td style="border-bottom: 1px solid;">{{ item.rm_phone_no }}</td>
                 <td style="border-bottom: 1px solid;"><p v-if="item.guests">{{ item.guests.profile.first_name  }}</p></td>

                 <td style="border-bottom: 1px solid;">{{ item.rm_phone_no }}</td>
                  <td v-if="item.guests " style="border-bottom: 1px solid;">{{ item.guests.out_date }}</td>
                 <td style="border-bottom: 1px solid;"></td>

                </tr>
                <tr>
                  <td style="box-shadow: 0px 5px 8px 0px;text-align: center">
                    <b>{{$t('Room Count')}} => {{ dataCount }}</b>
                  </td>
                </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>
        <div style="display: flex;justify-content: space-between;margin-top: 5%">
          <h5> {{formattedDate }}</h5>
          <h6> {{$t('Printed By')}} :  {{ userData.firstname }} {{userData.lastname}}</h6>
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
      RoomStatus:[],
      Floor:[],
      floor_id:'',
      start_dates:'',
      end_dates:'',
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
    this.getFloor()

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

    getFloor(){
      axios.get(`${this.URL}/api/floor`).then(res=>(this.Floor=res.data))
    },
    getCurrent() {
      axios.post(`${this.URL}/api/rooms-status-report`,{
        floor_id:this.floor_id.id
      }).then(res =>(this.RoomStatus = res.data,this.dataCount = res.data.length))
      this.Visability = true
    },

    printInvoice(){
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
