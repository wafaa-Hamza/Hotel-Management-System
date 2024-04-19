<script setup>
import { useTheme } from 'vuetify'
const { global } = useTheme()
import { staticPrimaryColor } from '../plugins/vuetify/theme'

import AnalyticsMonthlyCampaignState from '@/views/dashboards/analytics/AnalyticsMonthlyCampaignState.vue'
import AnalyticsSalesByCountries from '@/views/dashboards/analytics/AnalyticsSalesByCountries.vue'
import AnalyticsSourceVisits from '@/views/dashboards/analytics/AnalyticsSourceVisits.vue'
import AnalyticsWebsiteAnalytics from '@/views/dashboards/analytics/AnalyticsWebsiteAnalytics.vue'


import logoPath from '@images/MasaSoftw1.png'

import AnalyticsSupportTracker from "@/views/dashboards/analytics/AnalyticsSupportTracker.vue"

const vuetifyTheme = useTheme()
const currentTheme = vuetifyTheme.current.value.colors


const currentActiveTab = ref('New')
</script>

<template>
  <VRow class="match-height" >
    <VCol
      cols="12"
      md="12"
      sm="12">
      <VCard>
        <VRow>
          <VCol cols="7">

            <VCardText class="pb-0 px-0 position-relative h-100">
              <VImg
                :src="logoPath"
                height="140"
                class="w-50 position-absolute"
                style="bottom: 0;"
              />
            </VCardText>
          </VCol>

          <VCol cols="5">
            <VCardText>
              <h6 class="text-h4">
                {{ $i18n.locale === 'en' ? SettingData.name : SettingData?.name_loc }}
              </h6>

              <h5 class="text-h5 my-3">
                {{ SettingData?.hotel_date }}  {{ currentTime }}
              </h5>

            </VCardText>
          </VCol>
        </VRow>
      </VCard>
    </VCol>
    <VCol
      cols="12"
      md="12"
      sm="12"
    >
      <TestComponent />
    </VCol>
    <VCol
        cols="12"
        md="6"
        sm="12"
      >
        <VCard title="Reservations">
          <template #append>
            <div class="mt-n4 me-n2">
              <a>
                <VIcon
                  size="22"
                  icon="tabler-arrow-narrow-right"
                />
                Go To Reservation</a>
            </div>
          </template>

          <VTabs
            v-model="currentActiveTab"
            grow
          >
            <VTab>
              Arrival
            </VTab>
            <VTab>
              In House
            </VTab>
            <VTab>
              Due out
            </VTab>
          </VTabs>
          <VDivider />

          <VCardText>
            <VWindow
              v-model="currentActiveTab"
              class="disable-tab-transition"
            >
              <VWindowItem>
                <VTable
                  id="element-to-pdf"
                  height="300"
                >
                  <thead>
                  <tr>

                    <th>
                      {{ $t('room name') }}
                    </th>

                    <th>
                      {{ $t('Guest Name') }}
                    </th>
                    <th>
                      {{ $t('Guest mobile') }}
                    </th>

                  </tr>
                  </thead>
                  <tbody>
                  <tr  v-if="Guest_Arrival.length === 0">
                    <td
                      colspan="12"
                      style="text-align: center;"
                    >
                      {{ $t('No data available') }}
                    </td>
                  </tr>

                  <tr
                    v-for="(item, index) in Guest_Arrival"
                    class="hover-pointer"
                    @click="selectReservation(item)"

                  >


                    <td>
                      {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                      : null
                      }}
                    </td>

                    <td>
                      {{ item.profile? item.profile.first_name:'' }}
                    </td>
                    <td>
                      {{ item.profile?item.profile.mobile:'' }}
                    </td>

                  </tr>
                  </tbody>
                </VTable>
              </VWindowItem>
              <VWindowItem>
                <VTable
                  id="element-to-pdf"
                  height="300"
                >
                  <thead>
                  <tr>

                    <th>
                      {{ $t('room name') }}
                    </th>

                    <th>
                      {{ $t('Guest Name') }}
                    </th>
                    <th>
                      {{ $t('Guest mobile') }}
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr  v-if="Guest_Inhouse.length === 0">
                    <td
                      colspan="12"
                      style="text-align: center;"
                    >
                      {{ $t('No data available') }}
                    </td>
                  </tr>

                  <tr
                    v-for="(item, index) in Guest_Inhouse"

                    class="hover-pointer"
                    @click="selectFolio(item)"

                  >


                    <td>
                      <!--                            {{ $i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc }} -->
                      {{ item.room != null ? ($i18n.locale === 'en' ? item.room.rm_name_en : item.room.rm_name_loc)
                      : null
                      }}
                    </td>

                    <td>
                      {{ item.profile? item.profile.first_name:'' }}
                    </td>
                    <td>
                      {{ item.profile?item.profile.mobile:'' }}
                    </td>

                  </tr>
                  </tbody>
                </VTable>
              </VWindowItem>
              <VWindowItem>
                <VTable
                  id="element-to-pdf"
                  height="300"
                >
                  <thead>
                  <tr>

                    <th>
                      {{ $t('room name') }}
                    </th>

                    <th>
                      {{ $t('Guest Name') }}
                    </th>
                    <th>
                      {{ $t('Guest mobile') }}
                    </th>

                  </tr>
                  </thead>
                  <tbody >
                  <tr v-if="Guest_Due_out.length === 0">
                    <td
                      colspan="12"
                      style="text-align: center;"
                    >
                      {{ $t('No data available') }}
                    </td>
                  </tr>
                  <tr
                    v-for="(item, index) in Guest_Due_out"
                    class="hover-pointer"
                    @click="selectFolio(item)"
                  >

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

                  </tr>
                  </tbody>
                </VTable>
              </VWindowItem>
            </VWindow>
          </VCardText>
        </VCard>
      </VCol>
    <VCol
        cols="12"
        md="6"
        sm="12"

      >
        <VCard  style="width: 100%;display: flex;height: 45%">
          <VRow>
            <VCol

              cols="12"
              sm="4"
              class="border rounded  mt-3 pa-5"
            >
              <div class="d-flex align-center justify-space-between">
                <h3>
                  Arrivals Expected
                </h3>
                <VAvatar
                  rounded
                  size="30"
                  color="primary"
                  variant="tonal"
                  class="me-2"
                >
                  <VIcon icon="tabler-arrow-narrow-right" />
                </VAvatar>
              </div>

              <h6 class="text-h6 my-3">
                {{ DashboardData.expected_arrival }}
              </h6>
              <div v-if="DashboardData">
                <VProgressLinear
                  :model-value="DashboardData.expected_arrival_checked"
                  color="primary"
                  height="8"
                  rounded
                  rounded-bar
                />
              </div>

              <div class="d-flex align-center justify-space-around mt-8">
                <h6 class="text-base font-weight-medium">
                  checked_in
                </h6>
                {{ DashboardData.expected_arrival_checked }} / {{ DashboardData.expected_arrival_total }}
              </div>
            </VCol>
            <VCol

              cols="12"
              sm="4"
              class="border rounded  mt-3 pa-5"
            >
              <div class="d-flex align-center justify-space-between">
                <h3>
                  In-house
                </h3>
                <VAvatar
                  rounded
                  size="30"
                  color="primary"
                  variant="tonal"
                  class="me-2"
                >
                  <VIcon icon="tabler-arrow-narrow-right" />
                </VAvatar>
              </div>

              <h6 class="text-h6 my-3">
                {{ DashboardData.inhouse_count }}
              </h6>

            </VCol>
            <VCol

              cols="12"
              sm="4"
              class="border rounded  mt-3 pa-5"
            >
              <div class="d-flex align-center justify-space-between">
                <h3>
                  Departures Expected
                </h3>
                <VAvatar
                  rounded
                  size="30"
                  color="primary"
                  variant="tonal"
                  class="me-2"
                >
                  <VIcon icon="tabler-arrow-narrow-right" />
                </VAvatar>
              </div>

              <h6 class="text-h6 my-3">
                {{ DashboardData.expected_out }}
              </h6>
              <div v-if="DashboardData">
                <VProgressLinear
                  :model-value="DashboardData.expected_out_out"
                  color="primary"
                  height="8"
                  rounded
                  rounded-bar
                />
              </div>

              <div class="d-flex align-center justify-space-around mt-8">
                <h6 class="text-base font-weight-medium">
                  checked_out
                </h6>
                {{ DashboardData.expected_out_out }} / {{ DashboardData.expected_out_total }}
              </div>
            </VCol>
          </VRow>
        </VCard>
        <br>
        <VCard  style="width: 100%;height: 45%">
             <VCard class="pa-6">
              <h2 class="ml-5">
                Guests
              </h2>
               <br>
              <VRow>
                <VCol
                  cols="12"
                  sm="4"
                  class="mt-3 pa-2"
                >
                  <div class="d-flex align-center justify-space-between">
                    <h4>
                      Arrivals
                    </h4>
                  </div>

                  <h5 class="text-h5 my-3">
                    {{ DashboardData.pax_arrival }}
                  </h5>
                </VCol>
                <VCol
                  cols="12"
                  sm="4"
                  class="mt-3 pa-2"
                >
                  <div class="d-flex align-center justify-space-between">
                    <h4>
                      In-house
                    </h4>
                  </div>

                  <h5 class="text-h5 my-3">
                    {{ DashboardData.pax_inhouse }}
                  </h5>
                </VCol>
                <VCol
                  cols="12"
                  sm="4"
                  class="mt-3 pa-2"
                >
                  <div class="d-flex align-center justify-space-between">
                    <h4>
                      Departures
                    </h4>
                  </div>

                  <h5 class="text-h5 my-3">
                    {{ DashboardData.pax_out }}
                  </h5>
                </VCol>
              </VRow>
            </VCard>
         </VCard>

      </VCol>
    <VCol
      style="height: 50%;"
      cols="12"
      md="12"
    >




      <VCol
        cols="12"
        md="12"
        style="height: 100%;float: right"
      >
        <AnalyticsSupportTracker />
      </VCol>
    </VCol>

  </VRow>
</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data(){
    return{
      URL: window.location.origin,

      DashboardData: [],
      Guest_Inhouse: [],
      Guest_Arrival: [],
      Guest_Due_out: [],
      SettingData: null,
      timer: null,
      currentTime: this.getCurrentTime(),
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    // const SettingData = localStorage.getItem('settings')
    // if (SettingData) {
    //   this.SettingData = JSON.parse(SettingData)
    // }
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
    this.Inhouse()
    this.Arrival()
    this.Due_out()
    this.getDashboardData()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async Inhouse() {

      await axios.post(`${this.URL}/api/guest_filter`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          is_checked_in: 1,
        },
      }).then(res => {
        this.Guest_Inhouse = res.data.data.Guests_Filter
      })
    },
    async Arrival() {
      await axios.post(`${this.URL}/api/guest_filter`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          is_checked_in: 0,
          is_reservation: 1,
          "in_date+date": this.SettingData.hotel_date,
        },
      }).then(res => {
        this.Guest_Arrival = res.data.data.Guests_Filter

      })
    },
    async Due_out() {
      await axios.post(`${this.URL}/api/guest_filter`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          checked_out: 0,
          "out_date+date": this.SettingData.hotel_date,
        },
      }).then(res => {
        this.Guest_Due_out = res.data.data.Guests_Filter

      })
    },

    selectFolio(reservation) {

      this.$router.push({
        name: `selectedFolio-folio`,
        params: { folio: reservation.id },
      })



    },
    selectReservation(reservation) {

      this.$router.push({
        name: `reservation-update-reservation`,
        params: { reservation: reservation.id },
      })

    },

    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
    getDashboardData() {
      axios
        .get(`${window.location.origin}/api/dashboard-calculations`, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },

        })
        .then(res => {
          this.DashboardData = res.data.Calculations
        })
    },
  },
}
</script>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";

</style>
