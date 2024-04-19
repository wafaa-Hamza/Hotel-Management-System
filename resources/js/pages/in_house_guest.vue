<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard>
      <VCardText>
        <VContainer>
          <VRow>
            <VSpacer />
            <VExpansionPanels variant="inset" class="mb-5 mt-5">
              <VExpansionPanel v-for="item in 1" :key="item">
                <VExpansionPanelTitle>Filter</VExpansionPanelTitle>
                <VExpansionPanelText>
                  <VCol cols="12" sm="12" md="12">
                    <VCard class="mb-6">
                      <VCardText>
                        <VRow>
                          <VCol cols="4" sm="4" md="4">
                            <VCol style="display: flex;">
                              <VRadioGroup v-model="radioGroup" inline>
                                <VRadio :label="$t('All')" value="All" />
                                <VRadio :label="$t('Arrival Date')" value="InDate" />
                                <VRadio :label="$t('out date')" value="OutDate" />

                              </VRadioGroup>
                            </VCol>
                            <AppDateTimePicker v-model="Arrival_Date" :label="$t('Date From')" clearable class="mt-5"
                              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />

                            <AppDateTimePicker v-model="Out_Date" :label="$t('Date To')" clearable class="mt-5"
                              :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                            <VTextField v-model="Guest_Name" :label="$t('Guest Name')" type="text" class="mt-5"
                              clearable />


                          </VCol>
                          <VCol cols="4" sm="4" md="4">
                            <VCol style="display: flex;">
                              <VCheckbox v-model="Checked" :label="$t('check out')" :true-value="true"
                                :false-value="false" />
                            </VCol>
                            <VTextField v-model="Res_No" :label="$t('Res No')" type="number" class="mt-5" clearable />
                            <VTextField v-model="Ref_No" :label="$t('Ref.No')" type="text" class="mt-5" clearable />

                            <VCol style="display: flex;margin-top: 3%;">
                              <VCombobox v-model="company_id" :items="company" item-title="company_name"
                                :label="$t('company')" style="width: 50%;" clearable />
                            </VCol>


                          </VCol>
                          <VCol cols="4" sm="4" md="4">
                            <VCol style="display: flex;">
                              <VCombobox v-model="market_segment_id" :items="Segment" item-title="name"
                                :label="$t('market segment')" style="width: 50%;" clearable />
                            </VCol>
                            <VCol style="display: flex;">
                              <VCombobox v-model="source_of_business_id" :items="bussiness" item-title="name"
                                :label="$t('source of business')" style="width: 50%;" clearable />
                            </VCol>
                            <VCol style="display: flex;">
                              <VCombobox v-model="room_type_id" :items="Types" item-title="rm_type_name"
                                :label="$t('room type')" style="width: 50%;" clearable />
                            </VCol>

                            <VCol style="display: flex;">
                              <VCombobox v-model="rate_code_id" :items="Ratecode" item-title="name"
                                :label="$t('Rate Code')" style="width: 50%;" clearable />
                            </VCol>
                          </VCol>
                        </VRow>
                      </VCardText>
                    </VCard>
                  </VCol>
                </VExpansionPanelText>
              </VExpansionPanel>
            </VExpansionPanels>
            <VCol cols="6" sm="3" md="3">
              <VBtn variant="tonal" @click="GetinHouseGuest" style="margin: 10% auto;float: inline-start;">
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
                          <th>
                            {{ $t('Folio no') }}
                          </th>
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
                            {{ $t('company') }}
                          </th>
                          <th>
                            {{ $t('rate') }}
                          </th>
                          <th>
                            {{ $t('status') }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in Guest" style="text-align: center;" @dblclick="select(item)"
                          class="hover-pointer">
                          <td>
                            {{ item.id }}
                          </td>
                          <td>
                            {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en :
                              item.room.rm_name_loc)
                              : null
                            }}
                          </td>
                          <td>
                            {{ item.folio_no }}
                          </td>
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
                            {{ item.company.company_name }}
                          </td>
                          <td>
                            {{ item.rate_code != null ? item.rate_code.name : '' }}
                          </td>
                          <td>
                            {{ item.res_status }}
                          </td>
                        </tr>
                      </tbody>
                    </VTable>
                  </VCardText>
                </VCard>
              </VCard>
            </VCol>
          </VRow>

          <!--          <VCol cols="12" sm="12" md="12">-->
          <!--            <br>-->

          <!--            <VCard style="float: left;width: 22%;margin: 0% 0% 3% 0%;display: flex;justify-content: space-around">-->
          <!--              <VCol cols="6" md="3">-->
          <!--                <div class="d-flex">-->
          <!--                  <VAvatar color="info" variant="tonal" size="42" class="me-3">-->
          <!--                    <VIcon size="24" icon="tabler-currency-dollar" />-->
          <!--                  </VAvatar>-->

          <!--                  <div class="d-flex flex-column">-->
          <!--                    <span class="text-h6 font-weight-medium">{{ Total_Charge_Amount - Total_payment_Amount }}</span>-->
          <!--                    <span class="text-caption">-->
          <!--                      {{ $t('Current Balance') }}-->
          <!--                    </span>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </VCol>-->
          <!--            </VCard>-->
          <!--          </VCol>-->
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
    // radioGroup() {
    //   if (this.radioGroup == 'All') {
    //     this.Checked = true
    //   }
    // },
    // Checked() {
    //   if (this.Checked == true && this.radioGroup == 'All') {
    //     this.Checked = true
    //   }
    // },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {

    this.getAllSegment()
    this.getAllSourcebusiness()
    this.getRateCode()
    this.getAllTypes()
    this.getCompany()
    this.GetinHouseGuest()

  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    GetinHouseGuest() {
      // eslint-disable-next-line sonarjs/no-all-duplicated-branches
      this.Guest = []
      if (this.radioGroup === 'InDate') {
        axios.post(`${this.URL}/api/getGuestInhouswithDates`, {


          'id': this.Res_No,
          'ref_no': this.Ref_No,
          'InDate': this.Arrival_Date,
          'start_date': this.Arrival_Date,
          'end_date': this.Out_Date != null ? this.Out_Date : this.Arrival_Date,
          'guest_name': this.Guest_Name,
          'room_type_id': this.room_type_id.id,
          'source_of_business_id': this.source_of_business_id.id,
          'market_segment_id': this.market_segment_id.id,
          'company_id': this.company_id.id,
          'rate_code_id': this.rate_code_id.id,
          'checked_out': this.Checked,

        }).then(res => (this.Guest = res.data.data))

        // eslint-disable-next-line sonarjs/no-duplicated-branches
      } else if (this.radioGroup === 'OutDate') {
        axios.post(`${this.URL}/api/getGuestInhouswithDates`, {

          'res_no': this.Res_No,
          'ref_no': this.Ref_No,
          'OutDate': this.Arrival_Date,
          'start_date': this.Arrival_Date,
          'end_date': this.Out_Date != null ? this.Out_Date : this.Arrival_Date,
          'guest_name': this.Guest_Name,
          'room_type_id': this.room_type_id.id,
          'source_of_business_id': this.source_of_business_id.id,
          'market_segment_id': this.market_segment_id.id,
          'company_id': this.company_id.id,
          'rate_code_id': this.rate_code_id.id,
          'checked_out': this.Checked,

        }).then(res => (this.Guest = res.data.data))
      } else {

        axios.post(`${this.URL}/api/getGuestInhouswithDates`, {

          'res_no': this.Res_No,
          'ref_no': this.Ref_No,
          'in_date': this.Arrival_Date,
          'out_date': this.Out_Date,
          'guest_name': this.Guest_Name,
          'room_type_id': this.room_type_id.id,
          'source_of_business_id': this.source_of_business_id.id,
          'market_segment_id': this.market_segment_id.id,
          'company_id': this.company_id.id,
          'rate_code_id': this.rate_code_id.id,
          'checked_out': this.Checked ? 1 : 0,

        }).then(res => (this.Guest = res.data.data))
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
          this.GetinHouseGuest()
        })
    },
    select(reservation) {
      this.$router.push({
        name: `selectedFolio-folio`,
        params: { folio: reservation.id },
      })


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
