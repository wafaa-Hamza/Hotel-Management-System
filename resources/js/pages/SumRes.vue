<script setup>
const currentTab = ref('Reservation')


</script>

<template>
  <div>
    <Breadcrumb />

    <VTabs
      v-model="currentTab"
      class="v-tabs-pill"
    >
      <VTab>  {{ $t("Summary") }}</VTab>
      <VTab>  {{ $t("Reservation") }}</VTab>
    </VTabs>


    <VCard class="mt-5">
      <VCardText>
        <VWindow v-model="currentTab">
          <VWindowItem>
            <VCol
              cols="12"
              md="12"
            >
              <br>
              <div style="display: flex;width: 55%;justify-content: space-between;float: inline-end;">
                <VCol>
                  <AppDateTimePicker
                    v-model="summ_start_date"
                    :placeholder="$t('Start date')"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                  />
                </VCol>
                <VCol>
                  <AppDateTimePicker
                    v-model="summ_end_date"
                    :placeholder="$t('end date')"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                  />
                </VCol>
                <VCol>
                  <VBtn @click="ChartSummary">
                    {{ $t('Search') }}
                  </VBtn>
                </VCol>
              </div>
              <br><br><br>

              <br>
            </VCol>
            <VRow>
              <VCol
                cols="12"
                sm="12"
                md="12"
              >
                <VCol
                  style="padding: 2px;"
                  cols="12"
                  sm="12"
                  md="12"
                >
                  <table style=" width: 100%;  color: black;font-size: 12px;">
                    <thead style=" color: black;font-size: 12px;">
                      <tr>
                        <th class="text-left">
                          <h3>  {{ $t("Guest / Group Name") }}</h3>
                        </th>
                        <th
                          class="text-center"
                          style="display: flex;justify-content: space-around;"
                        >
                          <h3 v-for="(item,key) in Summary">
                            {{ item.date }}
                          </h3>
                        </th>
                      </tr>
                    </thead>
                    <br><br>
                    <tbody>
                      <tr>
                        <td class="text-left">
                          <p
                            v-for="item in AllThData"
                            style=" font-weight: bold "
                          >
                            {{ item }}
                          </p>
                        </td>
                        <td
                          style="  display: flex;justify-content: space-around;"
                          class="text-center"
                        >
                          <h3 v-for="(i,key) in Summary">
                            <h2
                              v-for="(i,key) in Final_Data2"
                              style="margin: 1px"
                            >
                              {{ parseFloat(Final_Data2[key]).toFixed(2) }}
                            </h2>

                            <h2
                              v-for="(item,key) in Final_DataType1"
                              style="color: green;margin-top: 12%"
                            >
                              {{ item }}
                            </h2>
                          </h3>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </VCol>
              </VCol>
            </VRow>
          </VWindowItem>
          <VWindowItem>
            <VCol
              cols="12"
              md="12"
            >
              <br>
              <div style="display: flex;width: 55%;justify-content: space-between;float: inline-end;">
                <VCol>
                  <AppDateTimePicker
                    v-model="Res_start_date"
                    :placeholder="$t('Res Start date')"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                  />
                </VCol>
                <VCol>
                  <AppDateTimePicker
                    v-model="Res_end_date"
                    :placeholder="$t('Res end date')"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                  />
                </VCol>
                <VCol>
                  <VBtn
                    style="float: inline-end;"
                    @click="ChartReservation"
                  >
                    {{ $t('Search') }}
                  </VBtn>
                </VCol>
              </div>
              <br><br><br>
            </VCol>
            <VRow>
              <VCol
                cols="12"
                sm="12"
                md="12"
              >
                <VCol
                  style="padding: 2px;"
                  cols="12"
                  sm="12"
                  md="12"
                >
                  <table style=" width: 100%;  color: black;font-size: 12px;">
                    <thead style=" color: black;font-size: 12px;">
                      <tr>
                        <th class="text-left">
                          <h3>  {{ $t("Res No.") }}</h3>
                        </th>
                        <th class="text-center">
                          <h3>  {{ $t("Guest / Group Name") }}</h3>
                        </th>
                        <th
                          class="text-center"
                          style="display: flex;justify-content: space-around;"
                        >
                          <h3 v-for="DateHead in SelectedDate">
                            {{ DateHead }}
                          </h3>
                        </th>
                        <th
                          v-for="RMType in RmKey"
                          class="text-center"
                        >
                          <h3>
                            {{ RMType }}
                          </h3>
                        </th>
                        <th class="text-right">
                          <h3>
                             {{ $t("Res No.") }}
                          </h3>
                        </th>
                      </tr>
                    </thead>
                    <br>
                    <tbody>
                      <tr
                        v-for="(item,key) in ReservationsGroup"
                        style="border-bottom: 1px solid black; "
                      >
                        <td
                          class="text-left"
                          style="  position: relative; "
                        >
                          <p style="display:block;">
                            <b>{{ item.guest_id }}</b>
                          </p>
                        </td>
                        <td
                          class="text-center;"
                          style=" position: relative;"
                        >
                          <p style="display:block;text-align: center;">
                            <b> {{ item.guest_name }} </b>
                          </p>
                        </td>


                        <td

                          style=" display: flex;justify-content: space-around"
                          class="text-center"
                        >
                          <h3
                            v-for="(CountDate,key) in SelectedDate"
                            style="display:inline; "
                          >
                            <p>
                              <b v-if="item.hasOwnProperty(CountDate)">  {{ item[CountDate] }}</b>
                              <b v-else>  0</b>
                            </p>
                          </h3>
                        </td>
                        <td

                          v-for="CountRM in RmKey"

                          class="text-center"
                        >
                          <h3>
                            <p>
                              <b v-if="item.hasOwnProperty(CountRM)">{{ item[CountRM] }}</b>
                              <b v-else>0</b>
                            </p>
                          </h3>
                        </td>
                        <td
                          class="text-right"
                          style=" position: relative;"
                        >
                          <p><b>{{ item.no_of_pax }}</b></p>
                        </td>
                      </tr>
                      <tr
                        v-for="(item,key) in ReservationsIndividaul"
                        style="border-bottom: 1px solid black; "
                      >
                        <td
                          class="text-left"
                          style="  position: relative; "
                        >
                          <p style="display:block;">
                            <b>{{ item.guest_id }}</b>
                          </p>
                        </td>
                        <td
                          class="text-center;"
                          style=" position: relative;"
                        >
                          <p style="display:block;text-align: center;">
                            <b> {{ item.guest_name }} </b>
                          </p>
                        </td>


                        <td

                          style=" display: flex;justify-content: space-around"
                          class="text-center"
                        >
                          <h3
                            v-for="(CountDate,key) in SelectedDate"
                            style="display:inline; "
                          >
                            <p>
                              <b v-if="item.hasOwnProperty(CountDate)">  {{ item[CountDate] }}</b>
                              <b v-else>  0</b>
                            </p>
                          </h3>
                        </td>
                        <td

                          v-for="CountRM in RmKey"

                          class="text-center"
                        >
                          <h3>
                            <p>
                              <b v-if="item.hasOwnProperty(CountRM)">{{ item[CountRM] }}</b>
                              <b v-else>0</b>
                            </p>
                          </h3>
                        </td>
                        <td
                          class="text-right"
                          style=" position: relative;"
                        >
                          <p><b>{{ item.no_of_pax }}</b></p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </VCol>
              </VCol>
            </VRow>
          </VWindowItem>
        </VWindow>
      </VCardText>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  data(){
    return{
      summ_start_date: '',
      summ_end_date: '',
      Res_start_date: '',
      Res_end_date: '',
      Summary: [],
      ReservationsGroup: [],
      ReservationsIndividaul: [],
      DateData: [],
      DD: [],
      Final_Data1: [],
      Final_Data2: [],
      Final_DataType: [],
      Final_DataType1: [],
      ones: [],
      RmKey: [],
      SelectedDate: [],
      DayData: [],
      lengths: [],
      KeyOfTable: [],
      KeyOfRMT: [],
      AllRMT: [],
      ReservationsRMType: [],
      TRTR: [],
      AllThData: [],
      ThData1: [],
      ThData2: [],
      AllDayData: [],
      DateRoomType: [],
      URL: window.location.origin,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    DateLoop(){

      let startDate = new Date(this.Res_start_date)
      let endDate = new Date(this.Res_end_date)


      while (startDate <= endDate) {

        this.SelectedDate.push(startDate.toISOString().slice(0, 10))


        console.log('date', this.SelectedDate)
        startDate.setDate(startDate.getDate() + 1)

      }
    },
    async ChartSummary() {
      await axios.post(`${this.URL}/api/reservision-chart-summary`, {
        start_date: this.summ_start_date,
        end_date: this.summ_end_date,
      }).then(res =>
        (
          this.Summary = res.data.data.dataForDate,
          this.KeyOfTable = Object.keys(this.Summary[0]),
          this.KeyOfRMT = Object.keys(this.Summary[0].getCountOfRoomType),

          //this.ThData = this.KeyOfTable.push(this.KeyOfRMT),
          this.ThData1.push(this.KeyOfTable.slice(1, -1)),
          this.ThData2 = this.ThData1,
          this.ThData2.push(this.KeyOfRMT),
          this.AllThData = this.ThData2.flat(),
          console.log('dsa', this.AllThData)


        ),
      )
      this.test()
    },
    async ChartReservation() {
      await axios.post(`${this.URL}/api/reservation-chart`, {

        start_date: this.Res_start_date,
        end_date: this.Res_end_date,
      }).then(res =>
        (
          this.ReservationsGroup = res.data.group,
          this.ReservationsIndividaul= res.data.individaul[0],
          this.ReservationsRMType= res.data.roomTypes,
          this.RmKey = this.ReservationsRMType.map(ele=>ele.rm_type_name)
        ),
      )
      this.SelectedDate = []
      this.DateLoop()
    },
    test() {

      // console.log('this.Summary',this.Summary.length)

      for (let n = 0;n<=this.Summary.length-1;n++){

        this.Final_Data1 =  this.Summary[n]
        this.Final_DataType =  this.Summary[n].getCountOfRoomType
        this.Final_Data2 = Object.values(this.Final_Data1)
          .filter(value => typeof value === 'number')
        this.Final_DataType1 = Object.values(this.Final_DataType)
          .filter(value => typeof value === 'number')

      }



    },
  },
}
</script>
