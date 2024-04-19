<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VCardText>
        <VContainer>
          <VRow>
            <VSpacer />
            <VCol cols="6" sm="5" md="5">
              <VCard>
                <VCardText>

                  <VRadioGroup v-model="radioGroup" inline>
                    <VRadio :label="$t('All')" value="All" />
                    <VRadio :label="$t('Arrival Date')" value="InDate" />
                    <VRadio :label="$t('Due Out Date')" value="OutDate" />

                  </VRadioGroup>
                  <AppDateTimePicker v-model="Arrival_Date" :label="$t('Date From')" clearable class="mt-5"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                  <AppDateTimePicker v-model="Out_Date" :label="$t('Date To')" clearable class="mt-5"
                    :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                  <VTextField v-model="Guest_Name" :label="$t('Guest Name')" type="text" class="mt-5" clearable />
                  <VTextField v-model="Res_No" :label="$t('Res No')" type="number" class="mt-5" clearable />
                  <VTextField v-model="Ref_No" :label="$t('Ref No')" type="text" class="mt-5" clearable />

                </VCardText>
              </VCard>
            </VCol>
            <VCol cols="6" sm="4" md="4">
              <VCard>
                <VCardText>
                  <VList class="card-list">
                    <VListItem>
                      <VListItemTitle class="font-weight-semibold">
                        <VCol style="display: center;">
                          <VCheckbox v-model="Checked" :label="$t('check out')" :true-value="true" :false-value="false" />
                        </VCol>
                        <VCol style="display: flex;">
                          <VCombobox item-color="itemColor" v-model="company_id" :items="company" item-title="company_name"
                            :label="$t('company')" style="width: 50%;" clearable />
                        </VCol>
                        <VCol style="display: flex;">
                          <VCombobox item-color="customColorValue" v-model="market_segment_id" :items="Segment" item-title="name"
                            :label="$t('market segment')" style="width: 50%;" clearable />
                        </VCol>
                        <VCol style="display: flex;">
                          <VCombobox item-color="customColorValue" v-model="source_of_business_id" :items="bussiness" item-title="name"
                            :label="$t('source of business')" style="width: 50%;" clearable />
                        </VCol>
                        <VCol style="display: flex;">
                          <VCombobox item-color="customColorValue" v-model="room_type_id" :items="Types" item-title="rm_type_name"
                            :label="$t('room type')" style="width: 50%;" clearable />
                        </VCol>
                        <VCol style="display: flex;">
                          <VCombobox item-color="customColorValue" v-model="rate_code_id" :items="Ratecode" item-title="name" :label="$t('Rate Code')"
                            style="width: 50%;" clearable />
                        </VCol>
                      </VListItemTitle>
                    </VListItem>
                  </VList>
                </VCardText>
              </VCard>
            </VCol>
            <VCol cols="6" sm="3" md="3">
              <VBtn variant="tonal" @click="getFolio" style="margin: 10% auto;float: inline-start;">
                {{ $t('Show Data') }}
              </VBtn>
            </VCol>
            <VCol cols="12" sm="12" md="12">
              <VCard>
                <VCard>
                  <VCardText>
                    <VTable id="element-to-pdf" style="table-layout: fixed;">
                      <thead>
                        <tr>
                          <th>
                            {{ $t('no.') }}
                          </th>
                          <th>
                            {{ $t('room name') }}
                          </th>
                          <!--  <th>
                            {{ $t('Folio no') }}
                          </th> -->
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
                        <tr v-for="(item, index) in Guest" style="text-align: center;" @click="select(item)"
                          class="hover-pointer">
                          <td>
                            {{ item.id }}
                          </td>
                          <td>
                            <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }}-->
                            {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                              : null
                            }}

                          </td>
                          <!--  <td>
                            {{ item.folio_no }}
                          </td> -->
                          <td>
                            {{ item.profile.first_name }}
                          </td>
                          <td>
                            {{ item.profile.mobile }}
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
          </VRow>

          <VCol cols="12" sm="12" md="12">
            <br>

            <VCard style="display: flex;width: 22%;justify-content: space-around;margin: 0% 0% 3%;float: inline-start;">
              <VCol cols="6" md="3">
                <div class="d-flex">
                  <VAvatar color="info" variant="tonal" size="42" class="me-3">
                    <VIcon size="24" icon="tabler-currency-dollar" />
                  </VAvatar>

                  <div class="d-flex flex-column">
                    <span class="text-h6 font-weight-medium">{{ parseFloat(Total_Charge_Amount -
                      Total_payment_Amount).toFixed(3) }}</span>
                    <span class="text-caption">
                      {{ $t('Current Balance') }}
                    </span>
                  </div>
                </div>
              </VCol>
            </VCard>
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
      Arrival_Date: '',
      Out_Date: '',
      Guest_Name: '',
      Res_No: '',
      Ref_No: '',
      room_type_id: '',
      Total_payment_Amount: '',
      Total_Charge_Amounts: '',
      source_of_business_id: '',
      market_segment_id: '',
      company_id: '',
      rate_code_id: '',
      Checked: false,
      Guest: [],
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
    radioGroup() {
      if (this.radioGroup == 'All') {
        this.Checked = false
      }
    },
    Checked() {
      if (this.Checked == true && this.radioGroup == 'All') {
        this.Checked = false
      }
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {

    this.getAllSegment()
    this.getAllSourcebusiness()
    this.getRateCode()
    this.getAllTypes()
    this.getCompany()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
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
          room_type_id: this.room_type_id.id,
          source_of_business_id: this.source_of_business_id.id,
          market_segment_id: this.market_segment_id.id,
          company_id: this.company_id.id,
          rate_code_id: this.rate_code_id.id,
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
          room_type_id: this.room_type_id.id,
          source_of_business_id: this.source_of_business_id.id,
          market_segment_id: this.market_segment_id.id,
          company_id: this.company_id.id,
          rate_code_id: this.rate_code_id.id,
          checked_out: this.Checked,
        }).then(res => (this.Guest = res.data.data.Guests_Filter, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount))
      } else {
        axios.post(`${this.URL}/api/guest_filter`, {
          res_no: this.Res_No,
          ref_no: this.Ref_No,
          guest_name: this.Guest_Name,
          room_type_id: this.room_type_id.id,
          source_of_business_id: this.source_of_business_id.id,
          market_segment_id: this.market_segment_id.id,
          company_id: this.company_id.id,
          rate_code_id: this.rate_code_id.id,
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
          this.getFolio()
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
}
</style>
