<script setup>
import { useThemeConfig } from '@core/composable/useThemeConfig'
import { hexToRgb } from '@layouts/utils'
import { useTheme } from 'vuetify'


const {
  syncInitialLoaderTheme,
  syncVuetifyThemeWithTheme: syncConfigThemeWithVuetifyTheme,
  isAppRtl,
} = useThemeConfig()

const { global } = useTheme()

// ℹ️ Sync current theme with initial loader theme
syncInitialLoaderTheme()
syncConfigThemeWithVuetifyTheme()
</script>

<template>
  <div>
    <VLocaleProvider :rtl="isAppRtl">
      <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
      <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
        <RouterView />

      </VApp>
    </VLocaleProvider>
  </div>
</template>

<script>
import router from "@/router"

import axios from "axios"


export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      URL: window.location.origin,
      MyUrl: '',
      status: '',
    }
  },

  mounted() {

     this.loadPage()
    this.GetUrls()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    GetUrls() {
      axios
        .get(`${this.URL}/api/check-tenant`)

        .then(res => (this.MyUrl = res,console.log('res',res)))   .catch(error => {
          console.log('err',error)
          if (error.response && error.response.status === 404) {

            this.$router.push({ name: 'ErrorHeaders' })


          } else {
            this.status = error.response.status
            console.error("Request failed:", error.message)
          }

        })


    },
    loadPage() {

      const token = localStorage.getItem('accessToken')

      if (!token) {
        this.GetUrls()
        if (this.status === 404) {
          this.$router.push({ name: 'error404' })
        } else {
          router.push('/login')
        }

      }

    },
  },

}
</script>

<style lang="scss">
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: rgb(var(--v-theme-primary)) !important;
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgb(var(--v-theme-primary)) !important;
}
.dialog-bottom-transition-enter-active,
.dialog-bottom-transition-leave-active {
  transition: transform .2s ease-in-out;
}
</style>
