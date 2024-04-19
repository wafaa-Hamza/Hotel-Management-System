<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { useAbility } from "@casl/vue"

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)
const isPasswordVisible = ref(false)
</script>

<template>
  <VRow no-gutters class="auth-wrapper">
    <VCol lg="6" class="d-none d-lg-flex">
      <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg max-width="505" :src="authThemeImg" class="auth-illustration mt-16 mb-2" />
        </div>

        <VImg :src="authThemeMask" class="auth-footer-mask" />
      </div>
    </VCol>

    <VCol cols="12" lg="6" class="d-flex align-center justify-center">
      <VCard flat width="450" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <div style="text-align: center;">
            <VNodeRenderer :nodes="themeConfig.app.logo" class="mb-6 d-inline" />
            <h2 style="display: inline;">
              {{ $t('MASA') }}
            </h2>
          </div>
          <h5 class="text-h5 font-weight-semibold mb-1 mt-5">
            {{ $t('Welcome to') }} {{ themeConfig.app.title }}! 👋🏻
          </h5>
          <p class="mt-6">
            {{ $t('Please login') }}
          </p>

          <VForm @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <VTextField v-model="userid" :label="$t('Email OR Phone')" :type="type" />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField v-model="password" :label="$t('Password')" :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible" />
                <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox v-model="rememberMe" label="Remember me" />

                </div>

                <VBtn block type="submit">
                  {{ $t('Login') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<script>
import router from "@/router"
import axios from "@axios"

export default {
  state: {
    accessToken: null,
    status: null,
    refreshToken: null,
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      userid: '',
      password: '',
      type: 'text',
      data: '',



    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    //this.getinfoData()

  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async  getinfoData() {
      await    axios.get(`${window.location.origin}/api/public-home-page/${1}`)
        .then(res => {
          this.info = res.data
          localStorage.setItem('hotel-details', JSON.stringify(this.info))
        })
        .catch(err => {
          console.log(err)
        })
    },
       onSubmit() {
      // this.fetchSettingData()

      let URL = ''
      if (!isNaN(this.userid)) {
        this.data = {
          phonenumber: this.userid,
          password: this.password,
        }
        URL = `api/user-login?phonenumber=${this.userid}&password=${this.password}`
      } else {
        this.data = {
          email: this.userid,
          password: this.password,
        }
        URL = `api/user-login?email=${this.userid}&password=${this.password}`
      }


      let result =  axios.post(`${window.location.origin}/api/user-login`, this.data, this.Settings)
        .then(
          result => {
            if (result.status === 200) {
              const userAbilities = [{
                action: 'manage',
                subject: 'all',
              }]
               localStorage.setItem('userAbilities', JSON.stringify(result.data.userAbilities))

              localStorage.setItem('keyinfo', JSON.stringify(result.data))

              localStorage.setItem('settings', JSON.stringify(result.data.Settings[0]))

              //localStorage.setItem('language', JSON.stringify(result.data.user.language))

              localStorage.setItem('accessToken', result.data.token)

              localStorage.setItem('userData', JSON.stringify(result.data))

              //localStorage.setItem('userAbilities', JSON.stringify(userAbilities))


              let reloadFlag = false

              router.push('/').then(() => {
                if (!reloadFlag) {
                  reloadFlag = true
                  window.location.reload()
                }
              })
            }
          })
    },
  },
}
</script>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";

.change {
  display: flex;
  justify-content: space-between;
  /* stylelint-disable-next-line order/properties-order */
  flex-wrap: wrap;
}
</style>


<route lang="yaml">
meta:
  layout: blank
</route>

