<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import {
  requiredValidator,
} from '@validators'

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)
const isPasswordVisible = ref(false)

const rememberMe = ref(false)
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VRow no-gutters class="auth-wrapper">

      <VCol>
        <VImg :src="authThemeMask" class="auth-footer-mask" />

      </VCol>

      <VCol cols="12" lg="4" class="d-flex align-center justify-center">
        <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
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
              {{ $t('Please sign-up to your account and start the adventure') }}
            </p>
          </VCardText>
          <VCardText>
            <VForm @submit.prevent="handleSumbit">
              <VRow>
                <!-- first name -->
                <VCol cols="12">
                  <VTextField v-model="firstname" :label="$t('first name')" type="text" :rules="[requiredValidator]" />
                </VCol>

                <!-- last name -->
                <VCol cols="12">
                  <VTextField v-model="lastname" :label="$t('last name')" type="text" :rules="[requiredValidator]" />
                </VCol>

                <!--    id   -->


                <!--    roles   -->

                <VCol>
                  <VSelect item-color="customColorValue" v-model="role" :items="Role_type" item-title="role type" item-value="id" label="role type"
                           :rules="[requiredValidator]" />
                </VCol>

                <!-- number -->
                <VCol cols="12">
                  <VTextField v-model="phonenumber" :label="$t('phonenumber')" type="number" :rules="[requiredValidator]" />
                </VCol>



                <!-- email -->
                <VCol cols="12">
                  <VTextField v-model="email" :rules="[requiredValidator]" :label="$t('Email')" type="email" />
                </VCol>

                <!-- password -->
                <VCol cols="12">
                  <VTextField v-model="password" label="Password" :rules="[requiredValidator]"
                              :type="isPasswordVisible ? 'text' : 'password'"
                              :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                              @click:append-inner="isPasswordVisible = !isPasswordVisible" />


                  <VBtn block type="submit" class="mt-5">
                    {{ $t('Create new user') }}
                  </VBtn>
                </VCol>
              </VRow>
            </VForm>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      firstname: null,
      lastname: null,
      email: null,
      phonenumber: null,
      role: null,
      lang_id: 1,
      password: null,
      Role_type: ['User', 'Owner'],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    handleSumbit() {
      const data = {
        firstname: this.firstname,
        lastname: this.lastname,
        email: this.email,
        phonenumber: this.phonenumber,
        language_id: this.lang_id,
        role: this.role,
        password: this.password,
      }

      console.log(data)
      axios.post(`${window.location.origin}/api/user`, data)
        .then(
          res => {
            if (res.status === 200) {
              this.insertAlert()
            }
          },
        ).catch(
        err => {
          console.log(err)
        },
      )
    },
    insertAlert() {
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
        title: 'User Created successfully',
        color: 'green',
        timer: 5000,
      })
    },
  },
}
</script>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
layout: blank
action: read
subject: Auth
redirectIfLoggedIn: true
</route>
