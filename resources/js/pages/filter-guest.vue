<template>
  <div>
    <Breadcrumb />
    <VCard>
      <VCardText>
        <VContainer>
          <VRow>
            <VSpacer />
            <VExpansionPanels
              variant="inset"
              class="mb-5 mt-5"
            >
              <VExpansionPanel>
                <VExpansionPanelTitle> {{ $t('Filter') }}</VExpansionPanelTitle>
                <VExpansionPanelText>
                  <VRadioGroup
                    v-model="radioGroup"
                    inline
                  >
                    <VRadio
                      :label="$t('All')"
                      value="All"
                    />
                    <VRadio
                      :label="$t('Arrival Date')"
                      value="InDate"
                    />
                    <VRadio
                      :label="$t('Due Out Date')"
                      value="OutDate"
                    />
                  </VRadioGroup>

                  <VCol
                    cols="12"
                    md="12"
                    style="display: flex"
                  >
                    <VCol
                      cols="6"
                      md="6"
                    >
                      <AppDateTimePicker
                        v-model="Arrival_Date"
                        :label="$t('Date From')"
                        clearable
                        class="mt-5"
                        :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
                      />

                    </VCol>
                    <VCol
                      cols="6"
                      md="6"
                    >
                      <AppDateTimePicker
                        v-model="Out_Date"
                        :label="$t('Date To')"
                        clearable
                        class="mt-5"
                        :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
                      />
                    </VCol>

                  </VCol>
                  <VCol
                    cols="12"
                    md="12"
                    style="display: flex"
                  >
                    <VCol
                      cols="4"
                      md="4"
                    >
                      <VTextField
                        v-model="Guest_Name"
                        :label="$t('Guest Name')"
                        type="text"
                        class="mt-5"
                        clearable
                      />
                    </VCol>
                    <VCol
                      cols="4"
                      md="4"
                    >
                      <VTextField
                        v-model="Res_No"
                        :label="$t('Res No')"
                        type="number"
                        class="mt-5"
                        clearable
                      />
                    </VCol>
                    <VCol
                      cols="4"
                      md="4"
                    >
                      <VTextField
                        v-model="Ref_No"
                        :label="$t('Ref No.')"
                        type="text"
                        class="mt-5"
                        clearable
                      />
                    </VCol>
                  </VCol>
                  <VCol
                    cols="12"
                    md="12"
                    style="display: flex"
                  >
                    <VCol
                      cols="3"
                      md="3"
                    >
                      <VCombobox
                        v-model="company_id"
                        :items="company"
                        item-title="company_name"
                        :label="$t('company')"
                        clearable
                        class="mt-5"
                      />
                    </VCol>

                    <VCol
                      cols="2"
                      md="2"
                    >
                      <VCombobox
                        v-model="market_segment_id"
                        :items="Segment"
                        item-title="name"
                        :label="$t('market segment')"
                        clearable
                        class="mt-5"
                      />
                    </VCol>
                    <VCol
                      cols="2"
                      md="2"
                    >
                      <VCombobox
                        v-model="source_of_business_id"
                        :items="bussiness"
                        item-title="name"
                        :label="$t('source of business')"
                        clearable
                        class="mt-5"
                      />
                    </VCol>

                    <VCol
                      cols="2"
                      md="2"
                    >
                      <VCombobox
                        v-model="rate_code_id"
                        :items="Ratecode"
                        item-title="name"
                        :label="$t('Rate Code')"
                        clearable
                        class="mt-5"
                      />
                    </VCol>  <VCol
                      cols="3"
                      md="3"
                    >
                      <VCombobox
                        v-model="room_type_id"
                        :items="Types"
                        item-title="rm_type_name"
                        :label="$t('room type')"
                        clearable
                        class="mt-5"
                      />
                    </VCol>
                  </VCol>

                  <VCol
                    cols="12"
                    md="12"
                    style="display: flex;justify-content: space-around"
                  >
                    <VCheckbox
                      v-model="Checked"
                      :label="$t('check out')"
                      false-value="false"
                      true-value="true"

                    />

                    <VBtn @click="Filters">
                      {{ $t('Filter') }}
                    </VBtn>
                  </VCol>
                </VExpansionPanelText>
              </VExpansionPanel>
            </VExpansionPanels>
            <VCol
              cols="12"
              sm="12"
              md="12"
            >
              <VAlert
                v-if="selected_option"
                color="success"
                style=" font-size: larger;text-align: center;"
              >
                {{ $t('Selected Filter') + ' : ' + $t(selected_option) }}
              </VAlert>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="10"
            >
              <VCard>
                <VCard>
                  <VCardText>
                    <VTable
                      id="element-to-pdf"
                      height="300"
                    >
                      <thead>
                        <tr>
                          <th>
                            {{ $t('no.') }}
                          </th>
                          <th>
                            {{ $t('room name') }}
                          </th>
                          <!--
                            <th>
                            {{ $t('Folio no') }}
                            </th> 
                          -->
                          <th>
                            {{ $t('Guest Name') }}
                          </th>
                          <th>
                            {{ $t('Guest mobile') }}
                          </th>
                          <th>
                            {{ $t('Arrival Date') }}
                          </th>
                          <th>
                            {{ $t('Due Out Date') }}
                          </th>
                          <th>
                            {{ $t('Res.No') }}
                          </th>
                          <th>
                            {{ $t('Charge') }}
                          </th>
                          <th>
                            {{ $t('Payment') }}
                          </th>
                          <th>
                            {{ $t('balance') }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="Guest.length === 0">
                          <td
                            colspan="12"
                            style="text-align: center;"
                          >
                            {{ $t('No data available') }}
                          </td>
                        </tr>

                        <tr
                          v-for="(item, index) in Guest"
                          style="text-align: center;"
                          class="hover-pointer"
                          @click="select(item)"
                        >
                          <td>
                            {{ item.id }}
                          </td>
                          <td>
                            <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }} -->
                            {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                              : null
                            }}
                          </td>
                          <!--
                            <td>
                            {{ item.folio_no }}
                            </td> 
                          -->
                          <td>
                            {{ item.profile? item.profile.first_name:'' }}
                          </td>
                          <td>
                            {{ item.profile?item.profile.mobile:'' }}
                          </td>
                          <td>
                            {{ item.in_date }}
                          </td>
                          <td>
                            {{ item.out_date }}
                          </td>
                          <td>
                            {{ item.res_no }}
                          </td>
                          <td>
                            {{ item.transactions_sum_charge_amount }}
                          </td>
                          <td>
                            {{ item.transactions_sum_payment_amount }}
                          </td>
                          <td>
                            {{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}
                          </td>
                        </tr>

                      </tbody>
                    </VTable>
                  </VCardText>
                </VCard>
              </VCard>
            </VCol>
            <VCol
              cols="6"
              sm="3"
              md="2"
            >
              <VBtn
                style="width: 200px;"
                @click="filter('Inhouse Guest')"
              >
                {{ $t('Inhouse Guest') }}
              </VBtn>
              <VBtn
                class="mt-5"
                style="width: 200px;"
                @click="filter('Arrival Guest')"
              >
                {{ $t('Arrival Guest') }}
              </VBtn>
              <VBtn
                class="mt-5"
                style="width: 200px;"
                @click="filter('Due out Guest')"
              >
                {{ $t('Due out Guest') }}
              </VBtn>
              <VBtn
                class="mt-5"
                style="width: 200px;"
                @click="filter('Payment required')"
              >
                {{ $t('Payment required') }}
              </VBtn>
              <VBtn
                class="mt-5"
                style="width: 200px;"
                @click="filter('Checked out Guest')"
              >
                {{ $t('Checked out Guest') }}
              </VBtn>
            </VCol>
          </VRow>

          <VCol
            cols="12"
            sm="12"
            md="12"
          >
            <br>
            <div class="d-flex">
              <VAvatar
                color="info"
                variant="tonal"
                size="48"
                class="me-3"
              >
                <VIcon
                  size="24"
                  icon="tabler-cash"
                 />
              </VAvatar>
              <div class="d-flex flex-column mt-2">
                <span class="text-h6 font-weight-medium">{{ parseFloat(Total_Charge_Amount -
                  Total_payment_Amount).toFixed(2) }}</span>
                <span class="text-caption">
                  {{ $t('Current Balance') }}
                </span>
              </div>
            </div>
          </VCol>
        </VContainer>
      </VCardText>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  name: "GuestFilterTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      selected_option: null,
      SettingData: null,
      Arrival_Date: null,
      Out_Date: null,
      Guest_Name: null,
      Res_No: null,
      Ref_No: null,
      room_type_id: null,
      Total_payment_Amount: null,
      Total_Charge_Amounts: null,
      source_of_business_id: null,
      market_segment_id: null,
      company_id: null,
      rate_code_id: null,
      Checked: null,
      Guest: [],
      FilterDate: [],
      URL: window.location.origin,
      radioGroup: "All",
      Segment: [],
      bussiness: [],
      Ratecode: [],
      Types: [],
      company: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    // radioGroup() {
    //   if (this.radioGroup == 'All') {
    //     this.Checked = false
    //   }
    // },
    Checked(item) {

      // eslint-disable-next-line sonarjs/no-redundant-boolean
      if (item === 'true') {

        this.radioGroup  = 'OutDate'
        this.Arrival_Date = this.SettingData.hotel_date
        console.log(this.Arrival_Date)

        // var currentDate = new Date()
        // var tommorrow = new Date(currentDate)
        // tommorrow.setDate(currentDate.getDate()+4)
        // this.Out_Date = tommorrow.toLocaleDateString()
        // console.log('fdfd',this.Out_Date)
      }

    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.getAllSegment()
    this.getAllSourcebusiness()
    this.getRateCode()
    this.getAllTypes()
    this.getCompany()


  },
  // eslint-disable-next-line vue/component-api-style
  methods: {

    async filter(type) {
      if (type == 'Inhouse Guest') {

        await axios.post(`${this.URL}/api/guest_filter`, {
          mixedSearch: 1,
          spaceficSearch: 1,
          term: {
            is_checked_in: 1,
          },
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
          this.Total_payment_Amount = res.data.data.Total_Payment_Amount
          this.Total_Charge_Amount = res.data.data.Total_Charge_Amount
          this.selected_option = type
        })
      }
      if (type == 'Arrival Guest') {
        await axios.post(`${this.URL}/api/guest_filter`, {
          mixedSearch: 1,
          spaceficSearch: 1,
          term: {
            is_checked_in: 0,
            is_reservation: 1,
            "in_date+date": this.SettingData.hotel_date,
          },
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
          this.Total_payment_Amount = res.data.data.Total_Payment_Amount
          this.Total_Charge_Amount = res.data.data.Total_Charge_Amount
          this.selected_option = type
        })
      }
      if (type == 'Due out Guest') {
        await axios.post(`${this.URL}/api/guest_filter`, {
          mixedSearch: 1,
          spaceficSearch: 1,
          term: {
            checked_out: 0,
            "out_date+date": this.SettingData.hotel_date,
          },
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
          this.Total_payment_Amount = res.data.data.Total_Payment_Amount
          this.Total_Charge_Amount = res.data.data.Total_Charge_Amount
          this.selected_option = type
        })
      }
      if (type == 'Payment required') {
        await axios.post(`${this.URL}/api/guest_filter`, {
          mixedSearch: 1,
          spaceficSearch: 1,
          term: {
            is_checked_in: 1,
          },
        }).then(res => {
          this.Guest = this.returnPositiveBalance(res.data.data.Guests_Filter)
          this.Total_payment_Amount = res.data.data.Total_Payment_Amount
          this.Total_Charge_Amount = res.data.data.Total_Charge_Amount
          this.selected_option = type
        })
      }
      if (type == 'Checked out Guest') {
        await axios.post(`${this.URL}/api/guest_filter`, {
          mixedSearch: 1,
          spaceficSearch: 1,
          term: {
            checked_out: 1,
            "out_date+date": this.SettingData.hotel_date,
          },
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
          this.Total_payment_Amount = res.data.data.Total_Payment_Amount
          this.Total_Charge_Amount = res.data.data.Total_Charge_Amount
          this.selected_option = type
        })
      }
    },
    Filters(){
      if(this.radioGroup == 'All'){
        axios.post(`${this.URL}/api/guest_filter`, {
          AllDate: 1,
          start_date: this.Arrival_Date,
          end_date:this.Out_Date,
          rate_code_id:this.rate_code_id?.id,
          is_reservation:this.Res_No?.this.Res_No,
          market_segment_id:this.market_segment_id?.id,
          source_of_business_id:this.source_of_business_id?.id,
          room_type_id:this.room_type_id?.id,
          ref_no:this.Ref_No?.this.Ref_No,
          guest_name:this.Guest_Name?.this.Guest_Name,
          company_id:this.company_id?.id,
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
        })
      }else if (this.radioGroup=='InDate'){
        axios.post(`${this.URL}/api/guest_filter`, {
          InDate: 1,
          start_date: this.Arrival_Date,
          end_date:this.Out_Date,
          rate_code_id:this.rate_code_id?.id,
          is_reservation:this.Res_No?.this.Res_No,
          market_segment_id:this.market_segment_id?.id,
          source_of_business_id:this.source_of_business_id?.id,
          room_type_id:this.room_type_id?.id,
          ref_no:this.Ref_No?.this.Ref_No,
          guest_name:this.Guest_Name?.this.Guest_Name,
          company_id:this.company_id?.id,

        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
        })
      }else if (this.radioGroup=='OutDate'){
        axios.post(`${this.URL}/api/guest_filter`, {
          OutDate: 1,
          start_date: this.Arrival_Date,
          end_date:this.Out_Date,
          rate_code_id:this.rate_code_id?.id,
          is_reservation:this.Res_No?.this.Res_No,
          market_segment_id:this.market_segment_id?.id,
          source_of_business_id:this.source_of_business_id?.id,
          room_type_id:this.room_type_id?.id,
          ref_no:this.Ref_No?.this.Ref_No,
          guest_name:this.Guest_Name?.this.Guest_Name,
          company_id:this.company_id?.id,
        }).then(res => {
          this.Guest = res.data.data.Guests_Filter
        })
      }
    },
    returnPositiveBalance(guests) {
      let guest_array = []
      for (let index = 0; index < guests.length; index++) {
        if (guests[index].transactions_sum_charge_amount - guests[index].transactions_sum_payment_amount > 0) {
          guest_array.push(guests[index])
        }
      }
      
      return guest_array
    },
    getFolio() {
      // eslint-disable-next-line sonarjs/no-all-duplicated-branches
      if (this.radioGroup === 'InDate') {
        axios.post(`${this.URL}/api/guest_filter`, {
          res_no: this.Res_No,
          ref_no: this.Ref_No,
          InDate: this.Arrival_Date,
          start_date: this.Arrival_Date,
          end_date: this.Out_Date != null ? this.Out_Date : this.Arrival_Date,
          guest_name: this.Guest_Name,
          room_type_id: this.room_type_id?.id,
          source_of_business_id: this.source_of_business_id?.id,
          market_segment_id: this.market_segment_id?.id,
          company_id: this.company_id?.id,
          rate_code_id: this.rate_code_id?.id,
          checked_out: this.Checked,
        }).then(res => (this.Guest = res.data.data.Guests_Filter, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount))

        // eslint-disable-next-line sonarjs/no-duplicated-branches
      } else if (this.radioGroup === 'OutDate') {
        axios.post(`${this.URL}/api/guest_filter`, {
          res_no: this.Res_No,
          ref_no: this.Ref_No,
          OutDate: this.Arrival_Date,
          start_date: this.Arrival_Date,
          end_date: this.Out_Date != null ? this.Out_Date : this.Arrival_Date,
          guest_name: this.Guest_Name,
          room_type_id: this.room_type_id?.id,
          source_of_business_id: this.source_of_business_id?.id,
          market_segment_id: this.market_segment_id?.id,
          company_id: this.company_id?.id,
          rate_code_id: this.rate_code_id?.id,
          checked_out: this.Checked,
        }).then(res => (this.Guest = res.data.data.Guests_Filter, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount))
      } else {
        axios.post(`${this.URL}/api/guest_filter`, {
          res_no: this.Res_No,
          ref_no: this.Ref_No,
          guest_name: this.Guest_Name,
          room_type_id: this.room_type_id?.id,
          source_of_business_id: this.source_of_business_id?.id,
          market_segment_id: this.market_segment_id?.id,
          company_id: this.company_id?.id,
          rate_code_id: this.rate_code_id?.id,
          checked_out: this.Checked ? 1 : 0,
        }).then(res => (this.Guest = res.data.data.Guests_Filter, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount))
      }
    },
    async getAllSegment() {
      await axios

        .get(`${this.URL}/api/segment`)

        .then(response => (this.Segment = response.data))
    },
    async getAllSourcebusiness() {
      await axios
        .get(`${this.URL}/api/sourcebusiness`)
        .then(response => (this.bussiness = response.data))
    },
    async getRateCode() {
      await axios
        .get(`${this.URL}/api/rate-code`)
        .then(response => (this.Ratecode = response.data))
    },
    async getAllTypes() {
      await axios
        .get(`${this.URL}/api/room_types`)
        .then(res => {
          this.Types = res.data
        })
    },
    async getCompany() {
      await axios
        .get(`${this.URL}/api/companyProfile`)
        .then(res => {
          this.company = res.data
          this.filter('Inhouse Guest')
        })
    },
    select(reservation) {
      this.$router.push({
        name: `selectedFolio-folio`,
        params: { folio: reservation.id },
      })

      /*  if (reservation.checked_out == 0) {
         this.$router.push({
           name: `selectedFolio-folio`,
           params: { folio: reservation.id },
         })
       } */

    },
  },


}
</script>

<style>
.v-textarea .v-field__field {
  --v-input-control-height: var(--v-textarea-control-height);

  block-size: 60px;
}

.text-caption {
  font-family: "Public Sans", sans-serif, -apple-system, blinkmacsystemfont, "Segoe UI", roboto, "Helvetica Neue", arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol" !important;
  font-size: 0.75rem !important;
  font-weight: 800;
  inline-size: 200%;

  /* line-height: 1.25rem; */
  letter-spacing: 0.0333333333em !important;
  text-transform: none !important;
}

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

.hover-pointer:hover {
  cursor: pointer;
  background: steelblue;
}
</style>
