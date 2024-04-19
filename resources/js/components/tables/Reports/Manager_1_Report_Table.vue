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
              <AppDateTimePicker v-model="date" placeholder="Hotel Date"
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
              <h2>Manager Report 1</h2>
            </VCol>
            <br>

            <div style="display: flex;justify-content: space-between;">
              <h4 style="display: inline-block;color: blue;">
                For the date of : {{ date }}
              </h4>
            </div>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                    <th class="text-center" style="padding: 1%;">
                      <h3>Description</h3>
                      <VDivider />
                  <tr>
                    <td style="width: 100px;" class="text-center">
                      {{ Manager_Report.name }}
                    </td>
                  </tr>
                  </th>
                  <th class="text-center">
                    <h3>Today</h3>
                    <VDivider />
                  <td style="width: 100px;" class="text-center">
                    {{ transactions_led_cat.charge_amount_by_ledCat?parseFloat(transactions_led_cat.charge_amount_by_ledCat).toFixed(2):'' }}
                  </td>
                  </th>
                  <th class="text-center">
                    <h3>Month to Date</h3>
                    <VDivider />
                  <td style="width: 100px;" class="text-center">
                    {{ parseFloat(transactions_mtd_led_cat.charge_amount_MTD_by_ledCat).toFixed(2) }}
                  </td>
                  </th>
                  <th>
                    <h3>Year to Date</h3>
                    <VDivider />
                    <tr>
                      <td style="border-bottom: 1px solid;">{{ parseFloat(transactions_ytd_led_cat.charge_amount_YTD_by_ledCat).toFixed(2) }}</td>
                    </tr>
                  </th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="Manager in Manager_Report.ledgers">
                    <td style="border-bottom: 1px solid;"> {{ Manager.name }}</td>
                    <td v-for="TransDay in Manager.transactions"> {{ TransDay.charge_amount }}</td>
                    <td v-for="TransMonth in Manager.transactions_mtd"> {{ TransMonth.charge_amount_MTD }}</td>
                    <td v-for="TransYear in Manager.transactions_ytd"> {{ TransYear.charge_amount_YTD }}</td>
                  </tr>
                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>
        <div style="display: flex;justify-content: space-between;margin-top: 5%;">
          <h6>30/04/2023 12:24:16</h6>
        </div>
        <br>
        <hr>
        <hr>
        <hr>
      </div>
      <br>
      <VRow>
        <VCol cols="12" sm="12" md="12">
          <VCol style="padding: 2px;" cols="12" sm="12" md="12">
            <table style=" width: 100%;  color: black;font-size: 12px;">
              <thead style=" color: black;font-size: 12px;">
                <tr>
                  <th class="text-center" />
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
                  <td class="text-left">
                    guest House
                  </td>

                  <td style="border-bottom: 1px solid;">{{ guest_House.Guests_InHouse }}</td>
                  <td style="border-bottom: 1px solid;">{{ guest_House.guestHouseMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ guest_House.guestHouseYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ guest_House.guestHouseLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ guest_House.guestHouseLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ guest_House.guestHouseLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Rooms Occupied
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupied }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupiedMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupiedYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupiedLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupiedLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied.RoomsOccupiedLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    total rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRoomLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ total_rooms.TotalRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Available Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AvailableRooms.AvailableRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Rooms Occupied Percent
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercent }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercentMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercentYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent.RoomsOccupiedPercentLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Complimentary Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ComplimentaryRooms.ComplimentaryRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    House Use Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.HouseUseRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.HouseUseRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.HouseUseRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.HouseUseRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.HouseUseRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ HouseUseRooms.ComplimentaryRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Out Of Order Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OutOfOrderRooms.OutOfOrderRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Total Room_minusOOO Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minusOOORooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ TotalRoom_minusOOORooms.TotalRoom_minus_OOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Available Room_minusOOO Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ Available_Room_minusOOORooms.Available_Room_minusOOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Rooms Occupied Percent_minus OOORooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_OOORooms.RoomsOccupiedPercent_minus_OOORoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    Rooms Occupied minus CompsHouse Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_Rooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_RoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_RoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_RoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_RoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomsOccupied_minus_CompsHouse_Rooms.RoomsOccupied_minus_CompsHouse_RoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    OccRooms inc Comps minus House Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_Rooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_RoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_RoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_RoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_RoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRooms_inc_Comps_minus_House_Rooms.OccRooms_inc_Comps_minus_House_RoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    OccRoomsPercent inc Comps minus House_Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_Rooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_RoomsMTD }}
                  </td>
                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_RoomsYTD }}
                  </td>
                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD }}
                  </td>
                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD }}
                  </td>
                  <td style="border-bottom: 1px solid;">{{ OccRoomsPercent_inc_Comps_minus_House_Rooms.OccRoomsPercent_inc_Comps_minus_House_RoomsLYY }}
                  </td>
                </tr>
                <tr>
                  <td class="text-left">
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO
                  }}</td>
                  <td style="border-bottom: 1px solid;">{{
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD
                  }}</td>
                  <td style="border-bottom: 1px solid;">{{
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD
                  }}</td>
                  <td style="border-bottom: 1px solid;">{{
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD
                  }}</td>
                  <td style="border-bottom: 1px solid;">{{
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD
                  }}</td>
                  <td style="border-bottom: 1px solid;">{{
                    RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY
                  }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    DoubleOCC_Percent
                  </td>

                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_Percent }}</td>
                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_PercentMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_PercentYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_PercentLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_PercentLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DoubleOCC_Percent.DoubleOCC_PercentLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    RoomArrivals
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivals }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivalsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivalsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivalsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivalsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivals.RoomArrivalsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    RoomArrivalsPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomArrivalsPax.RoomArrivalsPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    RoomDepartures
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDepartures }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDeparturesMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDeparturesYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDeparturesLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDeparturesLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDepartures.RoomDeparturesLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    GroupGuest
                  </td>

                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuest }}</td>
                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuestMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuestYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuestLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuestLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ GroupGuest.GroupGuestLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    RoomDeparturesPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ RoomDeparturesPax.RoomDeparturesPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    ReservationsMadeToday
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeToday }}</td>
                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeTodayMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeTodayYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeTodayLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeTodayLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ReservationsMadeToday.ReservationsMadeTodayLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    CancellationsMadeToday
                  </td>

                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeToday }}</td>
                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeTodayMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeTodayYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeTodayLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeTodayLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ CancellationsMadeToday.CancellationsMadeTodayLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    NoShow
                  </td>

                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShow }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShowMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShowYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShowLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShowLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShow.NoShowLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    NoShowPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ NoShowPax.NoShowPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    EarlyDepartures
                  </td>

                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDepartures }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDeparturesMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDeparturesYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDeparturesLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDeparturesLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDepartures.EarlyDeparturesLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    EarlyDeparturePax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ EarlyDeparturePax.EarlyDeparturesPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    ExtendStay
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStay }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStayMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStayYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStayLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStayLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStay.ExtendStayLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    ExtendStayPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ExtendStayPax.ExtendStayPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    WalkIn_Rooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_Rooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_RoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_RoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_RoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_RoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_Rooms.WalkIn_RoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    WalkIn_RoomsPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ WalkIn_RoomsPax.WalkIn_RoomsPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    DayUse
                  </td>

                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUse }}</td>
                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUseMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUseYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUseLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUseLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DayUse.DayUseLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    ArrivalsTomorrowRooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRooms.ArrivalsTomorrowRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    ArrivalsTomorrowRoomsPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ ArrivalsTomorrowRoomsPax.ArrivalsTomorrowRoomsPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    DeparturesTomorrowRooms
                  </td>

                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRooms }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRoomsMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRoomsYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRoomsLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRoomsLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRooms.DeparturesTomorrowRoomsLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    DeparturesTomorrowRoomsPax
                  </td>

                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPax }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPaxMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPaxYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPaxLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPaxLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ DeparturesTomorrowRoomsPax.DeparturesTomorrowRoomsPaxLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    OccupancyTomorrowPercent
                  </td>

                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercent }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercentMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercentYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercentLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercentLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ OccupancyTomorrowPercent.OccupancyTomorrowPercentLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    AverageRoomRate
                  </td>

                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRate }}</td>
                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRateMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRateYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRateLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRateLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AverageRoomRate.AverageRoomRateLYY }}</td>
                </tr>
                <tr>
                  <td class="text-left">
                    AveragePersonRate
                  </td>

                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.AveragePersonRate }}</td>
                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.AveragePersonRateMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.AveragePersonRateYTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.AveragePersonRateLYSD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.AveragePersonRateLYMTD }}</td>
                  <td style="border-bottom: 1px solid;">{{ AveragePersonRate.DeparturesTomorrowRoomsPaxLYY }}</td>
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
      date: '',
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
      currentDate: new Date(),
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

  // mounted() {
  //   this.getManageReport()
  // },
  mounted() {
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

    getManageReport() {
      axios.post(`${this.URL}/api/manager1-report`, {
        date: this.date,

      }).then(res => (
        this.Manager_Report = res.data.data.manager_report[0],
        this.RoomsOccupied = res.data.data.Manager_Report1_Calculations.RoomsOccupied[0],
        this.total_rooms = res.data.data.Manager_Report1_Calculations.total_rooms[0],
        this.AvailableRooms = res.data.data.Manager_Report1_Calculations.AvailableRooms[0],
        this.RoomsOccupiedPercent = res.data.data.Manager_Report1_Calculations.RoomsOccupiedPercent[0],
        this.ComplimentaryRooms = res.data.data.Manager_Report1_Calculations.ComplimentaryRooms[0],
        this.HouseUseRooms = res.data.data.Manager_Report1_Calculations.HouseUseRooms[0],
        this.OutOfOrderRooms = res.data.data.Manager_Report1_Calculations.OutOfOrderRooms[0],
        this.TotalRoom_minusOOORooms = res.data.data.Manager_Report1_Calculations.TotalRoom_minusOOORooms[0],
        this.Available_Room_minusOOORooms = res.data.data.Manager_Report1_Calculations.Available_Room_minusOOORooms[0],
        this.RoomsOccupiedPercent_minus_OOORooms = res.data.data.Manager_Report1_Calculations.RoomsOccupiedPercent_minus_OOORooms[0],
        this.OccRooms_inc_Comps_minus_House_Rooms = res.data.data.Manager_Report1_Calculations.OccRooms_inc_Comps_minus_House_Rooms[0],
        this.OccRoomsPercent_inc_Comps_minus_House_Rooms = res.data.data.Manager_Report1_Calculations.OccRoomsPercent_inc_Comps_minus_House_Rooms[0],
        this.RoomsOccupiedPercent_minus_Comps_Rooms = res.data.data.Manager_Report1_Calculations.RoomsOccupiedPercent_minus_Comps_Rooms[0],
        this.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO = res.data.data.Manager_Report1_Calculations.RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO[0],
        this.DoubleOCC_Percent = res.data.data.Manager_Report1_Calculations.DoubleOCC_Percent[0],
        this.RoomArrivals = res.data.data.Manager_Report1_Calculations.RoomArrivals[0],
        this.RoomArrivalsPax = res.data.data.Manager_Report1_Calculations.RoomArrivalsPax[0],
        this.RoomDepartures = res.data.data.Manager_Report1_Calculations.RoomDepartures[0],
        this.GroupGuest = res.data.data.Manager_Report1_Calculations.GroupGuest[0],
        this.RoomDeparturesPax = res.data.data.Manager_Report1_Calculations.RoomDeparturesPax[0],
        this.RoomsOccupied_minus_CompsHouse_Rooms = res.data.data.Manager_Report1_Calculations.RoomsOccupied_minus_CompsHouse_Rooms[0],
        this.ReservationsMadeToday = res.data.data.Manager_Report1_Calculations.ReservationsMadeToday[0],
        this.CancellationsMadeToday = res.data.data.Manager_Report1_Calculations.CancellationsMadeToday[0],
        this.NoShow = res.data.data.Manager_Report1_Calculations.NoShow[0],
        this.NoShowPax = res.data.data.Manager_Report1_Calculations.NoShowPax[0],
        this.EarlyDepartures = res.data.data.Manager_Report1_Calculations.EarlyDepartures[0],
        this.EarlyDeparturePax = res.data.data.Manager_Report1_Calculations.EarlyDeparturePax[0],
        this.ExtendStay = res.data.data.Manager_Report1_Calculations.ExtendStay[0],
        this.ExtendStayPax = res.data.data.Manager_Report1_Calculations.ExtendStayPax[0],
        this.WalkIn_Rooms = res.data.data.Manager_Report1_Calculations.WalkIn_Rooms[0],
        this.WalkIn_RoomsPax = res.data.data.Manager_Report1_Calculations.WalkIn_RoomsPax[0],
        this.DayUse = res.data.data.Manager_Report1_Calculations.DayUse[0],
        this.ArrivalsTomorrowRooms = res.data.data.Manager_Report1_Calculations.ArrivalsTomorrowRooms[0],
        this.ArrivalsTomorrowRoomsPax = res.data.data.Manager_Report1_Calculations.ArrivalsTomorrowRoomsPax[0],
        this.DeparturesTomorrowRooms = res.data.data.Manager_Report1_Calculations.DeparturesTomorrowRooms[0],
        this.DeparturesTomorrowRoomsPax = res.data.data.Manager_Report1_Calculations.DeparturesTomorrowRoomsPax[0],
        this.OccupancyTomorrowPercent = res.data.data.Manager_Report1_Calculations.OccupancyTomorrowPercent[0],
        this.AverageRoomRate = res.data.data.Manager_Report1_Calculations.AverageRoomRate[0],
        this.AveragePersonRate = res.data.data.Manager_Report1_Calculations.AveragePersonRate[0],
        this.Manager_Report1_Calculations = res.data.data.Manager_Report1_Calculations, this.guest_House = res.data.data.Manager_Report1_Calculations.guest_House[0], this.dataCount = res.data.data.manager_report.length, this.transactions_ytd_led_cat = res.data.data.manager_report[0].transactions_ytd_led_cat[0], this.transactions_led_cat = res.data.data.manager_report[0].transactions_led_cat[0], this.transactions_mtd_led_cat = res.data.data.manager_report[0].transactions_mtd_led_cat[0], this.transactions_mtd_led_cat = res.data.data.ledgers[0].transactions_mtd_led_cat[0]))
      this.Visability = true
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
