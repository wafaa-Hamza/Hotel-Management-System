<template>
  <div>



    <VCard height="700" v-if="findDialog">
      <VDialog v-model="findDialog" width="1024">
        <VCard>
          <VCardTitle>
            <span class="text-h5">{{ $t('Find') }}</span>
          </VCardTitle>

          <VCardText>
            <VContainer>
              <VRow>
                <VCol cols="12" sm="6" md="6">
                  <VTextField v-model="room" :label="$t('Room')" :disabled="disable_room" />
                </VCol>
                <VCol cols="12" sm="6" md="6">
                  <VTextField v-model="folio_no" :label="$t('Folio No')" :disabled="disable_folio" />
                </VCol>
                <VCol cols="12" sm="6" md="12">
                  <VTextField v-model="name" :label="$t('Guest Name')" readonly />
                </VCol>
              </VRow>
            </VContainer>
          </VCardText>

          <VCardActions>
            <VSpacer />
            <VBtn color="blue-darken-1" variant="text" @click="findDialog = false">
              {{ $t('Close') }}
            </VBtn>
            <VBtn color="blue-darken-1" variant="text" @click="findGuest">
              {{ $t('Find') }}
            </VBtn>
            <VBtn color="blue-darken-1" variant="text" @click="goToFilio">
              {{ $t('Go') }}
            </VBtn>
          </VCardActions>

        </VCard>
      </VDialog>
    </VCard>
  </div>
</template>
    

<script>
import axios from '@axios'
import Swal from "sweetalert2"

export default {
  name: "findFolio",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      name: null,
      room: null,
      findDialog: false,
      folio_no: null,
      disable_folio: false,
      disable_room: false,
      result: null,
      room_result: null,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    room() {
      if (this.room != null && this.room != '') {
        this.disable_folio = true
      }
      else {
        this.disable_folio = false
      }

    },
    folio_no() {
      if (this.folio_no != null && this.folio_no != '') {

        this.disable_room = true
      }
      else {
        this.disable_room = false
      }

    },
    findDialog() {
      if (this.findDialog == false) {
        this.room = null
        this.folio_no = null
        this.name = null
        this.result = null
        this.room_result = null
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    window.addEventListener('keydown', this.handleKeyDown)
  },
  // eslint-disable-next-line vue/component-api-style
  unmounted() {
    window.removeEventListener('keydown', this.handleKeyDown);
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    handleKeyDown(event) {
      if (event.key === 'F1' && event.ctrlKey) {
        this.findDialog = true
      }
    },
    goToFilio() {
      if (this.name != null) {
        this.findDialog = false
        this.$router.push({ name: `selectedFolio-folio`, params: { folio: this.folio_no } })
      }
    },
    findGuest() {
      if (this.room != null && this.room != '') {
        axios.post(`${window.location.origin}/api/find-folio`, {
          rm_name: this.room,
        })
          .then(res => {

            this.room_result = res.data.Room
            this.folio_no = this.room_result.guest_id
            this.name = this.room_result.profileFirstName + ' ' + this.room_result.profileLastName
            this.room = this.$i18n.locale == 'en' ? this.room_result.rm_name : this.room_result.rm_name_loc
          })
          .catch(err => {
            alert('This room is vaccant or not found')
          })
      }
      else {
        axios.post(`${window.location.origin}/api/find-folio`, {
          guest_id: this.folio_no,
        })
          .then(res => {
            this.result = res.data.Guest
            this.folio_no = this.result.guest_id
            this.name = this.result.profileFirstName + ' ' + this.result.profileLastName
            this.room = this.$i18n.locale == 'en' ? this.result.rm_name : this.result.rm_name_loc
          })
          .catch(err => {
            alert('Not found')
          })
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
      });
    },

  },
}
</script>

<style scoped>
.link-container {
  display: flex;
  flex-direction: column;
}

.link-container>div {
  flex-basis: 50%;
}
</style>
