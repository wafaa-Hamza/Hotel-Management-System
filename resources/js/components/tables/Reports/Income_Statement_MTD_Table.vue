<template>
  <div>
    <VCol cols="12" md="12">
      <VCard class="d-print-none">
        <div style="display: flex;width: 40%;justify-content: space-between;float: inline-end;">
          <VCol>
            <AppDateTimePicker v-model="income_date" :placeholder="$t('date')"
              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
          </VCol>
          <VCol style="display: flex;justify-content: space-around;">
            <VBtn @click="getIncome_MTD">
              {{ $t('Search') }}
            </VBtn>


            <VBtn @click="printInvoice">
              {{ $t('print') }}
            </VBtn>

          </VCol>
        </div>
      </VCard>
      <br>



    </VCol>
    <VCard v-show="Visability">
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
              <h2>Income Statement (Date-MTD-YTD)</h2>
            </VCol>
            <br>
            <VDivider />
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead style=" color: black;font-size: 12px;">
                  <tr>
                   <th class="text-center" style="border-bottom: 2px solid;">
                      Description
                    </th>
                   <th class="text-center" style="border-bottom: 2px solid;">
                      Today
                    </th>
                   <th class="text-center" style="border-bottom: 2px solid;">
                      Month to Date
                    </th>
                   <th class="text-center" style="border-bottom: 2px solid;">
                      Year to Date
                    </th>
                  </tr>
                </thead>
                <tbody v-for="(item, itemObjKey) in Income_statement" :key="itemObjKey"  style="height: 20px;text-align: center">
                  <tr class="text-center" style="border: none;background: lightslategray;">
                    <td style="border-bottom: 1px solid;">{{ item.name }}</td>
                    <td style="border: 1px solid black;">
                      <p v-for="TransToday in item.transactions_led_cat">{{ TransToday.charge_amount_by_ledCat }}</p>
                    </td>
                    <td style="border: 1px solid black;">
                      <p v-for="Transmonth in item.transactions_mtd_led_cat">{{ Transmonth.charge_amount_MTD_by_ledCat }}
                      </p>
                    </td>
                    <td style="border: 1px solid black;">
                      <p v-for="TransYear in item.transactions_ytd_led_cat">{{ TransYear.charge_amount_YTD_by_ledCat }}</p>
                    </td>

                  </tr>
                  <tr v-for="ledger in item.ledgers" class="text-center">
                    <td style="border-bottom: 1px solid;">{{ ledger.name }}</td>
                    <td style="border-bottom: 1px solid;" v-for="TransToday in ledger.transactions">{{ TransToday.charge_amount }} </td>
                    <td style="border-bottom: 1px solid;" v-for="TransMonth in ledger.transactions_mtd"> {{ TransMonth.charge_amount_MTD }} </td>
                    <td  style="border-bottom: 1px solid;" v-for="TransYear in ledger.transactions_ytd"> {{ TransYear.charge_amount_YTD }} </td>

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
      </div>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios";

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      URL: window.location.origin,
      Income_statement: [],
      income_date: '',
      dataCount: 0,
      Visability: false,
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

    getIncome_MTD() {
      axios.post(`${this.URL}/api/income_statement_MTD_YTD`, {
        date: this.income_date,
      }).then(res => (this.Income_statement = res.data.income_statement_MTD_YTD))
      this.Visability = true
    },

    async printInvoice() {
      await this.getIncome_MTD()

      window.print()
    }
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
