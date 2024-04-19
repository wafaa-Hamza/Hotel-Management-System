<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <br>

      <div style="display: flex;width: 85%;align-content: space-between;justify-content: space-around;float: inline-end;">
        <VCol>
          <AppDateTimePicker v-model="start_dates" :placeholder="$t('Start date')" :label="$t('Start date')"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>
        <VCol>
          <AppDateTimePicker v-model="end_dates" :placeholder="$t('end date')" :label="$t('end date')"
            :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
        </VCol>

        <VCol style="margin-top: 25px;">
          <VCombobox v-model="Account_Code" :items="Account" :label="$t('Account Code')" item-title="label"></VCombobox>
        </VCol>

        <VCol style="margin-top: 25px;">
          <VBtn @click="getStatement">
            {{ $t('Refresh') }}
          </VBtn>
        </VCol>
      </div>
      <br><br><br>
    </VCard>
    <br>
    <VCard v-show="Visability" id="printableContent" style="padding-bottom: 2%;">
      <br>
      <div id="element-to-print" style="padding: 0 2%;">
        <VRow>
          <VCol cols="12" sm="12" md="12">
            <VCol style="padding: 2px;" cols="12" sm="12" md="12">
              <VTable>
                <thead>
                  <tr>
                    <th class="text-center">

                      {{ $t('No') }}
                    </th>
                    <th class="text-center">

                      {{ $t('Voucher no') }}
                    </th>
                    <th class="text-center">

                      {{ $t('Date') }}
                    </th>
                    <th class="text-center">

                      {{ $t('Description') }}
                    </th>
                    <th class="text-center">

                      {{ $t('Debit') }}
                    </th>
                    <th class="text-center">
                      {{ $t('Credit') }}
                    </th>
                    <th class="text-center">
                      {{ $t('Balance') }}
                    </th>
                    <th class="text-center">
                      {{ $t('Reference') }}
                    </th>
                  </tr>
                </thead>
                <tbody v-for="item in Statement" class="text-center">

                  <tr v-for="items in item.journal_voucher_details">

                    <td>?</td>
                    <td>{{ item.jv_no }}</td>
                    <td>{{ item.jv_date }}</td>
                    <td> {{ item.mdescription }}</td>

                    <td>
                      <VChip color="primary" variant="outlined">{{ items.debit }}</VChip>
                    </td>
                    <td>
                      <VChip color="primary" variant="outlined"> {{ items.credit }}</VChip>
                    </td>
                    <td>{{ item.total_debit - item.total_credit }}</td>
                    <td>
                      <VChip color="primary" variant="outlined">{{ items.reference }}</VChip>
                    </td>


                  </tr>

                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>
        <h1 v-if="dataCount == 0" style="padding: 1%;box-shadow: 5px 4px 8px 0;text-align: center;">

          {{ $t('No Data Selected') }}
        </h1>
        <div v-if="dataCount > 0" style="display: flex;justify-content: space-between;margin-top: 5%;">
          <h6>30/04/2023 12:24:16</h6>
          <h6>
            {{ $t('Printed By') }} : {{ userData.firstname }} {{ userData.lastname }}</h6>
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
      Visability: false,
      URL: window.location.origin,
      Statement: [],
      date: '',
      in: '',
      i: 0,
      out: '',
      start_dates: '',
      end_dates: '',
      ledgers_id: [],
      dataCount: 0,
      tot_sum_charge: 0,
      doc: '',
      content: '',
      Account_Code: '',
      StatementVouch: [],
      Account: [],
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

    this.getAccount();

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
  // eslint-disable-next-line vue/component-api-style
  methods: {
    getAccount() {
      axios.get(`${this.URL}/api/ChartOfAccounts`).then(res => this.Account = res.data.data)
    },
    getStatement() {
      axios.post(`${this.URL}/api/account-state`, {
        startDate: this.start_dates = '2023-05-01',
        endDate: this.end_dates = '2023-08-01',
        account_id: this.Account_Code.id,
      }).then(res => (this.Statement = res.data.Account_statements, this.StatementVouch = res.data.Account_statements, this.dataCount = res.data.Account_statements))
      this.Visability = true
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
