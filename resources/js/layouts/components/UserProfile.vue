<script setup>

</script>

<template>
  <VBadge dot location="bottom right" offset-x="3" offset-y="3" bordered color="success">
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      {{ firLett }}{{ lastLett }}

      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success">
                  <VAvatar color="primary" variant="tonal" />
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ userData.firstname + ' ' + userData.lastname }}
            </VListItemTitle>
            <!--            <VListItemSubtitle>Admin</VListItemSubtitle> -->
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 re-pass -->
          <VListItem link>
            <template #prepend>
              <VIcon class="me-2" icon="tabler-user" size="22" />
            </template>

            <VListItemTitle>
              <VDialog v-model="isDialogVisible" width="800">
                <!-- Activator -->
                <template #activator="{ props }">
                  <VListItemTitle v-bind="props">
                    Re-Pass
                  </VListItemTitle>
                </template>

                <!-- Dialog close btn -->
                <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

                <!-- Dialog Content -->
                <VForm>
                  <VCard title="Privacy Policy">
                    <VCardText>
                      <VCol cols="12" sm="12" md="12">
                        <VTextField v-model="Current_Password" label="Current_Password" persistent-placeholder type="text"
                          @keyup.enter="goNext($event.target)" />
                      </VCol>
                      <VCol cols="12" sm="12" md="12">
                        <VTextField v-model="New_Password" label="New_Password" persistent-placeholder type="text"
                          @keyup.enter="goNext($event.target)" @keyup="checkPasswordMatch" />
                      </VCol>
                      <VCol cols="12" sm="12" md="12">
                        <VTextField ref="confirmPassInput" v-model="confirm_pass" label="confirm_Password"
                          persistent-placeholder type="text" :style="{ 'color': passwordsMatch ? '' : 'red' }" />
                      </VCol>
                    </VCardText>

                    <VCardText class="d-flex justify-end">
                      <VBtn @click="ChangePass">
                        I accept
                      </VBtn>
                    </VCardText>
                  </VCard>
                </VForm>
              </VDialog>
            </VListItemTitle>
          </VListItem>

          <!-- 👉 Profile -->
          <!-- <VListItem>
            <template>
              <NavBarI18n />
            </template>
          </VListItem> -->

          <!-- 👉 Settings -->
          <!-- <VListItem link>
            <template #prepend>
              <VIcon class="me-2" icon="tabler-settings" size="22" />
            </template>

            <VListItemTitle>Settings</VListItemTitle>
          </VListItem> -->

          <!-- 👉 Pricing -->
          <!-- <VListItem link>
            <template #prepend>
              <VIcon class="me-2" icon="tabler-currency-dollar" size="22" />
            </template>

            <VListItemTitle>Pricing</VListItemTitle>
          </VListItem> -->

          <!-- 👉 FAQ -->
          <!-- <VListItem link>
            <template #prepend>
              <VIcon class="me-2" icon="tabler-help" size="22" />
            </template>

            <VListItemTitle>FAQ</VListItemTitle>
          </VListItem> -->

          <!-- Divider -->
          <VDivider class="my-2" />
          <!-- 👉 Logout -->
          <VListItem to="/login" @click="removeLocal">
            <template #prepend>
              <VIcon class="me-2" icon="tabler-logout" size="22" />
            </template>

            Logout
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>

<script>
import LogoutButt from "@/auth_butt/LogoutButt.vue";
import axios from "@axios";
import Swal from "sweetalert2";

export default {
  components: {
    LogoutButt,
  },
  data() {
    return {
      Current_Password: '',
      New_Password: '',
      confirm_pass: '',
      active: '',
      URL: window.location.origin,
      Data: '',
      isDialogVisible: false,
      passwordsMatch: false,
      check: false,
      myData: [],
      userData: '',
      firLett: '',
      lastLett: '',

    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    // eslint-disable-next-line vue/no-dupe-keys
    passwordsMatch() {
      return this.New_Password === this.confirm_pass
    },
  },
  mounted() {
    const storedData = localStorage.getItem('userData')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.user
      this.firLett = this.userData.firstname.slice(0, 1).toUpperCase()
      this.lastLett = this.userData.lastname.slice(0, 1).toUpperCase()
    }
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {


    insertAlert(message) {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    checkPasswordMatch() {
      if (!this.passwordsMatch) {
        this.$nextTick(() => {
          this.$refs.confirmPassInput.validate(true)
        })


      }
    },

    ChangePass() {
      if (this.New_Password == this.confirm_pass) {
        axios.post(`${this.URL}/api/change-password-by-user`, {
          CurrentPassword: this.Current_Password,
          NewPassword: this.New_Password,
        }).then(res => (this.Data = res.data.message, this.insertAlert(this.Data)))
        this.isDialogVisible = false
        this.passwordsMatch = true
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
    removeLocal() {
      axios.post(`${this.URL}/api/user-logout`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
      localStorage.clear()
    },
  },
}
</script>

<style scoped>
.red-border {
  border-color: red;
}
</style>
