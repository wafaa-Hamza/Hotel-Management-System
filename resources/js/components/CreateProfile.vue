<script setup>
import { requiredValidator, betweenValidator } from "@validators"

const refForm = ref()
const form = ref()
const formprofile = ref()
</script>

<template>
  <VCard :disabled="reservation != null">
    <VContainer style="display: flex">
      <VCol
        cols="6"
        sm="6"
        md="6"
      >
        <VTextField
          v-model="id_no_search"
          :label="$t('National ID / Mobile number')"
          clearable
          @keydown.enter="searchProfile"
        />
      </VCol>
      <VCol
        cols="7"
        sm="10"
        md="7"
      >
        <VForm ref="formprofile">
          <VRow>
            <VSnackbar
              v-model="isSnackbarVisible"
              location="top"
              :timeout="2000"
            >
              {{ $t("Please enter data") }}
            </VSnackbar>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VDialog
                v-model="searchDialog"
                width="1024"
                scroll-strategy="none"
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    @click="searchProfile"
                  >
                    {{ $t("Search") }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <span class="text-h5">{{ $t("Select Profile") }}</span>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VTable height="600">
                      <thead>
                        <tr>
                          <th>
                            {{ $t("First name") }}
                          </th>
                          <th>
                            {{ $t("Last name") }}
                          </th>
                          <th>
                            {{ $t("National ID") }}
                          </th>
                          <th>
                            {{ $t("Mobile") }}
                          </th>
                          <th>
                            {{ $t("Select") }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="item in guests"
                          :key="item.id"
                        >
                          <td>{{ item.first_name }}</td>
                          <td>{{ item.last_name }}</td>
                          <td>{{ item.id_number }}</td>
                          <td>{{ item.mobile }}</td>
                          <td>
                            <VBtn
                              color="primary"
                              @click="selectProfile(item)"
                            >
                              <VIcon icon="mdi-check" />
                            </VBtn>
                          </td>
                        </tr>
                      </tbody>
                    </VTable>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="searchDialog = false"
                    >
                      {{ $t("Close") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VDialog
                v-model="profiledetailsDialog"
                width="1048"
                scroll-strategy="none"
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    @click="showGuestProfile"
                  >
                    {{ $t("Show") }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <span class="text-h5">{{ $t("Profile") }}</span>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="first_name"
                          :label="$t('First name')"
                        />
                      </VCol>
                      <VSnackbar
                        v-model="more_isSnackbarVisible"
                        location="top"
                        :timeout="2000"
                        @keyup.enter="goNext($event.target)"
                      >
                        {{ $t("Please enter data") }}
                      </VSnackbar>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="last_name"
                          :label="$t('Last name')"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="mobile"
                          :label="$t('Mobile')"
                        />
                      </VCol>


                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="id_number"
                          :label="$t('National ID number')"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="3"
                        md="3"
                      >
                        <VTextField
                          v-model="id_number"
                          :label="$t('Version number')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="3"
                        md="3"
                      >
                        <VTextField
                          v-model="id_number"
                          :label="$t('ID type')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="country"
                          label="Country"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="gender"
                          :label="$t('gender')"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="email"
                          :label="$t('E-mail')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="2"
                      >
                        <VTextField
                          v-model="language"
                          label="language"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="city"
                          :label="$t('City')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="6"
                      >
                        <VTextField
                          v-model="phone"
                          :label="$t('Phone')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="6"
                      >
                        <VTextField
                          v-model="address"
                          :label="$t('Address')"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="12"
                        md="12"
                        style="display: flex; text-align: center;"
                      >
                        <VCol
                          cols="2"
                          sm="2"
                          md="2"
                        >
                          <b style="font-size: 140%;">Birthdate :</b>
                        </VCol>

                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_day"
                            :items="days"
                            :label="$t('days')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_month"
                            :items="month"
                            :label="$t('months')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_year"
                            :items="years"
                            :label="$t('Years')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                      </VCol>
                    </VRow>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="profiledetailsDialog = false"
                    >
                      {{ $t("Close") }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="submitUpdateProfile"
                    >
                      {{ $t("Save") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VBtn @click="clearSeletedProfile">
                {{ $t("Remove") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VDialog
                v-model="createProfileDialog"
                width="1048"
                persistent
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    :disabled="!noProfileSelected"
                  >
                    {{ $t("Create new profile") }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <span class="text-h5">{{ $t("Create Profile") }}</span>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="first_name_new"
                          :label="$t('First name')"
                          :rules="[requiredValidator]"
                        />
                      </VCol>
                      <VSnackbar
                        v-model="more_isSnackbarVisible"
                        location="top"
                        :timeout="2000"
                        @keyup.enter="goNext($event.target)"
                      >
                        {{ $t("Please enter data") }}
                      </VSnackbar>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="last_name_new"
                          :label="$t('Last name')"
                          :rules="[requiredValidator]"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="mobile_new"
                          :label="$t('Mobile')"
                        />
                      </VCol>


                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="id_no_new"
                          :label="$t('National ID number')"
                          @keyup.enter="goNext($event.target)"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="3"
                        md="3"
                      >
                        <VCombobox
                          v-model="version_num"
                          :items="Id_Num_ver"
                          :label="$t('Version number')"
                          @keyup.enter="goNext($event.target)"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="3"
                        md="3"
                      >
                        <VCombobox
                          v-model="id_no_new_compo"
                          :items="idtypes"
                          item-title="name"
                          :label="$t('ID type')"
                          @keyup.enter="goNext($event.target)"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VCombobox
                          v-model="country_new"
                          :items="countries"
                          label="country"
                          item-title="name"
                          item-value="country"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VCombobox
                          v-model="gender_new"
                          :items="gender_data"
                          label="gender"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="email_new"
                          :label="$t('E-mail')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="2"
                      >
                        <VCombobox
                          v-model="language_new"
                          :items="lang_data"
                          label="language"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VTextField
                          v-model="city_new"
                          :label="$t('City')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="6"
                      >
                        <VTextField
                          v-model="phone_new"
                          :label="$t('Phone')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="4"
                        md="6"
                      >
                        <VTextField
                          v-model="address_new"
                          :label="$t('Address')"
                        />
                      </VCol>

                      <VCol
                        cols="12"
                        sm="12"
                        md="12"
                        style="display: flex; text-align: center;"
                      >
                        <VCol
                          cols="2"
                          sm="2"
                          md="2"
                        >
                          <b style="font-size: 140%;">Birthdate :</b>
                        </VCol>
                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_day"
                            :items="days"
                            :label="$t('days')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_month"
                            :items="month"
                            :label="$t('months')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                        <VCol
                          cols="3"
                          sm="3"
                          md="3"
                          style="display: inline-block;"
                        >
                          <VCombobox
                            v-model="select_year"
                            :items="years"
                            :label="$t('Years')"
                            style="width: 100%;"
                            clearable
                          />
                        </VCol>
                      </VCol>
                    </VRow>
                  </VCardText>
                  <VCardActions style="display: flex;justify-content: right">
                    <VSpacer />
                    <VBtn

                      color="blue-darken-1"
                      variant="text"
                      @click="createProfileDialog = false"
                    >
                      {{ $t("Close") }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click.prevent="formprofile?.validate().then(res => { res.valid ? createProfile() : null })"
                    >
                      {{ $t("Submit") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
                <br> <br> <br>
              </VDialog>
            </VCol>
          </VRow>
        </VForm>
      </VCol>
    </VContainer>
  </VCard>
</template>

<script>
import { useRoomStore } from "@/stores/RoomStore"
import CreateProfile from "@/components/CreateProfile.vue"
import axios from "@axios"
import moment from "moment"
import { mapActions, mapStores } from "pinia"
import Swal from "sweetalert2"
import { useExtrasStore } from "../stores/ExtrasStore"
import { useGeneralStore } from "../stores/GeneralStore"
import { useGuestStore } from "../stores/GuestStore"
import { useLedgerStore } from '../stores/LedgerStore'
import { useRatecodeStore } from "../stores/RatecodeStore"

export default {

  name: "CreateProfile2",
  props: {

    selected_guest: {
        type: Array,
        required: true,

    },
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      configOutDate: {
        minDate: this.in_date, altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',
      },
      purpose_of_visit: null,
      Id_Num_ver: [],
      version_num: null,

      SettingData: null,
      rooms: [],
      out_date_hijri: null,
      in_date_hijri: null,
      PaymentDialog: false,
      folio_no: null,
      arrival_date: null,
      departure_date: null,
      extended_date: null,
      window_number: null,
      window_id: null,
      Recepit_no: null,
      Amount: null,
      Descriptions: null,
      Payments: [],
      Payments_Selected: null,
      postingchargeDialog: false,
      ledgerCats: [],
      tax_included: false,
      amount: null,
      ledger_id: null,
      ledger_name: null,
      no_data: null,
      description: null,
      cashier_items: [
        {
          title: 'Cashier Payment',
          value: 'CaherPayment',
        },
        {
          title: 'Posting Charges',
          value: 'postingCharges',
        },
      ],
      vip: null,
      vips: ["A", "B", "C"],
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      extras_show: false,
      files: [],
      vat_no: null,
      company_name: null,
      inv_address: null,
      reservation: null,
      gender_data: ["male", "female", "no gender found"],
      years: [],
      days: [],
      month: [],
      select_year: '',
      select_day: '',
      select_month: '',
      lang_data: ["ar", "en", "fr"],
      country_new: null,
      language: null,
      language_new: null,
      first_name_new: null,
      last_name_new: null,
      mobile_new: null,
      phone_new: null,
      id_no_new: null,
      id_no_new_compo: null,
      All_id_num: null,
      email_new: null,
      city_new: null,
      address_new: null,
      date_of_birth_new: null,
      gender_new: null,
      createProfileDialog: false,
      data: [],
      more_isSnackbarVisible: false,
      more_search_dialog: [false],
      id_no_search: [],
      mobile_search: [],
      profile_id_companion: [],
      country_id_companion: [],
      first_name_companion: [],
      last_name_companion: [],
      id_no_companion: [],
      mobile_companion: [],
      no_of_companions: 0,
      search: null,
      dialog: false,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: false,
      isSnackbarVisible: false,
      showprofilealert: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: 1,
      original_out_date: null,
      room: null,
      roomType: null,
      no_of_pax: null,
      room_rate: 0,
      rate_code: null,
      meal: null,
      hotel_note: null,
      client_note: null,
      way_of_payment: null,
      company_id: null,
      market_segment_id: null,
      source_of_business_id: null,
      country: null,
      ref_no: null,
      first_name: null,
      last_name: null,
      mobile: null,
      phone: null,
      id_number: null,
      email: null,
      city: null,
      address: null,
      date_of_birth: null,
      gender: null,
      store_from_landlord: null,
      total_rate: 0,
      genders: ["male", "female", "no gender found"],
      payments: [
        {
          name: "cash",
          code: "CASH",
        },
        {
          name: "cards",
          code: "CARD",
        },
        {
          name: "City ledger",
          code: "CL",
        },
        {
          name: "Complimentary",
          code: "COMP",
        },
        {
          name: "House Use",
          code: "HUSU",
        },
      ],
      profileId: null,
      reservations_status: null,
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
      noProfileSelected: true,
      selected_guest: null,
      isLoading: false,
      idtypes: [],
      timer: null,

      URL: window.location.origin,
      currentTime: this.getCurrentTime(),
    }
  },

  mounted() {
    //this.StoreLand()
    this.YeardData()
    this.MonthData()
    this.DayData()
    this.idtypeData()
    this.isLoading = true
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
    for (let i = 1 ; i<= 30 ; i++){
      this.Id_Num_ver.push(i)
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

    ...mapStores(
      useRatecodeStore,
      useRoomStore,
      useGuestStore,
      useGeneralStore,
      useExtrasStore,
      useLedgerStore,

    ),


    selected_profile_for_reservation() {
      return this.guestStore.selected_profile_for_reservation
    },
    companies() {
      return this.generalStore.companies
    },
    countries() {
      return this.generalStore.countries
    },
    sources() {
      return this.generalStore.sources
    },
    segments() {
      return this.generalStore.segments
    },
    rate_codes() {
      return this.ratecodeStore.rate_codes
    },
    response() {
      return this.guestStore.response
    },
    guests() {
      return this.guestStore.guests
    },
    reservations_statuses() {
      return this.generalStore.reservations
    },
    available_roomtypes() {
      return this.guestStore.available_rooms
    },
    checkin() {
      return this.guestStore.checkin
    },
    extras() {
      return this.extrasStore.extras
    },
    guest_extras() {
      return this.extrasStore.guest_extras
    },
    ledgers() {
      return this.ledgerStore.ledgers
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

    select_month() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_day() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_year() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },





    selected_guest() {
      if (this.selected_guest == null) {
        this.noProfileSelected = true
      }
    },
    searchDialog() {
      // eslint-disable-next-line sonarjs/no-redundant-boolean
      if (this.searchDialog == false) {
        this.guests = []
      }
    },

  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
  
    StoreLand(){
      console.log('this.selected_guest.Profile_no', this.selected_guest)
      axios.get(`${window.location.origin}/api/store_from_landlord/${this.selected_guest.profile_no}`).then(res => (this.store_from_landlord = res.data
      , this.selected_guest=this.store_from_landlord.data))
    },
  
    async submitUpdateProfile() {
      //console.log('date_of_birth_new', this.showGuestProfile)
      console.log('selected guest', this.selected_guest)
      await this.updateGuestProfileAction({
        'first_name': this.first_name,
        'last_name': this.last_name,
        'id_no': this.id_no,
        'mobile': this.mobile,
        'phone': this.phone,
        'email': this.email,
        'address': this.address,
        'city': this.city,
        'language': this.language,
        'date_of_birth': this.date_of_birth_new,
        'gender': this.gender,
        'country_id': this.country.id,
      }, this.selected_guest.id)

      this.profiledetailsDialog = false
    },
    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },
    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)

      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },
    YeardData() {
      const startYear = 1900
      const endYear = 2024
      for (let year = startYear; year <= endYear; year++) {
        this.years.push(year)
      }
    },
    MonthData() {
      const starMonth = 1
      const endMonth = 12
      for (let months = starMonth; months <= endMonth; months++) {
        this.month.push(months)
      }
    },
    DayData() {
      const statday = 1
      const endday = 31
      for (let day = statday; day <= endday; day++) {
        this.days.push(day)
      }
    },
    ...mapActions(useLedgerStore, ['getLedgers']),
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      'resetResponse',
      'updateGuestProfileAction',
      'getAvaliableRooms',
      'getGuestprofileAction',
      'nullSelectProfileForReservation',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),



    getPayment() {
      axios.get(`${window.location.origin}/api/payment`).then(res => (this.Payments = res.data),
      )
    },

    callMethod(methodName) {
      this[methodName]()
    },

    clear() {

      this.vip = null
      this.guest_extras = []
      this.extras_show = false
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true
      this.in_date = this.SettingData.hotel_date
      this.out_date = moment(this.in_date).add(1, 'days').format("YYYY-MM-DD")
      this.no_of_nights = 1
      this.original_out_date = null
      this.room = null
      this.roomType = this.available_roomtypes[0]
      this.no_of_pax = this.available_roomtypes[0].def_pax
      this.rooms = this.roomType.rooms
      this.room_rate = 0
      this.rate_code = null
      this.meal = null
      this.hotel_note = null
      this.client_note = null
      this.way_of_payment = this.payments[0]
      this.company_id = this.companies[0]
      this.market_segment_id = this.segments[0]
      this.source_of_business_id = this.sources[0]
      this.ref_no = null
      this.reservations_status = this.reservations_statuses[0]
      this.vat_no = null
      this.company_name = null
      this.inv_address = null
      this.reservation = null
      this.purpose_of_visit = null
      this.customer_type = null


    },

    selectProfile(guest) {

      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
      this.StoreLand()

    },
    showGuestProfile() {
      this.profiledetailsDialog = true
      console.log('this.selected_guest', this.selected_guest)
      if (this.selected_guest == null) {
        this.isSnackbarVisible = true
        this.profiledetailsDialog = false
      } else {
        this.first_name = this.selected_guest.first_name
        this.last_name = this.selected_guest.last_name
        this.id_number = this.selected_guest.id_number
        this.gender = this.selected_guest.gender
        this.mobile = this.selected_guest.mobile
        this.phone = this.selected_guest.phone
        this.email = this.selected_guest.email
        this.city = this.selected_guest.city
        this.address = this.selected_guest.address
        this.country = this.selected_guest.country.name
        this.language = this.selected_guest.language
        this.select_day = this.selected_guest.date_of_birth.slice(8, 10)
        this.select_month = this.selected_guest.date_of_birth.slice(5, 7)
        this.select_year = this.selected_guest.date_of_birth.slice(0, 4)

      }
    },
    clearSeletedProfile() {
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true

    },
    searchProfile() {
      if (this.id_no_search == null) {
        this.isSnackbarVisible = true
        this.searchDialog = false
      } else {
        this.searchDialog = true
        this.getSearchguestAction({
          id_no: this.id_no_search,
          mobile: this.id_no_search,
        })
      }
    },

    createProfile() {


      if (this.id_no_new != null) {
        axios
          .post(
            `${window.location.origin}/api/guestProfile`,
            {
              first_name: this.first_name_new,
              last_name: this.last_name_new,
              id_no: this.id_no_new,
              mobile: this.mobile_new,
              phone: this.phone_new,
              email: this.email_new,
              address: this.address_new,
              city: this.city_new,
              gender: this.gender_new,
              language: this.language_new,
              date_of_birth: this.date_of_birth_new,
              country_id: this.country_new.id,

              id_type_id: this.id_no_new_compo.id,
              version_no: this.version_num,
            },
          )
          .then(res => {
            if (res.status != undefined) {
              this.createProfileDialog = false
              this.insertAlert("profile created successfully")
              this.selected_guest = res.data.data[0]
              this.showprofilealert = false
              this.noProfileSelected = false
              console.log(res.data.data[0].id)
              this.profileId = res.data.data[0].id
            }

          })
          .catch(err => {
          })
      } else {
        this.more_isSnackbarVisible = true

        this.selected_guest = {
          first_name: null,
          last_name: null,
          mobile: null,
          email: null,
          id_number: null,

        }
        this.DangerAlert("Only name profile")
        this.selected_guest.first_name = this.first_name_new
        this.selected_guest.last_name = this.last_name_new
        this.selected_guest.mobile = this.mobile_new
        this.selected_guest.email = this.email_new
        this.showprofilealert = false
        this.noProfileSelected = false
        this.createProfileDialog = false
      }
    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },



  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  async created() {

    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    const response = await this.getAvaliableRooms(this.SettingData.hotel_date, moment(this.SettingData.hotel_date).add(1, 'days').format("YYYY-MM-DD"))

    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getledgerCats()
    this.getPayment()

    if (this.countries.length == 0) {
      this.getCountriesAction()
    }
    if (response.status != undefined) {

      this.getReservationstatusesAction()
    }
    this.getExtras()
  },


}
</script>
