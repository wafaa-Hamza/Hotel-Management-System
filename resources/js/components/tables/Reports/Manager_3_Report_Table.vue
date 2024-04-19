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
      <VCard class="d-print-none">
        <br>
        <div
          style="display: flex;width: 35%;align-items: center;justify-content: space-between;padding: 0 1%;float: inline-end;">

          <div style="display: flex;width: 100%;justify-content: space-between;">

            <VCol>
              <AppDateTimePicker v-model="hotel_date" placeholder="Hotel Date"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
          </div>
          <VBtn @click="getManageReport">
            search
          </VBtn>
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
    <VCard v-show="Visability" style="padding: 2%;">
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
                Hotel Date : {{ localSetting.hotel_date }}
              </h5>
              <h2>Manager Report 3</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                For the date of : {{ hotel_date }}
              </h4>

            </div>
            <VDivider />
            <VCol style="padding: 1px;" cols="12" sm="12" md="12">
              <VTable style="border: 1px solid black;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th style="border: 1px solid black;" class="text-center">
                      <h3>Description</h3>
                      <hr v-for="i in 2" style="background: black;" />
                  <tr class="text-center" style="display: flex;justify-content: space-between;">
                    <td>{{ Manager_Report.name }}</td>
                  </tr>
                  </th>
                  <th class="text-center" style="border: 1px solid black;">
                    <h3 style="background: gray;">to day Date</h3>
                    <hr v-for="i in 2" style="margin: auto;background: black;" />
                    <tr class="text-center" style="display: flex;justify-content: space-between;">

                      {{ Manager_Report.transactions_led_cat[0].charge_amount_by_ledCat }}
                    </tr>
                  </th>
                  <th class="text-center" style="border: 1px solid black;">
                    <h3 style="background: gray;">Month to Date</h3>
                    <hr v-for="i in 2" style="margin: auto;background: black;" />
                    <tr class="text-center" style="display: flex;justify-content: space-between;">

                      {{ Manager_Report.transactions_mtd_led_cat[0].charge_amount_MTD_by_ledCat }}
                    </tr>
                  </th>
                  <th class="text-center" style="border: 1px solid black;">
                    <h3 style="background: gray;">Year to Date</h3>
                    <hr v-for="i in 2" style="background: black;" />
                    <tr class="text-center" style="display: flex;justify-content: space-between;">
                      {{ Manager_Report.transactions_ytd_led_cat[0].charge_amount_YTD_by_ledCat }}

                    </tr>
                  </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="Manager in Manager_Report.ledgers">
                    <td><b>{{ Manager.name }}</b></td>


                    <td v-for="TransDay in Manager.transactions"> {{ TransDay.charge_amount }}</td>
                    <td v-for="TransMonth in Manager.transactions_mtd"> {{ TransMonth.charge_amount_MTD }}</td>
                    <td v-for="TransYear in Manager.transactions_ytd"> {{ TransYear.charge_amount_YTD }}</td>
                    <!-- -->
                  </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>
        <div style="display: flex;justify-content: space-between;margin-top: 5%;">
        </div>
      </div>
      <br>
      <VRow>
        <VCol cols="12" sm="12" md="12">


          <VCol style="padding: 2px;" cols="12" sm="12" md="12">
            <table style=" width: 100%;  color: black;font-size: 12px;">
              <thead style=" color: black;font-size: 12px;">
                <tr>
                  <th class="text-center">

                  </th>
                  <th class="text-center">
                    <h3>Day</h3>
                  </th>
                  <th class="text-center">
                    <h3>MTD</h3>
                  </th>
                  <th class="text-center">
                    <h3>YTD</h3>
                  </th>
                  <th class="text-center">
                    <h3>LYSD</h3>
                  </th>
                  <th class="text-center">
                    <h3>MYMTD</h3>
                  </th>
                  <th class="text-center">
                    <h3>LYY</h3>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">guest House</td>

                  <td>{{ guest_House.Guests_InHouse }}</td>
                  <td>{{ guest_House.guestHouseMTD }}</td>
                  <td>{{ guest_House.guestHouseYTD }}</td>
                  <td>{{ guest_House.guestHouseLYSD }}</td>
                  <td>{{ guest_House.guestHouseLYMTD }}</td>
                  <td>{{ guest_House.guestHouseLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Rooms Occupied</td>

                  <td>{{ RoomsOccupied.RoomsOccupied }}</td>
                  <td>{{ RoomsOccupied.RoomsOccupiedMTD }}</td>
                  <td>{{ RoomsOccupied.RoomsOccupiedYTD }}</td>
                  <td>{{ RoomsOccupied.RoomsOccupiedLYSD }}</td>
                  <td>{{ RoomsOccupied.RoomsOccupiedLYMTD }}</td>
                  <td>{{ RoomsOccupied.RoomsOccupiedLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">total rooms</td>

                  <td>{{ total_rooms.TotalRooms }}</td>
                  <td>{{ total_rooms.TotalRoomsMTD }}</td>
                  <td>{{ total_rooms.TotalRoomsYTD }}</td>
                  <td>{{ total_rooms.TotalRoomsLYSD }}</td>
                  <td>{{ total_rooms.TotalRoomLYMTD }}</td>
                  <td>{{ total_rooms.TotalRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Available Rooms</td>

                  <td>{{ AvailableRooms.AvailableRooms }}</td>
                  <td>{{ AvailableRooms.AvailableRoomsMTD }}</td>
                  <td>{{ AvailableRooms.AvailableRoomsYTD }}</td>
                  <td>{{ AvailableRooms.AvailableRoomsLYSD }}</td>
                  <td>{{ AvailableRooms.AvailableRoomsLYMTD }}</td>
                  <td>{{ AvailableRooms.AvailableRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Rooms Occupied Percent</td>

                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercent }}</td>
                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercentMTD }}</td>
                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercentYTD }}</td>
                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYSD }}</td>
                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYMTD }}</td>
                  <td>{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Complimentary Rooms</td>

                  <td>{{ ComplimentaryRooms.ComplimentaryRooms }}</td>
                  <td>{{ ComplimentaryRooms.ComplimentaryRoomsMTD }}</td>
                  <td>{{ ComplimentaryRooms.ComplimentaryRoomsYTD }}</td>
                  <td>{{ ComplimentaryRooms.ComplimentaryRoomsYTD }}</td>
                  <td>{{ ComplimentaryRooms.ComplimentaryRoomsLYMTD }}</td>
                  <td>{{ ComplimentaryRooms.ComplimentaryRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">House Use Rooms</td>

                  <td>{{ HouseUseRooms.HouseUseRooms }}</td>
                  <td>{{ HouseUseRooms.HouseUseRoomsMTD }}</td>
                  <td>{{ HouseUseRooms.HouseUseRoomsYTD }}</td>
                  <td>{{ HouseUseRooms.HouseUseRoomsLYSD }}</td>
                  <td>{{ HouseUseRooms.HouseUseRoomsLYMTD }}</td>
                  <td>{{ HouseUseRooms.ComplimentaryRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Out Of Order Rooms</td>

                  <td>{{ OutOfOrderRooms.OutOfOrderRooms }}</td>
                  <td>{{ OutOfOrderRooms.OutOfOrderRoomsMTD }}</td>
                  <td>{{ OutOfOrderRooms.OutOfOrderRoomsYTD }}</td>
                  <td>{{ OutOfOrderRooms.OutOfOrderRoomsLYSD }}</td>
                  <td>{{ OutOfOrderRooms.OutOfOrderRoomsLYMTD }}</td>
                  <td>{{ OutOfOrderRooms.OutOfOrderRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Total Room_minusOOO Rooms</td>

                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minusOOORooms }}</td>
                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsMTD }}</td>
                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsYTD }}</td>
                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYSD }}</td>
                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYMTD }}</td>
                  <td>{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Available Room_minusOOO Rooms</td>

                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORooms }}</td>
                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsMTD }}</td>
                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsYTD }}</td>
                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYSD }}</td>
                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYMTD }}</td>
                  <td>{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">Rooms Occupied Percent_minus OOORooms</td>

                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORooms }}</td>
                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsMTD }}</td>
                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsYTD }}</td>
                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYSD }}</td>
                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYMTD }}</td>
                  <td>{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">AverageRoomRate</td>

                  <td>{{ AverageRoomRate.AverageRoomRate }}</td>
                  <td>{{ AverageRoomRate.AverageRoomRateMTD }}</td>
                  <td>{{ AverageRoomRate.AverageRoomRateYTD }}</td>
                  <td>{{ AverageRoomRate.AverageRoomRateLYSD }}</td>
                  <td>{{ AverageRoomRate.AverageRoomRateLYMTD }}</td>
                  <td>{{ AverageRoomRate.AverageRoomRateLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">AveragePersonRate</td>

                  <td>{{ AveragePersonRate.AveragePersonRate }}</td>
                  <td>{{ AveragePersonRate.AveragePersonRateMTD }}</td>
                  <td>{{ AveragePersonRate.AveragePersonRateYTD }}</td>
                  <td>{{ AveragePersonRate.AveragePersonRateLYSD }}</td>
                  <td>{{ AveragePersonRate.AveragePersonRateLYMTD }}</td>
                  <td>{{ AveragePersonRate.DeparturesTomorrowRoomsPaxLYY }}</td>
                </tr>
              </tbody>
            </table>
          </VCol>
        </VCol>
      </VRow>
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
      Manager_Report: [],
      hotel_date: '',
      dataCount: 0,
      transactions_ytd_led_cat: [],
      transactions_led_cat: [],
      transactions_mtd_led_cat: [],
      ledgers: [],
      Manager_Report1_Calculations: [],
      guest_House: [],
      RoomsOccupied: [],
      total_rooms: [],
      AvailableRooms: [],
      RoomsOccupiedPercent: [],
      ComplimentaryRooms: [],
      HouseUseRooms: [],
      OutOfOrderRooms: [],
      TotalRoom_minusOOORooms: [],
      Available_Room_minusOOORooms: [],
      RoomsOccupiedPercent_minus_OOORooms: [],
      RoomsOccupied_minus_CompsHouse_Rooms: [],
      OccRooms_inc_Comps_minus_House_Rooms: [],
      OccRoomsPercent_inc_Comps_minus_House_Rooms: [],
      RoomsOccupiedPercent_minus_Comps_Rooms: [],
      RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO: [],
      DoubleOCC_Percent: [],
      RoomArrivals: [],
      RoomArrivalsPax: [],
      RoomDepartures: [],
      GroupGuest: [],
      RoomDeparturesPax: [],
      ReservationsMadeToday: [],
      CancellationsMadeToday: [],
      NoShow: [],
      NoShowPax: [],
      EarlyDepartures: [],
      EarlyDeparturePax: [],
      ExtendStay: [],
      ExtendStayPax: [],
      WalkIn_Rooms: [],
      WalkIn_RoomsPax: [],
      DayUse: [],
      ArrivalsTomorrowRooms: [],
      ArrivalsTomorrowRoomsPax: [],
      DeparturesTomorrowRooms: [],
      DeparturesTomorrowRoomsPax: [],
      OccupancyTomorrowPercent: [],
      AverageRoomRate: [],
      AveragePersonRate: [],
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }
  },
  mounted() {
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

    getManageReport() {
      axios.post(`${this.URL}/api/manager5-report`, {
        date: this.hotel_date,
      }).then(res => (
        this.Manager_Report = res.data.data.manager_report5[0],
        this.RoomsOccupied = res.data.data.manager_report5_Calculations.RoomsOccupied[0],
        this.total_rooms = res.data.data.manager_report5_Calculations.total_rooms[0],
        this.AvailableRooms = res.data.data.manager_report5_Calculations.AvailableRooms[0],
        this.RoomsOccupiedPercent = res.data.data.manager_report5_Calculations.RoomsOccupiedPercent[0],
        this.ComplimentaryRooms = res.data.data.manager_report5_Calculations.ComplimentaryRooms[0],
        this.HouseUseRooms = res.data.data.manager_report5_Calculations.HouseUseRooms[0],
        this.OutOfOrderRooms = res.data.data.manager_report5_Calculations.OutOfOrderRooms[0],
        this.TotalRoom_minusOOORooms = res.data.data.manager_report5_Calculations.TotalRoom_minusOOORooms[0],
        this.Available_Room_minusOOORooms = res.data.data.manager_report5_Calculations.Available_Room_minusOOORooms[0],
        this.RoomsOccupiedPercent_minus_OOORooms = res.data.data.manager_report5_Calculations.RoomsOccupiedPercent_minus_OOORooms[0],
        this.AverageRoomRate = res.data.data.manager_report5_Calculations.AverageRoomRate[0],
        this.AveragePersonRate = res.data.data.manager_report5_Calculations.AveragePersonRate[0],
        this.Manager_Report1_Calculations = res.data.data.Manager_Report1_Calculations, this.guest_House = res.data.data.manager_report5_Calculations.guest_House[0], this.transactions_ytd_led_cat = res.data.data.manager_report[0].transactions_ytd_led_cat[0], this.transactions_led_cat = res.data.data.manager_report[0].transactions_led_cat[0], this.transactions_mtd_led_cat = res.data.data.manager_report[0].transactions_mtd_led_cat[0], this.transactions_mtd_led_cat = res.data.data.ledgers[0].transactions_mtd_led_cat[0]))
      this.Visability = true
    },

    async printInvoice() {
      await this.getManageReport()
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
