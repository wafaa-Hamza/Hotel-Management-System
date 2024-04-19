<script setup>
import { requiredValidator } from "@validators"
import { vMaska } from "maska"
</script>

<template>
  <div>
    <Breadcrumb />

<CreateProfile2   @select-profile="selectProfile"  @show-profile="showGuestProfile" @clear-profile="clearSeletedProfile"   :guestFromParent="selected_guest" />
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
          {{ selected_guest.first_name + " " + selected_guest.last_name }}
          {{ selected_guest.id_no != "" ? "( Profile )" : "( Only name )" }}
        </VAlert>
        <VForm
          ref="refForm"
          @submit.prevent="CreateReservation"
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
                :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData.hotel_date).subtract(1, 'days').format('YYYY-MM-DD') }], altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
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
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="out_date"
                :label="$t('Out date*')"
                :rules="[requiredValidator, outDateValidator]"
                @keyup.enter="goNext($event.target)"
                :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData.hotel_date).format('YYYY-MM-DD') }], altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
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
              md="4"
            >
              <VCombobox
                v-model="rate_code"
                :label="$t('Rate code')"
                :items="rate_codes"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="rate_code"
                clearable
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox
                v-model="meal"
                :label="$t('Meal or Meal package')"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                readonly
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="4"
            >
              <VTextField
                v-model="no_of_pax"
                :label="$t('Total PAX*')"
                type="number"
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
                :item-title="$i18n.locale == 'en' ? 'company_name' : 'company_name_loc'
                "
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
                v-model="market_segment_id"
                :label="$t('Market Segment')"
                :items="segments"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
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
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
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
                :label="$t('INV Company name')"
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
                rows="3"
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
                ref="fileInput"
                show-size
                multiple
                label="File input"
                clearable
              />
            </VCol>
          </VRow>
        </VForm>
      </VContainer>
    </VCard>

    <VCard
      class="mt-6"
      :disabled="noProfileSelected"
    >
      <VCardTitle>
        <VRow>
          <VCol
            cols="12"
            sm="6"
            md="9"
          >
            <span>Select number of rooms for every roomtype</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <span>Total rooms: {{ total_rooms }}</span>
          </VCol>
        </VRow>
      </VCardTitle>
      <VCardText class="mt-3">
        <VTable class="table-container">
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
          <tr
            v-for="(item, index) in available_roomtypes"
            :key="index"
          >
            <td>
              {{ item.rm_type_name }}
            </td>
            <td>{{ item.countOfType }}</td>
            <td>
              <VTextField
                v-model="required_rooms[index]"
                type="number"
                style="width: 100px;"
              />
            </td>
          </tr>
          </tbody>
        </VTable>
        <VBtn
          class="mt-3 float-end mb-3"
          @click="SubmitRoomsFromRoomtypes"
        >
          {{ $t("Submit") }}
        </VBtn>
      </VCardText>
    </VCard>
    <VCard
      class="mt-6"
      :disabled="noProfileSelected"
    >
      <VCardTitle>
        <VProgressCircular
          v-show="loading"
          :size="50"
          color="primary"
          indeterminate
        />
        <VRow>
          <VCol
            cols="12"
            sm="6"
            md="9"
          >
            <span>Rooms</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <span>Total PAX: {{ no_of_pax }}</span>
          </VCol>
        </VRow>
      </VCardTitle>
      <VCardText class="mt-3">
        <VTable
          class="table-container"
          height="300px"
        >
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
          </tr>
          </thead>
          <tbody>
          <template
            v-for="(item, index) in flattenedRows"
            :key="index"
          >
            <tr>
              <td style="display: flex;padding-top: 2%;">
                <AppCombobox
                  v-model="room[index]"
                  :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'"
                  item-value="room"
                  style="width: 150px;"
                  readonly
                />
                <div>
                  <VIcon
                    class="ml-2 mr-2 mt-2"
                    @click="selectRoom(index, group_room_type[index].id)"
                  >
                    tabler-ballpen
                  </VIcon>
                  <VIcon
                    class="mt-2"
                    @click="removeRoom(index, group_room_type[index].id)"
                  >
                    tabler-trash
                  </VIcon>
                </div>
              </td>
              <td>
                <AppCombobox
                  v-model="group_room_type[index]"
                  type="number"
                  style="width: 150px;"
                  readonly
                  :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                  item-value="room_type"
                />
              </td>
              <td>
                <AppCombobox
                  v-model="group_rate_code[index]"
                  :items="rate_codes"
                  :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                  item-value="id"
                  style="width: 200px;"
                  clearable
                />
              </td>
              <td>
                <VTextField
                  v-model="pax[index]"
                  type="number"
                  style="width: 100px;"
                  min="1"
                  @focus="setTouchedField(index)"
                />
              </td>
              <td>
                <VTextField
                  v-model="rate[index]"
                  type="number"
                  style="width: 100px;"
                  min="1"
                  @change="showRate(index)"
                />
              </td>
            </tr>
          </template>
          </tbody>
        </VTable>
        <div class="button-container">
          <VDialog
            v-model="extra_dialog"
            width="1300"
            scroll-strategy="none"
          >
            <template #activator="{ props }">
              <VBtn
                v-if="extras_show"
                v-bind="props"
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
                <div>
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
                <VBtn @click="updateExtras">
                  {{ $t("Update") }}
                </VBtn>
              </VCardActions>
            </VCard>
          </VDialog>
          <VMenu location="bottom">
            <template #activator="{ props }">
              <VBtn
                v-bind="props"
                :disabled="reservation == null"
              >
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
          <VBtn v-if="reservation != null" @click="GoToUpdateReservation">
            {{ $t("update Reservation") }}
          </VBtn>

          <VBtn
            class="float-end"
            :disabled="disableSubmit"
            @click="CreateReservation"
          >

            {{ $t("Submit") }}s
          </VBtn>
        </div>
      </VCardText>
    </VCard>
    <VDialog
      v-model="roomDialog"
      width="1024"
      scroll-strategy="none"
    >
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
            <tr
              v-for="item in filterData"
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
                <VChip class="ma-2">
                  <VIcon
                    v-if="item.selected"
                    icon="mdi-check"
                    color="success"
                  />
                  <VIcon
                    v-if="!item.selected"
                    icon="mdi-close-octagon"
                    color="error"
                  />
                </VChip>
              </td>
              <td>
                <VBtn
                  color="primary"
                  :disabled="item.selected"
                  @click="submitRoom(item)"
                >
                  <VIcon icon="mdi-check" />
                </VBtn>
              </td>
            </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>
          <VBtn
            color="blue-darken-1"
            variant="text"
            class="float-end"
            @click="roomDialog = false"
          >
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="RoomCheckIn"
      width="1024"
      scroll-strategy="none"
    >
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >

              <span class="text-h5">{{ $t("Selecdt Room") }}</span>
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

            <tr
              v-for="item in filterData"
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
                  {{ item }}
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
                <VChip class="ma-2">

                  <VIcon
                    v-if="item.selected"
                    icon="mdi-check"
                    color="success"
                  />
                  <VIcon
                    v-if="!item.selected"
                    icon="mdi-close-octagon"
                    color="error"
                  />
                </VChip>
              </td>

            </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>

          <VBtn @click="RoomCheckIn = false">

            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="postingchargeDialog"
      width="1024"
      scroll-strategy="none"
    >

      <VCard>
        <VCardTitle>
          <span class="text-h5"> ${{ $t('Posting charges') }}</span>
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
            ${{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disablePostingCharge"
            @click="submitPostingCharge"
          >
            ${{ $t('Submit') }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="PaymentDialog"
      width="1024"
      scroll-strategy="none"
      @click:outside="PaymentDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 "> ${{ $t('Cacher Payment') }}</span>
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
                  label="Room"
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
                  label="guest name"
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
                  label="window name"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VTextarea
                  v-model="Amount"
                  label="Amount"
                  type="number"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VTextarea
                  v-model="Descriptions"
                  label="Descriptions"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox
                  v-model="Payments_Selected"
                  :items="Payments"
                  item-title="name"
                  item-value="Payments_Selected"
                  label="Way of Payments"
                />
              </VCol>
              <VCol
                class="Date_inputs"
                cols="6"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="no_data"
                  label="Ref no"
                />
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
            ${{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="submitPayment"
          >
            ${{ $t('Submit') }}
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
      out_date_hijri: null,
      in_date_hijri: null,
      PaymentDialog: false,
      folio_no: '',
      arrival_date: '',
      departure_date: '',
      extended_date: '',
      window_number: '',
      window_id: '',
      Recepit_no: '',
      Amount: '',
      Descriptions: '',
      Payments: [],
      Payments_Selected: '',
      postingchargeDialog: false,
      ledgerCats: [],
      tax_included: false,
      amount: '',
      ledger_id: '',
      ledger_name: '',
      no_data: '',
      description: '',
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
      RoomCheckIn: false,
      extra_amount: null,
      selected_extra: null,
      extras_show: false,
      loading: false,
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
      reservation: null,
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

      Id_Num:[1,4,5,6],

      Id_Num_ver:[],
      version_num:null,
      idtypes:[],
      id_no_new_compo: null,
      All_id_num: null,
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
      disableSubmit: true,
      select_year: '',
      select_day: '',
      select_month: '',
      years: [],
      days: [],
      month: [],

      URL: window.location.origin,

    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    flattenedRows() {
      const rows = []
      for (const item of this.selected_room_type) {
        for (let i = 0; i < item.number_of_rooms; i++) {
          rows.push(item)
        }
      }

      return rows
    },
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

        return selectedDate >= new Date(this.in_date) || 'Out Date must be on or after the In date'
      }
    },
    filterData() {
      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id === this.room_type_filter)

      return filteredRoomType ? filteredRoomType.rooms : []
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
    available_roomtypes() {
      return this.guestStore.available_rooms
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
    room_types() {
      this.required_rooms = Array(this.room_types.length).fill(0)
    },
    rate_code() {
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
    },
    in_date() {
      if (this.no_of_nights != "") {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, "days")
        this.out_date = inn.format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date)
      }
      if (this.in_date == "") {
        this.in_date = moment().format("YYYY-MM-DD")
        this.in_date_hijri = this.$getHijriDate(this.in_date)
      }
    },
    async out_date() {
      if (this.no_of_nights != "") {
        var inn = moment(this.in_date)
        var out = moment(this.out_date)
        this.no_of_nights = out.diff(inn, "days")
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      const response = await this.getAvaliableRooms(this.in_date, this.out_date)

      /* if (response.status == 200) {
        this.fillNewRoomType()
      } */
    },
    no_of_nights() {
      if (this.no_of_nights != "") {
        var inn = moment(this.in_date)
        inn.add(this.no_of_nights, "days")
        this.out_date = inn.format("YYYY-MM-DD")
        this.out_date_hijri = this.$getHijriDate(this.out_date)
      }
      if (this.no_of_nights == "") {
        this.no_of_nights = 1
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    response() {
      if (this.response != null) {
        this.extras_show = true
        this.reservation = this.response
        this.insertAlert("Reservation has been created")
        if (
          (this.reservation.res_status == "CNF" ||
            this.reservation.res_status == "NCF") &&
          this.reservation.in_date == moment().format("YYYY-MM-DD")
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
      if (this.reservations_statuses != "") {
        this.Clear()
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
    group_rate_code: {
      // eslint-disable-next-line sonarjs/cognitive-complexity
      handler(newCodes, oldCodes) {
        for (let index = 0; index < this.group_rate_code.length; index++) {
          if (this.group_rate_code[index] != null && this.group_rate_code[index] != '') {

            for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
              // eslint-disable-next-line sonarjs/no-collapsible-if
              if (this.group_rate_code[index].room_types[i].id == this.flattenedRows[index].roomtype.id) {
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
        }
      },
      deep: true, // Watch for nested changes within the array
    },
    pax: {
      // eslint-disable-next-line sonarjs/cognitive-complexity
      handler(newCodes, oldCodes) {
        let total = null

        this.disableSubmit = newCodes.some(pax => pax == null || pax == '' || pax < 1)
        for (let index = 0; index < this.pax.length; index++) {

          total += parseInt(this.pax[index])
          if (this.group_rate_code[index] != null && this.group_rate_code[index] != '') {

            for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
              // eslint-disable-next-line sonarjs/no-collapsible-if
              if (this.group_rate_code[index].room_types[i].id == this.flattenedRows[index].roomtype.id) {

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
        }


        this.no_of_pax = total
      },
      deep: true, // Watch for nested changes within the array
    },
    required_rooms: {
      // eslint-disable-next-line sonarjs/cognitive-complexity
      handler(newCodes, oldCodes) {
        let total = null
        for (let index = 0; index < this.required_rooms.length; index++) {
          if (this.required_rooms[index] != null && this.required_rooms[index] != '' && this.required_rooms[index] >= 1) {

            total += parseInt(this.required_rooms[index])
          }
        }
        this.total_rooms = total
      },
      deep: true, // Watch for nested changes within the array
    },
    rate: {
      handler(newRates) {
        this.disableSubmit = newRates.some(rate => rate == null || rate == '' || rate < 1)
      },
      deep: true,
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
      console.log('date_of_birth_new', this.showGuestProfile)
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
      "CreateGroupReservationAction",
      'resetResponse',
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
    ...mapActions(useExtrasStore, ['getExtras',
      'getExtraByGuestId',
      'addGuestExtra',
      'deleteGuestExtra']),
    fillNewRoomType() {
      this.roomType = this.available_roomtypes.find(roomtype => roomtype.id == this.roomType.id)
      this.rooms = this.roomType.rooms
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
          console.log(err)
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
        console.log(res)
        this.closePostingChargeDialog()
        this.insertAlert('Posting charge has been recorded')
      })
    },
    closePostingChargeDialog() {
      this.postingchargeDialog = false
      this.amount = ''
      this.ledger_id = ''
      this.ledger_name = ''
      this.description = ''
    },
    GoToUpdateReservation() {

      // this.$router.push({
      //   name: `reservation-update-reservation`,
      //   params: { reservation: this.reservation.id },
      // })
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
    selectRoom(index, id) {
      this.room_type_filter = id
      this.roomDialog = true
      this.selected_room_index = index
    },
    removeRoom(room_index, id) {
      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == id)
      for (let index = 0; index < filteredRoomType.rooms.length; index++) {
        if (filteredRoomType.rooms[index].id == this.room[room_index].id) {
          filteredRoomType.rooms[index].selected = false
        }
      }
      this.room[room_index] = null
    },
    submitRoom(room) {
      this.roomDialog = false
      this.room[this.selected_room_index] = room

      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == this.room_type_filter)
      for (let index = 0; index < filteredRoomType.rooms.length; index++) {
        if (filteredRoomType.rooms[index].id == room.id) {
          filteredRoomType.rooms[index].selected = true
        }
      }
    },
    submitAllRooms() {
      this.CreateReservation()
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    SubmitRoomsFromRoomtypes() {
      this.selected_room_type = []
      this.group_room_type = []
      this.pax = []
      this.group_rate_code = []
      this.rate = []
      for (let index = 0; index < this.available_roomtypes.length; index++) {
        if (this.required_rooms[index] != 0 && this.required_rooms[index] != '') {
          this.selected_room_type.push({
            roomtype: this.available_roomtypes[index],
            number_of_rooms: this.required_rooms[index],
          })
        }
      }
      for (let index = 0; index < this.flattenedRows.length; index++) {
        this.group_room_type[index] = this.flattenedRows[index].roomtype
        this.pax[index] = this.flattenedRows[index].roomtype.def_pax
        this.rate[index] = 0
        if (this.rate_code != null && this.rate_code != '') {
          this.group_rate_code[index] = this.rate_code
          for (var i = 0, len = this.rate_code.room_types.length; i < len; i++) {
            // eslint-disable-next-line sonarjs/no-collapsible-if
            if (this.rate_code.room_types[i].id == this.flattenedRows[index].roomtype.id) {
              if (this.pax[index] == 1) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax1
              }
              if (this.pax[index] == 2) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax2
              }
              if (this.pax[index] == 3) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax3
              }
              if (this.pax[index] == 4) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax4
              }
              if (this.pax[index] == 5) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax5
              }
              if (this.pax[index] == 6) {
                this.rate[index] = this.rate_code.room_types[i].pivot.pax6
              }
              if (this.pax[index] > 6) {
                this.rate[index] = this.rate_code.room_types[i].pivot.extra_pax
              }
            }
          }
        }
      }


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
    // eslint-disable-next-line sonarjs/cognitive-complexity
    CreateReservation() {
      this.rooms_array = []
      for (let index = 0; index < this.flattenedRows.length; index++) {
        this.rooms_array.push({
          roomType: this.group_room_type[index].id,
          rm_rate: this.rate[index],
          no_pax: this.pax[index],
          rate_code_id: this.group_rate_code[index] != null && this.group_rate_code[index] != '' ? this.group_rate_code[index].id : null,
          room_id: this.room[index] != null && this.room[index] != '' ? this.room[index].id : 0,
          checked_out: 0,
        })
      }
      this.CreateGroupReservationAction({
        profile_id: this.selected_guest.id,
        mobile: this.selected_guest.mobile,
        in_date: this.in_date,
        out_date: this.out_date,
        original_out_date: this.out_date,
        no_of_nights: this.no_of_nights,
        no_of_pax: this.no_of_pax,
        rate_code_id: this.rate_code != null && this.rate_code != '' ? this.rate_code.id : null,
        rm_rate: this.room_rate,
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
        rooms: this.rooms_array,
        vip: this.vip,
        res_type: "D",
        checked_out: 0,
        customerType: this.customer_type?.id,
        purposeOfVisit: this.purpose_of_visit?.id,
      })

    },
    selectProfile(guest) {
      this.selected_guest = guest
      this.searchDialog = false
      this.showprofilealert = false
      this.noProfileSelected = false
    },
    showGuestProfile() {
      this.profiledetailsDialog = true
      if (this.guestFromParent == null) {
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
      if (this.id_no_search == "") {
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


            id_type_id: this.id_no_new_compo.id,
            version_no:this.version_num,

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
    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)
      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },

  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.getAvaliableRooms(this.SettingData.hotel_date, moment(this.SettingData.hotel_date).add(1, 'days').format("YYYY-MM-DD"))
    this.getRoomTypesAction()
    this.getRoomsAction()
    this.getRateCodesAction()
    this.getCompaniesAction()
    this.getBusinessesAction()
    this.getSegmentsAction()
    this.getledgerCats()
    this.getPayment()
    if (this.countries == "") {
      this.getCountriesAction()
    }
    this.getExtras()
    this.getReservationstatusesAction()
  },
}
</script>

<style scoped>
.flex-grow-1 {
  flex-grow: 0 !important;
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
</style>
