<template>
  <div>


    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>

      <div style="float: right;width: 50%;display: flex;justify-content: space-between;">


        <VCol>
          <VCombobox
            v-model="jv_id"
            :label="$t('jv_id')"
            :items="journalVoucherNo"
            item-title="jv_no"
          /></VCol>

        <VCol>

          <VBtn
            @click="getExtend_Stay"
            style="float: right"
          >
            {{ $t('Search') }}
          </VBtn>
        </VCol>
      </div>

      <br><br><br>
    </VCard>
    <br>
    <VCard v-show="Visability" style="padding-bottom:2% ">
      <br>
      <div
        id="element-to-print"
        style="padding: 0 2%"
      >
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%">
          <h6>{{ Settings.name }}</h6>
          <h6>{{ Settings.address }}</h6>
        </div>
        <VRow>
          <VCol
            cols="12"
            sm="12"
            md="12"
          >
            <VCol

              cols="12"
              sm="12"
              md="12"
            >

              <div  style="border: 1px solid black;border-radius: 10px;text-align: center;padding: 2%;display: flex;justify-content: space-between">
                <h4>{{ $t('Financial Year') }} : {{PrintJournalVoucher.fyear}}</h4>
                <h2>{{ $t('Journal Voucher') }}</h2>
                <h4>{{ $t('Page :') }}</h4>
              </div>

            </VCol>

            <hr/>
            <VCol

              cols="12"
              sm="12"
              md="12"
              style="display:flex; justify-content: space-between"
            >
              <VCol style="border: 1px solid black;border-radius: 10px;padding: 1.5%" cols="5">
                <VCol

                  cols="12"
                  sm="12"
                  md="12"
                  style="display:flex; justify-content:space-between"
                >
                  <div>
                    <h3 >
                      {{ $t('Voucher Type') }} : {{ JournalVoucherType.name }}
                    </h3>
                    <br>
                    <h3>
                      {{ $t('Voucher Date') }} : {{ PrintJournalVoucher.jv_date }}
                    </h3>

                  </div>

                  <div>
                    <h3 >
                      {{ $t('Date H') }} : --------
                    </h3>
                    <br>
                    <h3>
                      {{ $t('Financial Period') }} : {{ JournalVoucherType.out_date }}
                    </h3>

                  </div>
                </VCol>
              </VCol>

              <VCol  style="text-align: center;padding: 2%" cols="2">
                <h2>{{ $t('Voucher No.') }}</h2>
                <h2 style="border: 1px solid black;border-radius: 10px;">{{ PrintJournalVoucher.jv_no }}</h2>
              </VCol>
              <VCol style="border: 1px solid black;border-radius: 10px;text-align: center;padding: 2%" cols="3">
                <h5 >
                  {{ $t('Descriptions') }} : {{PrintJournalVoucher.mdescription}}
                </h5>
              </VCol>
            </VCol>

            <hr/>

            <VDivider />
            <VCol
              cols="12"
              sm="12"
              md="12">
              <VTable>
                <thead>
                <tr>
                  <th class="text-left">
                    {{ $t('Line No.') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Account ') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Account Name') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Description') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Reference') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Debit') }}
                  </th>
                  <th class="text-left">
                    {{ $t('Credit') }}
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>{{ chartOfAccount.id}}</td>
                  <td>{{ chartOfAccount.account_code }}</td>
                  <td>{{ chartOfAccount.account_name }}</td>
                  <td>{{ JournalVoucherDetails.descriptions }}</td>
                  <td>{{ JournalVoucherDetails.reference }}</td>
                  <td>{{ JournalVoucherDetails.debit }}</td>
                  <td>{{ JournalVoucherDetails.credit }}</td>
                </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>
        <h1 style="text-align: center;box-shadow: 5px 4px 8px 0px;padding: 1%"  v-if="dataCount == 0">{{$t('No Data Selected')}}</h1>
        <br><br>
        <VCard>
          <div style="width: 100%;display: flex;justify-content: space-around">
            <div class="text-center">
              <b>{{ CreatedBy.firstname }} {{ CreatedBy.lastname }}</b>
              <hr>
              <b>{{ $t('Changed By') }}</b>
            </div>

          </div>
        </VCard>
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
      Visability:true,
      URL : window.location.origin,
      PrintJournalVoucher:[],
      JournalVoucherType:[],
      JournalVoucherDetails:[],
      journalVoucherNo:[],
      Settings:[],
      CreatedBy:[],
      chartOfAccount:[],
      guest:[],
      User:[],
      room_change_from:[],
      room_change_to:[],
      profile:[],
      jv_id:'',
      dataCount: 0,
    }
  },
  mounted() {
    this.getAlljournalVoucherNo()
  },
  // eslint-disable-next-line vue/component-api-style
  methods:{
    getAlljournalVoucherNo(){

      axios
        .get(`${this.URL}/api/journalVoucher`)
        .then(response => (this.journalVoucherNo=response.data.data))
    },
    getExtend_Stay() {
      axios.post(`${this.URL}/api/print-journal-voucher`,{
        jv_id:this.jv_id.jv_no,
      }).then(res =>(this.Settings = res.data.data.Settings,
          this.PrintJournalVoucher = res.data.data.Journal_Voucher_Print[0],
          this.CreatedBy = res.data.data.Journal_Voucher_Print[0].created_by,
          this.JournalVoucherDetails = res.data.data.Journal_Voucher_Print[0].journal_voucher_details[0],
          this.chartOfAccount = res.data.data.Journal_Voucher_Print[0].journal_voucher_details[0].chart_of_account,
          this.JournalVoucherType = res.data.data.Journal_Voucher_Print[0].journal_voucher_type,
          this.dataCount = res.data.data.Journal_Voucher_Print.length
      ))
      this.Visability = true
    },
  },
}
</script>

<style>
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
