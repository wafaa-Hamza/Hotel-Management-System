<script setup>
import { requiredValidator } from "@validators"
import { vMaska } from "maska"
</script>
<template>
  <VCard>
    <VContainer>
      <VRow>
        <VCol
          cols="12"
          sm="6"
          md="7"
        >
          <VTextField
            v-model="id_no_search"
            :label="$t('National ID / Mobile number')"
            clearable
            @keydown.enter="searchProfile"
          />
        </VCol>
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
          md="1"
        >
          <VDialog
            v-model="searchDialog"
            width="1024"
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
          md="1"
        >
          <VDialog
            v-model="profiledetailsDialog"
            width="1048"
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
                    sm="6"
                    md="3"
                  >
                    <VTextField
                      v-model="first_name"
                      :label="$t('First name')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <VTextField
                      v-model="last_name"
                      :label="$t('Last name')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <VTextField
                      v-model="mobile"
                      :label="$t('Mobile')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <VTextField
                      v-model="email"
                      :label="$t('E-mail')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="id_no"
                      :label="$t('National ID number')"
                      readonly
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
                      v-model="language"
                      label="language"

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
                      v-model="phone"
                      :label="$t('Phone')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="city"
                      :label="$t('City')"

                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="address"
                      :label="$t('Address')"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="6"
                  >
                    <VTextField
                      v-model="date_of_birth"
                      :label="$t('Date of birth')"

                    />
                  </VCol>
                </VRow>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn
                  color="blue-darken-1"
                  variant="text"
                  @click="submitUpdateProfile"
                >
                  {{ $t("Save") }}
                </VBtn>
                <VBtn
                  color="blue-darken-1"
                  variant="text"
                  @click="profiledetailsDialog = false"
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
          md="1"
        >
          <VBtn @click="clearSeletedProfile">
            {{ $t("Remove") }}
          </VBtn>
        </VCol>
        <VCol
          cols="12"
          sm="6"
          md="2"
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
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="4"
                    md="4"
                  >
                    <VTextField
                      v-model="last_name_new"
                      :label="$t('Last name')"
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

                    <VTextField
                      v-model="id_no_new"
                      :label="$t('National ID number')"
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

                  <VCol cols="12" sm="12" md="12" style="display: flex; text-align: center;">
                    <VCol cols="2" sm="2" md="2">
                      <b style="font-size: 140%;">Birthdate :</b>
                    </VCol>
                    <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                      <VCombobox v-model="select_day" :items="days" :label="$t('days')" style="width: 100%;"
                                 clearable />
                    </VCol>
                    <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                      <VCombobox v-model="select_month" :items="month" :label="$t('months')" style="width: 100%;"
                                 clearable />
                    </VCol>
                    <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                      <VCombobox v-model="select_year" :items="years" :label="$t('Years')" style="width: 100%;"
                                 clearable />
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
                  @click="createProfile"
                >
                  {{ $t("Submit") }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
        </VCol>
      </VRow>
    </VContainer>
  </VCard></template>

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
      customer_type: null,
      purposeOfVisitList: [
        { id: 1, nameE: 'Tourism', nameA: 'السياحة' },
        { id: 2, nameE: 'Family or Friends', nameA: 'زيارة الأقارب والاصدقاء' },
        { id: 3, nameE: 'Religious', nameA: 'زيارة دينية' },
        { id: 4, nameE: 'Business or Work', nameA: 'عمل' },
        { id: 5, nameE: 'Sports', nameA: 'نشاط رياضي' },
        { id: 6, nameE: 'Entertainment', nameA: 'نشاط ترفيهي' },
        { id: 7, nameE: 'Other', nameA: 'اخرى' },
        { id: 8, nameE: 'Work (Royal Court)', nameA: 'موظف ديوان' },
        { id: 9, nameE: 'Quarantined guests', nameA: 'عمل نزيل حجر' },
        { id: 10, nameE: 'Ministry of Health Staff', nameA: 'موظف وزارة الصحة' },
      ],
      customerTypeList: [
        { id: 1, nameE: 'Citizen', nameA: 'مواطن' },
        { id: 2, nameE: 'GolfCitizen', nameA: 'مواطن خليجي' },
        { id: 3, nameE: 'Visitor', nameA: 'زائر' },
        { id: 4, nameE: 'Resident', nameA: 'مقيم' },
      ],
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

    const roomStore = useRoomStore()

    this.isLoading = true

    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
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

    inDateValidator() {
      return value => {
        if (!value) {
          return true // Allow if the value is empty
        }

        const selectedDate = moment(value, 'YYYY-MM-DD')

        //console.log('vali',moment(this.SettingData.hotel_date,'YYYY-MM-DD') )
        return selectedDate >= moment(this.SettingData.hotel_date, 'YYYY-MM-DD') || 'In Date must be on or after the hotel date'
      }
    },
    outDateValidator() {
      return value => {
        if (!value) {
          return true // Allow if the value is empty
        }
        const selectedDate = moment(value, 'YYYY-MM-DD')

        return selectedDate >= moment(this.SettingData.hotel_date, 'YYYY-MM-DD') || 'Out Date must be on or after the In date'
      }
    },
    minDateForOut() {
      console.log('comp', this.in_date)

      return this.in_date
    },
    filterData() {
      return this.rooms.filter(room => {
        return (
          room.rm_name_en.toLowerCase().includes(this.search) ||
          room.rm_name_loc.toLowerCase().includes(this.search)
        )
      })
    },
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
    // id_no_new(num){
    //   this.id_no_new = num.concat(' ', '|');
    // },
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
    room_rate() {
      this.total_rate = this.room_rate * this.no_of_nights
    },

    in_date() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date.substring(0, 10))
        inn.add(this.no_of_nights, "days")

        // console.log('in',inn.format("YYYY-MM-DD"))
        this.out_date = inn.format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date.substring(0, 10))
        this.configOutDate.minDate = this.in_date
      }
      if (this.in_date == null) {
        this.in_date = moment().format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date).substring(0, 10)
      }
    },
    no_of_nights() {
      if (this.no_of_nights == null) {
        this.no_of_nights = 1
      }
      else {
        var inn = moment(this.in_date.substring(0, 10))
        inn.add(this.no_of_nights, "days")
        console.log(inn)
        this.out_date = inn.format("YYYY-MM-DD")
        this.configOutDate.minDate = this.in_date
      }
      this.total_rate = this.room_rate * this.no_of_nights
    },
    async out_date() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date.substring(0, 10))
        var out = moment(this.out_date.substring(0, 10))
        this.no_of_nights = out.diff(inn, "days")
        this.out_date_hijri = this.$getHijriDate(this.out_date.substring(0, 10))
      }

      if (!this.isLoading) {
        const response = await this.getAvaliableRooms(this.in_date.substring(0, 10), this.out_date.substring(0, 10))
        if (response.status == 200) {
          console.log('aaaa')
          this.fillNewRoomType()
        }
      }
      this.roomId()
      this.isLoading = false
    },
    response() {
      if (this.response != null) {
        this.extras_show = true
        this.reservation = this.response
        if (this.files.length > 0) {
          this.uploadfile(this.reservation.id)
        }
        this.insertAlert("Reservation has been created")
        if (
          (this.reservation.res_status == "CNF" ||
            this.reservation.res_status == "NCF") &&
          this.reservation.in_date == this.SettingData.hotel_date
          && this.reservation.room != null
        ) {
          this.disableCheckin = false
        } else {
          this.disableCheckin = true
        }
        this.resetResponse()
      }
    },
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
      }
    },
    reservations_statuses() {
      this.clear()
      if (this.selected_profile_for_reservation != null) {
        this.selected_guest = this.selected_profile_for_reservation
        this.showprofilealert = false
        this.nullSelectProfileForReservation()
      }
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
    updateIdNoSearch(newValue) {
      this.id_no_search = newValue;
    },
    StoreLand(){
      console.log('this.selected_guest.Profile_no', this.selected_guest)
      axios.get(`${window.location.origin}/api/store_from_landlord/${this.selected_guest.profile_no}`).then(res => (this.store_from_landlord = res.data
        , this.selected_guest=this.store_from_landlord.data))
    },
    handleCustomEvent(data) {
      console.log(data) // Output: Data from child!
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
    fillNewRoomType() {
      this.roomType = this.available_roomtypes.find(roomtype => roomtype.id == this.roomType.id)

      this.rooms = this.roomType.rooms
      this.roomId()
    },
    roomId() {
      const roomStore = useRoomStore()

      //console.log('room rack id',roomStore.roomId.room_id)
      if (roomStore.roomId?.room_type_id) {
        this.roomType = this.available_roomtypes.find(roomtype => roomtype.id == roomStore.roomId.room_type_id)
        this.rooms = this.roomType?.rooms
        var roomFromRack = this.rooms?.find(element => element.id == roomStore.roomId.room_id)


        return this.room = roomFromRack
      }
    },
    noOfPaxchanged() {
      console.log(this.no_of_pax)
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rateCodechanged()
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
      }
      this.no_of_companions = this.no_of_pax - 1
    },
    roomTypechanged() {

      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rooms = this.roomType.rooms

        this.rateCodechanged()
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
        this.rooms = []
      }

      // this.no_of_pax = this.roomType.def_pax
    },


    rateCodechanged() {
      if (this.rate_code != null) {
        for (var i = 0, len = this.rate_code.room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.rate_code.room_types[i].id == this.roomType.id) {
            if (this.no_of_pax == 1) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax1
            }
            if (this.no_of_pax == 2) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax2
            }
            if (this.no_of_pax == 3) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax3
            }
            if (this.no_of_pax == 4) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax4
            }
            if (this.no_of_pax == 5) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax5
            }
            if (this.no_of_pax == 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax6
            }
            if (this.no_of_pax > 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.extra_pax
            }
          }
        }
        if (this.rate_code.meal != null) {
          this.meal = this.rate_code.meal
        }
        if (this.rate_code.meal_package != null) {
          this.meal = this.rate_code.meal_package
        }
      }
    },
    getPayment() {
      axios.get(`${window.location.origin}/api/payment`).then(res => (this.Payments = res.data),
      )
    },
    CaherPayment() {
      this.PaymentDialog = true
      this.room_no = this.reservation.room != null ? this.reservation.room.rm_name_en : null
      this.folio_no = this.reservation.res_no
      this.guest_id = this.reservation.id
      this.window_number = this.reservation.windows[0].window_name
      this.window_id = this.reservation.windows[0].id
      this.departure_date = this.reservation.out_date
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
    },
    submitPayment() {
      // eslint-disable-next-line promise/valid-params
      axios.post(`${window.location.origin}/api/transactions`, {
        'guest_id': this.guest_id,
        'room_id': this.reservation.room != null ? this.reservation.room.id : null,
        'ref_id': this.no_data,
        'description': this.Descriptions,
        'hotel_date': moment().format("YYYY-MM-DD"),
        'trans_type': 'P',
        'recd_type': 'REC',
        'inc': 0,
        'res_id': this.reservation.res_no,
        'is_reservation': this.reservation.is_reservation,
        'payment_amount': this.Amount,
        'window_id': this.reservation.windows[0].id,
        'payment_type_id': this.Payments_Selected.id,
      }).then(res => {
        this.PaymentDialog = false
        this.insertAlert('Departure date has been Payment')
      })


    },
    callMethod(methodName) {
      this[methodName]()
    },
    getledgerCats() {
      axios.get(`${window.location.origin}/api/ledger-cats`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.ledgerCats = res.data
        })
        .catch(err => {
        })
    },
    postingCharges() {
      this.postingchargeDialog = true
      this.room_no = this.reservation.room != null ? this.reservation.room.rm_name_en : null
      this.folio_no = this.reservation.id
      this.arrival_date = this.reservation.in_date
      this.departure_date = this.reservation.out_date
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
    },
    ledgerSelected(ledger) {
      this.ledger_id = ledger.id
      this.ledger_name = ledger.name
      this.description = ledger.name
    },
    submitPostingCharge() {
      axios.post(`${window.location.origin}/api/transactions`, {
        'guest_id': this.reservation.id,
        'room_id': this.reservation.room != null ? this.reservation.room.id : null,
        'hotel_date': moment().format("YYYY-MM-DD"),
        'window_id': this.reservation.windows[0].id,
        'ledger_id': this.ledger_id,
        'charge_amount': this.amount,
        'trans_type': 'C',
        'recd_type': 'CHR',
        'description': this.description,
        'is_reservation': this.reservation.is_reservation,
        'inc': this.tax_included == true ? 1 : 0,
      }).then(res => {
        this.closePostingChargeDialog()
        this.insertAlert('Posting charge has been recorded')
      })
    },
    closePostingChargeDialog() {
      this.postingchargeDialog = false
      this.amount = null
      this.ledger_id = null
      this.ledger_name = null
      this.description = null
    },
    GoToUpdateReservation() {
      this.$router.push({
        name: `reservation-update-reservation`,
        params: { reservation: this.reservation.id },
      })
    },
    addExtraForGuest() {
      this.addGuestExtra({
        data: [{
          guest_id: this.reservation.id,
          extra_id: this.selected_extra.id,
          amount: this.extra_amount,
        }],
      })
      this.selected_extra = null
      this.extra_amount = null
    },
    deleteExtraGuest(item) {
      this.deleteGuestExtra(item, this.reservation.id)
    },
    onFileChange(event) {
      this.files = event.target.files
    },
    uploadfile() {
      if (this.files.length > 3) {
        this.DangerAlert('Only 3 files is allowed')
      }
      else {
        const formData = new FormData()
        for (let i = 0; i < this.files.length; i++) {
          formData.append('file[]', this.files[i])
        }
        formData.append('guest_id', this.reservation.id)
        axios.post(`${window.location.origin}/api/guestAttachment`, formData, {
          headers: {
            'Content-Type': 'application/*',
          },
        }).then(
          res => {
          },
        ).catch(
          err => {
          },
        )
      }


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
    async  CreateReservation() {

      if (this.formValues.length == 0) {
        this.fill_res_days()
        this.save_res_days()
      }
      if (this.no_of_companions > 0) {
        for (let index = 1; index <= this.no_of_companions; index++) {
          this.data.push({
            profile_id: this.profile_id_companion[index],
            country_id:
              this.country_id_companion[index] != null
                ? this.country_id_companion[index].id
                : null,
            first_name: this.first_name_companion[index],
            last_name: this.last_name_companion[index],
            id_no: this.id_no_companion[index],
            mobile: this.mobile_companion[index],
          })
        }
      }
      else {
        this.data = null
      }
      const onlyname = []

      console.log('this.selected_guest', this.selected_guest)
      if (this.selected_guest.id_number == null) {
        onlyname.push(this.selected_guest)
        this.CreateGuestAction({
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null && this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null && this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_package_id : null,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          res_rate_days: this.formValues,
          onlyNameData: onlyname.length != 0 ? onlyname : null,
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "D",
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })
      } else {
        this.StoreLand()
        this.CreateGuestAction({
          profile_id: this.selected_guest.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null && this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null && this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_package_id : null,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          res_rate_days: this.formValues != null ? this.formValues : null,
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "D",
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })
      }
      const roomStore = useRoomStore()



      roomStore.setRoomId(null)



    },
    selectroom(room) {
      this.dialog = false
      this.no_of_pax = room.rm_max_pax
      console.log(room.rm_max_pax)
      this.room = room
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
      console.log('this.selected_guest',this.selected_guest)
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
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    clear_res_days() {
      this.ratecode = []
      this.roomrate = []
    },
    fill_res_days() {
      for (let i = 1; i <= this.no_of_nights; i++) {
        this.ratecode[i] = this.rate_code != null && this.rate_code != null ? this.rate_code : null
        this.roomrate[i] = this.room_rate
      }
    },
    fill_rest_res_days(index) {
      for (let i = index; i <= this.no_of_nights; i++) {
        this.ratecode[i] = this.ratecode[index]
        this.roomrate[i] = this.roomrate[index]
      }
    },
    getStartDate(index) {
      const startDate = new Date(this.in_date)

      startDate.setDate(startDate.getDate() + index - 1)

      return startDate.toISOString().substr(0, 10)
    },
    save_res_days() {
      let empty_flag = 1
      this.formValues = [] // clear the array before adding new values
      for (let i = 1; i <= this.no_of_nights; i++) {
        const formValue = {
          day_date: this.getStartDate(i),
          rate_id: this.ratecode[i] != null ? this.ratecode[i].id : null,
          meal_id:
            this.ratecode[i] != null && this.ratecode[i].meal_id != null
              ? this.ratecode[i].meal_id
              : null,
          meal_package_id:
            this.ratecode[i] != null && this.ratecode[i].meal_package_id != null
              ? this.ratecode[i].meal_package_id
              : null,
          rm_rate: this.roomrate[i] != null ? this.roomrate[i] : null,
        }

        if (this.roomrate[i] != null) {
          empty_flag = 0
        }
        this.formValues.push(formValue) // add the form values to the array
      }
      if (empty_flag == 1) {
        this.formValues = null
      }

      this.reservation_days_dialog = false
    },
    checkinfunction() {
      if (this.reservation.profile.id == 1) {
        this.DangerAlert("please choose or create full profile to checkin")
      } else {
        Swal.fire({
          title: 'Do you want to Checkin?',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Save',

        }).then(result => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            this.GuestCheckinAction(this.reservation.id)
          }
        })

      }
    },
    clearSeletedProfileCompanion(index) {
      this.profile_id_companion[index] = null
      this.first_name_companion[index] = null
      this.last_name_companion[index] = null
      this.id_no_companion[index] = null
      this.mobile_companion[index] = null
      this.country_id_companion[index] = null
    },
    AddCompanion() {
      this.no_of_companions += 1
    },
    RemoveCompanion() {
      if (this.no_of_companions >= 1) {
        this.no_of_companions -= 1
      }
    },
    searchProfileCompanion(index) {
      if (
        this.id_no_companion[index] == null &&
        this.mobile_companion[index] == null
      ) {
        this.more_search_dialog[index] = false
        this.more_isSnackbarVisible = true
      } else {
        this.getSearchguestAction({
          id_no: this.id_no_companion[index],
          mobile: this.mobile_companion[index],
        })
      }
    },
    selectProfilecompanion(guest, index) {
      this.profile_id_companion[index] = guest.id
      this.country_id_companion[index] = guest.country
      this.first_name_companion[index] = guest.first_name
      this.last_name_companion[index] = guest.last_name
      this.id_no_companion[index] = guest.id_number
      this.mobile_companion[index] = guest.mobile
      this.more_search_dialog[index] = false
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
  unmounted(to, from, next) {
    const roomStore = useRoomStore()

    roomStore.setRoomId(null)

  },

}
</script>
<style scoped>

</style>
