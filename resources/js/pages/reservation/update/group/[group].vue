<script setup>
import { requiredValidator } from "@validators"
</script>

<template>
  <div>

    <VCard>
      <VContainer>
        <VRow>
          <VCol cols="12" sm="6" md="5"></VCol>
          <VCol cols="12" sm="6" md="6">
            <VCheckbox v-model="locked" :label="$t('Reservation locked')" @update:modelValue="changeLocked"
                       :true-value="1" :false-value="0" />
          </VCol>
          <VCol cols="12" sm="6" md="4">
            <VTextField v-model="id_no_search" :label="$t('National ID')" :disabled="locked" />
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VTextField v-model="mobile_search" :label="$t('Mobile number')" :disabled="locked" />
          </VCol>
          <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
            {{ $t("Please enter data") }}
          </VSnackbar>
          <VCol cols="12" sm="6" md="1">
            <VDialog v-model="searchDialog" width="1024">
              <template #activator="{ props }">
                <VBtn v-bind="props" @click="searchProfile" :disabled="locked">
                  {{ $t("Search") }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <span class="text-h5">{{ $t("Select Profile") }}</span>
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
                    <tr v-for="item in guests" :key="item.id">
                      <td>{{ item.first_name }}</td>
                      <td>{{ item.last_name }}</td>
                      <td>{{ item.id_number }}</td>
                      <td>{{ item.mobile }}</td>
                      <td>
                        <VBtn color="primary" @click="selectProfile(item)">
                          <VIcon icon="mdi-check" />
                        </VBtn>
                      </td>
                    </tr>
                    </tbody>
                  </VTable>
                </VCardText>
                <VCardActions>
                  <VSpacer />
                  <VBtn color="blue-darken-1" variant="text" @click="searchDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VDialog v-model="profiledetailsDialog" width="1048">
              <template #activator="{ props }">
                <VBtn v-bind="props" @click="showGuestProfile" :disabled="locked">
                  {{ $t("Show") }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <span class="text-h5">{{ $t("Profile") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="first_name" :label="$t('First name')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="last_name" :label="$t('Last name')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="mobile" :label="$t('Mobile')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="email" :label="$t('E-mail')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="id_no" :label="$t('National ID number')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="gender" :label="$t('gender')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="language" label="language" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="country" label="Country" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="phone" :label="$t('Phone')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="city" :label="$t('City')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="address" :label="$t('Address')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="date_of_birth" :label="$t('Date of birth')" readonly />
                    </VCol>
                  </VRow>
                </VCardText>
                <VCardActions>
                  <VSpacer />
                  <VBtn color="blue-darken-1" variant="text" @click="profiledetailsDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          <VCol cols="12" sm="6" md="1">
            <VBtn @click="clearSeletedProfile" :disabled="locked">
              {{ $t("Remove") }}
            </VBtn>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <VDialog
              v-model="createProfileDialog"
              width="1048"
              persistent
            >
              <template #activator="{ props }">
                <VBtn
                  v-bind="props"
                  :disabled="!noProfileSelected"
                >
                  {{ $t("Create new profile") }}
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
                      <span class="text-h5">{{ $t("Create Profile") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="first_name_new"
                        :label="$t('First name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="last_name_new"
                        :label="$t('Last name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"

                    >
                      <VTextField
                        v-model="mobile_new"
                        :label="$t('Mobile')"
                      />
                    </VCol>


                    <VCol
                      cols="12"
                      sm="2"
                      md="2"
                    >
                       <VCombobox item-color="customColorValue"
                        v-model="id_no_new_compo"
                        :items="idtypes"
                        item-title="name"
                        :label="$t('National ID number')"
                        @keyup.enter="goNext($event.target)"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="4"
                      md="4"
                    >

                      <VTextField
                        v-model="id_no_new"
                        :label="$t('National ID number')"
                        @keyup.enter="goNext($event.target)"

                      />
                    </VCol>

                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VCombobox item-color="customColorValue"
                        v-model="country_new"
                        :items="countries"
                        label="country"
                        item-title="name"
                        item-value="country"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VCombobox item-color="customColorValue"
                        v-model="gender_new"
                        :items="gender_data"
                        label="gender"
                      />
                    </VCol>

                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="email_new"
                        :label="$t('E-mail')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VCombobox item-color="customColorValue"
                        v-model="language_new"
                        :items="lang_data"
                        label="language"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="4"
                      md="4"
                    >
                      <VTextField
                        v-model="city_new"
                        :label="$t('City')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="4"
                      md="6"
                    >
                      <VTextField
                        v-model="phone_new"
                        :label="$t('Phone')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="4"
                      md="6"
                    >
                      <VTextField
                        v-model="address_new"
                        :label="$t('Address')"
                      />
                    </VCol>

                    <VCol cols="12" sm="12" md="12" style="display: flex; text-align: center;">
                      <VCol cols="2" sm="2" md="2">
                        <b style="font-size: 140%;">Birthdate :</b>
                      </VCol>
                      <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                        <VCombobox item-color="customColorValue" v-model="select_day" :items="days" :label="$t('days')" style="width: 100%;"
                                   clearable />
                      </VCol>
                      <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                        <VCombobox item-color="customColorValue" v-model="select_month" :items="month" :label="$t('months')" style="width: 100%;"
                                   clearable />
                      </VCol>
                      <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                        <VCombobox item-color="customColorValue" v-model="select_year" :items="years" :label="$t('Years')" style="width: 100%;"
                                   clearable />
                      </VCol>
                    </VCol>
                  </VRow>
                </VCardText>
                <VCardActions style="display: flex;justify-content: right">
                  <VSpacer />
                  <VBtn

                    color="blue-darken-1"
                    variant="text"
                    @click="createProfileDialog = false"
                  >
                    {{ $t("Close") }}
                  </VBtn>
                  <VBtn
                    color="blue-darken-1"
                    variant="text"
                    @click="createProfile"
                  >
                    {{ $t("Submit") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
        </VRow>
      </VContainer>
    </VCard>

    <VCard class="mt-6" :disabled="noProfileSelected || locked">
      <VContainer>
        <VAlert v-if="alert" variant="tonal" color="success">
          {{ $t("Selected profile:") }}
          {{ reservation != null ? reservation.profile.first_name + " " + reservation.profile.last_name + " ( Profile ) "
          : ''
          }}
        </VAlert>
        <VForm ref="refForm" @submit.prevent="CreateReservation">
          <VRow class="mb-2 mt-2">
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="in_date" :label="$t('In date*')" :rules="[requiredValidator]"
                                 @focus="master_touched = true" :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="6" md="5">
              <AppDateTimePicker v-model="out_date" :label="$t('Out date*')" :rules="[requiredValidator]"
                                 @focus="master_touched = true" :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_nights" :label="$t('Number of nights*')" type="number"
                          :rules="[requiredValidator]" class="mt-6" @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="rate_code" :label="$t('Rate code')" :items="rate_codes"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="rate_code" clearable
                         @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="meal" :label="$t('Meal or Meal package')"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" readonly @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="no_of_pax" :label="$t('Total PAX*')" type="number" :rules="[requiredValidator]"
                          @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="way_of_payment" :label="$t('Way of payment*')" :items="payments" item-title="name"
                         item-value="code" :rules="[requiredValidator]" @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="company_id" :label="$t('Company')" :items="companies" :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'
                " item-value="id" @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="market_segment_id" :label="$t('Market Segment')" :items="segments"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"
                         @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="source_of_business_id" :label="$t('Source of business')" :items="sources"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"
                         @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="reservations_status" :label="$t('Reservation status*')" :items="reservations_statuses"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="res_status_code"
                         :rules="[requiredValidator]" @focus="master_touched = true"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="ref_no" :label="$t('Reference number')" @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="vat_no" :label="$t('VAT number')" @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="company_name" :label="$t('INV Company name')" @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="inv_address" :label="$t('INV address')" @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VCombobox v-model="vip" :label="$t('VIP')" :items="vips" clearable  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="hotel_note" :label="$t('Hotel note')" auto-grow rows="3" row-height="15"
                         @focus="master_touched = true" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="client_note" :label="$t('Client note')" auto-grow rows="3" row-height="15"
                         @focus="master_touched = true" />
            </VCol>
          </VRow>
        </VForm>
      </VContainer>
    </VCard>


    <VCard class="mt-6" :disabled="noProfileSelected || locked">
      <VCardTitle>
        <VRow>
          <VCol cols="12" sm="6" md="5">
            <span>Rooms</span>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <span>Total PAX: {{ no_of_pax }}</span>
          </VCol>
          <VCol cols="12" sm="6" md="2">
            <span>Total Rooms: {{ flattenedRows }}</span>
          </VCol>
          <VCol cols="12" sm="6" md="3">
            <VBtn class="float-right" @click="moreRoomsDialog = true">
              {{ $t("Add more rooms") }}
            </VBtn>
          </VCol>
        </VRow>
      </VCardTitle>
      <VCardText class="mt-3">
        <VTable class="table-container" height="300px">
          <thead>
          <tr>
            <th>
              {{ $t("Room no") }}
            </th>
            <th>
              {{ $t("Room type") }}
            </th>
            <th>
              {{ $t("Rate code") }}
            </th>
            <th>
              {{ $t("PAX") }}
            </th>
            <th>
              {{ $t("Rate") }}
            </th>
            <th>
              {{ $t("Delete") }}
            </th>
          </tr>
          </thead>
          <tbody>
          <template v-for="(item, index) in flattenedRows" :key="index">
            <tr>
              <td style="display: flex;padding-top: 2%;">
                <AppCombobox v-model="room[index]" :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'"
                             item-value="room" style="width: 150px;" readonly />
                <div>
                  <VIcon class="ml-2 mr-2 mt-2" @click="selectRoom(index, group_room_type[index].id)"
                         @focus="setTouchedField(index)">
                    tabler-ballpen
                  </VIcon>
                  <VIcon class="mt-2" @click="removeRoom(index)" @focus="setTouchedField(index)">
                    tabler-trash
                  </VIcon>
                </div>
              </td>
              <td>
                <AppCombobox v-model="group_room_type[index]" style="width: 150px;" readonly
                             :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'" />
              </td>
              <td>
                <AppCombobox v-model="group_rate_code[index]" :items="rate_codes"
                             :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" style="width: 200px;" clearable
                             @update:modelValue="rateCodechanged(index)" />
              </td>
              <td>
                <VTextField v-model="pax[index]" type="number" style="width: 100px;" min="1"
                            @change="paxchanged(index)" />
              </td>
              <td>
                <VTextField v-model="rate[index]" type="number" style="width: 100px;" min="1"
                            @change="ratechanged(index)" />
              </td>
              <td>
                <VBtn @click="deleteRoom(index)">
                  {{ $t("Delete") }}
                </VBtn>
              </td>
            </tr>
          </template>
          </tbody>
        </VTable>
        <VDialog v-model="extra_dialog" width="1300">
          <template #activator="{ props }">
            <VBtn v-bind="props" class="mb-4" style="left: 40%;">
              {{ $t("Update Extras") }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <VRow>
                <VCol cols="12" sm="6" md="9">
                  <span>{{ $t("Extras") }}</span>
                </VCol>
              </VRow>
            </VCardTitle>
            <VCardText>
              <div class="mb-5 mt-2">
                <VRow>
                  <VCol cols="12" sm="6" md="6">
                    <VCombobox v-model="selected_extra" :label="$t('Selected extras')" :items="extras"
                               :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" clearable />
                  </VCol>
                  <VCol cols="12" sm="6" md="4">
                    <VTextField v-model="extra_amount" :label="$t('Amount')" />
                  </VCol>
                  <VCol cols="12" sm="6" md="2">
                    <VBtn @click="addExtraForGuest">
                      {{ $t("Add extra") }}
                    </VBtn>
                  </VCol>
                  <VCol cols="12" sm="6" md="12">
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
                      <tr v-for="item in guest_extras" :key="item.id">
                        <td v-if="$i18n.locale === 'en'">{{ item.extra_bed_meal.name }}</td>
                        <td v-if="$i18n.locale !== 'en'">{{ item.extra_bed_meal.name_loc }}</td>
                        <td>{{ item.amount }}</td>
                        <td>
                          <VBtn @click="deleteExtraGuest(item.id)" color="red" style="background: red;color: white;">
                            <VIcon icon="mdi-delete" style="font-size: 135%;" />
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
              <VBtn @click="updateExtras">
                {{ $t("Update") }}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
        <VBtn class="mt-3 float-right mb-3" @click="submitAllRooms" :disabled="disableSubmit">
          {{ $t("Submit") }}
        </VBtn>
      </VCardText>
    </VCard>
    <VDialog v-model="roomDialog" width="1024">
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol cols="12" sm="6" md="6">
              <span class="text-h5">{{ $t("Select Room") }}</span>
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
                {{ $t("Selected") }}
              </th>
              <th>
                {{ $t("Select") }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in filterData" :key="item.id">
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
                <VChip class="ma-2">
                  <VIcon v-if="item.selected" icon="mdi-check" color="success" />
                  <VIcon v-if="!item.selected" icon="mdi-close-octagon" color="error" />
                </VChip>
              </td>
              <td>
                <VBtn color="primary" :disabled="item.selected" @click="submitRoom(item)">
                  <VIcon icon="mdi-check" />
                </VBtn>
              </td>
            </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>
          <VBtn color="blue-darken-1" variant="text" class="float-right" @click="roomDialog = false">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="moreRoomsDialog" width="1024">
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol cols="12" sm="6" md="9">
              <span>Add more rooms</span>
            </VCol>
          </VRow>
        </VCardTitle>
        <VCardText class="mt-3">
          <VTable>
            <thead>
            <tr>
              <th>
                {{ $t("Room type") }}
              </th>
              <th>
                {{ $t("Count") }}
              </th>
              <th>
                {{ $t("Required rooms") }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in RoomTypeFilter" :key="index">
              <td>
                {{ item.rm_type_name }}
              </td>
              <td>{{ 0 }}</td>
              <td>
                <VTextField v-model="required_rooms[index]" type="number" style="width: 100px;" />
              </td>
            </tr>
            </tbody>
          </VTable>
          <VRow>

            <VCol cols="12" sm="6" md="10">
              <VBtn class="float-right mb-3 mr-3" @click="moreRoomsDialog = false">
                {{ $t("Close") }}
              </VBtn>
            </VCol>
            <VCol cols="12" sm="6" md="2">
              <VBtn class="float-right mb-3 mr-3" @click="SubmitRoomsFromRoomtypes">
                {{ $t("Submit") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>

      </VCard>
    </VDialog>
  </div>
</template>

<script>
import axios from "@axios"
import moment from "moment"
import { mapActions, mapStores } from "pinia"
import Swal from "sweetalert2"
import { useExtrasStore } from "../../../../stores/ExtrasStore"
import { useGeneralStore } from "../../../../stores/GeneralStore"
import { useGuestStore } from "../../../../stores/GuestStore"
import { useRatecodeStore } from "../../../../stores/RatecodeStore"
import { useRoomStore } from "../../../../stores/RoomStore"


export default {
  name: "Guest",
  // eslint-disable-next-line vue/component-api-style
  data() {

    return {
      vip: null,
      vips: ["A", "B", "C"],
      locked: null,
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      flattenedRows: 0,
      taken_rooms: [],
      master_touched: false,
      moreRoomsDialog: false,
      guest_id: [],
      updated: [],
      group_guest: [],
      loading: true,
      touchedIndex: null,
      rooms_array: [],
      total_rooms: null,
      changedIndexes: [],
      group_room_type: [],
      room_type_filter: null,
      selected_room_index: null,
      roomDialog: false,
      room: [],
      pax: [],
      rate: [],
      group_rate_code: [],
      selected_room_type: [],
      count: [],
      required_rooms: [],
      vat_no: "",
      company_name: "",
      inv_address: "",
      gender_data: ["male", "female", "no gender found"],
      lang_data: ["ar", "en", "fr"],
      country_new: "",
      language: "",
      language_new: "",
      first_name_new: "",
      last_name_new: "",
      mobile_new: "",
      phone_new: "",
      id_no_new: "",
      email_new: "",
      city_new: "",
      address_new: "",
      date_of_birth_new: "",
      gender_new: "",
      createProfileDialog: false,
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
      search: "",
      dialog: false,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: true,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: "",
      out_date: "",
      no_of_nights: 1,
      original_out_date: "",
      roomType: "",
      no_of_pax: "",
      room_rate: 0,
      rate_code: null,
      meal: "",
      hotel_note: "",
      client_note: "",
      way_of_payment: "",
      company_id: "",
      market_segment_id: "",
      source_of_business_id: "",
      country: "",
      ref_no: "",
      first_name: "",
      last_name: "",
      mobile: "",
      phone: "",
      id_no: "",
      email: "",
      city: "",
      address: "",
      date_of_birth: "",
      gender: "",
      genders: ["male", "female", "no gender found"],
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
      selected_guest: "",
      reservations_status: "",
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
      disableSubmit: false,
      alert: false,
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
    filterData() {
      return this.rooms.filter(room => {
        return (
          room.room_type.id === this.room_type_filter
        )
      })
    },
    RoomTypeFilter() {
      return this.room_types.filter(room_type => {
        return (
          room_type.rm_type_code !== 'GPM'
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
    reservation() {
      return this.guestStore.group_reservation
    },
    reservations_statuses() {
      return this.generalStore.reservations
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


    out_date() {
      if (!this.loading) {
        if (this.no_of_nights != "") {
          var inn = moment(this.in_date)
          var out = moment(this.out_date)
          this.no_of_nights = out.diff(inn, "days")
        }
      }

    },
    in_date() {
      if (!this.loading) {
        if (this.no_of_nights != "") {
          var inn = moment(this.in_date)
          inn.add(this.no_of_nights, "days")
          this.out_date = inn.format("YYYY-MM-DD")
        }
        if (this.in_date == "") {
          this.in_date = moment().format("YYYY-MM-DD")
        }
      }
    },
    no_of_nights() {
      if (!this.loading) {
        if (this.no_of_nights != "") {
          var inn = moment(this.in_date)
          inn.add(this.no_of_nights, "days")
          this.out_date = inn.format("YYYY-MM-DD")
        }
        if (this.no_of_nights == "") {
          this.no_of_nights = 1
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
      }
    },
    selected_guest() {
      if (this.selected_guest == "") {
        this.noProfileSelected = true
      }
    },
    searchDialog() {
      // eslint-disable-next-line sonarjs/no-redundant-boolean
      if (this.searchDialog == false) {
        this.guests = []
      }
    },
    rate: {
      handler(newRates) {
        if (!this.loading) {
          this.disableSubmit = newRates.some(rate => rate === null || rate === '' || rate < 1)
        }
      },
      deep: true,

    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    reservation() {
      if (this.reservation != null) {
        this.vip = this.reservation.vip
        this.alert = true
        this.selected_guest = null
        this.noProfileSelected = false
        this.locked = this.reservation.locked
        this.in_date = this.reservation.in_date
        this.out_date = this.reservation.out_date
        this.no_of_nights = this.reservation.no_of_nights
        this.no_of_pax = this.reservation.no_of_pax
        this.original_out_date = this.reservation.original_out_date
        this.rate_code = this.reservation.rate_code
        this.meal = this.reservation.rate_code != null ? this.reservation.rate_code.meal_id != null ? this.reservation.rate_code.meal : this.reservation.rate_code.meal_package : null
        this.vat_no = this.reservation.vat_no
        this.company_name = this.reservation.company_name
        this.inv_address = this.reservation.inv_address
        this.hotel_note = this.reservation.hotel_note
        this.client_note = this.reservation.client_note
        this.market_segment_id = this.reservation.market_segment
        this.source_of_business_id = this.reservation.source_of_business
        this.company_id = this.reservation.company
        this.ref_no = this.reservation.ref_no
        for (let index = 0; index < this.payments.length; index++) {
          if (this.payments[index].code === this.reservation.way_of_payment) {
            this.way_of_payment = this.payments[index]
          }
        }
        for (
          let index = 0;
          index < this.reservations_statuses.length;
          index++
        ) {
          if (
            this.reservations_statuses[index].res_status_code ===
            this.reservation.res_status
          ) {
            this.reservations_status = this.reservations_statuses[index]
          }
        }
        this.flattenedRows = this.reservation.guestGroup.length
        for (let index = 0; index < this.reservation.guestGroup.length; index++) {
          this.group_room_type[index] = this.reservation.guestGroup[index].roomType
          this.pax[index] = this.reservation.guestGroup[index].no_of_pax
          this.no_of_pax += this.reservation.guestGroup[index].no_of_pax
          this.group_rate_code[index] = this.reservation.guestGroup[index].rateCode != null ? this.reservation.guestGroup[index].rateCode : null
          this.rate[index] = this.reservation.guestGroup[index].rm_rate
          this.room[index] = this.reservation.guestGroup[index].room
          this.guest_id[index] = this.reservation.guestGroup[index].id
          for (let i = 0; i < this.rooms.length; i++) {
            // eslint-disable-next-line sonarjs/no-collapsible-if
            if (this.room[index] != null) {
              if (this.room[index].id == this.rooms[i].id) {
                this.rooms[i].selected = true
              }
            }

          }
        }
      }
    },
    rate_code() {
      if (!this.loading) {
        if (this.rate_code != null) {
          if (this.rate_code.meal != null) {
            this.meal = this.rate_code.meal
          }
          if (this.rate_code.meal_package != null) {
            this.meal = this.rate_code.meal_package
          }
        }
        else {
          this.meal = null
        }
      }
      this.loading = false
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ratechanged(index) {
      this.updated[index] = 1
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    rateCodechanged(index) {
      this.updated[index] = 1
      if (this.group_rate_code[index] != null && this.group_rate_code[index] != '') {
        for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.group_rate_code[index].room_types[i].id === this.group_room_type[index].id) {
            if (this.pax[index] == 1) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax1
            }
            if (this.pax[index] == 2) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax2
            }
            if (this.pax[index] == 3) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax3
            }
            if (this.pax[index] == 4) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax4
            }
            if (this.pax[index] == 5) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax5
            }
            if (this.pax[index] == 6) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax6
            }
            if (this.pax[index] > 6) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.extra_pax
            }
          }
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    paxchanged(index) {
      this.updated[index] = 1
      if (this.group_rate_code[index] != null && this.group_rate_code[index] != '') {
        for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.group_rate_code[index].room_types[i].id === this.group_room_type[index].id) {
            if (this.pax[index] == 1) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax1
            }
            if (this.pax[index] == 2) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax2
            }
            if (this.pax[index] == 3) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax3
            }
            if (this.pax[index] == 4) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax4
            }
            if (this.pax[index] == 5) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax5
            }
            if (this.pax[index] == 6) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.pax6
            }
            if (this.pax[index] > 6) {
              this.rate[index] = this.group_rate_code[index].room_types[i].pivot.extra_pax
            }
          }
        }
      }
    },
    setTouchedField(index) {
      this.updated[index] = 1
    },
    deleteRoom(index) {
      if (this.guest_id[index] != undefined) {
        this.deleteReservation(this.guest_id[index])

        this.group_room_type.splice(index, 1)
        this.updated.splice(index, 1)
        this.pax.splice(index, 1)
        this.group_rate_code.splice(index, 1)
        this.rate.splice(index, 1)
        this.room.splice(index, 1)
        this.guest_id.splice(index, 1)
        this.flattenedRows -= 1


      }
      else {
        this.group_room_type.splice(index, 1)
        this.updated.splice(index, 1)
        this.pax.splice(index, 1)
        this.group_rate_code.splice(index, 1)
        this.rate.splice(index, 1)
        this.room.splice(index, 1)
        this.guest_id.splice(index, 1)
        this.flattenedRows -= 1
      }
    },
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction"]),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      "CreateGroupReservationAction",
      'updateGroupReservation',
      'getGroupReservation',
      'deleteReservation',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    changeLocked() {
      axios
        .post(
          `/api/lockedReservision/${this.reservation.id}`,
          {
            lock: this.locked,

          },
        )
        .then(res => {
          if (this.locked) {
            this.insertAlert('Reservation Locked')
          }
          else {
            this.insertAlert('Reservation Unlocked')
          }

        })
        .catch(err => {
          console.log(err)
        })
    },
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
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
    // eslint-disable-next-line sonarjs/cognitive-complexity
    selectRoom(index, id) {
      this.room_type_filter = id
      this.roomDialog = true
      this.selected_room_index = index
    },
    removeRoom(room_index) {
      for (let index = 0; index < this.rooms.length; index++) {
        if (this.rooms[index].id === this.room[room_index].id) {
          this.rooms[index].selected = false
        }
      }
      this.room[room_index] = null
    },
    submitRoom(room) {
      this.roomDialog = false
      this.room[this.selected_room_index] = room
      for (let index = 0; index < this.rooms.length; index++) {
        if (this.rooms[index].id === room.id) {
          this.rooms[index].selected = true
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    submitAllRooms() {
      this.group_guest = []
      if (this.master_touched) {
        this.group_guest.push({
          guest_id: this.reservation.id,
          updated: 1,
          profile_id: this.reservation.profile.id,
          room_type_id: 1,
          rm_rate: 0,
          no_of_pax: 0,
          room_id: null,
          rate_code_id: this.rate_code != null ? this.rate_code.id : null,
          folio_no: this.reservation.folio_no,
          taxes: this.reservation.taxes,
          meal_id: this.rate_code != null ? this.rate_code.meal != null ? this.rate_code.meal.id : null : null,
          meal_package_id: this.rate_code != null ? this.rate_code.meal_package != null ? this.rate_code.meal_package.id : null : null,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          created_inhousGuest_at: this.reservation.created_inhousGuest_at,
          checked_out: 0,
          is_reservation: 1,
          group_code: this.reservation.group_code,
          vip: this.vip,
        })
      }
      for (let index = 0; index < this.flattenedRows; index++) {

        if (this.guest_id[index] != undefined) {
          if (this.updated[index] == 1) {
            this.group_guest.push({
              guest_id: this.guest_id[index] != undefined ? this.guest_id[index] : null,
              updated: 1,
              profile_id: this.reservation.profile.id,
              room_type_id: this.group_room_type[index].id,
              rm_rate: this.rate[index],
              no_of_pax: this.pax[index],
              room_id: this.room[index] != null ? this.room[index].id : null,
              rate_code_id: this.group_rate_code[index] != null ? this.group_rate_code[index].id : null,
              group_code: this.reservation.group_code,
            })
          }

        }
        else {
          this.group_guest.push({
            guest_id: null,
            updated: 1,
            profile_id: this.reservation.profile.id,
            room_type_id: this.group_room_type[index].id,
            rm_rate: this.rate[index],
            no_of_pax: this.pax[index],
            room_id: this.room[index] != null ? this.room[index].id : null,
            rate_code_id: this.group_rate_code[index] != null ? this.group_rate_code[index].id : null,
            folio_no: this.reservation.folio_no,
            taxes: this.reservation.taxes,
            meal_id: /* this.group_rate_code[index] != null ? this.group_rate_code[index] :  */null,
            meal_package_id: /* this.group_rate_code[index] != null ? this.group_rate_code[index] :  */null,
            in_date: this.in_date,
            out_date: this.out_date,
            original_out_date: this.out_date,
            no_of_nights: this.no_of_nights,
            hotel_note: this.hotel_note,
            client_note: this.client_note,
            way_of_payment: this.way_of_payment.code,
            company_id: this.company_id.id,
            market_segment_id: this.market_segment_id.id,
            source_of_business_id: this.source_of_business_id.id,
            ref_no: this.ref_no,
            res_status: this.reservations_status.res_status_code,
            vat_no: this.vat_no,
            company_name: this.company_name,
            inv_address: this.inv_address,
            created_inhousGuest_at: this.reservation.created_inhousGuest_at,
            checked_out: 0,
            is_reservation: 1,
            group_code: this.reservation.group_code,
            vip: this.vip,
          })
        }


      }


      this.updateGroupReservation({ groupGuest: this.group_guest })

    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    SubmitRoomsFromRoomtypes() {
      for (let index = 0; index < this.room_types.length; index++) {
        if (this.required_rooms[index] != 0 && this.required_rooms[index] != '' && this.required_rooms[index] != null) {
          this.selected_room_type.push({
            roomtype: this.room_types[index + 1],
            number_of_rooms: this.required_rooms[index],
          })
        }
      }
      for (let y = 0; y < this.selected_room_type.length; y++) {
        for (let z = 0; z < this.selected_room_type[y].number_of_rooms; z++) {
          this.group_room_type.push(this.selected_room_type[y].roomtype)
          this.pax.push(this.selected_room_type[y].roomtype.def_pax)
          this.rate.push(0)
          if (this.rate_code != '' && this.rate_code != null) {
            this.group_rate_code.push(this.rate_code)
            this.rateCodechanged(this.flattenedRows)
          }
          this.flattenedRows += 1
        }
      }
      this.selected_room_type = []
    },
    Clear() {
      this.vip = this.reservation.vip
      this.locked = this.reservation.locked
      this.in_date = this.reservation.in_date
      this.out_date = this.reservation.out_date
      this.no_of_nights = this.reservation.no_of_nights
      this.no_of_pax = this.reservation.no_of_pax
      this.original_out_date = this.reservation.original_out_date
      this.rate_code = this.reservation.rate_code
      this.vat_no = this.reservation.vat_no
      this.company_name = this.reservation.company_name
      this.inv_address = this.reservation.inv_address
      this.hotel_note = this.reservation.hotel_note
      this.client_note = this.reservation.client_note
      this.market_segment_id = this.reservation.market_segment
      this.source_of_business_id = this.reservation.source_of_business
      this.company_id = this.reservation.company
      this.ref_no = this.reservation.ref_no
      for (let index = 0; index < this.payments.length; index++) {
        if (this.payments[index].code === this.reservation.way_of_payment) {
          this.way_of_payment = this.payments[index]
        }
      }
      for (
        let index = 0;
        index < this.reservations_statuses.length;
        index++
      ) {
        if (
          this.reservations_statuses[index].res_status_code ===
          this.reservation.res_status
        ) {
          this.reservations_status = this.reservations_statuses[index]
        }
      }
    },
    selectProfile(guest) {
      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
    },
    showGuestProfile() {
      if (this.selected_guest == "") {
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
      this.selected_guest = ""
      this.id_no_search = ""
      this.mobile_search = ""
      this.showprofilealert = true
      this.Clear()
    },
    searchProfile() {
      if (this.id_no_search == "" && this.mobile_search == "") {
        this.isSnackbarVisible = true
        this.searchDialog = false
      } else {
        this.getSearchguestAction({
          id_no: this.id_no_search,
          mobile: this.mobile_search,
        })
      }
    },
    createProfile() {
      axios
        .post(
          `${window.location.origin}/api/storeGroupProfile`,
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
            gender: this.gender_new,
            country_id: this.country_new.id,
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
    checkinfunction() {
      this.GuestCheckinAction(this.reservation.id)
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
    if (this.countries.length == 0) {
      this.getCountriesAction()
    }
    this.getExtras()
    this.getExtraByGuestId(this.$route.params.group)
    this.getReservationstatusesAction()
    this.getGroupReservation(this.$route.params.group)

  },
}
</script>

<style scoped>
.flex-grow-1 {
  flex-grow: 0 !important;
}
</style>
