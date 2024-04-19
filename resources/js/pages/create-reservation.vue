<script setup>
import { requiredValidator, betweenValidator } from "@validators"

const refForm = ref()
const form = ref()
const formprofile = ref()
</script>

<template>
  <div>

    <Breadcrumb />
    <CreateProfile2  @select-profile="selectProfile" @show-profile="showGuestProfile" @clear-profile="clearSeletedProfile"   :guestFromParent="selected_guest" />

    <VCard
      class="mt-3 pa-8"
      :disabled="reservation != null || selected_guest == null"
    >
      <VContainer>
        <VAlert
          v-show="!showprofilealert"
          variant="tonal"
          color="success"
          class="mb-5"
        >
          {{ $t("Selected profile:") }}
          {{ selected_guest != null ? selected_guest.first_name + " " + selected_guest.last_name : null }}
          {{ selected_guest != null ? selected_guest.id_number != null ? "( Profile )" : "( Only name )" : null }}
        </VAlert>
        <VForm
          ref="refForm"
          @submit.prevent="CreateReservation"
        >
          <VRow class="mb-2">
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="in_date"
                :label="$t('In date*')"
                :rules="[requiredValidator, inDateValidator]"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d', minDate: moment(SettingData.hotel_date).format('YYYY-MM-DD') }"
                @keyup.enter="goNext($event.target)"
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
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_nights"
                :label="$t('Number of nights*')"
                type="number"
                :rules="[requiredValidator]"
                @keyup.enter="goNext($event.target)"
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
                :config="configOutDate"
                @keyup.enter="goNext($event.target)"
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
                @keyup.enter="goNext($event.target)"
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
                :rules="[requiredValidator]"
                @update:modelValue="roomTypechanged"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="3"
              style="display: flex;justify-content: space-between;"
            >
              <VCombobox
                v-model="room"
                :label="$t('Room')"
                :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'
                "
                item-value="room"
                style="width: 40%;"
                readonly
                @keyup.enter="goNext($event.target)"
              />
              <VDialog
                v-model="dialog"
                width="1024"
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
                          clearable
                        />
                      </VCol>
                    </VRow>
                  </VCardTitle>
                  <VCardText>
                    <VTable height="600">
                      <thead>
                        <tr>
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
              <VIcon
                icon="tabler-trash"
                size="30"
                color="red"
                style="margin: 2%;"
                @click="room = null"
              />
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
                :rules="[requiredValidator,betweenValidator(no_of_pax,0,room?room.rm_max_pax:(roomType?roomType.def_pax:0))]"
                @update:modelValue="noOfPaxchanged"
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
              md="5"
            >
              <VCombobox
                v-model="rate_code"
                :label="$t('Rate code')"
                :items="rate_codes"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                :disabled="disableratecode"
                @update:modelValue="rateCodechanged"
                @keyup.enter="goNext($event.target)"
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
                readonly
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VForm ref="form">
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
                          <span class="text-h5">{{
                            $t("Reservation days")
                          }}</span>
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
                          <VTextField
                            v-model="day[index]"
                            :value="getStartDate(index)"
                            readonly
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="3"
                          md="4"
                        >
                          <VTextField
                            v-model="roomrate[index]"
                            :label="$t('Room rate*')"
                            type="number"
                            :rules="[requiredValidator]"
                            @keyup.enter="goNext($event.target)"
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
                      <VBtn @click.prevent="form?.validate().then(res => { res.valid ? save_res_days() : null })">
                        {{ $t("Save") }}
                      </VBtn>
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VForm>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="purpose_of_visit"
                :label="$t('Purpose of visit') + '*'"
                :items="purposeOfVisitList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
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
                v-model="customer_type"
                :label="$t('Customer type') + '*'"
                :items="customerTypeList"
                clearable
                :item-title="$i18n.locale === 'en' ? 'nameE' : 'nameA'"
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
                :item-title="$i18n.locale == 'en' ? 'company_name' : 'company_name_loc'
                "
                item-value="id"
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
                v-model="market_segment_id"
                :label="$t('Market Segment')"
                :items="segments"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
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
                v-model="source_of_business_id"
                :label="$t('Source of business')"
                :items="sources"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
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
                v-model="reservations_status"
                :label="$t('Reservation status*')"
                :items="reservations_statuses"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
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
                auto-grow
                rows="3"
                row-height="15"
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
                auto-grow
                rows="3"
                row-height="15"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="12"
            >
              <VExpansionPanels
                variant="inset"
                class="mb-5 mt-5"
              >
                <VExpansionPanel
                  v-for="item in 1"
                  :key="item"
                >
                  <VExpansionPanelTitle>Companions</VExpansionPanelTitle>
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
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
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
                            @keyup.enter="goNext($event.target)"
                          />
                        </VCol>
                        <VSnackbar
                          v-model="more_isSnackbarVisible"
                          location="top"
                          :timeout="2000"
                          @keyup.enter="goNext($event.target)"
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
                            z-index="1000"
                            scroll-strategy="none"
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
                            {{ $t("clear") }}
                          </VBtn>
                        </VCol>
                      </VRow>
                    </div>
                  </VExpansionPanelText>
                </VExpansionPanel>
              </VExpansionPanels>
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
          <VBtn
            class="float-end mb-4"
            type="submit"
          >
            {{ $t("Submit") }}
          </VBtn>
        </VForm>
      </VContainer>
    </VCard>

    <br>
    <VCol class="buttons_control">
      <VBtn
        :disabled="disableCheckin"
        @click="checkinfunction"
      >
        {{ $t("check in") }}
      </VBtn>

      <VMenu location="bottom">
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

      <VBtn @click="GoToUpdateReservation">
        {{ $t("update Reservation") }}
      </VBtn>


      <VDialog
        v-model="extra_dialog"
        width="1300"
        z-index="1000"
        scroll-strategy="none"
      >
        <template #activator="{ props }">
          <VBtn v-bind="props">
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
        class="float-end"
        @click="clear"
      >
        {{ $t("Reset") }}
      </VBtn>
    </VCol>
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
                  md="6"
                  class="mt-8"
                />
                <VCol
                  cols="12"
                  sm="6"
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
          <span class="text-h4 ">Cacher Payment</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VForm ref="refForm">
              <VRow>
                <VCol
                  cols="12"
                  sm="6"
                  md="4"
                >
                  <VTextField
                    v-model="room_no"
                    label="Room"
                    readonly
                    @keyup.enter="goNext($event.target)"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="4"
                >
                  <VTextField
                    v-model="guest_name"
                    label="guest name"
                    readonly
                    @keyup.enter="goNext($event.target)"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="4"
                >
                  <VTextField
                    v-model="window_number"
                    label="window name"
                    readonly
                    @keyup.enter="goNext($event.target)"
                  />
                </VCol>
              </VRow>


              <VRow>
                <VCol
                  cols="6"
                  sm="6"
                  md="6"
                >
                  <VCol
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <VTextField
                      v-model="Amount"
                      label="Amount"
                      type="number"
                      @keyup.enter="goNext($event.target)"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <VCombobox
                      v-model="Payments_Selected"
                      :items="Payments"
                      item-title="name"
                      item-value="Payments_Selected"
                      label="Way of Payments"
                      @keyup.enter="goNext($event.target)"
                    />
                  </VCol>
                  <VCol
                    class="Date_inputs"
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <VTextField
                      v-model="no_data"
                      label="Ref no"
                      @keyup.enter="goNext($event.target)"
                    />
                  </VCol>
                </VCol>
                <VCol
                  cols="6"
                  sm="6"
                  md="6"
                >
                  <VTextarea
                    v-model="Descriptions"
                    label="Descriptions"
                    rows="7.5"
                    @keyup.enter="goNext($event.target)"
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
                        {{ user_name }}
                      </h6>
                      <span> {{ SettingData?.hotel_date }}  {{ currentTime }}</span>
                    </VCardText>
                  </VCard>
                </VCol>
              </VRow>
            </VForm>
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
            @click.prevent="form?.validate().then(res => { res.valid ? submitPayment() : null })"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script>
import { useRoomStore } from "@/stores/RoomStore"
import CreateProfile from "@/components/CreateProfile.vue"
import axios from "@axios"
import moment from "moment"
import { mapActions, mapStores } from "pinia"
import Swal from "sweetalert2"
import { useExtrasStore } from "../stores/ExtrasStore"
import { useGeneralStore } from "../stores/GeneralStore"
import { useGuestStore } from "../stores/GuestStore"
import { useLedgerStore } from '../stores/LedgerStore'
import { useRatecodeStore } from "../stores/RatecodeStore"

export default {
  name: "Guest",
  components: {
    CreateProfile,
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      configOutDate: {
        minDate: this.in_date, altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',
      },
      purpose_of_visit: null,
      Id_Num_ver: [],
      version_num: null,
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
      ],
      vip: null,
      vips: ["A", "B", "C"],
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      extras_show: false,
      files: [],
      vat_no: null,
      company_name: null,
      inv_address: null,
      reservation: null,
      gender_data: ["male", "female", "no gender found"],
      years: [],
      days: [],
      month: [],
      select_year: '',
      select_day: '',
      select_month: '',
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
      search: null,
      dialog: false,
      searchDialog: false,
      profiledetailsDialog: false,
      disableratecode: false,
      isSnackbarVisible: false,
      showprofilealert: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: 1,
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
      first_name: null,
      last_name: null,
      mobile: null,
      phone: null,
      id_number: null,
      email: null,
      city: null,
      address: null,
      date_of_birth: null,
      gender: null,
      store_from_landlord: null,
      total_rate: 0,
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
      profileId: null,
      reservations_status: null,
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
      noProfileSelected: true,
      selected_guest: null,
      isLoading: false,
      idtypes: [],
      timer: null,

      URL: window.location.origin,
      currentTime: this.getCurrentTime(),
    }
  },
  mounted() {
    //this.StoreLand()
    this.YeardData()
    this.MonthData()
    this.DayData()
    this.idtypeData()

    const roomStore = useRoomStore()

    this.isLoading = true

    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
    for (let i = 1 ; i<= 30 ; i++){
      this.Id_Num_ver.push(i)

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
      useLedgerStore,

    ),

    inDateValidator() {
      return value => {
        if (!value) {
          return true // Allow if the value is empty
        }

        const selectedDate = moment(value, 'YYYY-MM-DD')

        //console.log('vali',moment(this.SettingData.hotel_date,'YYYY-MM-DD') )
        return selectedDate >= moment(this.SettingData.hotel_date, 'YYYY-MM-DD') || 'In Date must be on or after the hotel date'
      }
    },
    outDateValidator() {
      return value => {
        if (!value) {
          return true // Allow if the value is empty
        }
        const selectedDate = moment(value, 'YYYY-MM-DD')

        return selectedDate >= moment(this.SettingData.hotel_date, 'YYYY-MM-DD') || 'Out Date must be on or after the In date'
      }
    },
    minDateForOut() {
      console.log('comp', this.in_date)

      return this.in_date
    },
    filterData() {
      return this.rooms.filter(room => {
        return (
          room.rm_name_en.toLowerCase().includes(this.search) ||
          room.rm_name_loc.toLowerCase().includes(this.search)
        )
      })
    },
    selected_profile_for_reservation() {
      return this.guestStore.selected_profile_for_reservation
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
    ledgers() {
      return this.ledgerStore.ledgers
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    // id_no_new(num){
    //   this.id_no_new = num.concat(' ', '|');
    // },
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
    room_rate() {
      this.total_rate = this.room_rate * this.no_of_nights
    },

    in_date() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date.substring(0, 10))
        inn.add(this.no_of_nights, "days")

        // console.log('in',inn.format("YYYY-MM-DD"))
        this.out_date = inn.format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date.substring(0, 10))
        this.configOutDate.minDate = this.in_date
      }
      if (this.in_date == null) {
        this.in_date = moment().format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date).substring(0, 10)
      }
    },
    no_of_nights() {
      if (this.no_of_nights == null) {
        this.no_of_nights = 1
      }
      else {
        var inn = moment(this.in_date.substring(0, 10))
        inn.add(this.no_of_nights, "days")
        console.log(inn)
        this.out_date = inn.format("YYYY-MM-DD")
        this.configOutDate.minDate = this.in_date
      }
      this.total_rate = this.room_rate * this.no_of_nights
    },
    async out_date() {
      if (this.no_of_nights != null) {
        var inn = moment(this.in_date.substring(0, 10))
        var out = moment(this.out_date.substring(0, 10))
        this.no_of_nights = out.diff(inn, "days")
        this.out_date_hijri = this.$getHijriDate(this.out_date.substring(0, 10))
      }

      if (!this.isLoading) {
        const response = await this.getAvaliableRooms(this.in_date.substring(0, 10), this.out_date.substring(0, 10))
        if (response.status == 200) {
          console.log('aaaa')
          this.fillNewRoomType()
        }
      }
      this.roomId()
      this.isLoading = false
    },
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
          this.reservation.in_date == this.SettingData.hotel_date
          && this.reservation.room != null
        ) {
          this.disableCheckin = false
        } else {
          this.disableCheckin = true
        }
        this.resetResponse()
      }
    },
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
      }
    },
    reservations_statuses() {
      this.clear()
      if (this.selected_profile_for_reservation != null) {
        this.selected_guest = this.selected_profile_for_reservation
        this.showprofilealert = false
        this.nullSelectProfileForReservation()
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

  // eslint-disable-next-line vue/component-api-style
  methods: {
    handleSelectedGuest(guest) {
      this.selected_guest = guest
    },
    StoreLand(){
     console.log('this.selected_guest.Profile_no', this.selected_guest)
      axios.get(`${window.location.origin}/api/store_from_landlord/${this.selected_guest.profile_no}`).then(res => (this.store_from_landlord = res.data
      , this.selected_guest=this.store_from_landlord.data))
    },
    handleCustomEvent(data) {
      console.log(data) // Output: Data from child!
    },
    async submitUpdateProfile() {
      //console.log('date_of_birth_new', this.showGuestProfile)
      console.log('selected guest', this.selected_guest)
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
      }, this.selected_guest.id)

      this.profiledetailsDialog = false
    },
    idtypeData(){
      axios.get(`${this.URL}/api/idtype`).then(res =>(this.idtypes = res.data ))
    },
    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)

      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
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
    ...mapActions(useLedgerStore, ['getLedgers']),
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      'resetResponse',
      'updateGuestProfileAction',
      'getAvaliableRooms',
      'getGuestprofileAction',
      'nullSelectProfileForReservation',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    fillNewRoomType() {
      this.roomType = this.available_roomtypes.find(roomtype => roomtype.id == this.roomType.id)

      this.rooms = this.roomType.rooms
      this.roomId()
    },
    roomId() {
      const roomStore = useRoomStore()

      //console.log('room rack id',roomStore.roomId.room_id)
      if (roomStore.roomId?.room_type_id) {
        this.roomType = this.available_roomtypes.find(roomtype => roomtype.id == roomStore.roomId.room_type_id)
        this.rooms = this.roomType?.rooms
        var roomFromRack = this.rooms?.find(element => element.id == roomStore.roomId.room_id)


        return this.room = roomFromRack
      }
    },
    noOfPaxchanged() {
      console.log(this.no_of_pax)
      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rateCodechanged()
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
      }
      this.no_of_companions = this.no_of_pax - 1
    },
    roomTypechanged() {

      if (this.roomType != null && this.no_of_pax != null) {
        this.disableratecode = false
        this.rooms = this.roomType.rooms

        this.rateCodechanged()
      } else {
        this.disableratecode = true
        this.rate_code = null
        this.room_rate = 0
        this.rooms = []
      }

      // this.no_of_pax = this.roomType.def_pax
    },


    rateCodechanged() {
      if (this.rate_code != null) {
        for (var i = 0, len = this.rate_code.room_types.length; i < len; i++) {
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
        if (this.rate_code.meal != null) {
          this.meal = this.rate_code.meal
        }
        if (this.rate_code.meal_package != null) {
          this.meal = this.rate_code.meal_package
        }
      }
    },
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
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
    },
    submitPayment() {
      // eslint-disable-next-line promise/valid-params
      axios.post(`${window.location.origin}/api/transactions`, {
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
        this.PaymentDialog = false
        this.insertAlert('Departure date has been Payment')
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
        })
    },
    postingCharges() {
      this.postingchargeDialog = true
      this.room_no = this.reservation.room != null ? this.reservation.room.rm_name_en : null
      this.folio_no = this.reservation.id
      this.arrival_date = this.reservation.in_date
      this.departure_date = this.reservation.out_date
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
    },
    ledgerSelected(ledger) {
      this.ledger_id = ledger.id
      this.ledger_name = ledger.name
      this.description = ledger.name
    },
    submitPostingCharge() {
      axios.post(`${window.location.origin}/api/transactions`, {
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
        this.closePostingChargeDialog()
        this.insertAlert('Posting charge has been recorded')
      })
    },
    closePostingChargeDialog() {
      this.postingchargeDialog = false
      this.amount = null
      this.ledger_id = null
      this.ledger_name = null
      this.description = null
    },
    GoToUpdateReservation() {
      this.$router.push({
        name: `reservation-update-reservation`,
        params: { reservation: this.reservation.id },
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
          },
        )
      }


    },
    clear() {

      this.vip = null
      this.guest_extras = []
      this.extras_show = false
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true
      this.in_date = this.SettingData.hotel_date
      this.out_date = moment(this.in_date).add(1, 'days').format("YYYY-MM-DD")
      this.no_of_nights = 1
      this.original_out_date = null
      this.room = null
      this.roomType = this.available_roomtypes[0]
      this.no_of_pax = this.available_roomtypes[0].def_pax
      this.rooms = this.roomType.rooms
      this.room_rate = 0
      this.rate_code = null
      this.meal = null
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
      this.reservation = null
      this.purpose_of_visit = null
      this.customer_type = null


    },
    async  CreateReservation() {

      if (this.formValues.length == 0) {
        this.fill_res_days()
        this.save_res_days()
      }
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
      const onlyname = []

      console.log('this.selected_guest', this.selected_guest)
      if (this.selected_guest.id_number == null) {
        onlyname.push(this.selected_guest)
        this.CreateGuestAction({
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null && this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null && this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_package_id : null,
          rm_rate: this.room_rate,
          hotel_note: this.hotel_note,
          client_note: this.client_note,
          way_of_payment: this.way_of_payment.code,
          company_id: this.company_id.id,
          market_segment_id: this.market_segment_id.id,
          source_of_business_id: this.source_of_business_id.id,
          ref_no: this.ref_no,
          res_status: this.reservations_status.res_status_code,
          res_rate_days: this.formValues,
          onlyNameData: onlyname.length != 0 ? onlyname : null,
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "D",
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })
      } else {
        this.StoreLand()
        this.CreateGuestAction({
          profile_id: this.selected_guest.id,
          in_date: this.in_date,
          out_date: this.out_date,
          original_out_date: this.out_date,
          no_of_nights: this.no_of_nights,
          room_id: this.room != null && this.room != null ? this.room.id : null,
          room_type_id: this.roomType.id,
          no_of_pax: this.no_of_pax,
          rate_code_id: this.rate_code != null && this.rate_code != null ? this.rate_code.id : null,
          meal_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_id : null,
          meal_package_id: this.rate_code != null && this.rate_code != null ? this.rate_code.meal_package_id : null,
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
          moreNameData: this.data != null ? this.data : null,
          vat_no: this.vat_no,
          company_name: this.company_name,
          inv_address: this.inv_address,
          vip: this.vip,
          res_type: "D",
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })
      }
      const roomStore = useRoomStore()



      roomStore.setRoomId(null)



    },
    selectroom(room) {
      this.dialog = false
      this.no_of_pax = room.rm_max_pax
      console.log(room.rm_max_pax)
      this.room = room
    },
    selectProfile(guest) {

      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
      this.StoreLand()
    },
    showGuestProfile() {

      this.profiledetailsDialog = true
      console.log('this.selected_guest',this.selected_guest)
      if (this.selected_guest == null) {
        this.isSnackbarVisible = true
        this.profiledetailsDialog = false
      } else {
        this.first_name = this.selected_guest.first_name
        this.last_name = this.selected_guest.last_name
        this.id_number = this.selected_guest.id_number
        this.gender = this.selected_guest.gender
        this.mobile = this.selected_guest.mobile
        this.phone = this.selected_guest.phone
        this.email = this.selected_guest.email
        this.city = this.selected_guest.city
        this.address = this.selected_guest.address
        this.country = this.selected_guest.country.name
        this.language = this.selected_guest.language
        this.select_day = this.selected_guest.date_of_birth.slice(8, 10)
        this.select_month = this.selected_guest.date_of_birth.slice(5, 7)
        this.select_year = this.selected_guest.date_of_birth.slice(0, 4)
 
      }
    },
    clearSeletedProfile() {
      this.selected_guest = null
      this.id_no_search = null
      this.mobile_search = null
      this.showprofilealert = true

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


      if (this.id_no_new != null) {
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
              version_no: this.version_num,
            },
          )
          .then(res => {
            if (res.status != undefined) {
              this.createProfileDialog = false
              this.insertAlert("profile created successfully")
              this.selected_guest = res.data.data[0]
              this.showprofilealert = false
              this.noProfileSelected = false
              console.log(res.data.data[0].id)
              this.profileId = res.data.data[0].id
            }

          })
          .catch(err => {
          })
      } else {
        this.more_isSnackbarVisible = true

        this.selected_guest = {
          first_name: null,
          last_name: null,
          mobile: null,
          email: null,
          id_number: null,

        }
        this.DangerAlert("Only name profile")
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
    clear_res_days() {
      this.ratecode = []
      this.roomrate = []
    },
    fill_res_days() {
      for (let i = 1; i <= this.no_of_nights; i++) {
        this.ratecode[i] = this.rate_code != null && this.rate_code != null ? this.rate_code : null
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

      this.reservation_days_dialog = false
    },
    checkinfunction() {
      if (this.reservation.profile.id == 1) {
        this.DangerAlert("please choose or create full profile to checkin")
      } else {
        Swal.fire({
          title: 'Do you want to Checkin?',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Save',

        }).then(result => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            this.GuestCheckinAction(this.reservation.id)
          }
        })

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
    const response = await this.getAvaliableRooms(this.SettingData.hotel_date, moment(this.SettingData.hotel_date).add(1, 'days').format("YYYY-MM-DD"))

    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getledgerCats()
    this.getPayment()

    if (this.countries.length == 0) {
      this.getCountriesAction()
    }
    if (response.status != undefined) {

      this.getReservationstatusesAction()
    }
    this.getExtras()



  },
  unmounted(to, from, next) {
    const roomStore = useRoomStore()

    roomStore.setRoomId(null)

  },

}
</script>

<style scoped>

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
    grid: 50px 50px / 150px 150px;
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
<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
<route lang="yaml">
meta:
layout: blank
</route>
