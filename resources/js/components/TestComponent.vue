<script setup>
import { useTheme } from 'vuetify'
import AnalyticsMonthlyCampaignState from '@/views/dashboards/analytics/AnalyticsMonthlyCampaignState.vue'
import AnalyticsSalesByCountries from '@/views/dashboards/analytics/AnalyticsSalesByCountries.vue'
import AnalyticsSourceVisits from '@/views/dashboards/analytics/AnalyticsSourceVisits.vue'
import AnalyticsWebsiteAnalytics from '@/views/dashboards/analytics/AnalyticsWebsiteAnalytics.vue'


import AnalyticsSupportTracker from "@/views/dashboards/analytics/AnalyticsSupportTracker.vue"
import AcademyAssignmentProgress from "@/views/apps/academy/AcademyAssignmentProgress.vue"

const vuetifyTheme = useTheme()
const currentTheme = vuetifyTheme.current.value.colors
</script>
<template>
  <VCol cols="12" sm="12" md="12">
    <VCard>
      <VCardText>
        <VRow>
          <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
            {{ $t("Please enter data") }}
          </VSnackbar>
          <VCol cols="12" sm="6" md="8">
            <VTextField v-model="id_no_search" :label="$t('National ID / Mobile number')"
                        @keydown.enter="searchProfile" clearable />
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn v-bind="props" @click="searchProfile">
              {{ $t("Search") }}
            </VBtn>
            <VDialog v-model="searchDialog" width="1024"   scroll-strategy="none">
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
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
                    <tr v-for="item in guests" :key="item.id">
                      <td>{{ item.first_name }}</td>
                      <td>{{ item.last_name }}</td>
                      <td>{{ item.id_number }}</td>
                      <td>{{ item.mobile }}</td>
                      <td>
                        <VBtn color="primary" @click="selectProfile(item)">
                          <VIcon icon="mdi-check" />
                        </VBtn>
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>
                <VCardActions>
                  <VSpacer />
                  <VBtn color="blue-darken-1" variant="text" @click="searchDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="id_no_search = null, guests = null">
              {{ $t("Clear") }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VDialog v-model="createProfileDialog" width="1048" z-index="1000" scroll-strategy="none">
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t("Create new profile") }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <span class="text-h5">{{ $t("Create Profile") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="first_name_new" :label="$t('First name')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="last_name_new" :label="$t('Last name')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="mobile_new" :label="$t('Mobile')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="email_new" :label="$t('E-mail')" />
                    </VCol>

                    <VCol cols="12" md="6" style="display: flex">
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
                      >
                        <VCombobox
                          v-model="id_no_new_compo"
                          :items="Id_Num"
                          :label="$t('National ID number')"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="8"
                        md="8"
                      >

                        <VTextField
                          v-model="id_no_new"
                          :label="$t('National ID number')"


                        />
                      </VCol>

                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VCombobox v-model="gender_new" :items="gender_data" label="gender" />
                    </VCol>
                    <VCol cols="12" sm="3" md="6">
                      <VCombobox v-model="language_new" :items="lang_data" label="language" />
                    </VCol>
                    <VCol cols="12" sm="3" md="6">
                      <VCombobox v-model="country_new" :items="countries" label="country" item-title="name"
                                 item-value="country" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="phone_new" :label="$t('Phone')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="city_new" :label="$t('City')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="address_new" :label="$t('Address')" />
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
                <VCardActions>
                  <VSpacer />
                  <VBtn color="blue-darken-1" variant="text" @click="createProfileDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                  <VBtn color="blue-darken-1" variant="text" @click="createProfile">
                    {{ $t("Submit") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VCol>
</template>

<script>
import axios from "@axios"
import { mapActions } from "pinia"
import { useExtrasStore } from "../stores/ExtrasStore"
import { useGeneralStore } from "../stores/GeneralStore"
import { useGuestStore } from "../stores/GuestStore"
import { useLedgerStore } from '../stores/LedgerStore'
import { useRatecodeStore } from "../stores/RatecodeStore"
export default {
  name: "TestComponent",
  data(){
    return{
      gender_data: ["male", "female", "no gender found"],
      years: [],
      days: [],
      month: [],

      lang_data: ["ar", "en", "fr"],


      isSnackbarVisible: false,
      id_no_search: [],
      searchDialog: false,
      createProfileDialog: false,
      country_new: null,
      language: null,
      language_new: null,
      first_name_new: null,
      last_name_new: null,
      mobile_new: null,
      phone_new: null,
      id_no_new: null,
      email_new: null,
      city_new: null,
      address_new: null,
      date_of_birth_new: null,
      gender_new: null, select_year: '',
      select_day: '',
      select_month: '',
      SettingData: null,
      timer: null,
      currentTime: this.getCurrentTime(),
      URL: window.location.origin,

      id_no_new_compo: null,
      All_id_num: null,
      Id_Num: [1, 4, 5, 6],
    }
  },
  methods:{
    YeardData() {
      const startYear = 1900;
      const endYear = 2024;
      for (let year = startYear; year <= endYear; year++) {
        this.years.push(year)
      }
    },

    MonthData() {
      const starMonth = 1;
      const endMonth = 12;
      for (let months = starMonth; months <= endMonth; months++) {
        this.month.push(months)
      }
    },

    DayData() {
      const statday = 1;
      const endday = 31;
      for (let day = statday; day <= endday; day++) {
        this.days.push(day)
      }
    },
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "selectProfileForReservation",
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getCountriesAction",
    ]),

    selectProfile(guest) {
      this.selectProfileForReservation(guest)
      this.$router.push({ name: `create-reservation` })
    },
    createProfile() {
      console.log('this.country_new',this.country_new)
      console.log('dff',this.id_no_new_compo)
      this.All_id_num = this.id_no_new_compo + this.id_no_new
      axios.post(`${window.location.origin}/api/guestProfile`,
        {
          first_name: this.first_name_new,
          last_name: this.last_name_new,
          id_no: this.All_id_num,
          mobile: this.mobile_new,
          phone: this.phone_new,
          email: this.email_new,
          address: this.address_new,
          city: this.city_new,
          gender: this.gender_new,
          language: this.language_new,
          date_of_birth: this.date_of_birth_new,
          country_id: '1',
          id_type_id: '1',
          version_no:'43'
        },
      )
        .then(res => {
          if (res.status != undefined) {
            this.createProfileDialog = false
            this.$alert("profile created successfully", true)
            this.selected_guest = res.data.data[0]
          }
        })
    },
    async searchProfile() {
      if (this.id_no_search == null) {
        this.isSnackbarVisible = true
        this.searchDialog = false
      } else {

        const response = await this.getSearchguestAction({
          id_no: this.id_no_search,
          mobile: this.id_no_search,
        })
        if (response?.status != 200) {
          this.createProfileDialog = true
        } else {
          this.searchDialog = true
        }
      }
    },
    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
  },
  beforeUnmount() {
    // Clear the timer when the component is destroyed to prevent memory leaks
    clearInterval(this.timer)
  },
  watch: {

    select_month() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      // console.log(this.date_of_birth_new)
    },
    select_day() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      // console.log(this.date_of_birth_new)
    },
    select_year() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      // console.log(this.date_of_birth_new)
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(
      useGuestStore,
      useGeneralStore,
    ),

    guests() {
      return this.guestStore.guests
    },
    companies() {
      return this.generalStore.companies
    },
    countries() {
      return this.generalStore.countries
    },
  },

  async  created() {

      await this.getCompaniesAction()
      await this.getCountriesAction()

      // console.log(localStorage.getItem('userAbilities'))
      this.YeardData()
      this.MonthData()
      this.DayData()
      const SettingData = localStorage.getItem('settings')
      if (SettingData) {
        this.SettingData = JSON.parse(SettingData)
      }
      this.timer = setInterval(() => {
        this.currentTime = this.getCurrentTime()
      }, 1000)

    },

}
</script>

<style scoped>

</style>
