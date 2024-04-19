<script setup>
import { requiredValidator } from "@validators"
</script>

<template>
  <div>
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
     <VCard>
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
                     readonly
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
                     readonly
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
                     readonly
                   />
                 </VCol>
                 <VCol
                   cols="12"
                   sm="6"
                   md="3"
                 >
                   <VTextField
                     v-model="email"
                     :label="$t('E-mail')"
                     readonly
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
                     readonly
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
                     readonly
                   />
                 </VCol>
                 <VCol
                   cols="12"
                   sm="6"
                   md="6"
                 >
                   <VTextField
                     v-model="language"
                     label="language"
                     readonly
                   />
                 </VCol>
                 <VCol
                   cols="12"
                   sm="6"
                   md="6"
                 >
                   <VTextField
                     v-model="country"
                     label="Country"
                     readonly
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
                     readonly
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
                     readonly
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
                     readonly
                   />
                 </VCol>
                 <VCol
                   cols="12"
                   sm="6"
                   md="6"
                 >
                   <VTextField
                     v-model="date_of_birth"
                     :label="$t('Date of birth')"
                     readonly
                   />
                 </VCol>
               </VRow>
             </VCardText>
             <VCardActions>
               <VSpacer />
               <VBtn
                 color="blue-darken-1"
                 variant="text"
                 @click="profiledetailsDialog = false"
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
               :disabled="!noProfileSelected || locked"
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
               <VForm
                 ref="refForm"

               >
                 <VRow>
                   <VCol
                     cols="12"
                     sm="6"
                     md="3"
                   >
                     <VTextField
                       v-model="first_name_new"
                       :label="$t('First name')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="3"
                   >
                     <VTextField
                       v-model="last_name_new"
                       :label="$t('Last name')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="3"
                   >
                     <VTextField
                       v-model="mobile_new"
                       :label="$t('Mobile')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="3"
                   >
                     <VTextField
                       v-model="email_new"
                       :label="$t('E-mail')"
                       @keyup.enter="goNext($event.target)"
                     />
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
                   <VCol
                     cols="12"
                     sm="6"
                     md="6"
                   >
                     <VCombobox item-color="customColorValue"
                                v-model="gender_new"
                                :items="gender_data"
                                label="gender"
                                @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="3"
                     md="6"
                   >
                     <VCombobox item-color="customColorValue"
                                v-model="language_new"
                                :items="lang_data"
                                label="language"
                                @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="3"
                     md="6"
                   >
                     <VCombobox item-color="customColorValue"
                                v-model="country_new"
                                :items="countries"
                                label="country"
                                item-title="name"
                                item-value="country"
                                @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="6"
                   >
                     <VTextField
                       v-model="phone_new"
                       :label="$t('Phone')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="6"
                   >
                     <VTextField
                       v-model="city_new"
                       :label="$t('City')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="6"
                   >
                     <VTextField
                       v-model="address_new"
                       :label="$t('Address')"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                   <VCol
                     cols="12"
                     sm="6"
                     md="6"
                   >
                     <AppDateTimePicker
                       v-model="date_of_birth_new"
                       :label="$t('Date of birth')"
                       :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
                       @keyup.enter="goNext($event.target)"
                     />
                   </VCol>
                 </VRow>
               </VForm>
             </VCardText>
             <VCardActions>
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
     </VCard>
        </VRow>
      </VContainer>
    </VCard>

    <VCard
      class="mt-6"
      :disabled="noProfileSelected || locked"
    >
      <VContainer>
        <VAlert
          v-if="alert"
          variant="tonal"
          color="success"
        >
          {{ $t("Selected profile:") }}
          {{ selected_guest ? selected_guest.first_name + ' ' + selected_guest.last_name + ' ( Profile ) '
            : null
          }}
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
                @focus="master_touched = true"
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
                :config="{ disable: [{ from: `1900-01-01`, to: moment(SettingData.hotel_date).format('YYYY-MM-DD') }], altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                @focus="master_touched = true"
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
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_nights"
                :label="$t('Number of nights*')"
                type="number"
                :rules="[requiredValidator]"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="5"
            >
              <VCombobox item-color="customColorValue"
                v-model="rate_code"
                :label="$t('Rate code')"
                :items="rate_codes"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                clearable
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="5"
            >
              <VCombobox item-color="customColorValue"
                v-model="meal"
                :label="$t('Meal or Meal package')"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                readonly
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="2"
            >
              <VTextField
                v-model="no_of_pax"
                :label="$t('Total PAX*')"
                type="number"
                :rules="[requiredValidator]"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox item-color="customColorValue"
                v-model="way_of_payment"
                :label="$t('Way of payment*')"
                :items="payments"
                item-title="name"
                item-value="code"
                :rules="[requiredValidator]"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox item-color="customColorValue"
                v-model="company_id"
                :label="$t('Company')"
                :items="companies"
                :item-title="$i18n.locale == 'en' ? 'company_name' : 'company_name_loc'
                "
                item-value="id"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox item-color="customColorValue"
                v-model="market_segment_id"
                :label="$t('Market Segment')"
                :items="segments"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox item-color="customColorValue"
                v-model="source_of_business_id"
                :label="$t('Source of business')"
                :items="sources"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="id"
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="4"
            >
              <VCombobox item-color="customColorValue"
                v-model="reservations_status"
                :label="$t('Reservation status*')"
                :items="reservations_statuses"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="res_status_code"
                :rules="[requiredValidator]"
                @focus="master_touched = true"
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
                @focus="master_touched = true"
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
                @focus="master_touched = true"
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
                @focus="master_touched = true"
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
                @focus="master_touched = true"
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
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
                item-color="customColorValue"
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
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
                item-color="customColorValue"
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
                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
                item-color="customColorValue"
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

                @focus="master_touched = true"
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

                @focus="master_touched = true"
                @keyup.enter="goNext($event.target)"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="7"
            >
              <VDialog
                v-model="extra_dialog"
                width="1024"
                scroll-strategy="none"
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    class="float-end"
                  >
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
                      <VForm
                        ref="refForm"
                      >
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
                            @keyup.enter="goNext($event.target)"
                            item-color="customColorValue"
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
                            @keyup.enter="goNext($event.target)"
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
                      </VForm>
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
            </VCol>
          </VRow>
        </VForm>
      </VContainer>
    </VCard>


    <VCard
      class="mt-6"
      :disabled="noProfileSelected || locked"
    >
      <VCardTitle>
        <VRow>
          <VCol
            cols="12"
            sm="6"
            md="2"
          >
            <span>Rooms</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="2"
          >
            <span>Total PAX: {{ no_of_pax }}</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="2"
          >
            <span>Total Rooms: {{ flattenedRows }}</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <span>Total Room rate: {{ total_room_rate }}</span>
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="3"
          >
            <VBtn
              class="float-end"
              @click="moreRoomsDialog = true"
            >
              {{ $t("Add more rooms") }}
            </VBtn>
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
              <th>
                {{ $t("Delete") }}
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
                  <VCheckbox
                    v-if="guest_id[index] != null"
                    v-model="rooms_checked[index]"
                    :true-value="guest_id[index]"
                    :false-value="null"
                  />
                  <AppCombobox
                    v-model="room[index]"
                    :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'"
                    style="width: 150px;"
                    readonly
                  />
                  <div>
                    <VIcon
                      class="ml-2 mr-2 mt-2"
                      @click="selectRoom(index, group_room_type[index] != null ? group_room_type[index].id : null)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-ballpen
                    </VIcon>
                    <VIcon
                      class="mt-2"
                      @click="removeRoom(index, group_room_type[index].id)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-trash
                    </VIcon>
                  </div>
                </td>
                <td>
                  <AppCombobox
                    v-model="group_room_type[index]"
                    style="width: 150px;"
                    readonly
                    :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                  />
                </td>
                <td>
                  <AppCombobox
                    v-model="group_rate_code[index]"
                    :items="rate_codes"
                    :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                    style="width: 200px;"
                    clearable
                    @update:modelValue="rateCodechanged(index)"
                  />
                </td>
                <td>
                  <VTextField
                    v-model="pax[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="paxchanged(index)"
                  />
                </td>
                <td>
                  <VTextField
                    v-model="rate[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="ratechanged(index)"
                  />
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
        <VCol class="button-container"  >

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

          <VBtn
            color="error"
            @click="cancelReservationDialog = true"

          >
            {{ $t("Cancel reservation") }}
          </VBtn>
          <VBtn @click="UpdateReservationsDialog = true, returnSelectedRooms()">
            {{ $t("Update Reservation") }}
          </VBtn>

          <VBtn @click="DialogCheckin = true">
            {{ $t("check in") }}
          </VBtn>
          <VBtn @click="submitAllRooms">
            {{ $t("Submit") }}
          </VBtn>
        </VCol>
      </VCardText>
    </VCard>
    <VDialog
      v-model="roomDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
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
            class="float-right"
            @click="roomDialog = false"
          >
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="moreRoomsDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
    >
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="9"
            >
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
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="10"
            >
              <VBtn
                class="float-right mb-3 mr-3"
                @click="moreRoomsDialog = false"
              >
                {{ $t("Close") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VBtn
                class="float-right mb-3 mr-3"
                @click="SubmitRoomsFromRoomtypes"
              >
                {{ $t("Submit") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VDialog>
    <VDialog
      v-model="UpdateReservationsDialog"
      width="1024"
      persistent
      scroll-strategy="none"
      z-index="1000"
    >
      <VCard>
        <VCardTitle>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="9"
            >
              <span>Update selected guest/s</span>
            </VCol>
          </VRow>
        </VCardTitle>
        <VCardText class="mt-3">
          <span>Number of selected rooms: {{ checked_count }}</span>
          <VRow class="mb-2 mt-2">
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppDateTimePicker
                v-model="guest_in_date"
                :label="$t('In date*')"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <AppDateTimePicker
                v-model="guest_out_date"
                :label="$t('Out date*')"
                :config="{ altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VCombobox
                v-model="guest_rate_code"
                :label="$t('Rate code')"
                :items="rate_codes"
                :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                item-value="rate_code"
                clearable
                item-color="customColorValue"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VCombobox
                v-model="guest_vip"
                :label="$t('VIP')"
                :items="vips"
                clearable
                item-color="customColorValue"
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="6"
            >
              <VTextField
                v-model="guest_no_of_pax"
                :label="$t('Number of PAX*')"
                type="number"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              sm="3"
              md="6"
            >
              <VTextField
                v-model="guest_rm_rate"
                :label="$t('Room rate*')"
                type="number"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="10"
            >
              <VBtn
                class="float-right mb-3 mr-3"
                @click="CloseUpdateReservationChildren"
              >
                {{ $t("Close") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="2"
            >
              <VBtn
                class="float-right mb-3 mr-3"
                @click="UpdateChildrenReservations"
              >
                {{ $t("Submit") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VDialog>
    <VDialog
      v-model="postingchargeDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
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
      scroll-strategy="none"
      z-index="1000"
      @click:outside="PaymentDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Cacher Payment</span>
        </VCardTitle>
        <VCardText>
          <VForm
            ref="refForm"
            @submit.prevent="CreateReservation"
          >
          <VContainer>

            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
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
                md="6"
              >
                <VTextField
                  v-model="window_number"
                  label="window name"
                  readonly
                  @keyup.enter="goNext($event.target)"
                />
              </VCol>
            </VRow>

          </VContainer>
          <VContainer>
            <VRow>
              <VCol>

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
                    item-color="customColorValue"
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
                rows="8"
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
          </VContainer>
          </VForm>
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
                  item-color="customColorValue"
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
    <VDialog
      v-model="DialogCheckin"
      width="1224"
      z-index="2000"

    >
      <VCard
        max-height="700px"
        style="min-height:200px;padding: 20px"
      >
        <VTable class="table-container">
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
                {{ $t("checked in") }}
              </th>
            </tr>
          </thead>
          <tbody>

            <template
              v-for="(item, index) in flattenedRows"
              :key="index"
            >
               <tr v-if="reservation.guestGroup[index].is_checked_in ==0">
                <td style="display: flex;padding-top: 2%;">


                  <AppCombobox
                    v-model="room[index]"
                    :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'"
                    style="width: 150px;"
                    readonly
                    type=""
                  />

                  <div>


                    <VIcon
                      class="ml-2 mr-2 mt-2"
                      @click="selectRoom(index, group_room_type[index] != null ? group_room_type[index].id : null)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-ballpen
                    </VIcon>
                    <VIcon
                      class="mt-2"
                      @click="removeRoom(index, group_room_type[index].id)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-trash
                    </VIcon>

                  </div>
                </td>

                <td>
                  <AppCombobox
                    v-model="group_room_type[index]"
                    style="width: 150px;"
                    readonly
                    :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                  />
                </td>
                <td>
                  <AppCombobox
                    v-model="group_rate_code[index]"
                    :items="rate_codes"
                    :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                    style="width: 200px;"

                    @update:modelValue="rateCodechanged(index)"
                    readonly
                  />
                </td>
                <td>
                  <VTextField
                    v-model="pax[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="paxchanged(index)"
                    readonly
                  />
                </td>
                <td>
                  <VTextField
                    v-model="rate[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="ratechanged(index)"
                    readonly
                  />

                </td>

                <td>    <VCheckbox
                  v-if="guest_id[index] != null&&room[index]&&reservation.guestGroup[index].is_checked_in != 1"
                  v-model="rooms_checked[index]"
                  :true-value="guest_id[index]"
                  :false-value="null"

                /></td>
                 {{rooms_checked[index]}}
                <td />
              </tr>
              <tr v-if="reservation.guestGroup[index].is_checked_in ==1" style="background: rgb(225,0,0,.7) ;">
                <td style="display: flex;padding-top: 2%;">


                  <AppCombobox
                    v-model="room[index]"
                    :item-title="$i18n.locale == 'en' ? 'rm_name_en' : 'rm_name_loc'"
                    style="width: 150px;"
                    readonly
                    type=""
                  />

                  <div>


                    <VIcon
                      class="ml-2 mr-2 mt-2"
                      @click="selectRoom(index, group_room_type[index] != null ? group_room_type[index].id : null)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-ballpen
                    </VIcon>
                    <VIcon
                      class="mt-2"
                      @click="removeRoom(index, group_room_type[index].id)"
                      @focus="setTouchedField(index)"
                    >
                      tabler-trash
                    </VIcon>

                  </div>
                </td>

                <td>
                  <AppCombobox
                    v-model="group_room_type[index]"
                    style="width: 150px;"
                    readonly
                    :item-title="$i18n.locale == 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                  />
                </td>
                <td>
                  <AppCombobox
                    v-model="group_rate_code[index]"
                    :items="rate_codes"
                    :item-title="$i18n.locale == 'en' ? 'name' : 'name_loc'"
                    style="width: 200px;"

                    @update:modelValue="rateCodechanged(index)"
                    readonly
                  />
                </td>
                <td>
                  <VTextField
                    v-model="pax[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="paxchanged(index)"
                    readonly
                  />
                </td>
                <td>
                  <VTextField
                    v-model="rate[index]"
                    type="number"
                    style="width: 100px;"
                    min="1"
                    @change="ratechanged(index)"
                    readonly
                  />

                </td>

                <td>    <VCheckbox
                  v-if="guest_id[index] != null&&room[index]&&reservation.guestGroup[index].is_checked_in != 1"
                  v-model="rooms_checked[index]"
                  :true-value="guest_id[index]"
                  :false-value="null"

                /></td>

                <td />
              </tr>
              <br>
              <tr />
            </template>
          </tbody>
        </VTable>
        <br>
        <div style="width: 100%;display: flex;justify-content: space-between">
          <VBtn @click="DialogCheckin=false"
          >
            close
          </VBtn>
          <VBtn

            @click="check(rooms_checked)"
          >
            check in
          </VBtn>
        </div>
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
      refForm: ref(),
      cancel_reason: null,
      DialogCheckin: false,
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
      total_room_rate: 0,
      selected_rate_code: null,
      groupGuests: [],
      guest_vip: null,
      guest_rm_rate: null,
      guest_no_of_pax: null,
      guest_rate_code: null,
      guest_out_date: null,
      guest_in_date: null,
      UpdateReservationsDialog: false,
      rooms_checked: [],
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
      vat_no: null,
      company_name: null,
      inv_address: null,
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
      Id_Num:[1,4,5,6],
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
      checked_data: [],
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
      disableratecode: true,
      isSnackbarVisible: false,
      showprofilealert: true,
      noProfileSelected: true,
      refForm: ref(),
      in_date: null,
      out_date: null,
      no_of_nights: 1,
      original_out_date: null,
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
      user_name: null,
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
      reservation_days_dialog: false,
      day: [],
      ratecode: [],
      guestGroup: [],
      roomrate: [],
      formValues: [],
      disableCheckin: true,
      disableSubmit: false,
      alert: false,
      checked_count: 0,
      rooms_checked_array: [],
      reservation: null,
      timer: null,
      currentTime: this.getCurrentTime(),
    }
  },
  mounted() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
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

        return selectedDate >= new Date(this.in_date) || 'Out Date must be on or after the In date'
      }
    },
    filterData() {
      const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == this.room_type_filter)

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
    guests() {
      return this.guestStore.guests
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    out_date() {
      if (!this.loading) {
        if (this.no_of_nights != null) {
          var inn = moment(this.in_date)
          var out = moment(this.out_date)
          this.no_of_nights = out.diff(inn, "days")
          this.out_date_hijri = this.$getHijriDate(this.out_date)
        }
      }

    },
    in_date() {
      if (!this.loading) {
        if (this.no_of_nights != null) {
          var inn = moment(this.in_date)
          inn.add(this.no_of_nights, "days")
          this.out_date = inn.format("YYYY-MM-DD")
          this.in_date_hijri = this.$getHijriDate(this.in_date)
        }
        if (this.in_date == null) {
          this.in_date = moment().format("YYYY-MM-DD")
          this.in_date_hijri = this.$getHijriDate(this.in_date)
        }
      }
    },
    no_of_nights() {
      if (!this.loading) {
        if (this.no_of_nights != null) {
          var inn = moment(this.in_date)
          inn.add(this.no_of_nights, "days")
          this.out_date = inn.format("YYYY-MM-DD")
          this.out_date_hijri = this.$getHijriDate(this.out_date)
        }
        if (this.no_of_nights == null) {
          this.no_of_nights = 1
        }
      }
    },
    checkin() {
      if (this.checkin) {
        this.$router.push("/reservations")
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
    available_roomtypes() {
      if (this.available_roomtypes != null) {
        this.groupGuests = this.reformChildrenReservation()
        this.flattenedRows = this.groupGuests.length
        for (let index = 0; index < this.groupGuests.length; index++) {
          this.group_room_type[index] = this.available_roomtypes.find(roomType => roomType.id == this.groupGuests[index].roomType.id)
          this.pax[index] = this.groupGuests[index].no_of_pax
          this.no_of_pax += this.groupGuests[index].no_of_pax
          this.rate[index] = this.groupGuests[index].rm_rate
          this.total_room_rate += this.rate[index]
          if (this.groupGuests[index].rateCode != null) {
            this.selected_rate_code = this.rate_codes.find(item => item.id == this.groupGuests[index].rateCode.id)
          }
          this.group_rate_code[index] = this.selected_rate_code
          this.selected_rate_code = null
          this.room[index] = this.groupGuests[index].room
          this.guest_id[index] = this.groupGuests[index].id
          if (this.room[index] != null) {
            for (let i = 0; i < this.available_roomtypes.length; i++) {
              if (this.available_roomtypes[i].rooms.length != 0) {
                for (let z = 0; z < this.available_roomtypes[i].rooms.length; z++) {
                  if (this.available_roomtypes[i].rooms[z].id == this.room[index].id) {
                    this.available_roomtypes[i].rooms[z].selected = true
                  }
                }
              }
            }
          }
        }
        this.calculateTotalRoomRate()
        this.calculateTotalPax()
      }
    },
    reservation() {
      if (this.reservation != null) {
        this.Clear()
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
    ...mapActions(useRatecodeStore, ["getRateCodesAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction", "getRoomsAction"]),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    ...mapActions(useGuestStore, [
      "getSearchguestAction",
      "CreateGuestAction",
      "GuestCheckinAction",
      "CreateGuestMoreNamesAction",
      "CreateGroupReservationAction",
      'updateGroupReservation',
      'getGroupReservation',
      'deleteReservation',
      'getSelectedGroupReseravtion',
      'getAvaliableRooms',
    ]),
    ...mapActions(useGeneralStore, [
      "getCompaniesAction",
      "getBusinessesAction",
      "getSegmentsAction",
      "getCountriesAction",
      "getReservationstatusesAction",
    ]),
    async   check(checked){
      this.checked_data = checked.filter(id => id !== null)
      console.log('checked data', this.checked_data)
      await axios.post(`${window.location.origin}/api/groupCheckIn`, {



        'guestIDs': this.checked_data,
      }).then(res => {

        this.DialogCheckin = false
        this.$alert('Room checked in successfully', true)
        this.checked_data = []
      })
    },
    async cancelReservation() {
      await axios.post(`${window.location.origin}/api/cancel`, {
        'cancelReason': this.cancel_reason?.Cancel_Reason_Id,
        'groubeCode': this.reservation?.group_code,
        'guest_id': null,
      }).then(async res => {
        if (res.status != undefined) {
          this.cancelReservationDialog = false
          this.$alert('Reservation has been cancelled successfully', true)

          const response = await this.getSelectedGroupReseravtion(this.$route.params.new)
          if (response.status) {
            this.reservation = response.data.data[0]
          }

        }
      })
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
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            this.GuestCheckinAction(this.reservation.id)
          }
        })

      }
    },
    goToFolio() {
      this.$router.push({
        name: `selectedFolio-folio`,
        params: { folio: this.reservation.id },
      })
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
      this.window_number = this.reservation.window[0].window_name
      this.window_id = this.reservation.window[0].id
      this.departure_date = this.reservation.out_date
      this.guest_name = this.reservation.profile.first_name + ' ' + this.reservation.profile.last_name
      this.user_name = this.reservation.created_by.firstname + ' ' + this.reservation.created_by.lastname

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
        'window_id': this.reservation.window[0].id,
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
        'window_id': this.reservation.window[0].id,
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
    calculateTotalRoomRate() {
      this.total_room_rate = 0
      for (let i = 0; i < this.flattenedRows; i++) {
        this.total_room_rate += parseInt(this.rate[i])
      }
    },
    calculateTotalPax() {
      this.no_of_pax = 0
      for (let i = 0; i < this.flattenedRows; i++) {
        this.no_of_pax += parseInt(this.pax[i])
      }
    },
    reformChildrenReservation() {
      const mergedArray = [...this.reservation.guestGroup, ...this.reservation.guestGroupNullRateCode]

      mergedArray.sort((a, b) => a.id - b.id)

      return mergedArray
    },
    CloseUpdateReservationChildren() {
      this.guest_in_date = null
      this.guest_out_date = null
      this.guest_no_of_pax = null
      this.guest_rate_code = null
      this.guest_rm_rate = null
      this.guest_vip = null
      this.UpdateReservationsDialog = false
      this.rooms_checked = []
    },
    async UpdateChildrenReservations() {
      if (this.guest_in_date == null || this.guest_out_date == null) {
        this.DangerAlert('Please Enter required data')
      }
      else {
        await axios.post(
          `/api/updateGroupGuestByIds`,
          {
            guest_ids: this.rooms_checked_array,
            in_date: this.guest_in_date,
            out_date: this.guest_out_date,
            vip: this.guest_vip != null ? this.guest_vip : null,
            rate_code_id: this.guest_rate_code != null ? this.guest_rate_code.id : null,
            rm_rate: this.guest_rm_rate,
            no_of_pax: this.guest_no_of_pax,
            res_no: this.reservation.res_no,
          },
        ).then(async res => {
          this.$alert('Selected reservation updated', true)
          this.UpdateReservationsDialog = false
          this.rooms_checked_array = []
          this.rooms_checked = []

          const response = await this.getSelectedGroupReseravtion(this.$route.params.new)
          if (response.status) {
            this.reservation = response.data.data[0]
          }

        }).catch(err => {
        })
      }

    },
    returnSelectedRooms() {
      this.checked_count = 0
      this.rooms_checked_array = []
      for (let i = 0; i < this.rooms_checked.length; i++) {
        if (this.rooms_checked[i] != undefined) {
          this.rooms_checked_array.push(this.rooms_checked[i])
          this.checked_count += 1
        }
      }
      if (this.checked_count < 1) {
        this.UpdateReservationsDialog = false
        this.DangerAlert('please select atleast one reservation')
      }
      if (this.checked_count == 1) {
        for (let index = 0; index < this.reservation.guestGroup.length; index++) {
          if (this.reservation.guestGroup[index].id == this.rooms_checked_array[0]) {
            this.guest_in_date = this.reservation.guestGroup[index].in_date
            this.guest_out_date = this.reservation.guestGroup[index].out_date
            this.guest_rate_code = this.reservation.guestGroup[index].rateCode
            this.guest_no_of_pax = this.reservation.guestGroup[index].no_of_pax
            this.guest_rm_rate = this.reservation.guestGroup[index].rm_rate
            this.guest_vip = this.reservation.guestGroup[index].vip
          }
        }
        for (let index = 0; index < this.reservation.guestGroupNullRateCode.length; index++) {
          if (this.reservation.guestGroupNullRateCode[index].id == this.rooms_checked_array[0]) {
            this.guest_in_date = this.reservation.guestGroupNullRateCode[index].in_date
            this.guest_out_date = this.reservation.guestGroupNullRateCode[index].out_date
            this.guest_no_of_pax = this.reservation.guestGroupNullRateCode[index].no_of_pax
            this.guest_rm_rate = this.reservation.guestGroupNullRateCode[index].rm_rate
            this.guest_vip = this.reservation.guestGroupNullRateCode[index].vip
          }
        }
      }
      if (this.checked_count > 1) {
        this.guest_in_date = this.reservation.in_date
        this.guest_out_date = this.reservation.out_date
      }
    },
    ratechanged(index) {
      this.updated[index] = 1
      this.calculateTotalRoomRate()
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    rateCodechanged(index) {
      console.log(this.group_rate_code[index])
      this.updated[index] = 1
      if (this.group_rate_code[index] != null && this.group_rate_code[index] != null) {
        for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.group_rate_code[index].room_types[i].id == this.group_room_type[index].id) {
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
      this.calculateTotalRoomRate()
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    paxchanged(index) {
      this.updated[index] = 1
      if (this.group_rate_code[index] != null && this.group_rate_code[index] != null) {
        for (var i = 0, len = this.group_rate_code[index].room_types.length; i < len; i++) {
          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.group_rate_code[index].room_types[i].id == this.group_room_type[index].id) {
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
      this.calculateTotalRoomRate()
      this.calculateTotalPax()
    },
    setTouchedField(index) {
      this.updated[index] = 1
    },
    async deleteRoom(index) {
      Swal.fire({
        icon: 'warning',
        title: 'Are you sure you want to delete this reservation?',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Delete`,
      }).then(async result => {
        if (result.isDenied) {
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

            const response = await this.getSelectedGroupReseravtion(this.$route.params.new)

            if (response.status) {
              this.reservation = response.data.data[0]
            }
          }
          else {
            if (this.room[index] != null) {
              for (let i = 0; i < this.available_roomtypes.length; i++) {
                if (this.available_roomtypes[i].rooms.length != 0) {
                  for (let z = 0; z < this.available_roomtypes[i].rooms.length; z++) {
                    if (this.available_roomtypes[i].rooms[z].id == this.room[index].id) {
                      this.available_roomtypes[i].rooms[z].selected = false
                    }
                  }
                }
              }
            }
            this.group_room_type.splice(index, 1)
            this.updated.splice(index, 1)
            this.pax.splice(index, 1)
            this.group_rate_code.splice(index, 1)
            this.rate.splice(index, 1)
            this.room.splice(index, 1)
            this.guest_id.splice(index, 1)
            this.flattenedRows -= 1
          }
        }
      })

    },
    changeLocked() {
      axios.post(
        `/api/lockedReservision/${this.reservation.id}`,
        {
          lock: this.locked,
        },
      ).then(res => {
        if (this.locked) {
          this.insertAlert('Reservation Locked')
        }
        else {
          this.insertAlert('Reservation Unlocked')
        }
      }).catch(err => {
      })
    },
    async addExtraForGuest() {
      const response = await this.addGuestExtra({
        data: [{
          guest_id: this.reservation.id,
          extra_id: this.selected_extra.id,
          amount: this.extra_amount,
        }],
      })

      if (response.status != undefined) {
        this.$alert('Extra added successfully', true)
        this.selected_extra = null
        this.extra_amount = null
      }

    },
    async deleteExtraGuest(item) {
      const response = await this.deleteGuestExtra(item, this.reservation.id)
      if (response.status != undefined) {
        this.$alert('Extra deleted successfully', true)
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    selectRoom(index, id) {
      console.log(id)
      if (id != null) {
        this.room_type_filter = id
        this.roomDialog = true
        this.selected_room_index = index
      }

    },
    removeRoom(room_index, id) {
      if (this.room[room_index] != null) {
        const filteredRoomType = this.available_roomtypes.find(roomType => roomType.id == id)
        let room_found = 0
        for (let index = 0; index < filteredRoomType.rooms.length; index++) {
          if (filteredRoomType.rooms[index].id == this.room[room_index].id) {
            filteredRoomType.rooms[index].selected = false
            room_found = 1
          }
        }
        if (room_found == 0) {
          console.log(this.room[room_index])
          console.log(filteredRoomType.rooms)
          filteredRoomType.rooms.push(this.room[room_index])
        }

        this.room[room_index] = null
      }

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
    // eslint-disable-next-line sonarjs/cognitive-complexity
    async submitAllRooms() {
      this.group_guest = []
      if (this.master_touched) {
        this.group_guest.push({
          guest_id: this.reservation.id,
          updated: 1,
          profile_id: this.selected_guest.id,
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
          guestGroup:this.reservation.guestGroup,
          //created_inhousGuest_at: this.reservation.created_inhousGuest_at,
          checked_out: 0,
          is_reservation: 1,
          group_code: this.reservation.group_code,
          vip: this.vip,
          customerType: this.customer_type?.id,
          purposeOfVisit: this.purpose_of_visit?.id,
        })
      }
      for (let index = 0; index < this.flattenedRows; index++) {

        if (this.guest_id[index] != undefined) {
          if (this.updated[index] == 1) {
            this.group_guest.push({
              guest_id: this.guest_id[index] != undefined ? this.guest_id[index] : null,
              updated: 1,
              profile_id: this.selected_guest.id,
              room_type_id: this.group_room_type[index].id,
              rm_rate: this.rate[index],
              no_of_pax: this.pax[index],
              room_id: this.room[index] != null ? this.room[index].id : null,
              rate_code_id: this.group_rate_code[index] != null ? this.group_rate_code[index].id : null,
              group_code: this.reservation.group_code,
              res_no: this.reservation.res_no,
            })
          }
        }
        else {
          this.group_guest.push({
            guest_id: null,
            updated: 1,
            profile_id: this.selected_guest.id,
            room_type_id: this.group_room_type[index].id,
            rm_rate: this.rate[index],
            no_of_pax: this.pax[index],
            room_id: this.room[index] != null ? this.room[index].id : null,
            rate_code_id: this.group_rate_code[index] != null ? this.group_rate_code[index].id : null,
            folio_no: this.reservation.folio_no,
            taxes: this.reservation.taxes,
            meal_id: this.group_rate_code[index] != null ? this.group_rate_code[index].meal != null ? this.group_rate_code[index].meal.id : null : null,
            meal_package_id: this.group_rate_code[index] != null ? this.group_rate_code[index].meal_package != null ? this.group_rate_code[index].meal_package.id : null : null,
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
            created_inhousGuest_at: null,
            checked_out: 0,
            is_reservation: 1,
            group_code: this.reservation.group_code,
            vip: this.vip,
            res_no: this.reservation.res_no,
            customerType: this.customer_type?.id,
            purposeOfVisit: this.purpose_of_visit?.id,
            res_type: null,
          })
        }


      }
      const response = await this.updateGroupReservation({ groupGuest: this.group_guest })
      if (response.status != undefined) {
        this.$alert('Reservation updated successfully', true)

        const response = await this.getSelectedGroupReseravtion(this.$route.params.new)

        if (response.status) {
          this.reservation = response.data.data[0]
        }
      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    SubmitRoomsFromRoomtypes() {
      for (let index = 0; index < this.available_roomtypes.length; index++) {
        if (this.required_rooms[index] != 0 && this.required_rooms[index] != null) {
          this.selected_room_type.push({
            roomtype: this.available_roomtypes[index],
            number_of_rooms: this.required_rooms[index],
          })
        }
      }
      for (let y = 0; y < this.selected_room_type.length; y++) {
        for (let z = 0; z < this.selected_room_type[y].number_of_rooms; z++) {

          this.group_room_type.push(this.selected_room_type[y].roomtype)
          this.pax.push(this.selected_room_type[y].roomtype.def_pax)
          this.rate.push(0)
          this.guest_id.push(null)
          if (this.rate_code != null && this.rate_code != null) {
            this.group_rate_code.push(this.rate_code)
            this.rateCodechanged(this.flattenedRows)
          }
          else {
            this.group_rate_code.push(null)
          }
          this.flattenedRows += 1
        }
      }
      this.selected_room_type = []
      this.moreRoomsDialog = false
      this.insertAlert('New reservations has been added')
    },
    Clear() {
      this.vip = this.reservation.vip
      this.alert = true
      this.selected_guest = this.reservation.profile
      this.noProfileSelected = false
      this.locked = this.reservation.locked
      this.in_date = this.reservation.in_date
      this.out_date = this.reservation.out_date
      this.in_date_hijri = this.$getHijriDate(this.in_date)
      this.out_date_hijri = this.$getHijriDate(this.out_date)
      this.getAvaliableRooms(this.in_date, this.out_date)
      this.no_of_nights = this.reservation.no_of_nights
      this.no_of_pax = this.reservation.no_of_pax
      this.original_out_date = this.reservation.original_out_date
      this.rate_code = this.reservation.rateCode
      this.meal = this.reservation.rateCode != null ? this.reservation.rateCode.meal_id != null ? this.reservation.rateCode.meal : this.reservation.rateCode.meal_package : null
      this.vat_no = this.reservation.vat_no
      this.company_name = this.reservation.company_name
      this.inv_address = this.reservation.inv_address
      this.hotel_note = this.reservation.hotel_note
      this.client_note = this.reservation.client_note
      this.market_segment_id = this.reservation.marketSegment
      this.source_of_business_id = this.reservation.source_of_business
      this.company_id = this.reservation.company
      this.ref_no = this.reservation.ref_no
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
        this.id_no = this.selected_guest.id_number != null ? this.selected_guest.id_number : this.selected_guest.id_no
        this.gender = this.selected_guest.gender
        this.mobile = this.selected_guest.mobile
        this.phone = this.selected_guest.phone
        this.email = this.selected_guest.email
        this.city = this.selected_guest.city
        this.address = this.selected_guest.address
        this.date_of_birth = this.selected_guest.date_of_birth
        this.country = this.$i18n.locale == 'en' ? this.selected_guest.country?.name : this.selected_guest.country?.name_loc
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
      this.All_id_num = this.id_no_new_compo + this.id_no_new
      axios
        .post(
          `${window.location.origin}/api/storeGroupProfile`,
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

  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  async created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    if (this.rate_codes.length == 0) {
      this.getRateCodesAction()
    }
    if (this.companies.length == 0) {
      this.getCompaniesAction()
    }
    if (this.sources.length == 0) {
      this.getBusinessesAction()
    }
    if (this.segments.length == 0) {
      this.getSegmentsAction()
    }
    if (this.countries.length == 0) {
      this.getCountriesAction()
    }

    this.getExtras()

    if (this.reservations_statuses.length == 0) {
      this.getReservationstatusesAction()
    }
    this.getledgerCats()
    this.getPayment()
    this.getExtraByGuestId(this.$route.params.new)

    const response = await this.getSelectedGroupReseravtion(this.$route.params.new)

    if (response.status) {
      this.reservation = response.data.data[0]
    }

  },
}
</script>

<style scoped>
.flex-grow-1 {
  flex-grow: 0 !important;
}


  /* .button-container {*/
  /*  width: 120%;*/
  /*  margin-top: 1%;*/
  /*  display: grid;*/
  /*  grid: 80px 10px / auto auto auto ;*/
  /*  justify-content: space-between;*/
  /*}*/
  @media screen and (max-width: 768px) {
    .button-container {
      display: grid;
      grid: 50px 50px /  150px 150px;
      justify-content: space-around;
    }
  }
  @media screen and (min-width: 768px) {
    .button-container {
      display: flex;
      justify-content: space-around;
    }
  }

.button {
  flex: 1;
  margin-block: 0;
  margin-inline: 5px;

  /* Adjust the margin as needed to create the desired space between buttons */
}
</style>
