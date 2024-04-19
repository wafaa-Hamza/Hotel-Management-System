<script setup>
import { requiredValidator } from "@validators"

const form = ref()
</script>

<template>
  <div>
    <Breadcrumb />
<CreateProfileComponent />

    <VCard
      class="mt-6"
      :disabled="noProfileSelected"
    >
      <VContainer>
        <VAlert
          v-show="!showprofilealert"
          variant="tonal"
          color="success"
        >
          {{ $t("Selected profile:") }}
          {{ selected_guest != null ? selected_guest.first_name + " " + selected_guest.last_name + (
            selected_guest.id_no != null || selected_guest.id_number != null ? "( Profile )" : "( Only name )") : null }}
        </VAlert>
        <VForm
          ref="refForm"
          @submit.prevent="CreateReservation"
        >
          <VRow class="mb-2 mt-2">
            <VCol
              cols="12"
              sm="6"
              md="5"
            />
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VRadioGroup
                v-model="inlineRadio"
                inline
                @keyup.enter="goNext($event.target)"
              >
                <VRadio
                  label="Monthly"
                  value="M"
                  @keyup.enter="goNext($event.target)"
                />
                <VRadio
                  label="Yearly"
                  value="Y"
                  @keyup.enter="goNext($event.target)"
                />
              </VRadioGroup>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="1"
            />
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="in_date"
                :label="$t('In date*')"
                :rules="[requiredValidator, inDateValidator]"
                @keyup.enter="goNext($event.target)"
                :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData.hotel_date).subtract(1, 'days').format('YYYY-MM-DD') }], altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VTextField
                v-model="in_date_hijri"
                :label="$t('In date hijri')"
                readonly
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VTextField
                v-model="out_date"
                :label="$t('Out date')"
                @keyup.enter="goNext($event.target)"
                readonly
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VTextField
                v-model="out_date_hijri"
                :label="$t('Out date hijri')"
                @keyup.enter="goNext($event.target)"
                readonly
              />
            </VCol>
            <VCol
              v-if="inlineRadio == 'M'"
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_months"
                :label="$t('Number of months*')"
                type="number"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              v-if="inlineRadio == 'Y'"
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_years"
                :label="$t('Number of years*')"
                type="number"
                @keyup.enter="goNext($event.target)"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VCombobox
                v-model="roomType"
                :label="$t('Room type*')"
                :items="available_roomtypes"
                :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'
                "
                :rules="[requiredValidator]"
                @update:modelValue="roomTypechanged"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="3"
              style="display: flex"
            >
              <VCombobox
                v-model="room"
                :label="$t('Room')"
                :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'
                "
                item-value="room"
                readonly
                @keyup.enter="goNext($event.target)"
              />

              <VDialog
                v-model="dialog"
                width="1024"
              >
                <template #activator="{ props }">
                  <VIcon
                    v-bind="props"
                    icon="tabler-pencil"
                    size="30"
                    color="primary"
                    style="margin: 2%;"
                  />
                  <VIcon
                    icon="tabler-trash"
                    size="30"
                    color="red"
                    style="margin: 2%;"
                    @click="room = ''"
                  />
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <span class="text-h5">{{ $t("Select Room") }}</span>
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="6"
                      >
                        <VTextField
                          v-model="search"
                          type="text"
                          :label="$t('Search')"
                          style="width: 50%;"
                          class="float-end"
                        />
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VTable height="600">
                      <thead>
                        <tr>
                          <th>
                            {{ $t("Room number") }}
                          </th>
                          <th>
                            {{ $t("Room name") }}
                          </th>
                          <th>
                            {{ $t("Number of pax") }}
                          </th>
                          <th>
                            {{ $t("Floor") }}
                          </th>
                          <th>
                            {{ $t("Room type") }}
                          </th>
                          <th>
                            {{ $t("Select") }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="item in search != null ? filterData : rooms"
                          :key="item.id"
                        >
                          <td>{{ item.id }}</td>
                          <td>
                            <p v-if="$i18n.locale === 'en'">
                              {{ item.rm_name_en }}
                            </p>
                            <p v-else>
                              {{ item.rm_name_loc }}
                            </p>
                          </td>
                          <td>{{ item.rm_max_pax }}</td>

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
                              {{ item.room_type.rm_type_name }}
                            </p>
                            <p v-else>
                              {{ item.room_type.rm_type_name_loc }}
                            </p>
                          </td>
                          <td>
                            <VBtn
                              color="primary"
                              @click="selectroom(item)"
                            >
                              <VIcon icon="mdi-check" />
                            </VBtn>
                          </td>
                        </tr>
                      </tbody>
                    </VTable>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="dialog = false"
                    >
                      {{ $t("Close") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_pax"
                :label="$t('Number of pax*')"
                type="number"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="room_rate"
                :label="$t('Room rate*')"
                type="number"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VTextField
                v-model="total_rate"
                :label="$t('Total Rate')"
                @keyup.enter="goNext($event.target)"
                readonly
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="purpose_of_visit"
                :label="$t('Purpose of visit')"
                :items="purposeOfVisitList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="customer_type"
                :label="$t('Customer type')"
                :items="customerTypeList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="way_of_payment"
                :label="$t('Way of payment*')"
                :items="payments"
                item-title="name"
                item-value="code"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="company_id"
                :label="$t('Company')"
                :items="companies"
                :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'
                "
                @keyup.enter="goNext($event.target)"
                item-value="id"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="market_segment_id"
                :label="$t('Market Segment')"
                :items="segments"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                item-value="id"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="source_of_business_id"
                :label="$t('Source of business')"
                :items="sources"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                item-value="id"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="reservations_status"
                :label="$t('Reservation status*')"
                :items="reservations_statuses"
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                item-value="res_status_code"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VTextField
                v-model="ref_no"
                :label="$t('Reference number')"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VTextField
                v-model="vat_no"
                :label="$t('VAT number')"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VTextField
                v-model="company_name"
                :label="$t('Company name')"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VTextField
                v-model="inv_address"
                :label="$t('INV address')"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="vip"
                :label="$t('VIP')"
                :items="vips"
                clearable
                @keyup.enter="goNext($event.target)"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VTextarea
                v-model="hotel_note"
                :label="$t('Hotel note')"

                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VTextarea
                v-model="client_note"
                :label="$t('Client note')"

                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="12"
            >
              {{ $t('Attachments') }}<span style="color: red;"> ( {{ $t('Only 3 files allowed') }} )</span>
              <VFileInput
                v-model:files="files"
                show-size
                multiple
                label="File input"
                clearable
                @change="onFileChange"
              />
            </VCol>
          </VRow>
          <VExpansionPanels
            variant="inset"
            class="mt-5 mb-5"
          >
            <VExpansionPanel
              v-for="item in 1"
              :key="item"
            >
              <VExpansionPanelTitle>{{ $t("Companions") }}</VExpansionPanelTitle>

              <VExpansionPanelText>
                <VRow>
                  <VCol
                    cols="12"
                    sm="6"
                    md="10"
                  />
                  <VCol
                    cols="12"
                    sm="6"
                    md="1"
                  >
                    <VBtn
                      color="primary"
                      class="float-right"
                      @click="RemoveCompanion"
                    >
                      {{ $t("Remove") }}
                    </VBtn>
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="1"
                  >
                    <VBtn
                      color="primary"
                      class="float-right"
                      @click="AddCompanion"
                    >
                      {{ $t("Add") }}
                    </VBtn>
                  </VCol>
                </VRow>
                <div class="mb-5 mt-2">
                  <VRow
                    v-for="index in no_of_companions"
                    :key="index"
                  >
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VTextField
                        v-model="profile_id_companion[index]"
                        :label="$t('Profile id')"
                        readonly
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VCombobox
                        v-model="country_id_companion[index]"
                        :label="$t('Country')"
                        :items="countries"
                        item-title="name_loc"
                        item-value="id"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VTextField
                        v-model="first_name_companion[index]"
                        :label="$t('First name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VTextField
                        v-model="last_name_companion[index]"
                        :label="$t('Last name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VTextField
                        v-model="id_no_companion[index]"
                        :label="$t('National ID')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VTextField
                        v-model="mobile_companion[index]"
                        :label="$t('Mobile')"
                      />
                    </VCol>
                    <VSnackbar
                      v-model="more_isSnackbarVisible"
                      location="top"
                      :timeout="2000"
                    >
                      {{ $t("Please enter data") }}
                    </VSnackbar>
                    <VCol
                      cols="12"
                      sm="6"
                      md="10"
                    />
                    <VCol
                      cols="12"
                      sm="6"
                      md="1"
                    >
                      <VDialog
                        v-model="more_search_dialog[index]"
                        width="1024"
                      >
                        <template #activator="{ props }">
                          <VBtn
                            v-bind="props"
                            @click="searchProfileCompanion(index)"
                          >
                            {{ $t("Search") }}
                          </VBtn>
                        </template>
                        <VCard>
                          <VCardTitle>
                            <VRow>
                              <VCol
                                cols="12"
                                sm="6"
                                md="6"
                              >
                                <span class="text-h5">{{
                                  $t("Select Profile")
                                }}</span>
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
                                <tr
                                  v-for="item in guests"
                                  :key="item.id"
                                >
                                  <td>{{ item.first_name }}</td>
                                  <td>{{ item.last_name }}</td>
                                  <td>{{ item.id_number }}</td>
                                  <td>{{ item.mobile }}</td>
                                  <td>
                                    <VBtn
                                      color="primary"
                                      @click="
                                        selectProfilecompanion(item, index)
                                      "
                                    >
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
                    <VCol
                      cols="12"
                      sm="6"
                      md="1"
                    >
                      <VBtn
                        color="primary"
                        @click="clearSeletedProfileCompanion(index)"
                      >
                        {{ $t("Clear") }}
                      </VBtn>
                    </VCol>
                  </VRow>
                </div>
              </VExpansionPanelText>
            </VExpansionPanel>
          </VExpansionPanels>
          <VBtn
            class="mb-4"
            :disabled="disableCheckin"
            @click="checkinfunction"
          >
            {{ $t("check in") }}
          </VBtn>
          <VDialog
            v-model="extra_dialog"
            width="1300"
          >
            <template #activator="{ props }">
              <VBtn
                v-if="extras_show"
                v-bind="props"
                class="mb-4"
                style="left: 35%;"
              >
                {{ $t("Extras") }}
              </VBtn>
            </template>
            <VCard>
              <VCardTitle>
                <VRow>
                  <VCol
                    cols="12"
                    sm="6"
                    md="9"
                  >
                    <span>{{ $t("Extras") }}</span>
                  </VCol>
                </VRow>
              </VCardTitle>
              <VCardText>
                <div class="mb-5 mt-2">
                  <VRow>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VCombobox
                        v-model="selected_extra"
                        :label="$t('Selected extras')"
                        :items="extras"
                        :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                        clearable
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="4"
                    >
                      <VTextField
                        v-model="extra_amount"
                        :label="$t('Amount')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VBtn @click="addExtraForGuest">
                        {{ $t("Add extra") }}
                      </VBtn>
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="12"
                    >
                      <VTable height="200">
                        <thead>
                          <tr>
                            <th>
                              {{ $t("Name") }}
                            </th>
                            <th>
                              {{ $t("Amount") }}
                            </th>
                            <th>
                              {{ $t("Delete") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="item in guest_extras"
                            :key="item.id"
                          >
                            <td v-if="$i18n.locale === 'en'">
                              {{ item.extra_bed_meal.name }}
                            </td>
                            <td v-if="$i18n.locale !== 'en'">
                              {{ item.extra_bed_meal.name_loc }}
                            </td>
                            <td>{{ item.amount }}</td>
                            <td>
                              <VBtn
                                color="red"
                                style="background: red;color: white;"
                                @click="deleteExtraGuest(item.id)"
                              >
                                <VIcon
                                  icon="mdi-delete"
                                  style="font-size: 135%;"
                                />
                              </VBtn>
                            </td>
                          </tr>
                        </tbody>
                      </VTable>
                    </VCol>
                  </VRow>
                </div>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn @click="extra_dialog = false">
                  {{ $t("Close") }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
          <VBtn
            class="float-end mb-4"
            type="submit"
            @click="refForm?.validate()"
          >
            {{ $t("Submit") }}
          </VBtn>
          <VBtn
            class="float-end mb-4 mr-4 ml-4"
            @click="Clear"
          >
            {{ $t("Clear") }}
          </VBtn>
        </VForm>
      </VContainer>
    </VCard>

  </div>
</template>

<script>
import axios from "@axios"
import moment from "moment"
import { mapActions, mapStores } from "pinia"
import Swal from "sweetalert2"
import { useExtrasStore } from "../stores/ExtrasStore"
import { useGeneralStore } from "../stores/GeneralStore"
import { useGuestStore } from "../stores/GuestStore"
import { useRatecodeStore } from "../stores/RatecodeStore"
import { useRoomStore } from "../stores/RoomStore"

export default {
  name: "Guest",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      URL: window.location.origin,
      purpose_of_visit: null,
      customer_type: null,
      purposeOfVisitList: [
        { id: 1, nameE: 'Tourism', nameA: 'السياحة' },
        { id: 2, nameE: 'Family or Friends', nameA: 'زيارة الأقارب والاصدقاء' },
        { id: 3, nameE: 'Religious', nameA: 'زيارة دينية' },
        { id: 4, nameE: 'Business or Work', nameA: 'عمل' },
        { id: 5, nameE: 'Sports', nameA: 'نشاط رياضي' },
        { id: 6, nameE: 'Entertainment', nameA: 'نشاط ترفيهي' },
        { id: 7, nameE: 'Other', nameA: 'اخرى' },
        { id: 8, nameE: 'Work (Royal Court)', nameA: 'موظف ديوان' },
        { id: 9, nameE: 'Quarantined guests', nameA: 'عمل نزيل حجر' },
        { id: 10, nameE: 'Ministry of Health Staff', nameA: 'موظف وزارة الصحة' },
      ],
      customerTypeList: [
        { id: 1, nameE: 'Citizen', nameA: 'مواطن' },
        { id: 2, nameE: 'GolfCitizen', nameA: 'مواطن خليجي' },
        { id: 3, nameE: 'Visitor', nameA: 'زائر' },
        { id: 4, nameE: 'Resident', nameA: 'مقيم' },
      ],
      SettingData: null,
      rooms: [],
      out_date_hijri: this.$getHijriDate(moment().add(1, 'months').format("YYYY-MM-DD")),
      in_date_hijri: this.$getHijriDate(moment().format("YYYY-MM-DD")),
      vip: null,
      vips: ["A", "B", "C"],
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      extras_show: false,
      files: [],
      no_of_months: 1,
      no_of_years: 1,
      inlineRadio: 'M',
      vat_no: null,

      total_rate: 0,
      company_name: null,
      inv_address: null,
      reservation: null,
      gender_data: ["male", "female", "no gender found"],
      lang_data: ["ar", "en", "fr"],
      country_new: null,
      language: null,
      language_new: null,
      first_name_new: null,
      last_name_new: null,
      mobile_new: null,
      phone_new: null,
      id_no_new: null,

      id_no_new_compo: null,
      All_id_num: null,
      email_new: null,
      city_new: null,
      address_new: null,
      date_of_birth_new: null,
      gender_new: null,
      createProfileDialog: false,

      Id_Num:[1,4,5,6],
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
      Id_Num_ver:[],
      version_num:null,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: false,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: null,
      original_out_date: null,
      room: null,
      roomType: null,
      no_of_pax: null,
      room_rate: 0,
      rate_code: null,
      meal: null,
      hotel_note: null,
      client_note: null,
      way_of_payment: null,
      company_id: null,
      market_segment_id: null,
      source_of_business_id: null,
      country: null,
      ref_no: null,
      first_name: '',
      last_name: '',
      mobile: null,
      phone: null,
      id_no: null,
      email: null,
      city: null,
      address: null,
      date_of_birth: null,
      gender: null,
      genders: ["male", "female", "no gender found"],
      select_year: '',
      select_day: '',
      select_month: '',
      years: [],
      days: [],
      month: [],
      payments: [
        {
          name: "cash",
          code: "CASH",
        },
        {
          name: "cards",
          code: "CARD",
        },
        {
          name: "City ledger",
          code: "CL",
        },
        {
          name: "Complimentary",
          code: "COMP",
        },
        {
          name: "House Use",
          code: "HUSU",
        },
      ],
      selected_guest: null,
      reservations_status: null,
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      idtypes: [],
      formValues: [],
      disableCheckin: true,
      res_rate_dayss: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(
      useRatecodeStore,
      useRoomStore,
      useGuestStore,
      useGeneralStore,
      useExtrasStore,
    ),
    inDateValidator() {
      return value => {
        if (!value) {
          return true // Allow if the value is empty
        }
        const selectedDate = new Date(value)

        return selectedDate >= new Date(this.SettingData.hotel_date) || 'In Date must be on or after the hotel date'
      }
    },
    filterData() {
      return this.rooms.filter(room => {
        return (
          room.rm_name_en.toLowerCase().includes(this.search) ||
          room.rm_name_loc.toLowerCase().includes(this.search)
        )
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
    available_roomtypes() {
      return this.guestStore.available_rooms
    },
    checkin() {
      return this.guestStore.checkin
    },
    extras() {
      return this.extrasStore.extras
    },
    guest_extras() {
      return this.extrasStore.guest_extras
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    no_of_pax(){
      this.no_of_companions = this.no_of_pax - 1
    },
    room_rate(){
      this.total_rate = this.room_rate*this.no_of_months
    },

    select_month() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_day() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_year() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    inlineRadio() {
      if (this.no_of_months != null && this.inlineRadio == 'M') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_months, "months")
        this.out_date = inn.format("YYYY-MM-DD")
        this.no_of_years = 1

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)


        this.no_of_nights = date1.diff(date2, 'days')
        this.out_date_hijri = this.$getHijriDate(this.out_date)

        this.room_rate = this.roomType?.monthly_rate
      }
      if (this.no_of_years != null && this.inlineRadio == 'Y') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_years, "years")
        this.out_date = inn.format("YYYY-MM-DD")

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')
        this.no_of_months = 1
        this.out_date_hijri = this.$getHijriDate(this.out_date)

        this.room_rate = this.roomType?.yearly_rate
      }
    },
    in_date() {
      if (this.inlineRadio == 'M') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_months, "months")
        this.out_date = inn.format("YYYY-MM-DD")

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')

        this.in_date_hijri = this.$getHijriDate(this.in_date)
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      else {
        var inn = moment(this.in_date)
        inn.add(this.no_of_years, "years")
        this.out_date = inn.format("YYYY-MM-DD")

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')

        this.in_date_hijri = this.$getHijriDate(this.in_date)
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      if (this.in_date == null) {
        this.in_date = moment().format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date)
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
    },
    no_of_months() {
      if (this.no_of_months != null && this.inlineRadio == 'M') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_months, "months")
        this.out_date = inn.format("YYYY-MM-DD")

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')

        this.out_date_hijri = this.$getHijriDate(this.out_date)

      }
      if (this.no_of_months == null) {
        this.no_of_months = 1
      }

      this.total_rate = this.room_rate*this.no_of_months
    },
    no_of_years() {
      if (this.no_of_years != null && this.inlineRadio == 'Y') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_years, "years")
        this.out_date = inn.format("YYYY-MM-DD")

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')

        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      if (this.no_of_years == null) {
        this.no_of_years = 1
      }
      this.total_rate = this.room_rate*this.no_of_years
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    response() {
      if (this.response != null) {
        this.extras_show = true
        this.reservation = this.response
        if (this.files.length > 0) {
          this.uploadfile(this.reservation.id)
        }
        this.insertAlert("Reservation has been created")
        if (
          (this.reservation.res_status == "CNF" ||
            this.reservation.res_status == "NCF") &&
          this.reservation.in_date == moment().format("YYYY-MM-DD")
          && this.reservation.room != null
        ) {
          this.disableCheckin = false
        } else {
          this.disableCheckin = true
        }

        this.response = null
      }
    },
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
      }
    },
    reservations_statuses() {
      if (this.reservations_statuses != null) {
        this.Clear()
      }
    },
    selected_guest() {
      if (this.selected_guest == null) {
        this.noProfileSelected = true
      }
    },
    searchDialog() {
      // eslint-disable-next-line sonarjs/no-redundant-boolean
      if (this.searchDialog == false) {
        this.guests = []
      }
    },
  },
  mounted() {
    this.YeardData()
    this.MonthData()
    this.DayData()
    this.idtypeData()
    for (let i = 1 ; i<= 30 ; i++){
      this.Id_Num_ver.push(i)

    }
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    async submitUpdateProfile() {

      await this.updateGuestProfileAction({
        'first_name': this.first_name,
        'last_name': this.last_name,
        'id_no': this.id_no,
        'mobile': this.mobile,
        'phone': this.phone,
        'email': this.email,
        'address': this.address,
        'city': this.city,
        'language': this.language,
        'date_of_birth': this.date_of_birth_new,
        'gender': this.gender,
        'country_id': this.country.id,
      },this.selected_guest.profile_no)

      this.profiledetailsDialog = false
    },
    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },

    YeardData() {
      const startYear = 1900
      const endYear = 2024
      for (let year = startYear; year <= endYear; year++) {
        this.years.push(year)
      }
    },

    MonthData() {
      const starMonth = 1
      const endMonth = 12
      for (let months = starMonth; months <= endMonth; months++) {
        this.month.push(months)
      }
    },

    DayData() {
      const statday = 1
      const endday = 31
      for (let day = statday; day <= endday; day++) {
        this.days.push(day)
      }
    },
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction"]),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      'getAvaliableRooms',
      'updateGuestProfileAction',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    roomTypechanged() {
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rooms = this.roomType.rooms
        this.no_of_pax = this.roomType.def_pax
        if (this.inlineRadio == 'M') {
          console.log(this.roomType?.monthly_rate)
          this.room_rate = this.roomType?.monthly_rate
        }
        else {
          this.room_rate = this.roomType?.yearly_rate
        }
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
        this.rooms = []
      }
      this.no_of_pax = this.roomType.def_pax
    },
    addExtraForGuest() {
      this.addGuestExtra({
        data: [{
          guest_id: this.reservation.id,
          extra_id: this.selected_extra.id,
          amount: this.extra_amount,
        }],
      })
      this.selected_extra = null
      this.extra_amount = null
    },
    deleteExtraGuest(item) {
      this.deleteGuestExtra(item, this.reservation.id)
    },
    onFileChange(event) {
      this.files = event.target.files
    },
    uploadfile() {
      if (this.files.length > 3) {
        this.DangerAlert('Only 3 files is allowed')
      }
      else {
        const formData = new FormData()
        for (let i = 0; i < this.files.length; i++) {
          formData.append('file[]', this.files[i])
        }
        formData.append('guest_id', this.reservation.id)
        axios.post(`${window.location.origin}/api/guestAttachment`, formData, {
          headers: {
            'Content-Type': 'application/*',
          },
        }).then(
          res => {
          },
        ).catch(
          err => {
            console.log(err)
          },
        )
      }
    },
    Clear() {
      this.vip = null
      this.guest_extras = []
      this.extras_show = false
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true
      this.in_date = this.SettingData.hotel_date
      this.out_date = moment(this.in_date).add(1, 'months').format("YYYY-MM-DD")
      this.original_out_date = null
      this.room = null
      this.roomType = this.available_roomtypes[0]
      this.no_of_pax = this.available_roomtypes[0].def_pax
      this.rooms = this.roomType.rooms
      this.room_rate = this.roomType?.monthly_rate
      this.hotel_note = null
      this.client_note = null
      this.way_of_payment = this.payments[0]
      this.company_id = this.companies[0]
      this.market_segment_id = this.segments[0]
      this.source_of_business_id = this.sources[0]
      this.ref_no = null
      this.reservations_status = this.reservations_statuses[0]
      this.vat_no = null
      this.company_name = null
      this.inv_address = null
      this.purpose_of_visit = null
      this.customer_type = null
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    CreateReservation() {

      this.res_rate_dayss.push({
        rm_rate: this.room_rate,
        rate_id: this.rate_code != null && this.rate_code != '' ? this.rate_code.id : null,
        meal_id: this.rate_code != null && this.rate_code != '' ? this.rate_code.meal_id != null ? this.rate_code.meal_id : null : null,
        meal_package_id: this.rate_code != null && this.rate_code != '' ? this.rate_code.meal_package_id != null ? this.rate_code.meal_package_id : null : null,
      })

      const onlyname = []
      console.log('this.reservation.id',this.reservation)

      if (this.no_of_companions > 0) {
        for (let index = 1; index <= this.no_of_companions; index++) {
          this.data.push({
            profile_id: this.profile_id_companion[index],
            country_id:
              this.country_id_companion[index] != null
                ? this.country_id_companion[index].id
                : null,
            first_name: this.first_name_companion[index],
            last_name: this.last_name_companion[index],
            id_no: this.id_no_companion[index],
            mobile: this.mobile_companion[index],
          })
        }
      }
      else {
        this.data = null
      }
      if (this.inlineRadio == 'M') {
        this.CreateGuestAction({
          profile_id: this.selected_guest.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: 1,
          room_id: this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "M",
          res_rate_days: this.res_rate_dayss,
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id
        })
      } else {
        this.CreateGuestAction({
          profile_id: this.selected_guest.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: 1,
          room_id: this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "Y",
          res_rate_days: this.res_rate_dayss,
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id

        })
      }
      this.res_rate_dayss = []
    },
    selectroom(room) {
      this.dialog = false
      this.room = room
    },
    selectProfile(guest) {
      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
    },
    showGuestProfile() {
      if (this.selected_guest == null) {
        this.isSnackbarVisible = true
        this.profiledetailsDialog = false
      } else {
        this.first_name = this.selected_guest.first_name
        this.last_name = this.selected_guest.last_name
        this.id_no = this.selected_guest.id_number
        this.gender = this.selected_guest.gender
        this.mobile = this.selected_guest.mobile
        this.phone = this.selected_guest.phone
        this.email = this.selected_guest.email
        this.city = this.selected_guest.city
        this.address = this.selected_guest.address
        this.date_of_birth = this.selected_guest.date_of_birth
        this.country = this.selected_guest.country.name
        this.language = this.selected_guest.language
      }
    },
    clearSeletedProfile() {
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true
      this.Clear()
    },
    searchProfile() {
      if (this.id_no_search == null) {
        this.isSnackbarVisible = true
        this.searchDialog = false
      } else {
        this.searchDialog = true
        this.getSearchguestAction({
          id_no: this.id_no_search,
          mobile: this.id_no_search,
        })
      }
    },
    createProfile() {
      this.All_id_num = this.id_no_new_compo.id + this.id_no_new

      if (this.id_no_new != null) {
        console.log('this.country_new',this.country_new)
        axios
          .post(
            `${window.location.origin}/api/guestProfile`,
            {
              first_name: this.first_name_new,
              last_name: this.last_name_new,
              id_no: this.id_no_new,
              mobile: this.mobile_new,
              phone: this.phone_new,
              email: this.email_new,
              address: this.address_new,
              city: this.city_new,
              gender: this.gender_new,
              language: this.language_new,
              date_of_birth: this.date_of_birth_new,
              country_id: this.country_new.id,
              id_type_id: this.id_no_new_compo.id,
              version_no:this.version_num
            },
            {
              headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("accessToken"),
              },
            },
          )
          .then(res => {
            this.createProfileDialog = false
            this.insertAlert("profile created successfully")
            this.selected_guest = res.data.data[0]
            this.showprofilealert = false
            this.noProfileSelected = false
          })
          .catch(err => {
            console.log(err)
          })
      } else {
        this.selected_guest = {
          first_name: null,
          last_name: null,
          mobile: null,
          email: null,
          id_no: null,
        }
        this.insertAlert("profile with name only is created successfully")
        this.selected_guest.first_name = this.first_name_new
        this.selected_guest.last_name = this.last_name_new
        this.selected_guest.mobile = this.mobile_new
        this.selected_guest.email = this.email_new
        this.showprofilealert = false
        this.noProfileSelected = false
        this.createProfileDialog = false
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
      })
    },
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)
      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },
    getStartDate(index) {
      const startDate = new Date(this.in_date)

      startDate.setDate(startDate.getDate() + index - 1)

      return startDate.toISOString().substr(0, 10)
    },
    checkinfunction() {
      if (this.reservation.profile.id == 1) {
        this.DangerAlert("please choose or create full profile to checkin")
      } else {
        this.GuestCheckinAction(this.reservation.id)
      }
    },
    clearSeletedProfileCompanion(index) {
      this.profile_id_companion[index] = null
      this.first_name_companion[index] = null
      this.last_name_companion[index] = null
      this.id_no_companion[index] = null
      this.mobile_companion[index] = null
      this.country_id_companion[index] = null
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
      if (
        this.id_no_companion[index] == null &&
        this.mobile_companion[index] == null
      ) {
        this.more_search_dialog[index] = false
        this.more_isSnackbarVisible = true
      } else {
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
  async created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    const response = await this.getAvaliableRooms(this.SettingData.hotel_date, moment(this.SettingData.hotel_date).add(1, 'months').format("YYYY-MM-DD"))

    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    if (this.countries.length == 0) {
      this.getCountriesAction()
    }
    this.getExtras()
    if (response.status != undefined) {
      this.getReservationstatusesAction()
    }

    this.in_date = moment().format("YYYY-MM-DD")
  },
}
</script>

<style>
.mask_input {
  padding: 8px;
  border: 1px solid slategray;
  border-radius: 10px;
  inline-size: 100%;
}

.mask_input:hover {
  border: 2px solid purple;
  transition: 0.2s;
}
@media screen and (max-width: 768px) {
  .buttons_control {
    display: grid;
    grid: 50px 50px /  150px 150px;
    justify-content: space-around;
  }
}
@media screen and (min-width: 768px) {
  .buttons_control {
    display: flex;
    justify-content: space-around;
  }
}
</style>
