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
          <VCard>
          <VCol cols="12" sm="6" md="7">
            <VTextField v-model="id_no_search" :label="$t('National ID / Mobile number')" :disabled="locked"
                        @keydown.enter="searchProfile" clearable />
          </VCol>
          <VSnackbar v-model="isSnackbarVisible" location="top" :timeout="2000">
            {{ $t("Please enter data") }}
          </VSnackbar>
          <VCol cols="12" sm="6" md="1">
            <VDialog v-model="searchDialog" width="1024" scroll-strategy="none" z-index="1000">
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
            <VDialog v-model="profiledetailsDialog" width="1048" scroll-strategy="none" z-index="1000">
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
                      <VTextField v-model="email" :label="$t('email')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="id_no" :label="$t('National ID number')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="gender" :label="$t('gender')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="language" :label="$t('language')" readonly />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="country" :label="$t('Country')" readonly />
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
            <VDialog v-model="createProfileDialog" width="1048" scroll-strategy="none" z-index="1000">
              <template #activator="{ props }">
                <VBtn v-bind="props" :disabled="!noProfileSelected || locked">
                  {{ $t("Create new profile") }}
                </VBtn>
              </template>
              <VCard>
                <VCardTitle>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <span class="text-h5">{{ $t("Create Profile") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="first_name_new" :label="$t('First name')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="last_name_new" :label="$t('Last name')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="mobile_new" :label="$t('Mobile')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="3">
                      <VTextField v-model="email_new" :label="$t('email')" />
                    </VCol>

                    <VCol cols="12" md="6" style="display: flex">
                      <VCol
                        cols="12"
                        sm="4"
                        md="4"
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
                        sm="8"
                        md="8"
                      >

                        <VTextField
                          v-model="id_no_new"
                          :label="$t('National ID number')"
                          @keyup.enter="goNext($event.target)"

                        />
                      </VCol>
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VCombobox item-color="customColorValue" v-model="gender_new" :items="gender_data" :label="$t('gender')" />
                    </VCol>
                    <VCol cols="12" sm="3" md="6">
                      <VCombobox item-color="customColorValue" v-model="language_new" :items="lang_data" :label="$t('language')" />
                    </VCol>
                    <VCol cols="12" sm="3" md="6">
                      <VCombobox item-color="customColorValue" v-model="country_new" :items="countries" :label="$t('Country')" item-title="name"
                                 item-value="country" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="phone_new" :label="$t('Phone')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="city_new" :label="$t('City')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="address_new" :label="$t('Address')" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <AppDateTimePicker v-model="date_of_birth_new" :label="$t('Date of birth')"
                                         :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
                    </VCol>
                  </VRow>
                </VCardText>
                <VCardActions>
                  <VSpacer />
                  <VBtn color="blue-darken-1" variant="text" @click="createProfileDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                  <VBtn color="blue-darken-1" variant="text" :disabled="disablecreateprofile" @click="createProfile">
                    {{ $t("Submit") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          </VCard>
        </VRow>
      </VContainer>
    </VCard>
    <VCard class="mt-6" :disabled="locked">
      <VContainer>
        <VAlert v-if="alert" variant="tonal" color="success">
          {{ $t("Selected profile:") }}
          {{ selected_guest ?
          selected_guest.first_name + ' ' + selected_guest.last_name + ' ( Profile ) '
          : reservation.onlyName == null
            ? reservation.profile.first_name +
            " " +
            reservation.profile.last_name +
            " ( Profile ) "
            : reservation.onlyName.first_name +
            " " +
            reservation.onlyName.last_name +
            " ( Only name ) "
          }}
        </VAlert>
        <VForm ref="refForm" @submit.prevent="UpdateReservation">
          <VRow class="mb-2 mt-2">
            <VCol cols="12" sm="6" md="5">

            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VRadioGroup v-model="inlineRadio" inline>
                <VRadio label="Monthly" value="M" />
                <VRadio label="Yearly" value="Y" />
              </VRadioGroup>

            </VCol>
            <VCol cols="12" sm="6" md="1"></VCol>
            <VCol cols="12" sm="6" md="3">
              <AppDateTimePicker v-model="in_date" :label="$t('In date')" :rules="[requiredValidator, inDateValidator]"

                                 :config="{  altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }" />
            </VCol>
             <VCol cols="12" sm="6" md="2">
              <VTextField v-model="in_date_hijri" :label="$t('In date hijri')" readonly />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VTextField v-model="out_date" :label="$t('Out date')" readonly />
            </VCol>
            <VCol cols="12" sm="6" md="2">
              <VTextField v-model="out_date_hijri" :label="$t('Out date hijri')" readonly />
            </VCol>
            <VCol cols="12" sm="3" md="2" v-if="inlineRadio == 'M'">
              <VTextField v-model="no_of_months" :label="$t('Number of months*')" type="number"
                          :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="3" md="2" v-if="inlineRadio == 'Y'">
              <VTextField v-model="no_of_years" :label="$t('Number of years*')" type="number"
                          :rules="[requiredValidator]" />
            </VCol>
            <VCol cols="12" sm="6" md="3">
              <VCombobox item-color="customColorValue" v-model="roomType" :label="$t('Room type')" :items="available_roomtypes" :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'
                " :rules="[requiredValidator]" @update:modelValue="roomTypechanged" />
            </VCol>

            <VCol cols="12" sm="6" md="3" style="display: flex">
              <VCombobox item-color="customColorValue" v-model="room" :label="$t('Room')" :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'
                " readonly />
              <VDialog v-model="dialog" width="1024" scroll-strategy="none" z-index="1000">
                <template #activator="{ props }">
                  <VIcon v-bind="props" icon="tabler-pencil" size="30" color="primary" style="margin: 2%;" />
                  <VIcon  @click="room = ''" icon="tabler-trash" size="30" color="red" style="margin: 2%;" />
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol cols="12" sm="6" md="6">
                        <span class="text-h5">{{ $t("Select Room") }}</span>
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 50%;"
                                    class="float-end" />
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
                      <tr v-for="item in search != null ? filterData : rooms" :key="item.id">
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
                      {{ $t("Close") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="no_of_pax" :label="$t('Number of pax')" type="number" :rules="[requiredValidator]"
                          @update:modelValue="noOfPaxchanged" />
            </VCol>
            <VCol cols="12" sm="3" md="2">
              <VTextField v-model="room_rate" :label="$t('Room rate')" type="number" :rules="[requiredValidator]" />
            </VCol>

            <VCol cols="12" sm="6" md="2">
              <VTextField v-model="total_rate" :label="$t('Total Rate')" readonly />
            </VCol>



            <VCol cols="12" sm="6" md="4">
              <VCombobox item-color="customColorValue" v-model="purpose_of_visit" :label="$t('Purpose of visit')" :items="purposeOfVisitList" clearable
                         :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox item-color="customColorValue" v-model="customer_type" :label="$t('Customer type')" :items="customerTypeList" clearable
                         :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="way_of_payment" :label="$t('Way of payment')" :items="payments" item-title="name"
                         item-value="code" :rules="[requiredValidator]"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="company_id" :label="$t('Company')" :items="companies" :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'
                " item-value="id"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="market_segment_id" :label="$t('Market Segment')" :items="segments"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="source_of_business_id" :label="$t('Source of business')" :items="sources"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="id"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="reservations_status" :label="$t('Reservation status')" :items="reservations_statuses"
                         :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="res_status_code"
                         :rules="[requiredValidator]"  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VTextField v-model="ref_no" :label="$t('Reference number')" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VTextField v-model="vat_no" :label="$t('VAT number')" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VTextField v-model="company_name" :label="$t('Company name')" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VTextField v-model="inv_address" :label="$t('INV address')" />
            </VCol>
            <VCol cols="12" sm="6" md="4">
              <VCombobox v-model="vip" :label="$t('VIP')" :items="vips" clearable  item-color="customColorValue"/>
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="hotel_note" :label="$t('Hotel note')" auto-grow rows="3" row-height="15" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextarea v-model="client_note" :label="$t('Client note')" auto-grow rows="3" row-height="15" />
            </VCol>
            <VCol cols="12" sm="6" md="12">
              <VExpansionPanels variant="inset" class="mt-5 mb-5">
                <VExpansionPanel v-for="item in 1" :key="item">
                  <VExpansionPanelTitle>
                    {{ $t('Attachments') }}<span style="color: red;"> ( {{ $t('Only 3 files allowed') }} )</span>
                  </VExpansionPanelTitle>
                  <VExpansionPanelText>
                    <VRow>
                      <VCol cols="12" sm="6" md="9">
                        <VFileInput show-size multiple label="File input" v-model:files="files" clearable
                                    @change="onFileChange" />

                      </VCol>
                      <VCol cols="12" sm="6" md="3">
                        <VBtn @click="uploadfile">
                          {{ $t("Upload") }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="12">
                        <template v-for="(items, index) in reservation.attachment" :key="index">
                          <VRow>
                            <VCol cols="12" sm="6" md="6">
                              <a @click="downloadFile(items.attachment)">{{ items.attachment }}</a>
                            </VCol>
                            <VCol cols="12" sm="6" md="6">
                              <VIcon @click="deleteFile(items.id, index)">
                                tabler-trash
                              </VIcon>
                            </VCol>
                          </VRow>


                        </template>
                      </VCol>
                    </VRow>

                  </VExpansionPanelText>
                </VExpansionPanel>
              </VExpansionPanels>

            </VCol>
            <VCol cols="12" sm="6" md="4" />
            <VCol cols="12" sm="6" md="2">
              <VDialog v-model="companion_dialog" width="1300" scroll-strategy="none" z-index="1000">
                <template #activator="{ props }">
                  <VBtn v-bind="props">
                    {{ $t("Update Companions") }}
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <VRow>
                      <VCol cols="12" sm="6" md="9">
                        <span class="text-h5">{{ $t("Companions") }}</span>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow>
                      <VCol cols="12" sm="6" md="10" />
                      <VCol cols="12" sm="6" md="1">
                        <VBtn color="primary" class="float-right" @click="RemoveCompanion">
                          {{ $t("Remove") }}
                        </VBtn>
                      </VCol>
                      <VCol cols="12" sm="6" md="1">
                        <VBtn color="primary" class="float-right" @click="AddCompanion">
                          {{ $t("Add") }}
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
                        <VCol cols="12" sm="6" md="10" />
                        <VCol cols="12" sm="6" md="1">
                          <VDialog v-model="more_search_dialog[index]" width="1024" scroll-strategy="none" z-index="1000">
                            <template #activator="{ props }">
                              <VBtn v-bind="props" @click="searchProfileCompanion(index)">
                                {{ $t("Search") }}
                              </VBtn>
                            </template>
                            <VCard>
                              <VCardTitle>
                                <VRow>
                                  <VCol cols="12" sm="6" md="6">
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
                                  <tr v-for="item in guests" :key="item.id">
                                    <td>{{ item.first_name }}</td>
                                    <td>{{ item.last_name }}</td>
                                    <td>{{ item.id_number }}</td>
                                    <td>{{ item.mobile }}</td>
                                    <td>
                                      <VBtn color="primary" @click="selectProfilecompanion(item, index)
                                          ">
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
                            {{ $t("Clear") }}
                          </VBtn>
                        </VCol>
                      </VRow>
                    </div>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn @click="companion_dialog = false">
                      {{ $t("Close") }}
                    </VBtn>
                    <VBtn @click="updateCompanion">
                      {{ $t("Update") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
            <VCol cols="12" sm="6" md="2">
              <VDialog v-model="extra_dialog" width="1300" scroll-strategy="none" z-index="1000">
                <template #activator="{ props }">
                  <VBtn v-bind="props" class="mb-4" style="left: 35%;">
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
                                     :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" clearable  item-color="customColorValue"/>
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
                                <VBtn @click="deleteExtraGuest(item.id)" color="red"
                                      style="background: red;color: white;">
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
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>
          </VRow>
          <div class="button-container">
            <VBtn :disabled="disableCheckin" @click="checkinfunction">
              {{ $t("check in") }}
            </VBtn>
            <VBtn color="error" @click="cancelReservationDialog = true">
              {{ $t("Cancel reservation") }}
            </VBtn>
            <VBtn class="float-end mr-4" @click="Clear">
              {{ $t("Reset") }}
            </VBtn>
            <VBtn class="float-end" type="submit" @click="refForm?.validate()">
              {{ $t("Submit") }}
            </VBtn>
          </div>
        </VForm>
      </VContainer>
    </VCard>
    <VDialog v-model="cancelReservationDialog" width="1024" z-index="1000" scroll-strategy="none"
             @click:outside="cancelReservationDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">{{ $t('Please select reason of cancel') }}</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="3" />
              <VCol cols="12" sm="6" md="6">
                <VCombobox v-model="cancel_reason" :items="cancel_reasons"
                           :item-title="$i18n.locale === 'en' ? 'Cancel_Reason_EN' : 'Cancel_Reason_AR'"
                           :label="$t('Cancel reason')"  item-color="customColorValue"/>
              </VCol>
              <VCol cols="12" sm="6" md="3" />
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="blue-darken-1" variant="text" @click="cancelReservationDialog = false">
            {{ $t('Close') }}
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" @click="cancelReservation">
            {{ $t('Submit') }}
          </VBtn>
        </VCardActions>
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
      select_year: '',
      select_day: '',
      select_month: '',
      years: [],
      days: [],
      month: [],
      cancel_reason: null,
      cancel_reasons: [
        {
          "Cancel_Reason_Id": 0,
          "Cancel_Reason_EN": "Not Applicable / No Reason",
          "Cancel_Reason_AR": " لايوجدسبب"
        },
        {
          "Cancel_Reason_Id": 1,
          "Cancel_Reason_EN": "Cancelled by Customer",
          "Cancel_Reason_AR": " إلغاء من قبل العميل"
        },
        {
          "Cancel_Reason_Id": 2,
          "Cancel_Reason_EN": "Cancelled by Hotel",
          "Cancel_Reason_AR": " إلغاء من قبل المنشأة"
        },
        {
          "Cancel_Reason_Id": 3,
          "Cancel_Reason_EN": "Customer Find better deal",
          "Cancel_Reason_AR": "العميل وجد خيار افضل"
        },
        {
          "Cancel_Reason_Id": 4,
          "Cancel_Reason_EN": "Customer was not satisfied",
          "Cancel_Reason_AR": "العميل غير راضي"
        },
        {
          "Cancel_Reason_Id": 5,
          "Cancel_Reason_EN": "Customer does not arrived on given date",
          "Cancel_Reason_AR": "النزيل لم يصل في اليوم المحدد"
        },
        {
          "Cancel_Reason_Id": 6,
          "Cancel_Reason_EN": "Customer changed the dates ",
          "Cancel_Reason_AR": "العميل قام بتغيير التواريخ"
        },
        {
          "Cancel_Reason_Id": 7,
          "Cancel_Reason_EN": "Customer cancelled this booking but rebooked again with different dates or travellers",
          "Cancel_Reason_AR": "تم الالغاء وعمل حجز جديد بتاريخ مختلفه"
        },
        {
          "Cancel_Reason_Id": 8,
          "Cancel_Reason_EN": "Other Reason",
          "Cancel_Reason_AR": "اسباب اخرى"
        }
      ],
      cancelReservationDialog: false,
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
        { id: 10, nameE: 'Ministry of Health Staff', nameA: 'موظف وزارة الصحة', },
      ],
      customerTypeList: [
        { id: 1, nameE: 'Citizen', nameA: 'مواطن' },
        { id: 2, nameE: 'GolfCitizen', nameA: 'مواطن خليجي' },
        { id: 3, nameE: 'Visitor', nameA: 'زائر' },
        { id: 4, nameE: 'Resident', nameA: 'مقيم' },
      ],
      SettingData: null,
      rooms: [],
      out_date_hijri: null,
      in_date_hijri: null,
      vip: null,
      vips: ["A", "B", "C"],
      locked: null,
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      res_rate_dayss: [],
      no_of_months: 1,
      no_of_years: 1,
      inlineRadio: null,
      uploaded_files: [],
      files: [],
      vat_no: null,
      company_name: null,
      inv_address: null,
      edit_reservation: false,
      isRateCodeWatched: false,
      disablecreateprofile: true,
      gender_data: ["male", "female", "no gender found"],
      lang_data: ["ar", "en", "fr"],
      country_new: null,
      language: null,
      language_new: null,
      first_name_new: null,
      last_name_new: null,
      mobile_new: null,
      phone_new: null,

      id_no_new_compo: null,
      All_id_num: null,
      Id_Num:[1,4,5,6],
      id_no_new: null,
      email_new: null,
      city_new: null,
      address_new: null,

      total_rate:0,
      date_of_birth_new: null,
      gender_new: null,
      createProfileDialog: false,
      data: [],
      more_search_dialog: [false],
      profile_id_companion: [],
      country_id_companion: [],
      first_name_companion: [],
      last_name_companion: [],
      id_no_companion: [],
      mobile_companion: [],
      no_of_companions: null,
      companion_dialog: false,
      id_no_search: null,
      mobile_search: null,
      search: null,
      dialog: false,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: false,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: 1,
      original_out_date: null,
      room: null,
      roomType: null,
      no_of_pax: null,
      room_rate: null,
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
      first_name: null,
      last_name: null,
      mobile: null,
      phone: null,
      id_no: null,
      email: null,
      city: null,
      address: null,
      date_of_birth: null,
      gender: null,
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
      selected_guest: null,
      reservations_status: null,
      alert: false,
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
      loading: true,
      moreNames: [],
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
      return (value) => {
        if (!value) {
          return true;
         }
        const selectedDate = new Date(value);
        return selectedDate >= new Date(this.SettingData.hotel_date) || 'In Date must be on or after the hotel date';

      };
    },
    filterData() {
      return this.rooms.filter(room => {
        return (
          room.rm_name_en.toLowerCase().includes(this.search) ||
          room.rm_name_loc.toLowerCase().includes(this.search)
        )
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
    guest_profile() {
      return this.guestStore.guest_profile
    },
    reservation() {
      return this.guestStore.reservation
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
    // roomType(){
    //   if (this.roomType != null && this.no_of_pax != null) {
    //     this.disableratecode = false
    //     this.rooms = this.roomType.rooms
    //     this.no_of_pax = this.roomType.def_pax
    //     if (this.inlineRadio == 'M') {
    //       this.rm_rate = this.roomType?.monthly_rate
    //     }
    //     else {
    //       this.rm_rate = this.roomType?.yearly_rate
    //     }
    //   } else {
    //     this.disableratecode = true
    //     this.rate_code = null
    //     this.room_rate = 0
    //     this.rooms = []
    //   }
    //   this.no_of_pax = this.roomType.def_pax
    // },
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
    available_roomtypes() {
      if (this.available_roomtypes != null) {
        const selected_roomtype = this.available_roomtypes.find(room_type => room_type.id == this.roomType.id)
        this.roomType = selected_roomtype
        this.rooms = selected_roomtype.rooms
      }
    },
    reservation() {
      if (this.reservation != null) {
        this.Clear()
      }

    },
    inlineRadio() {
      if (this.no_of_months != null && this.inlineRadio == 'M') {
        var inn = moment(this.in_date)
        inn.add(this.no_of_months, "months")
        this.out_date = inn.format("YYYY-MM-DD")
        this.no_of_years = null

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
        this.no_of_months = null
        this.out_date_hijri = this.$getHijriDate(this.out_date)

        this.room_rate = this.roomType?.yearly_rate
      }
    },
    in_date() {
      if (this.inlineRadio == 'M') {
        var inn = moment(this.in_date);
        inn.add(this.no_of_months, "months");
        this.out_date = inn.format("YYYY-MM-DD");

        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_nights = date1.diff(date2, 'days')
        this.in_date_hijri = this.$getHijriDate(this.in_date)
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      else {
        var inn = moment(this.in_date);
        inn.add(this.no_of_years, "years");
        this.out_date = inn.format("YYYY-MM-DD");

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
    out_date() {
      if (this.inlineRadio == 'M') {


        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_months = date1.diff(date2, 'months')
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      else {


        const date1 = moment(this.out_date)
        const date2 = moment(this.in_date)

        this.no_of_years = date1.diff(date2, 'years')
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
        this.out_date_hijri = this.$getHijriDate(this.out_date)
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
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      this.total_rate = this.room_rate*this.no_of_years
    },
    companion_updated() {
      if (this.companion_updated) {
        this.insertAlert("Companions has been updated")
        this.getReservationAction(this.$route.params.reservation)
        this.setCompanionUpdated()
        this.companion_dialog = false
      }
    },
    searchDialog() {
      // eslint-disable-next-line sonarjs/no-redundant-boolean
      if (this.searchDialog == false) {
        this.guests = []
      }
    },
    first_name_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    last_name_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    mobile_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    country_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    gender_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    id_no_new() {
      if (
        this.first_name_new != null &&
        this.last_name_new != null &&
        this.mobile_new != null &&
        this.country_new != null &&
        this.gender_new != null &&
        this.id_no_new
      ) {
        this.disablecreateprofile = false
      } else {
        this.disablecreateprofile = true
      }
    },
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
      }
    },

  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.YeardData()
    this.MonthData()
    this.DayData()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    YeardData() {
      const startYear = 1900;
      const endYear = 2024;
      for (let year = startYear; year <= endYear; year++) {
        this.years.push(year)
      }
    },

    MonthData() {
      const starMonth = 1;
      const endMonth = 12;
      for (let months = starMonth; months <= endMonth; months++) {
        this.month.push(months)
      }
    },

    DayData() {
      const statday = 1;
      const endday = 31;
      for (let day = statday; day <= endday; day++) {
        this.days.push(day)
      }
    },
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction"]),
    ...mapActions(useGuestStore, [
      "setCompanionUpdated",
      "getSearchguestAction",
      "CreateGuestAction",
      "getGuestprofileAction",
      "getReservationAction",
      "UpdateReservationAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      "updateGuestMoreNamesAction",
      'getAvaliableRooms',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    async cancelReservation() {
      await axios.post(`${window.location.origin}/api/cancel`, {
        'guest_id': this.reservation?.id,
        'cancelReason': this.cancel_reason?.Cancel_Reason_Id,
        'groubeCode': null,
      }).then(res => {
        if (res.status != undefined) {
          this.cancelReservationDialog = false
          this.getReservationAction(this.$route.params.reservation)
          this.$alert('Reservation has been cancelled successfully', true)
        }
      })
    },
    removeRoom() {
      if (this.room != null) {
        let room_found = false
        for (let i = 0; i < this.available_roomtypes.length; i++) {
          if (this.available_roomtypes[i].rooms.length != 0) {
            for (let z = 0; z < this.available_roomtypes[i].rooms.length; z++) {
              if (this.available_roomtypes[i].rooms[z].id == this.room.id) {
                room_found = true
              }
            }
          }
        }
        if (!room_found) {
          this.rooms.push(this.room)
        }
        this.room = null
      }
    },
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
    // roomTypechanged() {
    //   if (this.roomType != null && this.no_of_pax != null) {
    //     this.disableratecode = false
    //     this.rooms = this.roomType.rooms
    //     this.no_of_pax = this.roomType.def_pax
    //     if (this.inlineRadio == 'M') {
    //       this.rm_rate = this.roomType?.monthly_rate
    //     }
    //     else {
    //       this.rm_rate = this.roomType?.yearly_rate
    //     }
    //   } else {
    //     this.disableratecode = true
    //     this.rate_code = null
    //     this.room_rate = 0
    //     this.rooms = []
    //   }
    //   this.no_of_pax = this.roomType.def_pax
    // },
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
      this.files = event.target.files;
    },
    Clear() {
      this.alert = true
      this.vip = this.reservation.vip
      this.locked = this.reservation.locked
      this.inlineRadio = this.reservation.res_type
      this.in_date = this.reservation.in_date
      this.out_date = this.reservation.out_date
      this.getAvaliableRooms(this.in_date, this.out_date)
      this.no_of_nights = this.reservation.no_of_nights
      this.no_of_pax = this.reservation.no_of_pax
      this.original_out_date = this.reservation.original_out_date
      this.room = this.reservation.room != null ? this.reservation.room : null
      this.roomType = this.reservation.roomType
      this.rate_code = this.reservation.rate_code
      this.hotel_note = this.reservation.hotel_note
      this.client_note = this.reservation.client_note
      this.vat_no = this.reservation.vat_no
      this.company_name = this.reservation.company_name
      this.inv_address = this.reservation.inv_address
      this.market_segment_id = this.reservation.market_segment
      this.source_of_business_id = this.reservation.source_of_business
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
      this.company_id = this.reservation.company
      this.ref_no = this.reservation.ref_no
      for (let i = 0; i < this.reservation.res_rate_day.length; i++) {
        this.ratecode[i + 1] = this.reservation.res_rate_day[i].rate_code
        this.roomrate[i + 1] = this.reservation.res_rate_day[i].rm_rate
      }
      if (this.reservation.moreName) {
        this.no_of_companions = this.reservation.moreName.length

        for (
          let index = 1;
          index <= this.reservation.moreName.length;
          index++
        ) {
          this.profile_id_companion[index] =
            this.reservation.moreName[index - 1].profile_id
          this.country_id_companion[index] =
            this.reservation.moreName[index - 1].country
          this.first_name_companion[index] =
            this.reservation.moreName[index - 1].first_name
          this.last_name_companion[index] =
            this.reservation.moreName[index - 1].last_name
          this.id_no_companion[index] =
            this.reservation.moreName[index - 1].id_no
          this.mobile_companion[index] =
            this.reservation.moreName[index - 1].mobile
        }
      }
      if (this.reservation.purpose_of_visit != null) {
        for (let i = 0; i < this.purposeOfVisitList.length; i++) {
          if (this.reservation.purpose_of_visit == this.purposeOfVisitList[i].id) {
            this.purpose_of_visit = this.purposeOfVisitList[i]
          }
        }
      }
      if (this.reservation.customer_type != null) {
        for (let i = 0; i < this.customerTypeList.length; i++) {
          if (this.reservation.customer_type == this.customerTypeList[i].id) {
            this.customer_type = this.customerTypeList[i]
          }
        }
      }
      if (
        this.reservation.is_reservation == 1 &&
        (this.reservation.res_status == "CNF" ||
          this.reservation.res_status == "NCF") &&
        this.reservation.is_checked_in == 0 &&
        this.reservation.in_date == this.SettingData.hotel_date
        && this.reservation.room != null
      ) {
        this.disableCheckin = false
      } else {
        this.disableCheckin = true
      }
      this.room_rate = this.reservation.rm_rate

    },
    uploadfile() {
      if (this.files.length + this.reservation.attachment.length > 3) {
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
            this.reservation.attachment = res.data.data
            this.insertAlert('Files has been uploaded')
          },
        ).catch(
          err => {
            console.log(err)
          },
        )
      }


    },
    deleteFile(id, index) {
      axios.delete(`${window.location.origin}/api/guestDeleteAttachment/${id}`).then(
        res => {
          this.insertAlert('File has been deleted')
          this.reservation.attachment.splice(index, 1)
        },
      ).catch(
        err => {
          console.log(err)
        },
      )


    },
    downloadFile(item) {
      const fileUrl = item

      // Replace with the actual file path
      axios.get(fileUrl, { responseType: 'blob' })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')

          link.href = url
          link.setAttribute('download', item)// Replace with the desired file name and extension
          document.body.appendChild(link)
          link.click()
        })
        .catch(error => {
          console.error('Error downloading file:', error)
        })
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    async UpdateReservation() {
      this.res_rate_dayss.push({
        rm_rate: this.room_rate,
        rate_id: this.rate_code != null && this.rate_code != null ? this.rate_code.id : null,
        meal_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_id != null ? this.rate_code.meal_id : null : null,
        meal_package_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_package_id != null ? this.rate_code.meal_package_id : null : null,
      })
      if (this.inlineRadio == 'M') {
        const response = await this.UpdateReservationAction(this.reservation.id, {
          profile_id: this.reservation.profile.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
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
        if (response.status != undefined) {
          this.$alert('Reservation updated successfully', true)
          this.getReservationAction(this.$route.params.reservation)
        }
      } else {
        const response = await this.UpdateReservationAction(this.reservation.id, {
          profile_id: this.reservation.profile.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
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
        if (response.status != undefined) {
          this.$alert('Reservation updated successfully', true)
          this.getReservationAction(this.$route.params.reservation)
        }
      }

      this.res_rate_dayss = []

    },
    selectroom(room) {
      this.dialog = false
      if (this.room != null) {
        let room_found = false
        for (let i = 0; i < this.available_roomtypes.length; i++) {
          if (this.available_roomtypes[i].rooms.length != 0) {
            for (let z = 0; z < this.available_roomtypes[i].rooms.length; z++) {
              if (this.available_roomtypes[i].rooms[z].id == this.room.id) {
                room_found = true
              }
            }
          }
        }
        if (!room_found) {
          this.rooms.push(this.room)
        }
      }
      this.room = room
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
        icon: "warning",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    checkinfunction() {
      if (this.reservation.profile.id == 1) {
        this.DangerAlert("Please select or create profile to checkin")
      } else {
        this.GuestCheckinAction(this.reservation.id)
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
      this.profile_id_companion[index] = null
      this.first_name_companion[index] = null
      this.last_name_companion[index] = null
      this.id_no_companion[index] = null
      this.mobile_companion[index] = null
      this.country_id_companion[index] = null
    },
    updateCompanion() {
      for (let index = 1; index <= this.no_of_companions; index++) {
        this.data.push({
          guest_id: this.$route.params.reservation,
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
      if (this.no_of_companions < 1) {
        this.data.push({})
      }

      this.updateGuestMoreNamesAction({ data: this.data }, this.reservation.id)

      this.data = []
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
      this.noProfileSelected = true
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
      this.All_id_num = this.id_no_new_compo + this.id_no_new
      axios
        .post(
          `/api/guestProfile`,
          {
            first_name: this.first_name_new,
            last_name: this.last_name_new,
            id_no: this.All_id_num,
            mobile: this.mobile_new,
            phone: this.phone_new,
            email: this.email_new,
            address: this.address_new,
            city: this.city_new,
            gender: this.gender_new,
            language: this.language_new,
            date_of_birth: this.date_of_birth_new,
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
    selectProfile(guest) {
      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
    },
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.getReservationstatusesAction()
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getCountriesAction()
    this.getExtras()
    this.getExtraByGuestId(this.$route.params.reservation)
    this.getReservationAction(this.$route.params.reservation)
  },
}
</script>

<style scoped>
a {
  cursor: pointer;
}

.required {
  @apply before: text-red-600 before:content-["*"] before:mr-1;
}

.button-container {
  display: flex;
  justify-content: space-between;
}

.button {
  flex: 1;
  margin-block: 0;
  margin-inline: 5px;

  /* Adjust the margin as needed to create the desired space between buttons */
}
</style>
