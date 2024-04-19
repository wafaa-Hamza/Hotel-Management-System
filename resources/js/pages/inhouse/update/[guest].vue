<script setup>
import {
  requiredValidator
} from '@validators'
</script>

<template>
  <div>

    <VCard class="mt-6">

      <VContainer>
        <VRow>
          <VCol cols="12" sm="6" md="10">
            <VAlert variant="tonal" color="success" v-if="alert">
              {{ $t('Selected profile:') }} {{ reservation.profile.first_name + ' ' + reservation.profile.last_name }}
            </VAlert>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VDialog v-model="updateProfileDialog" width="1024">
              <template #activator="{ props }">
                <VBtn v-bind="props" class="mt-1">
                  {{ $t('Update profile data') }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="9">
                      <span class="text-h5">{{ $t('Profile') }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="firstname" label="firstname" type="text" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="lastname" label="lastname" type="text" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VSelect item-color="customColorValue" v-model="gender" :items="gender_data" label="gender" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="id_no" label="National ID" type="text" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="mobile" label="mobile" type="number" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VSelect item-color="customColorValue" v-model="language" :items="lang_data" label="language" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="phone" label="phone" type="number" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="email" label="email" type="email" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VCombobox v-model="country" :items="countries" label="country" item-title="name"
                        item-value="country" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="city" label="city" type="text" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <VTextField v-model="address" label="address" type="text" />
                    </VCol>
                    <VCol cols="12" sm="3" md="4">
                      <AppDateTimePicker v-model="date_of_birth" label="Date of birth"
                        :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                    </VCol>
                  </VRow>
                </VCardText>
                <VCardActions>
                  <VSpacer />

                  <VBtn @click="resetProfile">
                    {{ $t('reset') }}
                  </VBtn>
                  <VBtn @click="updateProfileDialog = false">
                    {{ $t('Close') }}
                  </VBtn>
                  <VBtn @click="submitUpdateProfile">
                    {{ $t('Save') }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
        </VRow>

        <VForm ref="refForm" @submit.prevent="UpdateReservation">
          <VRow class="mb-2 mt-2">
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="in_date" :label="$t('In date*')" disabled :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="out_date" :label="$t('Out date*')" disabled
                :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}" />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_nights" :label="$t('Number of nights*')" type="number" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <VCombobox v-model="roomType" :label="$t('Room type*')" :items="room_types"
                :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'" item-value="roomType"
                disabled />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <VCombobox v-model="room" :label="$t('Room')"
                :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'" disabled />
            </VCol>

            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_pax" :label="$t('Number of pax*')" type="number" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <VCombobox v-model="rate_code" :label="$t('Rate code')" :items="rate_codes"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="rate_code" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox v-model="meal" :label="$t('Meal or Meal package')"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" disabled />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="room_rate" :label="$t('Room rate*')" type="number" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="company_id" :label="$t('Company')" :items="companies"
                :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" item-value="id" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="market_segment_id" :label="$t('Market Segment')" :items="segments"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="source_of_business_id" :label="$t('Source of business')" :items="sources"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="reservations_status" :label="$t('Reservation status*')" :items="reservations_statuses"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="res_status_code" disabled />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="way_of_payment" :label="$t('Way of payment*')" :items="payments" item-title="name"
                item-value="code" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="ref_no" :label="$t('Reference number')" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="hotel_note" :label="$t('Hotel note')" auto-grow rows="3" row-height="15" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="client_note" :label="$t('Client note')" auto-grow rows="3" row-height="15" />
            </VCol>
            <VCol cols="12" sm="6" md="5"></VCol>
            <VCol cols="12" sm="6" md="2">
              <VDialog v-model="companion_dialog" width="1300">
                <template #activator="{ props }">
                  <VBtn v-bind="props">
                    {{ $t('Update Companions') }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol cols="12" sm="6" md="9">
                        <span class="text-h5">{{ $t('Companions') }}</span>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow>
                      <VCol cols="12" sm="6" md="10"></VCol>
                      <VCol cols="12" sm="6" md="1">
                        <VBtn color="primary" @click="RemoveCompanion" class="float-right">
                          {{ $t('Remove') }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="1">
                        <VBtn color="primary" @click="AddCompanion" class="float-right">
                          {{ $t('Add') }}
                        </VBtn>
                      </VCol>
                    </VRow>
                    <div class="mb-5 mt-2">
                      <VRow v-for="index in no_of_companions" :key="index">
                        <VCol cols="12" sm="6" md="2">
                          <VTextField v-model="profile_id_companion[index]" :label="$t('Profile id')" readonly />
                        </VCol>
                        <VCol cols="12" sm="6" md="2">
                          <VCombobox v-model="country_id_companion[index]" :label="$t('Country')" :items="countries"
                            item-title="name_loc" item-value="id" />
                        </VCol>
                        <VCol cols="12" sm="6" md="2">
                          <VTextField v-model="first_name_companion[index]" :label="$t('First name')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="2">
                          <VTextField v-model="last_name_companion[index]" :label="$t('Last name')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="2">
                          <VTextField v-model="id_no_companion[index]" :label="$t('National ID')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="2">
                          <VTextField v-model="mobile_companion[index]" :label="$t('Mobile')" />
                        </VCol>
                        <VCol cols="12" sm="6" md="10"></VCol>
                        <VCol cols="12" sm="6" md="1">
                          <VDialog v-model="more_search_dialog[index]" width="1024">
                            <template #activator="{ props }">
                              <VBtn v-bind="props" @click="searchProfileCompanion(index)">
                                {{ $t('Search') }}
                              </VBtn>
                            </template>
                            <VCard>
                              <VCardTitle>
                                <VRow>
                                  <VCol cols="12" sm="6" md="6">
                                    <span class="text-h5">{{ $t('Select Profile') }}</span>
                                  </VCol>
                                </VRow>
                              </VCardTitle>
                              <VCardText>
                                <VTable height="600">
                                  <thead>
                                    <tr>
                                      <th>
                                        {{ $t('First name') }}
                                      </th>
                                      <th>
                                        {{ $t('Last name') }}
                                      </th>
                                      <th>
                                        {{ $t('National ID') }}
                                      </th>
                                      <th>
                                        {{ $t('Mobile') }}
                                      </th>
                                      <th>
                                        {{ $t('Select') }}
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
                                        <VBtn color="primary" @click="selectProfilecompanion(item, index)">
                                          <VIcon icon="mdi-check" />
                                        </VBtn>
                                      </td>
                                    </tr>
                                  </tbody>
                                </VTable>
                              </VCardText>
                            </VCard>
                          </VDialog>
                        </VCol>
                        <VCol cols="12" sm="6" md="1">
                          <VBtn color="primary" @click="clearSeletedProfileCompanion(index)">
                            {{ $t('Clear') }}
                          </VBtn>
                        </VCol>
                      </VRow>
                    </div>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn @click="companion_dialog = false">
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn @click="updateCompanion">
                      {{ $t('Update') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
          </VRow>

          <VBtn class="float-end mb-4" type="submit" @click="refForm?.validate()">
            {{ $t('Update') }}
          </VBtn>
          <!--  <VBtn class="float-end mb-4 mr-4" @click="Clear">
            {{ $t('Clear') }}
          </VBtn> -->
          <VBtn class="mb-4" @click="go_back">
            {{ $t('go back') }}
          </VBtn>
        </VForm>
      </VContainer>
    </VCard>
  </div>
</template>

<script>
import { mapActions, mapStores } from 'pinia'
import Swal from 'sweetalert2'
import { useGeneralStore } from '../../../stores/GeneralStore'
import { useGuestStore } from '../../../stores/GuestStore'
import { useRatecodeStore } from '../../../stores/RatecodeStore'
import { useRoomStore } from '../../../stores/RoomStore'
import axios from "@axios"

export default {
  name: 'Guest',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      firstname: '',
      lastname: '',
      id_no: '',
      mobile: '',
      phone: '',
      email: '',
      address: '',
      city: '',
      lang_data: ['ar', 'en', 'fr'],
      language: '',
      date_of_birth: '',
      gender_data: ['male', 'female', 'no gender found'],
      gender: '',
      country_data: ['1', '2'],
      country: '',
      updateProfileDialog: false,
      data: [],
      more_search_dialog: [false],
      profile_id_companion: [],
      country_id_companion: [],
      first_name_companion: [],
      last_name_companion: [],
      id_no_companion: [],
      mobile_companion: [],
      no_of_companions: 0,
      companion_dialog: false,
      id_no_search: '',
      mobile_search: '',
      search: '',
      dialog: false,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: true,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: '',
      out_date: '',
      no_of_nights: 1,
      original_out_date: '',
      room: '',
      roomType: '',
      no_of_pax: '',
      room_rate: '',
      rate_code: '',
      meal: '',
      hotel_note: '',
      client_note: '',
      way_of_payment: '',
      company_id: '',
      market_segment_id: '',
      source_of_business_id: '',
      ref_no: '',
      first_name: '',
      last_name: '',
      genders: ['male', 'female', 'no gender found'],
      payments: [
        {
          name: 'cash',
          code: 'CASH',
        },
        {
          name: 'cards',
          code: 'CARD',
        },
        {
          name: 'City ledger',
          code: 'CL',
        },
        {
          name: 'Complimentary',
          code: 'COMP',
        },
        {
          name: 'House Use',
          code: 'HUSU',
        },

      ],
      selected_guest: '',
      reservations_status: '',
      alert: false,
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useRatecodeStore, useRoomStore, useGuestStore, useGeneralStore),
    filterData() {
      return this.rooms.filter(room => {
        return room.room_name_en.toLowerCase().includes(this.search) ||
          room.room_name_loc.toLowerCase().includes(this.search) ||
          room.roomType.rm_type_name.toLowerCase().includes(this.search)
      })

    },
    companion_updated() {
      return this.guestStore.companion_updated
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
    rooms() {
      return this.roomStore.rooms
    },
    rate_code_id() {
      return this.ratecodeStore.rate_code_id
    },
    room_types() {
      return this.roomStore.room_types
    },
    all_rooms() {
      return this.rooms
    },
    hasErrors() {
      return this.errors !== null
    },
    errors() {
      return this.generalStore.errors
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
    guest_profile() {
      return this.guestStore.guest_profile
    },
    reservation() {
      return this.guestStore.reservation
    },
    checkin() {
      return this.guestStore.checkin
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    reservation() {
      if (this.reservation != '') {
        this.alert = true
        this.in_date = this.reservation.in_date
        this.out_date = this.reservation.out_date
        this.no_of_nights = this.reservation.no_of_nights
        this.no_of_pax = this.reservation.no_of_pax
        this.original_out_date = this.reservation.original_out_date
        this.room = this.reservation.room
        this.roomType = this.reservation.roomType
        this.room_rate = this.reservation.rm_rate
        this.rate_code = this.reservation.rate_code
        this.hotel_note = this.reservation.hotel_note
        this.client_note = this.reservation.client_note
        this.market_segment_id = this.reservation.market_segment
        this.source_of_business_id = this.reservation.source_of_business
        this.meal = this.reservation.rate_code != null ? this.reservation.rate_code.meal_package != null ? this.reservation.rate_code.meal_package : this.reservation.rate_code.meal : null
        for (let index = 0; index < this.payments.length; index++) {
          if (this.payments[index].code === this.reservation.way_of_payment) {
            this.way_of_payment = this.payments[index]
          }
        }
        for (let index = 0; index < this.reservations_statuses.length; index++) {
          if (this.reservations_statuses[index].res_status_code === this.reservation.res_status) {
            this.reservations_status = this.reservations_statuses[index]
          }
        }
        this.company_id = this.reservation.company
        this.ref_no = this.reservation.ref_no
        this.no_of_companions = this.reservation.moreName.length
        for (let index = 1; index <= this.reservation.moreName.length; index++) {

          this.profile_id_companion[index] = this.reservation.moreName[index - 1].profile_id
          this.country_id_companion[index] = this.reservation.moreName[index - 1].country
          this.first_name_companion[index] = this.reservation.moreName[index - 1].first_name
          this.last_name_companion[index] = this.reservation.moreName[index - 1].last_name
          this.id_no_companion[index] = this.reservation.moreName[index - 1].id_no
          this.mobile_companion[index] = this.reservation.moreName[index - 1].mobile
        }


      }
    },
    response() {
      if (this.response != null) {
        this.insertAlert('Guest data has been updated')
        this.response = null
      }
    },
    companion_updated() {
      if (this.companion_updated) {
        this.insertAlert('Companions has been updated')
        this.getReservationAction(this.$route.params.guest)
        this.companion_updated = false
        this.companion_dialog = false
      }
    },
    updateProfileDialog() {
      if (this.updateProfileDialog) {
        this.firstname = this.reservation.profile.first_name
        this.lastname = this.reservation.profile.last_name
        this.gender = this.reservation.profile.gender
        this.id_no = this.reservation.profile.id_no
        this.mobile = this.reservation.profile.mobile
        this.language = this.reservation.profile.language
        this.phone = this.reservation.profile.phone
        this.email = this.reservation.profile.email
        this.country = this.reservation.profile.country
        this.city = this.reservation.profile.city
        this.address = this.reservation.profile.address
        this.date_of_birth = this.reservation.profile.date_of_birth
        if (this.countries == '') {
          this.getCountriesAction()
        }
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    go_back() {
      this.$router.go(-1)
    },
    ...mapActions(useRatecodeStore, ['getRateCodesAction']),
    ...mapActions(useRoomStore, ['getRoomTypesAction', 'getRoomsAction']),
    ...mapActions(useGuestStore, ['updateGuestProfileAction', 'getSearchguestAction', 'CreateGuestAction', 'getGuestprofileAction', 'getReservationAction', 'UpdateReservationAction', 'GuestCheckinAction', 'CreateGuestMoreNamesAction', 'updateGuestMoreNamesAction']),
    ...mapActions(useGeneralStore, ['getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'getReservationstatusesAction']),
    submitUpdateProfile() {
      this.updateGuestProfileAction({
        'first_name': this.firstname,
        'last_name': this.lastname,
        'id_no': this.id_no,
        'mobile': this.mobile,
        'phone': this.phone,
        'email': this.email,
        'address': this.address,
        'city': this.city,
        'language': this.language,
        'date_of_birth': this.date_of_birth,
        'gender': this.gender,
        'country_id': this.country.id,
      }, this.reservation.profile.Profile_no)
      this.getReservationAction(this.$route.params.guest)
      this.updateProfileDialog = false
    },

    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },
    Clear() {

      this.hotel_note = ''
      this.client_note = ''
      this.way_of_payment = this.payments[0]
      this.ref_no = ''
    },
    UpdateReservation() {
      this.UpdateReservationAction(this.reservation.id, {
        hotel_note: this.hotel_note,
        client_note: this.client_note,
        way_of_payment: this.way_of_payment.code,
        ref_no: this.ref_no,
      })
    },
    selectroom(room) {
      this.dialog = false
      this.room = room
      this.roomType = this.room.roomType
    },
    insertAlert(message) {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    searchProfileCompanion(index) {
      if (this.id_no_companion[index] == '' && this.mobile_companion[index] == '') {

        this.more_search_dialog[index] = false
        this.more_isSnackbarVisible = true
      }
      else {
        this.getSearchguestAction({
          id_no: this.id_no_companion[index],
          mobile: this.mobile_companion[index],
        })
      }
    },
    AddCompanion() {
      this.no_of_companions += 1
    },
    RemoveCompanion() {
      if (this.no_of_companions >= 1) {
        this.no_of_companions -= 1
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
    clearSeletedProfileCompanion(index) {
      this.profile_id_companion[index] = ''
      this.first_name_companion[index] = ''
      this.last_name_companion[index] = ''
      this.id_no_companion[index] = ''
      this.mobile_companion[index] = ''
      this.country_id_companion[index] = ''
    },
    updateCompanion() {
      for (let index = 1; index <= this.no_of_companions; index++) {
        this.data.push({
          guest_id: this.$route.params.guest,
          profile_id: this.profile_id_companion[index],
          country_id: this.country_id_companion[index] != '' ? this.country_id_companion[index].id : null,
          first_name: this.first_name_companion[index],
          last_name: this.last_name_companion[index],
          id_no: this.id_no_companion[index],
          mobile: this.mobile_companion[index],
        })
      }
      if (this.no_of_companions < 1) {
        this.data.push({})
      }

      this.updateGuestMoreNamesAction(
        { data: this.data }, this.reservation.id)

      this.data = []
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getReservationstatusesAction()
    this.getReservationAction(this.$route.params.guest)
  },
}
</script>

<style scoped>
.required {
  @apply before: text-red-600 before:content-["*"] before:mr-1;
}
</style>
