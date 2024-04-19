<!-- eslint-disable vue/order-in-components -->
<script setup>
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard>
      <VContainer>
        <VForm ref="refForm" @submit.prevent="SearchReservation(selected_status)">
          <VRow>
            <VCol cols="12" sm="6" md="2"></VCol>
            <VCol cols="12" sm="6" md="4">
              <VRadioGroup v-model="inlineRadio" inline>
                <VRadio :label="$t('By arrival date')" value="1" />
                <VRadio :label="$t('By Created date')" value="0" />
              </VRadioGroup>
            </VCol>
            <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
              Please Enter right data
            </VSnackbar>
            <VCol cols="12" sm="6" md="3">
              <AppDateTimePicker v-model="date_from" :label="$t('Date from')" clearable
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <AppDateTimePicker v-model="date_to" :label="$t('Date to')" clearable
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VTextField v-model="guest_name" :label="$t('Guest name')" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VTextField v-model="ref_no" :label="$t('Reference number')" clearable />
            </VCol>
            <VCol cols="12" sm="3" md="3">
              <VTextField v-model="res_no_from" :label="$t('Reservation number from')" type="number" clearable />
            </VCol>
            <VCol cols="12" sm="3" md="3">
              <VTextField v-model="res_no_to" :label="$t('Reservation number to')" type="number" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="company" :label="$t('Company')" :items="companies" :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'
                " item-value="id" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="segment" :label="$t('Market Segment')" :items="segments"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="created_by" :label="$t('Created by')" :items="users" item-title="firstname"
                item-value="id" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="country" :label="$t('Country')" :items="countries"
                :item-title="$i18n.locale === 'en' ? 'name_loc' : 'name'" item-value="id"
                :menu-props="{ maxHeight: '200px' }" clearable />
            </VCol>
            <VCol cols="12" sm="6" md="10">
              <div class="checkbox-container">
                <div v-for="reservation in reservations" :key="reservation.id" class="checkbox-item">
                 <VCheckbox v-model="selected_status" :label="$i18n.locale === 'en'?reservation.name:reservation.name_loc" :value="reservation.res_status_code"
                    :style="{ backgroundColor: reservation.color }" class="mt-1 mb-2" true-icon="tabler-check" style="
                                width: 150px;
                                padding: 0% 5%;
                                border-radius: 10px;
                                font-weight: bold;
" />
                </div>
                <div class="checkbox-item">
                  <VCheckbox v-model="checkout_checkbox" :label="$i18n.locale === 'en'?$t('Checked out'):'قام بالخروج'" :true-value="1" :false-value="0"
                    class="mt-1 mb-2" true-icon="tabler-check" style="
                            width: 150px;
                            padding: 0% 5%;
                            border-radius: 10px;
                            background-color: grey;
                            font-weight: bold;
" />
                </div>
              </div>
            </VCol>
            <VCol cols="12" sm="6" md="2">
              <VBtn class="float-end" type="submit" @click="refForm?.validate()">
                {{ $t("Search") }}
              </VBtn>
              <VBtn class="float-end mr-4" @click="Clear">
                {{ $t("Clear") }}
              </VBtn>
            </VCol>
          </VRow>
          <VTable height="500">
            <thead>
              <tr>
                <th>
                  {{ $t("Res status") }}
                </th>
                <th>
                  {{ $t("User") }}
                </th>
                <th>
                  {{ $t("Res no") }}
                </th>
                <th>
                  {{ $t("Guest name") }}
                </th>
                <th>
                  {{ $t("Arrival date") }}
                </th>
                <th>
                  {{ $t("Departure date") }}
                </th>
                <th>
                  {{ $t("Created date") }}
                </th>
                <th>
                  {{ $t("Room name") }}
                </th>
                <th>
                  {{ $t("NOR") }}
                </th>
                <th>
                  {{ $t("Company") }}
                </th>
                <th>
                  {{ $t("Deposit") }}
                </th>
                <th>
                  {{ $t("No of pax") }}
                </th>
                <th>
                  {{ $t("Type") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="searched_reservations.length === 0">
                <td colspan="15" style="text-align: center;">{{ $t('No data available') }}</td>
              </tr>
              <tr v-for=" item  in  searched_reservations " :key="item.id" @click="selectreservation(item)"
                class="hover-pointer">
                <td :style="{ backgroundColor: item.reservation_status.color }">
                  {{ item.reservation_status.res_status_code }}
                </td>
                <td>{{ item.created_by.firstname }}</td>
                <td>{{ item.res_no }}</td>
                <td>
                  {{ item.profile?.first_name + " " + item.profile?.last_name }}
                </td>
                <td>{{ item.in_date }}</td>
                <td>{{ item.out_date }}</td>
                <td>{{ formatDate(item.created_at) }}</td>
                <td v-if="$i18n.locale === 'en' && item.room != null">
                  {{ item.room.rm_name_en }}
                </td>
                <td v-if="$i18n.locale !== 'en' && item.room != null">
                  {{ item.room.rm_name_loc }}
                </td>
                <td v-if="item.room == null">none</td>
                <td>{{ item.no_of_pax }}</td>
                <td v-if="$i18n.locale === 'en'">
                  {{ item.company.company_name }}
                </td>
                <td v-else>
                  {{ item.company.company_name_loc }}
                </td>
                <td>{{ 0 }}</td>
                <td>{{ item.no_of_pax }}</td>
                <td>{{ item.is_group == 0 ? 'I' : 'Group' }}</td>
              </tr>
            </tbody>
          </VTable>
        </VForm>
      </VContainer>
    </VCard>
  </div>
</template>

<script>


import { mapActions, mapStores } from "pinia";
import { useGeneralStore } from "../stores/GeneralStore";

export default {
  name: "Guest",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      checkout_checkbox: 0,
      search: null,
      isSnackbarVisible: false,
      inlineRadio: "1",
      refForm: ref(),
      date_from: '',
      date_to: '',
      res_no_from: null,
      res_no_to: null,
      ref_no: null,
      company: null,
      segment: null,
      country: null,
      created_by: null,
      res_status: [],
      selected_status: [],
      guest_name: null,
      AllSetData: [],
      SettingData: [],
      localSetting: [],
    }
  },
  mounted() {
    const SettingData = localStorage.getItem('keyinfo');
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData);
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }

    this.date_from = this.localSetting.hotel_date
    this.date_to = this.localSetting.hotel_date
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    formatDate() {
      return (dateString) => {
        const dateObject = new Date(dateString);
        const year = dateObject.getFullYear();
        const month = (dateObject.getMonth() + 1).toString().padStart(2, "0");
        const day = dateObject.getDate().toString().padStart(2, "0");
        return `${year}-${month}-${day}`;
      }
    },
    ...mapStores(useGeneralStore),

    companies() {
      return this.generalStore.companies
    },
    countries() {
      return this.generalStore.countries
    },
    segments() {
      return this.generalStore.segments
    },
    reservations() {
      return this.generalStore.reservations
    },
    response() {
      return this.generalStore.response
    },
    users() {
      return this.generalStore.users
    },
    searched_reservations() {
      return this.generalStore.searched_reservations
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    response() {
      if (this.response != null) {
        this.response = null
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, [
      "getAllusersAction",
      "getReservationstatusesAction",
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "searchReservationsAction",
    ]),
    Clear() {
      this.inlineRadio = "1"
      this.date_from = this.localSetting.hotel_date
      this.date_to = this.localSetting.hotel_date
      this.res_no_from = null
      this.res_no_to = null
      this.ref_no = null
      this.company = null
      this.segment = null
      this.country = null
      this.created_by = null
      this.res_status = []
      this.selected_status = []
      this.guest_name = null
      this.checkout_checkbox = 0
    },

    // eslint-disable-next-line sonarjs/cognitive-complexity
    SearchReservation(reservation) {
      this.searchReservationsAction({
        guest_name: this.guest_name,
        created_by: this.created_by,
        arrival_date_from: this.inlineRadio == "1" ? this.date_from : null,
        arrival_date_to: this.inlineRadio == "1" ? (this.date_to != null ? this.date_to : this.date_from) : null,
        created_date_from: this.inlineRadio == "0" ? this.date_from : null,
        created_date_to: this.inlineRadio == "0" ? (this.date_to != null ? this.date_to : this.date_from) : null,
        res_no_from: this.res_no_from,
        res_no_to: this.res_no_to != null ? this.res_no_to : (this.res_no_from != null ? this.res_no_from : null),
        ref_no: this.ref_no,
        company_id: this.company,
        market_segment_id: this.segment,
        res_status: reservation.length != 0 ? reservation : null,
        country_id: this.country,
        checked_out: this.checkout_checkbox == "1" ? true : false,
      })

    },
    selectreservation(reservation) {


      if (reservation.is_checked_in == 1) {
        this.$router.push({
          name: `selectedFolio-folio`,
          params: { folio: reservation.id },
        })
      }
      else if (reservation.res_type == 'M' || reservation.res_type == 'Y') {
        this.$router.push({
          name: `reservation-update-monthyear-reservation`,
          params: { reservation: reservation.id },
        })
      }
      else if (reservation.is_group == 0) {
        this.$router.push({
          name: `reservation-update-reservation`,
          params: { reservation: reservation.id },
        })
      }
      else {
        this.$router.push({
          name: `reservation-update-groupp-new`,
          params: { new: reservation.id },
        })
      }
    },
    alert() {
      this.$swal.fire({
        icon: "error",
        position: "top-end",
        title: "Please Enter right data",
        timer: 2000,
        showConfirmButton: false,
      })
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getCountriesAction()
    this.getAllusersAction()
    this.getReservationstatusesAction()
    this.searchReservationsAction({
      arrival_date_from: this.localSetting.hotel_date,
      arrival_date_to: this.localSetting.hotel_date,
      res_status: ['CNF'],
      checked_out: false,
    })
  },
}
</script>

<style >

.checkbox-container {
  display: flex;
  flex-wrap: wrap;
}

.checkbox-item {
  margin-block-end: 10px;
  margin-inline-end: 10px;
}

.hover-pointer:hover {
  cursor: pointer;
}
/*.v-selection-control__input input {*/
/*  cursor: pointer;*/
/*  position: absolute;*/
/*  left: 0;*/
/*  top: 10%;*/
/*  width: 43%;*/
/*  height: 84%;*/
/*  opacity: 1.1;*/
/*}*/
</style>
