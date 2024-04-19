<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCol
      cols="12"
      md="12"
      style="justify-content: space-around;display: flex"
    >
      <VCol
        cols="3"
        md="3"
      >
        <VCol cols="12" style="box-shadow: 0px 0px 5px 1px gray;text-align: center">
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Total No.of Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{Live.Total_rooms}}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Total unavailable Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Total_unavailable_rooms }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Total Sellable Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Total_sellable_rooms }}</span>
        </VCol>
        </VCol>
        <br>

        <VCol cols="12" style="box-shadow: 0px 0px 5px 1px gray;text-align: center">
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Occupied Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Occupied_rooms }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Vacant Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Vacant_rooms }}</span>
        </VCol>
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Expected Arrival') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Expected_arrivals }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Due Out') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Due_out }}</span>
        </VCol>
          <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Available to sell') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">?????</span>
        </VCol>
        </VCol>
        <br>

        <VCol cols="12" style="box-shadow: 0px 0px 5px 1px gray;text-align: center">

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Avarage Room Rate ARR') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ parseFloat(Live.Avg_room_rate).toFixed(2) }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Total PAX') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Tot_pax }}</span>
        </VCol>
        </VCol>
        <br>
        <VCol cols="12" style="box-shadow: 0px 0px 5px 1px gray;text-align: center">
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Occupied Rooms Last') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Occupied_room_last }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Arrived Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Arrived_rooms }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Check out Rooms') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.CheckedOut_rooms }}</span>
        </VCol>
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Current Occupied') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Vacant_rooms }}</span>
        </VCol>

        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Expected Arrivales') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Expected_arrivals }}</span>
        </VCol>
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Expected Depurture') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Expected_departures }}</span>
        </VCol>
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Expected Occupied tonight') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ Live.Expected_occupied_tonight }}</span>
        </VCol>
        <VCol cols="12">
          <b style="margin: 0 12px 0 0">{{ $t('Expected Occupancy') }}</b>
          <span style="box-shadow: 0px 0px 5px 1px gray;padding: 1%">{{ parseFloat(Live.Expected_occupancy).toFixed(3)}}</span>
        </VCol>
        </VCol>
        <br>

      </VCol>
      <VCol
        cols="9"
        md="9"
      >
        <apexchart
          type="bar"
          height="350"
          :options="chartOptions"
          :series="series"
        />
      </VCol>
    </VCol>
      <VBtn @click.once="Fetch"> {{$t('Fetch')}}</VBtn>
  </div>
</template>

<script>
import axios from "@axios"
import VueApexCharts from "vue3-apexcharts"

export default {
  components: {
    apexchart: VueApexCharts,
  },
  // eslint-disable-next-line vue/component-api-style
  data: function () {
    return {
      URL: window.location.origin,
      hotel_date: '',
      test1:[],
      test2:[],
      Live: [],
      char: [],
      AllCh: [],
      www: [],
      series: [{
        name: ['momo'],
        data: [],
      }],
      momo: [{
        mama: [],
      }],
      chartOptions: {
        chart: {
          height: 350,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            },
          },
        },

        plotOptions: {
          bar: {
            columnWidth: '45%',
            distributed: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        legend: {
          show: false,
        },
        xaxis: {
          categories: [
            'Occupied',
            'Vacant',
            'Out of Order',
          ],
          labels: {
            style: {
              fontSize: '12px',
            },
          },
        },
      },
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getLive()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    getLive(){
      axios.post(`${window.location.origin}/api/live_occupancy`,{

        hotel_date: this.hotel_date='2023-06-01',
      }).then(res=> this.Live= res.data.Live_Occupancy)

    },

    Fetch(){
     this.char.push(this.Live.Occupied_rooms,this.Live.Vacant_rooms,this.Live.Due_out)

      let c = this.series
      console.log("c",c)
      let m = c.map(ele=>ele.data).flat()

      m.push(...this.char)

      console.log("m",m)


      this.series.map(ele => ele.data.push(...m))
    },
  },


}
</script>
