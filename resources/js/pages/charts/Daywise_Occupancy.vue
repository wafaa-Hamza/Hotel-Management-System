<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCol cols="12" md="12" style="justify-content: space-around;display: flex">
      <VCol cols="4" md="4">
        <VCombobox v-model="Year" label="years" :label="$t('Reservation number')" :items="years" />
      </VCol>
      <VCol cols="4" md="4">
        <VCombobox v-model="month" label="months" :label="$t('Reservation number')" :items="months" />
      </VCol>
      <VCol cols="4" md="4">
        <VBtn @click="getDayWise">test chart {{ $t("Close") }}</VBtn>
      </VCol>

    </VCol>

    <VCol cols="12" md="12" style="display: flex">
      <VCol cols="12" md="12">
        <VTable class="text-no-wrap">
          <thead>
            <tr>
              <th className="text-uppercase">
                1
              </th>
              <th className="text-uppercase text-center">
                2
              </th>
              <th className="text-uppercase text-center">
                3
              </th>
              <th className="text-uppercase text-center">
                4
              </th>
              <th className="text-uppercase text-center">
                5
              </th>
              <th className="text-uppercase">
                6
              </th>
              <th className="text-uppercase text-center">
                7
              </th>
              <th className="text-uppercase text-center">
                8
              </th>
              <th className="text-uppercase text-center">
                9
              </th>
              <th className="text-uppercase text-center">
                10
              </th>
              <th className="text-uppercase text-center">
                11
              </th>
              <th className="text-uppercase text-center">
                12
              </th>
              <th className="text-uppercase text-center">
                13
              </th>
              <th className="text-uppercase text-center">
                14
              </th>
              <th className="text-uppercase text-center">
                15
              </th>
              <th className="text-uppercase text-center">
                16
              </th>
              <th className="text-uppercase text-center">
                17
              </th>
              <th className="text-uppercase text-center">
                18
              </th>
              <th className="text-uppercase text-center">
                19
              </th>
              <th className="text-uppercase text-center">
                20
              </th>
              <th className="text-uppercase text-center">
                21
              </th>
              <th className="text-uppercase text-center">
                22
              </th>
              <th className="text-uppercase text-center">
                23
              </th>
              <th className="text-uppercase text-center">
                24
              </th>
              <th className="text-uppercase text-center">
                25
              </th>
              <th className="text-uppercase text-center">
                26
              </th>
              <th className="text-uppercase text-center">
                27
              </th>
              <th className="text-uppercase text-center">
                28
              </th>
              <th className="text-uppercase text-center">
                29
              </th>
              <th className="text-uppercase text-center">
                30
              </th>
              <th className="text-uppercase text-center">
                31
              </th>
            </tr>
          </thead>


          <tbody>
            <tr v-if="desserts.length == 0"></tr>
            <tr v-else>
              <td v-for="(item, index) in desserts[0].data">
                {{ parseFloat(item).toFixed(1) }}
              </td>

            </tr>
          </tbody>

        </VTable>
      </VCol>
      <VCol cols="3" md="3" />
    </VCol>
    <br>
    <VCol cols="12" md="12">
      <apexchart type="bar" height="400" :options="chartOptions" :series="series"></apexchart>
    </VCol>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts"
import axios from "@axios"

export default {
  components: {
    apexchart: VueApexCharts,
  },
  // eslint-disable-next-line vue/component-api-style
  data: function () {
    return {
      DayWise: [],
      URL: window.location.origin,
      years: [2023, 2022, 2021, 2020, 2019],
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      desserts: [


      ],
      Year: '',
      month: '',

      series: [{
        name: 'Day Wise Occ Percent',
        data: [],
      }],
      chartOptions: {
        annotations: {
          points: [{
            x: 'Bananas',
            seriesIndex: 0,
            label: {
              borderColor: '#775DD0',
              offsetY: 0,
              style: {
                color: '#fff',
                background: '#775DD0',
              },
              text: 'Bananas are good',
            }
          }]
        },
        chart: {
          height: 450,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '100%',
          }
        },
        dataLabels: {
          enabled: true
        },
        stroke: {
          width: 0
        },

        grid: {
          row: {
            colors: ['#fff', '#f2f2f2']
          }
        },
        xaxis: {
          labels: {
            rotate: 145
          },
          categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],

          tickPlacement: 'on'
        },
        yaxis: {
          title: {
            text: 'Any thing',

          },
        },
        title: {
          text: 'Month days',
          floating: true,
          offsetY: 0,
          align: 'center',
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            type: "horizontal",
            shadeIntensity: 0.25,
            gradientToColors: undefined,
            inverseColors: true,
            opacityFrom: 0.85,
            opacityTo: 0.85,
            stops: [50, 0, 100]
          },
        }
      },




    }
  },
  // mounted() {
  //   this.getDayWise()
  // },
  methods: {
    transformData() {
      if (this.DayWise.length != 0) {
        this.series = []
        let data = []
        let y = 0
        for (let i = 1; i <= 31; i++) {
          const day = i.toString().padStart(2, '0');
          if (this.DayWise[y].Day != day) {
            data.push(0)
            //this.fillMonths(i, 0)
          }
          else {
            data.push(this.DayWise[y].DayWiseOccPercent)

            //this.fillMonths(i, this.DayWise[y].DayWiseOccPercent)
            if (y < this.DayWise.length - 1)
              y++
          }
        }
        this.series.push({
          name: 'Month occupancy',
          data: data,
        })
        this.desserts = this.series
      }
      else {
        this.$alert('No occupancy for this month', false)
      }

    },
    async getDayWise() {
      this.DayWise = []
      await axios.post(`${this.URL}/api/dayWise_occupancy`, {
        month: this.month,
        year: this.Year
      }).then(res => {
        this.DayWise = res.data.DayWise_Occupancy
        this.getTest()
      })
    },

    getTest() {
      this.transformData()
      //      let y = 0
      //       for(let i= 0;i<=30;i++){
      // if(this.DayWise) {
      //
      //   if (this.DayWise[i].Day == '01') {
      //     this.desserts[0].Day1 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //     y++
      //   } else if (this.DayWise[i].Day == '02') {
      //     this.desserts[0].Day2 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '03') {
      //     this.desserts[0].Day3 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '04') {
      //     this.desserts[0].Day4 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '05') {
      //     this.desserts[0].Day5 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '06') {
      //     this.desserts[0].Day6 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '07') {
      //     this.desserts[0].Day7 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '08') {
      //     this.desserts[0].Day8 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '09') {
      //     this.desserts[0].Day9 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '10') {
      //     this.desserts[0].Day10 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '11') {
      //     this.desserts[0].Day11 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '12') {
      //     this.desserts[0].Day12 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '13') {
      //     this.desserts[0].Day13 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '14') {
      //     this.desserts[0].Day14 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '15') {
      //     this.desserts[0].Day15 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '16') {
      //     this.desserts[0].Day16 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '17') {
      //     this.desserts[0].Day17 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '18') {
      //     this.desserts[0].Day18 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '19') {
      //     this.desserts[0].Day19 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '20') {
      //     this.desserts[0].Day20 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '21') {
      //     this.desserts[0].Day21 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '22') {
      //     this.desserts[0].Day22 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '23') {
      //     this.desserts[0].Day23 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '24') {
      //     this.desserts[0].Day24 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '25') {
      //     this.desserts[0].Day25 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '26') {
      //     this.desserts[0].Day26 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '27') {
      //     this.desserts[0].Day27 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '28') {
      //     this.desserts[0].Day28 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '29') {
      //     this.desserts[0].Day29 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '30') {
      //     this.desserts[0].Day30 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   } else if (this.DayWise[i].Day == '31') {
      //     this.desserts[0].Day31 = this.DayWise[i].DayWiseOccPercent
      //     // this.series[0].data.push(this.DayWise[i].DayWiseOccPercent)
      //   }
      //
      //   this.series[0].data.push(this.desserts[0])
      //   // console.log('series',this.series[0].data)
      //   console.log('Tezzz', this.series[0].data)
      // }
      //       }
    },
  },

}
</script>

