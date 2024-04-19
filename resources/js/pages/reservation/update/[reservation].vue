<script setup>
import { requiredValidator } from "@validators"

</script>


<template>
  <div>
    <br>  <VAlert
      variant="tonal"
      color="error"
  >
     click submit button after change anything.
    </VAlert>
    <br><br>
    <VCard>
      <VContainer>
        <VRow>
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
            <VCheckbox
              v-model="locked"
              :label="$t('Reservation locked')"
              :true-value="1"
              :false-value="0"
              @update:modelValue="changeLocked"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="7"
          >
            <VTextField
              v-model="id_no_search"
              :label="$t('National ID / Mobile number')"
              :disabled="locked"
              clearable
              @keydown.enter="searchProfile"
            />
          </VCol>
          <VSnackbar
            v-model="isSnackbarVisible"
            location="top"
            :timeout="2000"
          >
            {{ $t("Please enter data") }}
          </VSnackbar>
          <VCol
            cols="12"
            sm="6"
            md="1"
          >
            <VDialog
              v-model="searchDialog"
              width="1024"

              scroll-strategy="none"
            >
              <template #activator="{ props }">
                <VBtn
                  v-bind="props"
                  :disabled="locked"
                  @click="searchProfile"
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
                            @click="selectProfile(item)"
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
                    @click="searchDialog = false"
                  >
                    {{ $t("Close") }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="1"
          >
            <VDialog
              v-model="profiledetailsDialog"
              width="1048"
              z-index="1000"
              scroll-strategy="none"
            >
              <template #activator="{ props }">
                <VBtn
                  v-bind="props"
                  :disabled="locked"
                  @click="showGuestProfile"
                >
                  {{ $t("Show") }}
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
                      <span class="text-h5">{{ $t("Profile") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol
                      cols="12"
                      sm="6"
                      md="3"
                    >
                      <VTextField
                        v-model="first_name"
                        :label="$t('First name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="3"
                    >
                      <VTextField
                        v-model="last_name"
                        :label="$t('Last name')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="3"
                    >
                      <VTextField
                        v-model="mobile"
                        :label="$t('Mobile')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="3"
                    >
                      <VTextField
                        v-model="email"
                        :label="$t('email')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="id_no"
                        :label="$t('National ID number')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="gender"
                        :label="$t('gender')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="language"
                        :label="$t('language')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="country"
                        :label="$t('Country')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="phone"
                        :label="$t('Phone')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="city"
                        :label="$t('City')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <VTextField
                        v-model="address"
                        :label="$t('Address')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="12"
                      md="12"
                      style="display: flex; text-align: center;"
                    >
                      <VCol
                        cols="2"
                        sm="2"
                        md="2"
                      >
                        <b style="font-size: 140%;">Birthdate :</b>
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox item-color="customColorValue"
                          v-model="select_day"
                          :items="days"
                          :label="$t('days')"
                          style="width: 100%;"
                          clearable
                        />
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox item-color="customColorValue"
                          v-model="select_month"
                          :items="month"
                          :label="$t('months')"
                          style="width: 100%;"
                          clearable
                        />
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox item-color="customColorValue"
                          v-model="select_year"
                          :items="years"
                          :label="$t('Years')"
                          style="width: 100%;"
                          clearable
                        />
                      </VCol>
                    </VCol>
                  </VRow>
                </VCardText>
                <VCardActions>
                  <VSpacer />
                  <VBtn @click="profiledetailsDialog = false">
                    {{ $t("Close") }}
                  </VBtn>
                  <VBtn @click="submitUpdateProfile">
                    {{ $t('Save') }}
                  </VBtn>
                </VCardActions>
              </VCard>
            </VDialog>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="1"
          >
            <VBtn
              :disabled="locked"
              @click="clearSeletedProfile"
            >
              {{ $t("Remove") }}
            </VBtn>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="2"
          >
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
                      <VCombobox
                        v-model="language_new"
                        :items="lang_data"
                        label="language"
                        item-color="customColorValue"
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

                    <VCol
                      cols="12"
                      sm="12"
                      md="12"
                      style="display: flex; text-align: center;"
                    >
                      <VCol
                        cols="2"
                        sm="2"
                        md="2"
                      >
                        <b style="font-size: 140%;">Birthdate :</b>
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox
                          v-model="select_day_edit"
                          :items="days"
                          :label="$t('days')"
                          style="width: 100%;"
                          clearable
                          item-color="customColorValue"
                        />
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox
                          v-model="select_month_edit"
                          :items="month"
                          :label="$t('months')"
                          style="width: 100%;"
                          clearable
                          item-color="customColorValue"
                        />
                      </VCol>
                      <VCol
                        cols="3"
                        sm="3"
                        md="3"
                        style="display: inline-block;"
                      >
                        <VCombobox
                          v-model="select_year_edit"
                          :items="years"
                          :label="$t('Years')"
                          style="width: 100%;"
                          clearable
                          item-color="customColorValue"
                        />
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
    <VCard
      class="mt-6"
      :disabled="locked"
    >
      <VContainer>
        <VAlert
          v-if="alert"
          variant="tonal"
          color="success"
        >
          {{ $t("Selected profile:") }}
          {{ selected_guest ?
            selected_guest?.first_name + ' ' + selected_guest?.last_name + ' ( Profile ) ' :
            reservation?.onlyName?.first_name +
            ' ' + reservation?.onlyName?.last_name + ' ( Only name ) '
          }}
        </VAlert>
        <VForm
          ref="refForm"
          @submit.prevent="UpdateReservation"
        >
          <VRow class="mb-2 mt-2">
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="in_date"
                :label="$t('In date*')"
                :rules="[requiredValidator, inDateValidator]"

                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                @update:model-value="inDateChanged"
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
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="out_date"
                :label="$t('Out date*')"
                :rules="[requiredValidator, outDateValidator]"

                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                @update:model-value="outDateChanged"
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
                readonly
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_nights"
                :label="$t('Number of nights*')"
                type="number"
                :rules="[requiredValidator]"
                @update:model-value="noOfNightsChanged"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="5"
            >
              <VCombobox
                v-model="roomType"
                :label="$t('Room type*')"
                :items="available_roomtypes"
                :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                item-color="customColorValue"
                item-value="roomType"
                :rules="[requiredValidator]"
                @update:model-value="roomtypeChanged"
              />
            </VCol>




            <VCol
              cols="12"
              sm="6"
              md="3"
              style="display:flex;"
            >
              <VCombobox
                v-model="room"
                :label="$t('Room')"
                :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'
                "
                item-value="room"
                readonly
                item-color="customColorValue"
              />
              <VDialog
                v-model="dialog"
                width="1024"
                z-index="1000"
                scroll-strategy="none"
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
                    @click="removeRoom"
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
                            <p v-if="$i18n.locale == 'en'">
                              {{ item.rm_name_en }}
                            </p>
                            <p v-else>
                              {{ item.rm_name_loc }}
                            </p>
                          </td>
                          <td>{{ item.rm_max_pax }}</td>

                          <td>
                            <p v-if="$i18n.locale == 'en'">
                              {{ item.floors.floor_name }}
                            </p>
                            <p v-else>
                              {{ item.floors.floor_name_loc }}
                            </p>
                          </td>
                          <td>
                            <p v-if="$i18n.locale == 'en'">
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
                :label="$t('Number of pax')"
                type="number"
                :rules="[requiredValidator]"

                @update:model-value="numberOfPaxChanged"
              />
            </VCol>  <VCol
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="room_rate"
                :label="$t('Room rate')"
                type="number"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="5"
            >
              <VCombobox
                v-model="rate_code"
                :label="$t('Rate code')"
                :items="rate_codes"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="rate_code"
                :disabled="disableratecode"
                item-color="customColorValue"
                @update:model-value="rateCodeChanged"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VCombobox
                v-model="meal"
                :disabled="disableratecode"
                :label="$t('Meal or Meal package')"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                readonly
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
                readonly
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VDialog
                v-model="reservation_days_dialog"
                width="1024"
                z-index="1000"
                scroll-strategy="none"
              >
                <template #activator="{ props }">
                  <VBtn v-bind="props">
                    {{ $t("Fill Reservation days") }}
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
                        <span class="text-h5">{{ $t("Reservation days") }}</span>
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="3"
                      >
                        <VBtn @click="fill_res_days">
                          {{ $t("Fill from entered data") }}
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VRow
                      v-for="index in no_of_nights"
                      :key="index"
                    >
                      <VCol
                        cols="12"
                        sm="3"
                        md="3"
                      >
                        <AppDateTimePicker
                          v-model="day[index]"
                          :value="getStartDate(index)"
                          readonly
                          :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="3"
                        md="4"
                      >
                        <VCombobox
                          v-model="ratecode[index]"
                          :label="$t('Rate code')"
                          :items="rate_codes"
                          :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                          item-value="rate_code"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="3"
                        md="4"
                      >
                        <VTextField
                          v-model="roomrate[index]"
                          :label="$t('Room rate')"
                          type="number"
                          :rules="[requiredValidator]"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="3"
                        md="1"
                      >
                        <VBtn @click="fill_rest_res_days(index)">
                          {{ $t("Fill") }}
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />

                    <VBtn @click="clear_res_days">
                      {{ $t("clear") }}
                    </VBtn>
                    <VBtn @click="reservation_days_dialog = false">
                      {{ $t("Close") }}
                    </VBtn>
                    <VBtn @click="save_res_days">
                      {{ $t("Save") }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="purpose_of_visit"
                :label="$t('Purpose of visit*')"
                :items="purposeOfVisitList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="customer_type"
                :label="$t('Customer type*')"
                :items="customerTypeList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
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
                :item-title="$i18n.locale == 'en' ? 'company_name' : 'company_name_loc'
                "
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
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
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
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="reservations_status"
                :label="$t('Reservation status')"
                :items="reservations_statuses"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="res_status_code"
                :rules="[requiredValidator]"
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
                auto-grow
                rows="3"
                row-height="15"
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
                auto-grow
                rows="3"
                row-height="15"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="12"
            >
              <VExpansionPanels
                variant="inset"
                class="mt-5 mb-5"
              >
                <VExpansionPanel
                  v-for="item in 1"
                  :key="item"
                >
                  <VExpansionPanelTitle>
                    {{ $t('Attachments') }}<span style="color: red;">{{ $t('( Only 3 files allowed )') }}</span>
                  </VExpansionPanelTitle>
                  <VExpansionPanelText>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                        md="9"
                      >
                        <VFileInput
                          v-model:files="files"
                          show-size
                          multiple
                          label="File input"
                          clearable
                          @change="onFileChange"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="3"
                      >
                        <VBtn @click="uploadfile">
                          {{ $t("Upload") }}
                        </VBtn>
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="12"
                      >
                        <template
                          v-for="(items, index) in reservation.attachment"
                          :key="index"
                        >
                          <VRow>
                            <VCol
                              cols="12"
                              sm="6"
                              md="6"
                            >
                              <a @click="downloadFile(items.attachment)">{{ items.attachment }}</a>
                            </VCol>
                            <VCol
                              cols="12"
                              sm="6"
                              md="6"
                            >
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
          </VRow>

          <VCol
            cols="12"
            sm="12"
            md="12"
            class="buttons_control"
          >
            <VMenu
              location="bottom"
              z-index="1000"
            >
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t('Cashier') }}
                </VBtn>
              </template>

              <VList>
                <template
                  v-for="item in cashier_items"
                  :key="item.title"
                >
                  <VListItem @click="callMethod(item.value)">
                    <VListItemTitle>{{ item.title }}</VListItemTitle>
                  </VListItem>
                </template>
              </VList>
            </VMenu>
            <VDialog
              v-model="companion_dialog"
              width="1300"
              z-index="1000"
              scroll-strategy="none"
            >
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t("Update Companions") }}
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
                      <span class="text-h5">{{ $t("Companions") }}</span>
                    </VCol>
                  </VRow>
                </VCardTitle>
                <VCardText>
                  <VRow>
                    <VCol
                      cols="12"
                      sm="6"
                      md="5"
                    />
                    <VCol
                      cols="12"
                      sm="6"
                      md="2"
                    >
                      <VBtn
                        color="primary"
                        class="float-right"
                        @click="AddCompanion"
                      >
                        {{ $t("Add new companion") }}
                      </VBtn>
                    </VCol>
                  </VRow>
                  <div class="mb-5 mt-5">
                    <VRow
                      v-for="index in no_of_companions"
                      :key="index"
                    >
                      <VCol
                        cols="12"
                        sm="6"
                        md="1"
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
                      <VCol
                        cols="12"
                        sm="6"
                        md="1"
                      >
                        <VBtn
                          color="primary"
                          @click="RemoveCompanion(index)"
                        >
                          {{ $t("Remove") }}
                        </VBtn>
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                        md="10"
                      />
                      <VCol
                        cols="12"
                        sm="6"
                        md="2"
                      >
                        <VDialog
                          v-model="more_search_dialog[index]"
                          width="1024"
                          z-index="1000"
                          scroll-strategy="none"
                        >
                          <template #activator="{ props }">
                            <VBtn
                              v-bind="props"
                              @click="searchProfileCompanion(index)"
                            >
                              {{ $t("Search by mobile or ID") }}
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
                                        @click="selectProfilecompanion(item, index)
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
                      <!--
                        <VCol cols="12" sm="6" md="1">
                        <VBtn color="primary" @click="clearSeletedProfileCompanion(index)">
                        {{ $t("clear") }}
                        </VBtn>
                        </VCol>
                      -->
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
            <VDialog
              v-model="extra_dialog"
              width="1300"
              z-index="1000"
              scroll-strategy="none"
            >
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t("Update Extras") }}
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
                          :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
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
                              <td v-if="$i18n.locale == 'en'">
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
              :disabled="disableCheckin"
              @click="checkinfunction"
            >


               {{ $t("check in") }}
                <VTooltip
                  activator="parent"
                  location="top"
                >
                  {{ $t("Must choice Room And the indate is the current date") }}
                </VTooltip>
             </VBtn>
            <VBtn
              color="error"
              @click="cancelReservationDialog = true"
            >
              {{ $t("Cancel reservation") }}
            </VBtn>
            <VBtn @click="clear">
              {{ $t("Reset") }}
            </VBtn>
            <VBtn
              type="submit"
              @click="refForm?.validate()"
            >
              {{ $t("Submit") }}
            </VBtn>
          </VCol>
        </VForm>
      </VContainer>
    </VCard>
    <VDialog
      v-model="postingchargeDialog"
      width="1024"
      z-index="1000"
      scroll-strategy="none"
    >
      <VCard>
        <VCardTitle>
          <span class="text-h5">Posting charges</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="guest_name"
                  label="Guest name"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="room_no"
                  label="Room"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="arrival_date"
                  label="Arrival date"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="departure_date"
                  label="Departure date"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mt-8"
              >
                <VCol
                  cols="12"
                  sm="6"
                  md="9"
                >
                  <VTextField
                    v-model="ledger_id"
                    label="Ledger account"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="9"
                >
                  <VTextField
                    v-model="ledger_name"
                    label="Ledger name"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VTextField
                    v-model="amount"
                    label="Amount"
                    append-icon="tabler-cash"
                    type="number"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VTextarea
                    v-model="description"
                    label="Description"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VCheckbox
                    v-model="tax_included"
                    label="Tax Included"
                  />
                </VCol>
              </VCol>


              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mt-8"
              >
                <span>{{ $t('Ledger Categories') }}</span>
                <VList>
                  <template
                    v-for="category in ledgerCats"
                    :key="category.name"
                  >
                    <VListGroup
                      :value="category.name"
                      :title="category.name"
                    >
                      <template #activator="{ props }">
                        <VListItem
                          v-bind="props"
                          :title="category.name"
                        />
                      </template>
                      <VListItem
                        v-for="ledger in category.ledgers"
                        :key="ledger.id"
                        :value="ledger.id"
                        :title="ledger.id + ' - ' + ledger.name"
                        @click="ledgerSelected(ledger)"
                      />
                    </VListGroup>
                  </template>
                </VList>
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="closePostingChargeDialog"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disablePostingCharge"
            @click="submitPostingCharge"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="PaymentDialog"
      width="1024"
      z-index="1000"
      scroll-strategy="none"
      @click:outside="PaymentDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Cashier Payment</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="room_no"
                  :label="$t('Room')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="guest_name"
                  :label="$t('guest name')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="window_number"
                  :label="$t('window name')"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="6"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="Amount"
                  type="number"
                  :rules="[floatValidator]"
                  :label="$t('Amount')"
                />
                <br>
                <VCombobox
                  v-model="Payments_Selected"
                  :items="Payments"
                  item-title="name"
                  item-value="Payments_Selected"
                  :label="$t('Way of Payments')"
                />

                <br>
                <VTextField
                  v-model="no_data"
                  type="number"
                  :label="$t('No Date')"
                />
              </VCol>
              <VCol
                class="Date_inputs"
                cols="6"
                sm="6"
                md="6"
              >
                <VTextarea
                  v-model="Descriptions"
                  :label="$t('Description')"
                  style="height: 135%;"
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mx-auto"
              >
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar
                      size="42"
                      variant="tonal"
                      color="primary"
                    >
                      <VIcon
                        icon="tabler-users"
                        size="24"
                      />
                    </VAvatar>
                    {{ $t('Created by') }}
                    <h6 class="text-h6 font-weight-semibold my-2">
                      {{ created_by_name }}
                    </h6>
                    <span class="text-body-2">{{ currentDateTime }}</span>
                  </VCardText>
                </VCard>
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="PaymentDialog = false"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="submitPayment"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="cancelReservationDialog"
      width="1024"
      z-index="1000"
      scroll-strategy="none"
      @click:outside="cancelReservationDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">{{ $t('Please select reason of cancel') }}</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="3"
              />
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox
                  v-model="cancel_reason"
                  :items="cancel_reasons"
                  :item-title="$i18n.locale === 'en' ? 'Cancel_Reason_EN' : 'Cancel_Reason_AR'"
                  :label="$t('Cancel reason')"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              />
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="cancelReservationDialog = false"
          >
            {{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="cancelReservation"
          >
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
import flatPickr from 'vue-flatpickr-component'
import { useExtrasStore } from "../../../stores/ExtrasStore"
import { useGeneralStore } from "../../../stores/GeneralStore"
import { useGuestStore } from "../../../stores/GuestStore"
import { useRatecodeStore } from "../../../stores/RatecodeStore"
import { useRoomStore } from "../../../stores/RoomStore"

export default {
  name: "Guest",
  components: {

    flatPickr,

  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      currentDateTime: null,
      cancel_reason: null,
      cancel_reasons: [
        {
          "Cancel_Reason_Id": 0,
          "Cancel_Reason_EN": "Not Applicable / No Reason",
          "Cancel_Reason_AR": " لايوجدسبب",
        },
        {
          "Cancel_Reason_Id": 1,
          "Cancel_Reason_EN": "Cancelled by Customer",
          "Cancel_Reason_AR": " إلغاء من قبل العميل",
        },
        {
          "Cancel_Reason_Id": 2,
          "Cancel_Reason_EN": "Cancelled by Hotel",
          "Cancel_Reason_AR": " إلغاء من قبل المنشأة",
        },
        {
          "Cancel_Reason_Id": 3,
          "Cancel_Reason_EN": "Customer Find better deal",
          "Cancel_Reason_AR": "العميل وجد خيار افضل",
        },
        {
          "Cancel_Reason_Id": 4,
          "Cancel_Reason_EN": "Customer was not satisfied",
          "Cancel_Reason_AR": "العميل غير راضي",
        },
        {
          "Cancel_Reason_Id": 5,
          "Cancel_Reason_EN": "Customer does not arrived on given date",
          "Cancel_Reason_AR": "النزيل لم يصل في اليوم المحدد",
        },
        {
          "Cancel_Reason_Id": 6,
          "Cancel_Reason_EN": "Customer changed the dates ",
          "Cancel_Reason_AR": "العميل قام بتغيير التواريخ",
        },
        {
          "Cancel_Reason_Id": 7,
          "Cancel_Reason_EN": "Customer cancelled this booking but rebooked again with different dates or travellers",
          "Cancel_Reason_AR": "تم الالغاء وعمل حجز جديد بتاريخ مختلفه",
        },
        {
          "Cancel_Reason_Id": 8,
          "Cancel_Reason_EN": "Other Reason",
          "Cancel_Reason_AR": "اسباب اخرى",
        },
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
      out_date_hijri: null,
      in_date_hijri: null,
      PaymentDialog: false,
      folio_no: null,
      arrival_date: null,
      departure_date: null,
      extended_date: null,
      window_number: null,
      window_id: null,
      Recepit_no: null,
      Amount: null,
      Descriptions: null,
      Payments: [],
      Payments_Selected: null,
      postingchargeDialog: false,
      ledgerCats: [],
      tax_included: false,
      amount: null,
      ledger_id: null,
      ledger_name: null,
      no_data: null,
      description: null,
      cashier_items: [
        {
          title: 'Cashier Payment',
          value: 'CaherPayment',
        },
        {
          title: 'Posting Charges',
          value: 'postingCharges',
        },
        {
          title: 'Go to folio',
          value: 'goToFolio',
        },
      ],
      vip: null,
      vips: ["A", "B", "C"],
      locked: null,
      extra_dialog: false,

      total_rate: 0,
      extra_amount: null,
      selected_extra: null,
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
      id_no_new: null,
      id_no_new_compo: null,
      All_id_num: null,
      Id_Num: [1, 4, 5, 6],
      email_new: null,
      city_new: null,
      created_by_name: null,
      address_new: null,
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
      disableratecode: true,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: 1,
      original_out_date: null,
      years: [],
      days: [],
      month: [],
      select_year: '',
      select_day: '',
      select_month: '',
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
      select_day_edit: '',
      select_month_edit: '',
      select_year_edit: '',
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
      moreNames: [],
      res_rate_days_changed: false,
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
    outDateValidator() {
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
          room.rm_name_loc.toLowerCase().includes(this.search) ||
          room.room_type.rm_type_name.toLowerCase().includes(this.search)
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
    room_rate(){
      this.total_rate = this.room_rate*this.no_of_nights
    },
    no_of_nights() {
      this.total_rate = this.room_rate*this.no_of_nights
    },
    response() {
      if (this.response != null) {
        this.res_rate_days_changed = false
        this.insertAlert("Reservation has been updated")
        if (
          this.response.is_reservation == 1 &&
          (this.response.res_status == "CNF" ||
            this.response.res_status == "NCF") &&
          this.response.is_checked_in == 0 &&
          this.response.in_date == this.SettingData.hotel_date
          && this.response.room != null
        ) {
          this.disableCheckin = false
        } else {
          this.disableCheckin = true
        }
      }
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
        // this.$router.push("/reservations")
      }
    },
    reservation() {
      if (this.reservation != null) {

        // this.in_date = this.reservation.in_date
        this.clear()
      }
    },
    available_roomtypes() {
      if (this.available_roomtypes != null) {
        const selected_roomtype = this.available_roomtypes.find(room_type => room_type.id == this.roomType?.id)

        this.roomType = selected_roomtype
        this.rooms = selected_roomtype.rooms
      }
    },
    select_month() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')

      // console.log(this.date_of_birth_new)
    },
    select_day() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')

      // console.log(this.date_of_birth_new)
    },
    select_year() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')

      // console.log(this.date_of_birth_new)
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {

    this.YeardData()
    this.MonthData()
    this.DayData()

    this.idtypeData()
    this.currentDateTime = new Date().toLocaleString()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
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
    goToFolio() {
      this.$router.push({
        name: `selectedFolio-folio`,
        params: { folio: this.reservation.id },
      })
    },
    async submitUpdateProfile() {
      console.log('date_of_birth_new', this.date_of_birth_new)
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
      }, this.reservation.profile.Profile_no)
      this.getReservationAction(this.reservation.id)
      this.profiledetailsDialog = false
    },

    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },
    inDateChanged() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, "days")
        this.out_date = inn.format("YYYY-MM-DD")
      }
      if (this.in_date == null) {
        this.in_date = moment().format("YYYY-MM-DD")
        this.in_date = this.reservation.in_date
        console.log('this.reservation', this.reservation)
      }

      this.in_date_hijri = this.$getHijriDate(this.in_date)
      this.fill_res_days()
    },
    outDateChanged() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date)
        var out = moment(this.out_date)
        this.no_of_nights = out.diff(inn, "days")
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      this.fill_res_days()
    },
    noOfNightsChanged() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, "days")
        this.out_date = inn.format("YYYY-MM-DD")
      }
      if (this.no_of_nights == null) {
        this.no_of_nights = 1
      }
      this.fill_res_days()
    },
    updateRoomRate() {
      if (this.rate_code != null) {
        for (
          var i = 0, len = this.rate_code.room_types.length;
          i < len;
          i++
        ) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.rate_code.room_types[i].id == this.roomType.id) {
            if (this.no_of_pax == 1) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax1
            }
            if (this.no_of_pax == 2) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax2
            }
            if (this.no_of_pax == 3) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax3
            }
            if (this.no_of_pax == 4) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax4
            }
            if (this.no_of_pax == 5) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax5
            }
            if (this.no_of_pax == 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.pax6
            }
            if (this.no_of_pax > 6) {
              this.room_rate = this.rate_code.room_types[i].pivot.extra_pax
            }
          }
        }
      }
    },
    rateCodeChanged() {
      if (this.rate_code) {
        if (this.rate_code.meal != null) {
          this.meal = this.rate_code.meal
        }
        if (this.rate_code.meal_package != null) {
          this.meal = this.rate_code.meal_package
        }
      }

      this.updateRoomRate()

      if (this.rate_code == null) {
        this.meal = null
      }

    },
    roomtypeChanged() {
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rooms = this.roomType.rooms
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
      }
      this.no_of_pax = this.roomType.def_pax
      this.updateRoomRate()
    },
    numberOfPaxChanged() {
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
      }
      this.updateRoomRate()
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
      'updateReservationFromOnlyNameToProfile',
      'updateGuestProfileAction',
      'getAvaliableRooms',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    getPayment() {
      axios.get(`${window.location.origin}/api/payment`).then(res => (this.Payments = res.data),
      )
    },
    CaherPayment() {
      this.PaymentDialog = true
      this.room_no = this.reservation.room != null ? this.reservation.room.rm_name_en : null
      this.folio_no = this.reservation.res_no
      this.guest_id = this.reservation.id
      this.window_number = this.reservation.windows[0].window_name
      this.window_id = this.reservation.windows[0].id
      this.departure_date = this.reservation.out_date
      this.currentDateTime = new Date().toLocaleString()
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname

    },
    async submitPayment() {
      // eslint-disable-next-line promise/valid-params
      await axios.post(`${window.location.origin}/api/transactions`, {
        'guest_id': this.guest_id,
        'room_id': this.reservation.room != null ? this.reservation.room.id : null,
        'ref_id': this.no_data,
        'description': this.Descriptions,
        'hotel_date': moment().format("YYYY-MM-DD"),
        'trans_type': 'P',
        'recd_type': 'REC',
        'inc': 0,
        'res_id': this.reservation.res_no,
        'is_reservation': this.reservation.is_reservation,
        'payment_amount': this.Amount,
        'window_id': this.reservation.windows[0].id,
        'payment_type_id': this.Payments_Selected.id,
      }).then(res => {
        if (res.status != undefined) {
          this.PaymentDialog = false
          this.insertAlert('Departure date has been Payment')
        }

      })


    },
    callMethod(methodName) {
      this[methodName]()
    },
    getledgerCats() {
      axios.get(`${window.location.origin}/api/ledger-cats`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.ledgerCats = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    postingCharges() {
      this.postingchargeDialog = true
      this.room_no = this.reservation.room != null ? this.reservation.room.rm_name_en : null
      this.folio_no = this.reservation.id
      this.arrival_date = this.reservation.in_date
      this.departure_date = this.reservation.out_date
      this.currentDateTime = new Date().toLocaleString()
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname

    },
    ledgerSelected(ledger) {
      this.ledger_id = ledger.id
      this.ledger_name = ledger.name
      this.description = ledger.name
    },
    async submitPostingCharge() {
      await axios.post(`${window.location.origin}/api/transactions`, {
        'guest_id': this.reservation.id,
        'room_id': this.reservation.room != null ? this.reservation.room.id : null,
        'hotel_date': moment().format("YYYY-MM-DD"),
        'window_id': this.reservation.windows[0].id,
        'ledger_id': this.ledger_id,
        'charge_amount': this.amount,
        'trans_type': 'C',
        'recd_type': 'CHR',
        'description': this.description,
        'is_reservation': this.reservation.is_reservation,
        'inc': this.tax_included == true ? 1 : 0,
      }).then(res => {
        if (res.status != undefined) {
          this.closePostingChargeDialog()
          this.insertAlert('Posting charge has been recorded')
        }

      })
    },
    closePostingChargeDialog() {
      this.postingchargeDialog = false
      this.amount = null
      this.ledger_id = null
      this.ledger_name = null
      this.description = null
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
    onFileChange(event) {
      this.files = event.target.files
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    clear() {
      // console.log('this.reservation.rate_code',this.reservation.rate_code)

      this.vip = this.reservation.vip
      this.locked = this.reservation.locked
      this.alert = true
      this.in_date = this.reservation.in_date
      this.out_date = this.reservation.out_date
      this.roomType = this.reservation.roomType
      this.getAvaliableRooms(this.in_date, this.out_date)
      this.out_date_hijri = this.$getHijriDate(this.out_date)
      this.in_date_hijri = this.$getHijriDate(this.in_date)
      this.no_of_nights = this.reservation.no_of_nights
      this.no_of_pax = this.reservation.no_of_pax
      this.original_out_date = this.reservation.original_out_date
      this.room = this.reservation.room != null ? this.reservation.room : null
      this.rate_code = this.reservation.rate_code
      this.meal = this.reservation.rate_code != null ? (this.reservation.rate_code.meal != null ? this.reservation.rate_code.meal.name : (this.reservation.rate_code.meal_package ? this.reservation.rate_code.meal_package.name : null)) : null
      this.hotel_note = this.reservation.hotel_note
      this.client_note = this.reservation.client_note
      this.vat_no = this.reservation.vat_no
      this.company_name = this.reservation.company_name
      this.inv_address = this.reservation.inv_address
      this.market_segment_id = this.reservation.market_segment
      this.source_of_business_id = this.reservation.source_of_business
      this.selected_guest = this.reservation.profile.id != 1 ? this.reservation.profile : null
      for (let index = 0; index < this.payments.length; index++) {
        if (this.payments[index].code == this.reservation.way_of_payment) {
          this.way_of_payment = this.payments[index]
        }
      }
      for (let index = 0; index < this.reservations_statuses.length; index++) {
        if (this.reservations_statuses[index].res_status_code == this.reservation.res_status) {
          this.reservations_status = this.reservations_statuses[index]
        }
      }
      this.company_id = this.reservation.company
      this.ref_no = this.reservation.ref_no
      console.log(this.reservation.res_rate_day)
      for (let i = 0; i < this.reservation.res_rate_day.length; i++) {
        this.ratecode[i + 1] = this.reservation.res_rate_day[i].rate_code
        this.roomrate[i + 1] = this.reservation.res_rate_day[i].rm_rate
      }
      if (this.reservation.moreName) {
        this.no_of_companions = this.reservation.moreName.length
        for (let index = 1; index <= this.reservation.moreName.length; index++) {
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
      this.room_rate = this.reservation.rm_rate
      if (this.reservation.purpose_of_visit != null) {
        for (let i = 0; i < this.purposeOfVisitList.length; i++) {
          if (this.reservation.purpose_of_visit == this.purposeOfVisitList[i].id) {
            this.purpose_of_visit = this.purposeOfVisitList[i]
          }
        }
      }
      console.log(this.reservation.customer_type)
      if (this.reservation.customer_type != null) {
        for (let i = 0; i < this.customerTypeList.length; i++) {
          if (this.reservation.customer_type == this.customerTypeList[i].id) {

            this.customer_type = this.customerTypeList[i]
          }
        }
      }
      if (this.reservation.is_reservation == 1 && (this.reservation.res_status == "CNF" || this.reservation.res_status == "NCF") && this.reservation.is_checked_in == 0 && this.reservation.in_date == this.SettingData.hotel_date && this.reservation.room != null) {
        this.disableCheckin = false
      } else {
        this.disableCheckin = true
      }
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
      }


    },
    uploadfile() {
      let can_add_files = 1
      if (this.reservation.attachment == null) {
        if (this.files.length > 3) {
          this.DangerAlert('Only 3 files is allowed')
          can_add_files = 0
        }
      } else {
        if (this.files.length + this.reservation.attachment.length > 3) {
          this.DangerAlert('Only 3 files is allowed')
          can_add_files = 0
        }
      }
      if (can_add_files == 1) {
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
            this.files = []

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
          if (res.status != undefined) {
            this.insertAlert('File has been deleted')
            this.reservation.attachment.splice(index, 1)

          }
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
    async UpdateReservation() {
      if (this.res_rate_days_changed) {
        this.save_res_days()
      }
      if (this.formValues.length == 0) {
        this.fill_res_days()
        this.save_res_days()
      }
      if (this.reservation.profile.id != 1) {
        const response = await this.UpdateReservationAction(this.reservation.id, {
          profile_id: this.reservation.profile.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null ? this.rate_code.meal_package_id : null,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no != null ? this.ref_no : null,
          res_status: this.reservations_status.res_status_code,
          res_rate_days: this.formValues != null ? this.formValues : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })

        if (response.status != undefined) {
       this.getReservationAction(this.$route.params.reservation)
        }
      }

      if (this.reservation.profile.id == 1 && this.selected_guest.id != null) {
        //call change reservation from only name to profile
        const response = await this.updateReservationFromOnlyNameToProfile({
          guest_id: this.reservation.id,
          profile_id: this.selected_guest.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null ? this.rate_code.meal_package_id : null,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no != null ? this.ref_no : null,
          res_status: this.reservations_status.res_status_code,
          res_rate_days: this.formValues != null ? this.formValues : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })

        if (response.status != undefined) {
          this.getReservationAction(this.$route.params.reservation)
        }
      }
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
    DangerAlert() {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: 'Error',
        showConfirmButton: false,
        timer: 2000,
      })
    },
    clear_res_days() {
      this.ratecode = []
      this.roomrate = []
    },
    fill_res_days() {
      console.log('fill', this.roomrate)
      if (this.formValues.length == 0) {
        for (let i = 1; i <= this.no_of_nights; i++) {
          if (this.ratecode[i] == null) {
            this.ratecode[i] = this.rate_code
          }
          if (this.roomrate[i] == null) {
            this.roomrate[i] = this.room_rate
          }

        }
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
      console.log(this.roomrate)
      for (let i = 1; i <= this.no_of_nights; i++) {
        const formValue = {
          day_date: this.getStartDate(i),
          rate_id: this.ratecode[i] != null ? this.ratecode[i].id : null,
          meal_id:
            this.ratecode[i] != null && this.ratecode[i].meal_id != null
              ? this.ratecode[i].meal_id
              : null,
          meal_package_id:
            this.ratecode[i] != null && this.ratecode[i].meal_package_id != null
              ? this.ratecode[i].meal_package_id
              : null,
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
      this.res_rate_days_changed = true
      this.reservation_days_dialog = false
    },
    checkinfunction() {
      if (this.reservation.profile.id == 1) {
        this.DangerAlert("Please select or create profile to checkin")
      } else {
        Swal.fire({
          title: 'Do you want to Checkin?',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Save',

        }).then(result => {
          console.log('mohamed')
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {

            this.GuestCheckinAction(this.reservation.id)
            this.$router.push({
              name: `selectedFolio-folio`,
              params: { folio: this.reservation.id },
            })
          }
        })

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
    RemoveCompanion(index) {
      if (this.no_of_companions >= 1) {
        this.profile_id_companion.splice(index, 1)
        this.country_id_companion.splice(index, 1)
        this.first_name_companion.splice(index, 1)
        this.last_name_companion.splice(index, 1)
        this.id_no_companion.splice(index, 1)
        this.mobile_companion.splice(index, 1)
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
        this.id_no = this.selected_guest.id_no
        this.gender = this.selected_guest.gender
        this.mobile = this.selected_guest.mobile
        this.phone = this.selected_guest.phone
        this.email = this.selected_guest.email
        this.city = this.selected_guest.city
        this.address = this.selected_guest.address
        this.select_day  = this.selected_guest.date_of_birth.slice(8, 10)
        this.select_month  = this.selected_guest.date_of_birth.slice(5, 7)
        this.select_year  = this.selected_guest.date_of_birth.slice(0, 4)
        this.country = this.selected_guest.country.name
        this.language = this.selected_guest.language
      }
    },
    clearSeletedProfile() {


      this.alert = false

      this.selected_guest = ""
      this.id_no_search = ""
      this.mobile_search = ""



    },
    Clear() {
      this.guest_extras = []
      this.extras_show = false
      this.selected_guest = ""
      this.id_no_search = ""
      this.mobile_search = ""
      this.showprofilealert = true
      this.in_date = this.SettingData.hotel_date
      this.out_date = moment(this.in_date).add(1, 'days').format("YYYY-MM-DD")
      this.no_of_nights = 1
      this.original_out_date = ""
      this.no_of_pax = null
      this.room_rate = 0
      this.meal = ""
      this.hotel_note = ""
      this.client_note = ""
      this.way_of_payment = this.payments[0]
      this.company_id = this.companies[0]
      this.market_segment_id = this.segments[0]
      this.source_of_business_id = this.sources[0]
      this.ref_no = ""
      this.reservations_status = this.reservations_statuses[0]
      this.vat_no = ""
      this.company_name = ""
      this.inv_address = ""
      this.purpose_of_visit = null
      this.customer_type = null
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
          console.log(res)
          if (res.status === 200) { // Check the status code
            this.createProfileDialog = false
            this.insertAlert("Profile created successfully")
            this.selected_guest = res.data.data[0]
            this.showprofilealert = false
            this.noProfileSelected = false
          }
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
    this.getledgerCats()
    this.getPayment()
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
    grid: 50px 50px /  140px 140px;
    justify-content: space-between;
  }
}
@media screen and (min-width: 768px) {
  .buttons_control {
    display: flex;
    justify-content: space-around;
  }
}
.error-label {
  color: red;
}
</style>
