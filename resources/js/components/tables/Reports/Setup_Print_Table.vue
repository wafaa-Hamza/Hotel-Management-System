<script setup>
// 👉 Print Invoice
const printInvoice = () => {
  const printContents = document.getElementById('invoice').innerHTML;
  const originalContents = document.body.innerHTML;

  // Open a new window
  const printWindow = window.open('', '_blank');
  printWindow.document.open();
  printWindow.document.write('<html><head><title>Print Invoice</title></head><body>');
  printWindow.document.write(printContents);
  printWindow.document.write('</body></html>');
  printWindow.document.close();

  // Wait for the window to load the content
  printWindow.onload = function () {
    // Print the contents
    printWindow.print();
    // Close the window after printing
    printWindow.close();
  };

  // Restore the original page content
  // document.body.innerHTML = originalContents;
};
</script>
<template>
  <div>

    <VCard>
      <br>
      <div style="display: flex;width: 65%;justify-content: space-between;float: inline-end;">
        <VCol>
          <AppDateTimePicker v-model="start_dates" placeholder="Start date"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>
        <VCol>
          <AppDateTimePicker v-model="end_dates" placeholder="end date"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>

        <VCol>
          <VBtn @click="getDetails" style="float: inline-end;">
            search
          </VBtn>
        </VCol>
        <VCol>
          <VBtn class="btn-danger" @click="printInvoice">
            down
          </VBtn>
        </VCol>
      </div>
      <br><br><br>

    </VCard>
    <br><br>
    <div id="invoice">
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
                  Hotel Date : {{ hotel_date }}
                </h5>
                <h2>Report 1</h2>
                <h5 style="color: green;">
                  Corporate Name (BOOKING.COM)
                </h5>
              </VCol>
              <br>
              <h4 style="color: blue;">
                <div style="display: flex;justify-content: space-between;">
                  <h4 style="display: inline-block;color: blue;">
                    for the date of : From {{ start_dates }} to : {{ end_dates }}
                  </h4>

                </div>
              </h4>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style=" width: 100%;  color: black;font-size: 12px;">
                  <thead>
                    <tr>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Country
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Rooms
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Night
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Totla Rate
                      </th>
                      <th class="text-center" style="border-bottom: 2px solid;">
                        Totla F&B
                      </th>
                      <th class="text-left">
                        Pax
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in Details" :key="item.id">
                      <td>{{ item.country.name }}</td>
                      <td>{{ item.Rooms }}</td>
                      <td>{{ item.no_of_nights }}</td>
                      <td> {{ item.Total_Room_Rate }}</td>
                      <td>{{ item.FB }}</td>
                      <td>{{ item.no_of_pax }}</td>
                    </tr>
                    <tr>
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> Room Count => {{ dataCount }}</b>
                      </td>
                      <td />
                      <td />
                      <td />
                      <td />
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>Sum => {{ tot_sum_charge }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
            No Data Selected
          </h1>
          <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h5> {{ formattedDate }}</h5>
            <h6>Printed By : {{ userData.firstname }} {{ userData.lastname }}</h6>
          </div>
        </div>
      </VCard>
      <VDivider />
      <VDivider />
      <VDivider />
      <VDivider />
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
                  Hotel Date : {{ hotel_date }}
                </h5>
                <h2>Report 2</h2>
                <h5 style="color: green;">
                  Corporate Name (BOOKING.COM)
                </h5>
              </VCol>
              <br>
              <h4 style="color: blue;">
                <div style="display: flex;justify-content: space-between;">
                  <h4 style="display: inline-block;color: blue;">
                    for the date of : From {{ start_dates }} to : {{ end_dates }}
                  </h4>

                </div>
              </h4>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style=" width: 100%;  color: black;font-size: 12px;">
                  <thead>
                    <tr>
                      <th class="text-left">
                        Country
                      </th>
                      <th class="text-left">
                        Rooms
                      </th>
                      <th class="text-left">
                        Night
                      </th>
                      <th class="text-left">
                        Totla Rate
                      </th>
                      <th class="text-left">
                        Totla F&B
                      </th>
                      <th class="text-left">
                        Pax
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in Details" :key="item.id">
                      <td>{{ item.country.name }}</td>
                      <td>{{ item.Rooms }}</td>
                      <td>{{ item.no_of_nights }}</td>
                      <td> {{ item.Total_Room_Rate }}</td>
                      <td>{{ item.FB }}</td>
                      <td>{{ item.no_of_pax }}</td>
                    </tr>
                    <tr>
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> Room Count => {{ dataCount }}</b>
                      </td>
                      <td />
                      <td />
                      <td />
                      <td />
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>Sum => {{ tot_sum_charge }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
            No Data Selected
          </h1>
          <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h5> {{ formattedDate }}</h5>
            <h6>Printed By : {{ userData.firstname }} {{ userData.lastname }}</h6>
          </div>
        </div>
      </VCard>
      <br><br>
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
                  Hotel Date : {{ hotel_date }}
                </h5>
                <h2>Report 3</h2>
                <h5 style="color: green;">
                  Corporate Name (BOOKING.COM)
                </h5>
              </VCol>
              <br>
              <h4 style="color: blue;">
                <div style="display: flex;justify-content: space-between;">
                  <h4 style="display: inline-block;color: blue;">
                    for the date of : From {{ start_dates }} to : {{ end_dates }}
                  </h4>

                </div>
              </h4>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style=" width: 100%;  color: black;font-size: 12px;">
                  <thead>
                    <tr>
                      <th class="text-left">
                        Country
                      </th>
                      <th class="text-left">
                        Rooms
                      </th>
                      <th class="text-left">
                        Night
                      </th>
                      <th class="text-left">
                        Totla Rate
                      </th>
                      <th class="text-left">
                        Totla F&B
                      </th>
                      <th class="text-left">
                        Pax
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in Details" :key="item.id">
                      <td>{{ item.country.name }}</td>
                      <td>{{ item.Rooms }}</td>
                      <td>{{ item.no_of_nights }}</td>
                      <td> {{ item.Total_Room_Rate }}</td>
                      <td>{{ item.FB }}</td>
                      <td>{{ item.no_of_pax }}</td>
                    </tr>
                    <tr>
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> Room Count => {{ dataCount }}</b>
                      </td>
                      <td />
                      <td />
                      <td />
                      <td />
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>Sum => {{ tot_sum_charge }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
            No Data Selected
          </h1>
          <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h6>30/04/2023 12:24:16</h6>
            <h6>Printed By : MASASOFT</h6>
          </div>
        </div>
      </VCard>
      <br><br>
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
                  Hotel Date : {{ hotel_date }}
                </h5>
                <h2>Report 4</h2>
                <h5 style="color: green;">
                  Corporate Name (BOOKING.COM)
                </h5>
              </VCol>
              <br>
              <h4 style="color: blue;">
                <div style="display: flex;justify-content: space-between;">
                  <h4 style="display: inline-block;color: blue;">
                    for the date of : From {{ start_dates }} to : {{ end_dates }}
                  </h4>

                </div>
              </h4>
              <VDivider />
              <VCol style="padding: 2px;" cols="12" sm="12" md="12">
                <table style=" width: 100%;  color: black;font-size: 12px;">
                  <thead>
                    <tr>
                      <th class="text-left">
                        Country
                      </th>
                      <th class="text-left">
                        Rooms
                      </th>
                      <th class="text-left">
                        Night
                      </th>
                      <th class="text-left">
                        Totla Rate
                      </th>
                      <th class="text-left">
                        Totla F&B
                      </th>
                      <th class="text-left">
                        Pax
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in Details" :key="item.id">
                      <td>{{ item.country.name }}</td>
                      <td>{{ item.Rooms }}</td>
                      <td>{{ item.no_of_nights }}</td>
                      <td> {{ item.Total_Room_Rate }}</td>
                      <td>{{ item.FB }}</td>
                      <td>{{ item.no_of_pax }}</td>
                    </tr>
                    <tr>
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b> Room Count => {{ dataCount }}</b>
                      </td>
                      <td />
                      <td />
                      <td />
                      <td />
                      <td v-if="dataCount > 0" style="box-shadow: 0 5px 8px 0;text-align: center;">
                        <b>Sum => {{ tot_sum_charge }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </VCol>
            </VCol>
          </VRow>
          <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">
            No Data Selected
          </h1>
          <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
            <h6>30/04/2023 12:24:16</h6>
            <h6>Printed By : MASASOFT</h6>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script>
import axios from "@axios"
import { tr } from "vuetify/locale"


export default {


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: true,
      URL: window.location.origin,
      Details: [],
      date: '',
      in: '',
      out: '',
      start_dates: '',
      end_dates: '',
      company_id: null,
      dataCount: 0,
      tot_sum_charge: 0,
      country: [],
      Setup1: [],
      arr: [],
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getDetails()
    this.getSetup()
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

    getDetails() {
      axios.post(`${this.URL}/api/ProductivityByCountry`, {
        start_date: this.start_dates,
        end_date: this.end_dates,
      }).then(res => (this.Details = res.data, this.country = res.data.country, this.dataCount = res.data.length))
      this.Visability = true
    },
    getSetup() {
      axios.get(`${this.URL}/api/print-multi-reports`).then(response => (console.log(response)))
    },

  },

}
</script>

<style>
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
