<script setup>
import {
  requiredValidator
} from '@validators'
</script>

<template>
  <div>
    <VCard class="mt-6">

      <VContainer>
        <VAlert variant="tonal" color="success">
          {{ $t('Selected profile:') }} {{ guest_profile.first_name + ' ' + guest_profile.last_name }}
        </VAlert>
        <VForm ref="refForm" @submit.prevent="CreateReservation">
          <VRow class="mb-2 mt-2">
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="in_date" :label="$t('In date*')" :rules="[requiredValidator]"
                                 :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="out_date" :label="$t('Out date*')" :rules="[requiredValidator]"
                                 :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_nights" :label="$t('Number of nights*')" type="number"
                          :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <VCombobox item-color="customColorValue" v-model="roomType" :label="$t('Room type')" :items="room_types"
                         :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'" item-value="roomType"
                         :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox item-color="customColorValue" v-model="room" :label="$t('Room')"
                         :item-title="$i18n.locale === 'en' ? 'room_name_en' : 'room_name_loc'" item-value="room" readonly />
            </VCol>
            <VCol cols="12" sm="6" md="1">
              <VBtn color="primary" @click="room = ''">
                {{ $t('Clear') }}
              </VBtn>
            </VCol>
            <VCol cols="12" sm="6" md="1">
              <VDialog v-model="dialog" width="1024">
                <template #activator="{ props }">

                  <VBtn v-bind="props">
                    {{ $t('Rooms') }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol cols="12" sm="6" md="6">

                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 50%;"
                                    class="float-end " />
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VTable height="600">
                      <thead>
                      <tr>
                        <th>
                          {{ $t('Room number') }}
                        </th>
                        <th>
                          {{ $t('Room name') }}
                        </th>
                        <th>
                          {{ $t('Number of pax') }}
                        </th>
                        <th>
                          {{ $t('Floor') }}
                        </th>
                        <th>
                          {{ $t('Room type') }}
                        </th>
                        <th>
                          {{ $t('Select') }}
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="item in filterData" :key="item.id">
                        <td>{{ item.room_id }}</td>
                        <td>
                          <p v-if="$i18n.locale === 'en'">
                            {{ item.room_name_en }}
                          </p>
                          <p v-else>
                            {{ item.room_name_loc }}
                          </p>
                        </td>
                        <td>{{ item.room_max_pax }}</td>

                        <td>
                          <p v-if="$i18n.locale === 'en'">
                            {{ item.floors.floor_name }}
                          </p>
                          <p v-else>
                            {{ item.floors.floor_name_loc }}
                          </p>
                        </td>
                        <td>
                          <p v-if="$i18n.locale === 'en'">
                            {{ item.roomType.rm_type_name }}
                          </p>
                          <p v-else>
                            {{ item.roomType.rm_type_name_loc }}
                          </p>
                        </td>
                        <td>
                          <VBtn color="primary" @click="selectroom(item)">
                            <VIcon icon="mdi-check" />
                          </VBtn>
                        </td>
                      </tr>
                      </tbody>
                    </VTable>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                      {{ $t('Close') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_pax" :label="$t('Number of pax*')" type="number" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <VCombobox item-color="customColorValue" v-model="rate_code" :label="$t('Rate code')" :items="rate_codes"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="rate_code"
                         :disabled="disableratecode" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox item-color="customColorValue" v-model="meal" :disabled="disableratecode" :label="$t('Meal or Meal package')"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" readonly />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="room_rate" :label="$t('Room rate*')" type="number" :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="2">
              <VDialog v-model="reservation_days_dialog" width="1024">
                <template #activator="{ props }">
                  <VBtn v-bind="props">
                    {{ $t('Fill Reservation days') }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol cols="12" sm="6" md="9">
                        <span class="text-h5">{{ $t('Reservation days') }}</span>
                      </VCol>
                      <VCol cols="12" sm="6" md="3">
                        <VBtn @click="fill_res_days">
                          {{ $t('Fill from entered data') }}
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow v-for="(index) in no_of_nights" :key="index">
                      <VCol cols="12" sm="3" md="3">
                        <AppDateTimePicker v-model="day[index]" :value="getStartDate(index)" readonly
                                           :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                      </VCol>
                      <VCol cols="12" sm="3" md="4">
                        <VCombobox item-color="customColorValue" v-model="ratecode[index]" :label="$t('Rate code')" :items="rate_codes"
                                   :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="rate_code" />
                      </VCol>
                      <VCol cols="12" sm="3" md="4">
                        <VTextField v-model="roomrate[index]" :label="$t('Room rate*')" type="number"
                                    :rules="[requiredValidator]" />
                      </VCol>
                      <VCol cols="12" sm="3" md="1">
                        <VBtn @click="fill_rest_res_days(index)">
                          {{ $t('Fill') }}
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn @click="clear_res_days">
                      {{ $t('Clear') }}
                    </VBtn>
                    <VBtn @click="reservation_days_dialog = false">
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn @click="save_res_days">
                      {{ $t('Save') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="way_of_payment" :label="$t('Way of payment*')" :items="payments" item-title="name"
                         item-value="code" :rules="[requiredValidator]"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="company_id" :label="$t('Company')" :items="companies"
                         :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" item-value="id"  item-color="customColorValue" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="market_segment_id" :label="$t('Market Segment')" :items="segments"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="source_of_business_id" :label="$t('Source of business')" :items="sources"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="reservations_status" :label="$t('Reservation status*')" :items="reservations_statuses"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="res_status_code"
                         :rules="[requiredValidator]"  item-color="customColorValue"/>
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
          </VRow>
          <VExpansionPanels variant="inset" class="mt-5 mb-5">
            <VExpansionPanel v-for="item in 1" :key="item">
              <VExpansionPanelTitle>Companions</VExpansionPanelTitle>
              <VExpansionPanelText>
                <VRow>
                  <VCol cols="12" sm="6" md="10">
                  </VCol>
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
                                 item-title="name_loc" item-value="id"  item-color="customColorValue"/>
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
                    <VSnackbar v-model="more_isSnackbarVisible" location="top" :timeout="2000">
                      {{ $t('Please enter data') }}
                    </VSnackbar>
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
              </VExpansionPanelText>
            </VExpansionPanel>
          </VExpansionPanels>
          <VBtn class="float-end mb-4" type="submit" @click="refForm?.validate()">
            {{ $t('Submit') }}
          </VBtn>
          <VBtn class="float-end mb-4 mr-4" @click="Clear">
            {{ $t('Clear') }}
          </VBtn>
          <VBtn class="mb-4" @click="checkinfunction" :disabled="disableCheckin">
            {{ $t('check in') }}
          </VBtn>
        </VForm>
      </VContainer>
    </VCard>
  </div>
</template>

<script>
import moment from 'moment'
import { mapActions, mapStores } from 'pinia'
import Swal from 'sweetalert2'
import { useGeneralStore } from '../../../stores/GeneralStore'
import { useGuestStore } from '../../../stores/GuestStore'
import { useRatecodeStore } from '../../../stores/RatecodeStore'
import { useRoomStore } from '../../../stores/RoomStore'

export default {
  name: 'Guest',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      data: [],
      more_isSnackbarVisible: false,
      more_search_dialog: [false],
      id_no_search: [],
      mobile_search: [],
      profile_id_companion: [],
      country_id_companion: [],
      first_name_companion: [],
      last_name_companion: [],
      id_no_companion: [],
      mobile_companion: [],
      no_of_companions: 0,
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
      room_rate: 0,
      rate_code: '',
      meal: '',
      hotel_note: '',
      client_note: '',
      way_of_payment: '',
      company_id: '',
      market_segment_id: '',
      source_of_business_id: '',
      country: '',
      ref_no: '',
      first_name: '',
      last_name: '',
      mobile: '',
      phone: '',
      id_no: '',
      email: '',
      city: '',
      address: '',
      date_of_birth: '',
      gender: '',
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
    checkin() {
      return this.guestStore.checkin
    },

  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    // eslint-disable-next-line sonarjs/cognitive-complexity
    rate_code() {

      if (this.rate_code != '') {
        for (var i = 0, len = this.rate_code.room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.rate_code.room_types[i].id === this.roomType.id) {
            if (this.roomType.def_pax == 1) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax1
            }
            if (this.roomType.def_pax == 2) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax2
            }
            if (this.roomType.def_pax == 3) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax3
            }
            if (this.roomType.def_pax == 4) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax4
            }
            if (this.roomType.def_pax == 5) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax5
            }
            if (this.roomType.def_pax == 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax6
            }
            if (this.roomType.def_pax > 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.extra_pax
            }
          }

        }
        if (this.rate_code.meal != null) {
          this.meal = this.rate_code.meal
        }
        if (this.rate_code.meal_package != null) {
          this.meal = this.rate_code.meal_package
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    no_of_pax() {
      if (this.roomType != '' && this.no_of_pax != '') {
        this.disableratecode = false
      }
      else {
        this.disableratecode = true
        this.rate_code = ''
        this.room_rate = ''
      }
      if (this.rate_code != '') {
        for (var i = 0, len = this.rate_code.room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.rate_code.room_types[i].id === this.roomType.id) {
            if (this.roomType.def_pax == 1) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax1
            }
            if (this.roomType.def_pax == 2) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax2
            }
            if (this.roomType.def_pax == 3) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax3
            }
            if (this.roomType.def_pax == 4) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax4
            }
            if (this.roomType.def_pax == 5) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax5
            }
            if (this.roomType.def_pax == 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax6
            }
            if (this.roomType.def_pax > 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.extra_pax
            }
          }
        }
      }
      if (this.no_of_pax < 2) {
        this.no_of_companions = 0
      }
      else {
        this.no_of_companions = this.no_of_pax - 1
      }
    },
    roomType() {

      if (this.roomType != '' && this.no_of_pax != '') {
        this.disableratecode = false
      }
      else {
        this.disableratecode = true
        this.rate_code = ''
        this.room_rate = 0
      }
      this.no_of_pax = this.roomType.def_pax

    },
    room() {

      if (this.room != '' && this.roomType == '') {
        this.roomType = this.room.roomType
        this.all_rooms = this.rooms


      }
      if (this.room != '' && this.roomType != '') {
        this.roomType = this.room.roomType
        this.all_rooms = this.rooms

      }

      if (this.room == '' && this.roomType == '') {
        this.all_rooms = this.rooms
      }



    },
    out_date() {
      if (this.no_of_nights != '') {
        var inn = moment(this.in_date)
        var out = moment(this.out_date)
        this.no_of_nights = out.diff(inn, 'days')
      }

    },
    in_date() {
      if (this.no_of_nights != '') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, 'days')
        this.out_date = inn.format("YYYY-MM-DD")
      }
      if (this.in_date == '') {
        this.in_date = moment().format("YYYY-MM-DD")
      }
    },
    no_of_nights() {
      if (this.no_of_nights != '') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, 'days')
        this.out_date = inn.format("YYYY-MM-DD")
      }
      if (this.no_of_nights == '') {
        this.no_of_nights = 1
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    response() {
      if (this.response != null) {
        this.insertAlert()
        if ((this.response.res_status == 'CNF' || this.response.res_status == 'NCF') &&
          this.response.in_date == moment().format("YYYY-MM-DD")) {
          this.disableCheckin = false

        }
        else {
          this.disableCheckin = true
          if (this.checkin) {
            this.$router.push('/reservations')
          }
        }
        this.Clear()
        this.response = null
        for (let index = 1; index <= this.no_of_companions; index++) {
          this.data.push({
            guest_id: this.response.id,
            profile_id: this.profile_id_companion[index],
            country_id: this.country_id_companion[index] != '' ? this.country_id_companion[index].id : null,
            first_name: this.first_name_companion[index],
            last_name: this.last_name_companion[index],
            id_no: this.id_no_companion[index],
            mobile: this.mobile_companion[index],
            //birth_date: 0,
          })
        }
        if (this.data != '') {
          this.CreateGuestMoreNamesAction({
            data: this.data,
          })
        }

      }
    },
    reservations_statuses() {
      if (this.reservations_statuses != '') {
        this.Clear()
      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRatecodeStore, ['getRateCodesAction']),
    ...mapActions(useRoomStore, ['getRoomTypesAction', 'getRoomsAction']),
    ...mapActions(useGuestStore, ['getSearchguestAction', 'CreateGuestAction', 'getGuestprofileAction', 'GuestCheckinAction', 'CreateGuestMoreNamesAction']),
    ...mapActions(useGeneralStore, ['getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'getReservationstatusesAction']),
    Clear() {
      this.in_date = ''
      this.out_date = ''
      this.no_of_nights = 1
      this.original_out_date = ''
      this.room = ''
      this.roomType = this.room_types[0]
      this.room_rate = 0
      this.rate_code = ''
      this.meal = ''
      this.hotel_note = ''
      this.client_note = ''
      this.way_of_payment = this.payments[0]
      this.company_id = this.companies[0]
      this.market_segment_id = this.segments[0]
      this.source_of_business_id = this.sources[0]
      this.ref_no = ''
      this.reservations_status = this.reservations_statuses[0]
    },
    CreateReservation() {
      this.CreateGuestAction({
        profile_id: this.guest_profile.id,
        in_date: this.in_date,
        out_date: this.out_date,
        original_out_date: this.out_date,
        no_of_nights: this.no_of_nights,
        room_id: this.room.id,
        room_type_id: this.roomType.id,
        no_of_pax: this.no_of_pax,
        rate_code_id: this.rate_code.id,
        meal_id: this.rate_code.meal_id,
        meal_package_id: this.rate_code.meal_package_id,
        rm_rate: this.room_rate,
        hotel_note: this.hotel_note,
        client_note: this.client_note,
        way_of_payment: this.way_of_payment.code,
        company_id: this.company_id.id,
        market_segment_id: this.market_segment_id.id,
        source_of_business_id: this.source_of_business_id.id,
        ref_no: this.ref_no,
        res_status: this.reservations_status.res_status_code,
        res_rate_days: this.formValues != null ? this.formValues : null,
      })
    },
    selectroom(room) {
      this.dialog = false
      this.room = room
    },
    insertAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'Reservation has been created',
        showConfirmButton: false,
        timer: 2000,
      })
    },
    clear_res_days() {
      this.ratecode = []
      this.roomrate = []
    },
    fill_res_days() {
      for (let i = 1; i <= this.no_of_nights; i++) {
        this.ratecode[i] = this.rate_code
        this.roomrate[i] = this.room_rate
      }
    },
    fill_rest_res_days(index) {
      for (let i = index; i <= this.no_of_nights; i++) {
        this.ratecode[i] = this.ratecode[index]
        this.roomrate[i] = this.roomrate[index]
      }
    },
    getStartDate(index) {
      const startDate = new Date(this.in_date)

      startDate.setDate(startDate.getDate() + index - 1)

      return startDate.toISOString().substr(0, 10)
    },
    save_res_days() {
      let empty_flag = 1
      this.formValues = [] // clear the array before adding new values
      for (let i = 1; i <= this.no_of_nights; i++) {
        const formValue = {
          day_date: this.getStartDate(i),
          rate_id: this.ratecode[i] != null ? this.ratecode[i].id : null,
          meal_id: this.ratecode[i] != null && this.ratecode[i].meal_id != null ? this.ratecode[i].meal_id : null,
          meal_package_id: this.ratecode[i] != null && this.ratecode[i].meal_package_id != null ? this.ratecode[i].meal_package_id : null,
          rm_rate: this.roomrate[i] != null ? this.roomrate[i] : null,
        }

        if (this.roomrate[i] != null) {
          empty_flag = 0
        }
        this.formValues.push(formValue) // add the form values to the array
      }
      if (empty_flag == 1) {
        this.formValues = null
      }

      this.reservation_days_dialog = false
    },
    checkinfunction() {
      this.GuestCheckinAction(this.response.id)
    },
    clearSeletedProfileCompanion(index) {
      this.profile_id_companion[index] = ''
      this.first_name_companion[index] = ''
      this.last_name_companion[index] = ''
      this.id_no_companion[index] = ''
      this.mobile_companion[index] = ''
      this.country_id_companion[index] = ''
    },
    AddCompanion() {
      this.no_of_companions += 1
    },
    RemoveCompanion() {
      if (this.no_of_companions >= 1) {
        this.no_of_companions -= 1
      }

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
    selectProfilecompanion(guest, index) {
      this.profile_id_companion[index] = guest.id
      this.country_id_companion[index] = guest.country
      this.first_name_companion[index] = guest.first_name
      this.last_name_companion[index] = guest.last_name
      this.id_no_companion[index] = guest.id_number
      this.mobile_companion[index] = guest.mobile
      this.more_search_dialog[index] = false
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getCountriesAction()
    this.getReservationstatusesAction()
    this.getGuestprofileAction(this.$route.params.profile)



    this.in_date = moment().format("YYYY-MM-DD")

  },
}
</script>

<style scoped>
.required {
  @apply before: text-red-600 before:content-["*"] before:mr-1;
}
</style>
