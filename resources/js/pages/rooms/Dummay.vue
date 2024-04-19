<script setup>
import { requiredValidator } from "@validators"

const refForm = ref()
const formprofile = ref()
</script>

<template>
  <div>
    <Breadcrumb />

    <VBtn
      style="float: right"
      @click="createProfileDialog=true"
    >
      {{ $t("Create new profile") }}
    </VBtn>
    <VCol
      cols="7"
      sm="10"
      md="10"
    >
      <VForm ref="formprofile">
        <VRow>
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
            </VDialog>
          </VCol>
        </VRow>
      </VForm>
    </VCol>


    <br><br>
    <VCard style="padding: 2%">
      <VForm
        ref="refForm"
        @submit.prevent="StoreDummy"
      >
        <VRow class="mb-2">
          <VTextField
            v-model="test"
            :label="$t('test')"
            @keyup.enter="goNext($event.target)"
          />
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <AppDateTimePicker
              v-model="in_date"
              :label="$t('In date*')"
              :rules="[requiredValidator]"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <VTextField
              v-model="in_date_hijri"
              :label="$t('In date hijri')"
              readonly
              @keyup.enter="goNext($event.target)"
            />
          </VCol>

          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <AppDateTimePicker
              v-model="out_date"
              :label="$t('Out date*')"
              :rules="[requiredValidator, outDateValidator]"
              :config="configOutDate"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <VTextField
              v-model="out_date_hijri"
              :label="$t('Out date hijri')"
              readonly
              @keyup.enter="goNext($event.target)"
            />
          </VCol>

          <VCol
            cols="12"
            sm="3"
            md="2"
          >
            <VTextField
              v-model="no_of_nights"
              :label="$t('Number of nights*')"
              type="number"
              :rules="[requiredValidator]"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="2"
            style="display: flex;justify-content: space-between;"
          >
            <VCombobox
              v-model="RoomDummy_id"
              :items="AllDummy"
              :label="$t('Room')"
              :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'"
              item-value="id"
              style="width: 40%;"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>


          <VCol
            cols="12"
            sm="6"
            md="2"
          >
            <VCombobox
              v-model="company_id"
              :label="$t('Company')"
              :items="company"
              :item-title="$i18n.locale == 'en' ? 'company_name' : 'company_name_loc'
              "
              item-value="id"
              :rules="[requiredValidator]"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>


          <VCol
            cols="12"
            sm="6"
            md="2"
          >
            <VTextField
              v-model="vat_no"
              :label="$t('VAT number')"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <VTextField
              v-model="company_name"
              :label="$t('Company name')"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>


          <VCol
            cols="12"
            sm="6"
            md="12"
          >
            <VTextarea
              v-model="hotel_note"
              :label="$t('Hotel note')"
              auto-grow
              rows="3"
              row-height="15"
              @keyup.enter="goNext($event.target)"
            />
          </VCol>
        </VRow>
        <VBtn
          class="float-end mb-4"
          type="submit"
        >
          {{ $t("Submit") }}
        </VBtn>
      </VForm>
    </VCard>
  </div>
</template>

<script>
import axios from "axios"
import { mapActions, mapStores } from "pinia"
import { useGuestStore } from "@/stores/GuestStore"
import moment from "moment/moment"
import { useGeneralStore } from "@/stores/GeneralStore"
// import { ref, onMounted } from 'vue'
// import { openDB } from 'idb'
// eslint-disable-next-line import/extensions

export default {
  name: "Dummay",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      createProfileDialog: false,

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
      Id_Num_ver: [],
      date_of_birth_new: null,
      gender_data: ["male", "female", "no gender found"],

      gender_new: null,
      configOutDate: {
        minDate: this.in_date, altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',
      },

      purpose_of_visit: null,
      customer_type: null,
      rooms: [],
      out_date_hijri: null,


      AllDummy: [],

      description: null,

      vat_no: null,
      company_name: null,
      reservation: null,
      in_date_hijri: null,
      test: '',
      in_date: null,
      out_date: null,
      RoomDummy_id: null,
      no_of_nights: 1,
      original_out_date: null,
      room: null,
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
      version_num: null,
      company: [],
      profileId: null,
      isLoading: false,
      idtypes: [],

      URL: window.location.origin,
    }
  },
  mounted() {
    this.GetDummy()
    this.idtypeData()
    this.YeardData()
    this.MonthData()
    this.DayData()
    this.getCompany()
    for (let i = 1 ; i<= 30 ; i++){
      this.Id_Num_ver.push(i)

    }
    if (this.countries.length == 0) {
      this.getCountriesAction()
    }


  },
  // eslint-disable-next-line vue/component-api-style,vue/order-in-components
  computed: {

    ...mapStores(
      useGuestStore,
      useGeneralStore,
    ),

    countries() {
      return this.generalStore.countries
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


    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    // async storeDataInIndexedDB() {
    //
    //   const dbPromise = new Promise((resolve, reject) => {
    //     const request = indexedDB.open('masa', 1)
    //
    //     request.onerror = event => {
    //       console.error('Error opening IndexedDB:', event.target.error)
    //       reject(event.target.error)
    //     }
    //
    //     request.onsuccess = event => {
    //       const db = event.target.result
    //
    //       resolve(db)
    //     }
    //
    //     request.onupgradeneeded = event => {
    //       const db = event.target.result
    //       const store = db.createObjectStore('myStore', { keyPath: 'hotel_note' })
    //     }
    //   })
    //
    //   try {
    //     const db = await dbPromise
    //
    //     const transaction = db.transaction(['myStore'], 'readwrite')
    //     const store = transaction.objectStore('myStore')
    //     const data = { test: this.test, company_name: this.company_name, vat_no: this.vat_no, hotel_note: this.hotel_note }
    //
    //     store.add(data)
    //     store.refresh(data)
    //     console.log('Data successfully stored in IndexedDB')
    //
    //     setInterval(() => {
    //       store.clear()
    //       console.log('IndexedDB refreshed successfully')
    //     }, 5000)
    //   } catch (error) {
    //     console.error('Error storing data in IndexedDB:', error)
    //   }
    // },



    ...mapActions(useGuestStore, ["CreateGuestDummay",
      "GuestCheckinAction"]),
    ...mapActions(useGeneralStore, [
      "getCountriesAction",
    ]),
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
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)

      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },

    async getCompany() {
      await axios
        .get(`${this.URL}/api/companyProfile`)
        .then(res => {
          this.company = res.data
        })
    },
    GetDummy(){
      axios.get(`${this.URL}/api/get-aval-dummy-room`).then(res =>(this.AllDummy = res.data.data ))
    },
    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },

    createProfile() {
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

          this.createProfileDialog = false
          this.insertAlert("profile created successfully")
          this.selected_guest = res.data.data[0]
          console.log('done')
          this.profileId = res.data.data[0].id
        })
        .catch(err => {
        })
    },
    StoreDummy(){
      if (!this.RoomDummy_id) {

        this.CreateGuestDummay({
          in_date: this.in_date,
          out_date: this.out_date,
          no_of_nights: this.no_of_nights,

          room_type_id: 1,
          company_id: this.company_id.id,
          hotel_note: this.hotel_note,
          company_name: this.company_name,
          vat_no: this.vat_no,
          original_out_date: this.out_date,
          way_of_payment: 'way',
          rm_rate: 1,
          no_of_pax: 1,
          profile_id: this.profileId,
          market_segment_id: 1,
          source_of_business_id: 1,
          res_status: 'CNF',
          customerType: 1,
          purposeOfVisit: 1,
          is_dummy: 1,

        }).then(checkInId => {
          console.log('Received CheckInId in StoreDummy:', checkInId)
          this.GuestCheckinAction(checkInId)
        })
      } else {

        this.CreateGuestDummay({
          is_dummy: 1,
          in_date: this.in_date,
          out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.RoomDummy_id.id,
          room_type_id: this.RoomDummy_id.room_type.id,
          company_id: this.company_id.id,
          hotel_note: this.hotel_note,
          company_name: this.company_name,
          vat_no: this.vat_no,
          original_out_date: this.out_date,
          way_of_payment: 'way',
          rm_rate: 0,
          no_of_pax: 0,
          profile_id: this.profileId,
          market_segment_id: 1,
          source_of_business_id: 1,
          res_status: 'CNF',
          customerType: 1,
          purposeOfVisit: 1,
        })
        console.log('res')
      }
    },

  },
}
</script>

<style scoped>

</style>
