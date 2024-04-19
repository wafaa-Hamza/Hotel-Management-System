<script setup>
import navItems from '@/navigation/vertical'
import { useThemeConfig } from '@core/composable/useThemeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import Shortcuts from '@/layouts/components/NavbarShortcuts.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import CashierCloser from '@/layouts/components/cashier-closer-menu.vue'

// @layouts plugin
import NavBarI18n from "@/layouts/components/NavBarI18n.vue"
import { VerticalNavLayout } from '@layouts'
import FindFolio from '../../pages/find-folio.vue'


const { appRouteTransition, isLessThanOverlayNavBreakpoint } = useThemeConfig()
const { width: windowWidth } = useWindowSize()
</script>

<template>
  <VerticalNavLayout :nav-items="navItems" >
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }" >
      <div class="d-flex h-100 align-center d-print-none">
        <IconBtn v-if="isLessThanOverlayNavBreakpoint(windowWidth)" id="vertical-nav-toggle-btn" class="ms-n3"
          @click="toggleVerticalOverlayNavActive(true)">
          <VIcon size="26" icon="tabler-menu-2" />
        </IconBtn>
        <NavBarI18n />
        <VSpacer />

        <VSpacer />
        <h4>{{ $i18n.locale === 'en' ? localSetting.name : localSetting.name_loc }}</h4>
        <VSpacer />
        <h4 style="float: inline-start;"> {{ localSetting?.hotel_date }}
          {{ currentTime }} </h4>
        <VSpacer />


        <Shortcuts />
        <Notifications />
        <VSwitch v-model="Swaps" :label="Swaps" true-value="Open Cashier" false-value="Close Cashier" hide-details
          @click="openCashier" />
        <CashierCloser />
        <UserProfile />
        <FindFolio />
      </div>

      <div>
        <VAlert v-if="showAlert" type="error">
          {{ errorMessage }}
        </VAlert>
      </div>
    </template>

    <!-- 👉 Pages -->
    <RouterView v-slot="{ Component }">
      <Transition :name="appRouteTransition" mode="out-in">
        <Component :is="Component" />
      </Transition>
    </RouterView>

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <!-- <TheCustomizer /> -->
  </VerticalNavLayout>
</template>

<script>
import axios from "@axios"
import { f } from "vue3-treeselect"
export default {
  data() {
    return {
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      showAlert: false, // Start with showAlert as false
      errorMessage: null,
      URL: window.location.origin,
      no_of_opens: '',
      CurrentStatus: null,
      currentTime: this.getCurrentTime(),
      Swaps: null,
      switch2: ref(localStorage.getItem("Cashier") || 'Close Cashier'),
      // details: localStorage.getItem('hotel-details') != null ? JSON.parse(localStorage.getItem('hotel-details')) : null,
    }
  },

  mounted() {
    this.Status()
    const storedData = localStorage.getItem('userData')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.user
    }
    const SettingData = localStorage.getItem('keyinfo')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
  },

  methods: {
    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
    Status() {
      const keyinfoString = localStorage.getItem("keyinfo");
      const keyinfoObject = JSON.parse(keyinfoString)

      this.CurrentStatus = keyinfoObject.Cashier_Status
      if (this.CurrentStatus === 0) {
        this.Swaps = 'Close Cashier'
      } else {
        this.Swaps = 'Open Cashier'
      }
    },
    setupAxiosInterceptor() {
      // Set up a local Axios interceptor for error handling
      this.$axios.interceptors.response.use(
        response => response,
        error => {
          console.log('Axios error interceptor triggered.')
          this.handleAxiosError(error)

          return Promise.reject(error)
        },
      )
    },

    handleAxiosError(error) {
      console.log('Handling Axios error:', error)

      // Extract the error message from the Axios error
      const errorMessage = error.response?.data?.error || 'An error occurred'

      // Display the error message in the alert
      this.showAlert = true
      this.errorMessage = errorMessage

      // Hide the alert after 3 seconds (optional)
      setTimeout(() => {
        this.showAlert = false
      }, 3000)
    },
    insertAlert() {

      this.$swal.fire({
        icon: 'info',
        title: 'Do you want to Open Cashier',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Opend`,
      }).then(result => {

        if (result.isDenied) {
          axios.post(`${this.URL}/api/cashier-open`)

          const Toast = this.$swal.mixin({
            toast: true,
            position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
              toast.addEventListener('mouseenter', this.$swal.stopTimer)
              toast.addEventListener('mouseleave', this.$swal.resumeTimer)
            },
          })

          Toast.fire({
            icon: 'success',
            title: `Cashier Opend successfully`,
            color: 'green',
            timer: 10000,
          })

          const keyinfoString = localStorage.getItem("keyinfo");


          if (keyinfoString) {
            const keyinfoObject = JSON.parse(keyinfoString);


            keyinfoObject.Cashier_Status = 1;


            localStorage.setItem("keyinfo", JSON.stringify(keyinfoObject));
          } else {
            console.log("keyinfo data not found in localStorage.");
          }

        }
      })

    },

    DeleteAlert() {

      this.$swal.fire({
        icon: 'error',
        title: 'Do you want to Closed Cashier',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Closed`,
      }).then(result => {

        if (result.isDenied) {
          axios.post(`${this.URL}/api/cashier-close`)

          const Toast = this.$swal.mixin({
            toast: true,
            position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
            showConfirmButton: f,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
              toast.addEventListener('mouseenter', this.$swal.stopTimer)
              toast.addEventListener('mouseleave', this.$swal.resumeTimer)
            },
          })

          Toast.fire({
            icon: 'error',
            title: `Cashier Closed successfully`,
            color: 'red',
            timer: 10000,
          })
          const keyinfoString = localStorage.getItem("keyinfo");


          if (keyinfoString) {
            const keyinfoObject = JSON.parse(keyinfoString);


            keyinfoObject.Cashier_Status = 0;


            localStorage.setItem("keyinfo", JSON.stringify(keyinfoObject));
          } else {
            console.log("keyinfo data not found in localStorage.");
          }
        }
      })

    },


    openCashier() {
      if (this.Swaps === 'Close Cashier') {
        this.insertAlert()

      } else if (this.Swaps === 'Open Cashier') {
        this.DeleteAlert()

      }

    },


  }
}
</script>
<style scoped>
.v-selection-control__input input {
  position: absolute;
  display: none;
  block-size: 50%;
  inline-size: 50%;
  inset-block-start: 9px;
  inset-inline-start: 9px;
  opacity: 1;
}

</style>

