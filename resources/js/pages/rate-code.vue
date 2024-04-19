
<script setup>
import {
  requiredValidator,
} from '@validators'
const refForm = ref()
</script>

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="filter w-50">
        <div style="flex-basis: 20%;" class="ml-4">
          <VSelect item-color="customColorValue" :label="$t('row')" :items="['10', '20', '30', '40']" variant="solo" />
        </div>

        <div style="flex-basis: 50%;" class="ml-4">
          <VTextField v-model="search" type="text" :label="$t('Search')" />
        </div>

        <div>
          <VDialog v-model="dialog" width="1024" persistent z-index="1000">
            <template #activator="{ props }">
              <VBtn v-if="$can('create', 'ratecode')" class="ml-4" v-bind="props">
                {{ $t('Add Rate Code') }}
              </VBtn>
            </template>

            <VCard class="pt-5 pb-5 pr-5 pl-5">
              <VCardTitle>
                <span class="text-h5">{{ editFlag ? 'Edit' : 'Add New' }} {{ $t('Rate Code') }}</span>
              </VCardTitle>
              <VWindow v-model="currentStep" class="disable-tab-transition">
                <VWindowItem>
                  <VForm ref="refForm">
                    <VRow class="mb-4 mt-3">
                      <VCol cols="12" sm="4" md="4">

                        <VTextField v-model="rate_code" :label="$t('Rate Code')" type="text"
                          :rules="[requiredValidator]" />

                      </VCol>

                      <VCol cols="12" sm="4" md="4">
                        <VTextField v-model="rate_name" :label="$t('Name')" type="text" :rules="[requiredValidator]" />
                      </VCol>

                      <VCol cols="12" sm="4" md="4">

                        <VTextField v-model="name_loc" :label="$t('Name Loc')" type="text" :rules="[requiredValidator]" />

                      </VCol>

                      <VCol cols="12" sm="6" md="3">
                        <VTextField v-model="start_date" :label="$t('Start Date')"  readonly

                            @click="isDialogStart = !isDialogStart"/>
                      </VCol>
                      <VCol cols="12" sm="6" md="3">
                        <VTextField v-model="start_date_hijri" :label="$t('Start date hijri')" readonly />
                      </VCol>
                      <VCol cols="12" sm="6" md="3">

                        <VTextField v-model="end_date" :label="$t('End Date')"  readonly

                                   @click="isDialogEnd = !isDialogEnd"/>

                      </VCol>
                      <VCol cols="12" sm="6" md="3">
                        <VTextField v-model="end_date_hijri" :label="$t('End date hijri')" readonly />
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VCombobox v-model="meal_id" :label="$t('Meal')" :items="meals" item-title="name"
                          :disabled="disableMeal" clearable />
                      </VCol>
                      <VCol cols="12" sm="6" md="6">
                        <VCombobox v-model="meal_package_id" :label="$t('Meal Package')" :items="meal_packages"
                          item-title="name" :disabled="disableMealPackage" clearable />
                      </VCol>

                      <VCol cols="12" sm="6" md="6">
                        <VCombobox v-model="ledger_id" :label="$t('Ledger')" :items="ledgers" item-title="name"
                          :rules="[requiredValidator]" />
                      </VCol>

                      <VCol cols="12" sm="6" md="6">
                        <VCheckbox v-model="active" :label="$t('Active')" persistent-placeholder />
                      </VCol>

                      <!-- Dialog start -->
                      <VDialog
                        v-model="isDialogStart"
                        width="300"

                      >

                        <DialogCloseBtn @click="isDialogStart = false" />

                        <VCard style="margin: auto;padding: 20px">
                          <AppDateTimePicker v-model="start_date" :label="$t('Start Date')" type="date"
                          :config="{  altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',inline:true }"
                                             disabled="disabled"
                          :rules="[requiredValidator]" />
                          <VCardText class="d-flex flex-wrap gap-3">
                            <VSpacer />

                            <VBtn @click="isDialogStart = false">
                              ok
                            </VBtn>
                          </VCardText>
                        </VCard>
                      </VDialog>

                      <!-- Dialog end -->
                      <VDialog
                        v-model="isDialogEnd"
                        width="300"

                      >

                        <DialogCloseBtn @click="isDialogEnd = false" />

                        <VCard style="margin: auto;padding: 20px">

                          <AppDateTimePicker v-model="end_date" :label="$t('End Date')" type="date"
                          :config="{  altFormat: 'Y-m-d l', dateFormat: 'Y-m-d',inline:true }"
                                             disabled="disabled"
                          :rules="[requiredValidator]" />
                          <VCardText class="d-flex flex-wrap gap-3">
                            <VSpacer />

                            <VBtn @click="isDialogEnd = false">
                              ok
                            </VBtn>
                          </VCardText>
                        </VCard>
                      </VDialog>
                    </VRow>
                    <VBtn @click="dialog = false, currentStep = 0">
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn v-if="editFlag" style="left: 35%;" @click="currentStep += 1">
                      {{ $t('Next') }}
                    </VBtn>
                    <VBtn type="submit" class="float-end" @click.prevent="submitForm">
                      {{ $t('Submit Rate Code') }}
                    </VBtn>
                  </VForm>
                </VWindowItem>
                <VWindowItem>
                  <VTable>
                    <tbody>
                      <tr v-for="(roomType, index) in room_types" :key="roomType.id">
                        <th>{{ roomType.rm_type_name }}</th>
                        <td id="tdpax">
                          <VTextField v-model="pax1[index]" :label="$t('Pax 1')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="pax2[index]" :label="$t('Pax 2')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="pax3[index]" :label="$t('Pax 3')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="pax4[index]" :label="$t('Pax 4')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="pax5[index]" :label="$t('Pax 5')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="pax6[index]" :label="$t('Pax 6')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                        <td>
                          <VTextField v-model="extra[index]" :label="$t('Extra Pax')" persistent-placeholder type="Number"
                            class="my-4" />
                        </td>
                      </tr>
                    </tbody>
                  </VTable>
                  <VBtn @click="dialog = false, currentStep = 0">
                    {{ $t('Close') }}
                  </VBtn>
                  <VBtn v-if="editFlag" style="left: 35%;" @click="currentStep = 0">
                    {{ $t('Previous') }}
                  </VBtn>
                  <VBtn type="submit" class="float-end" @click="submitRooTypes">
                    {{ $t('Submit Pax') }}
                  </VBtn>
                </VWindowItem>
              </VWindow>
            </VCard>
          </VDialog>
        </div>
      </div>
    </div>

    <VTable>
      <thead>
        <tr>
          <th>Rate Code</th>
          <th v-if="$i18n.locale === 'en'">
            Name
          </th>
          <th v-else>
            {{ $t('name') }} ({{ $i18n.locale }})
          </th>
          <th>Start Date {{ $t('Submit Pax') }}</th>
          <th>End Date {{ $t('Submit Pax') }}</th>
          <th v-if="$can('edit', 'ratecode')">
            {{ $t('Update') }}
          </th>
          <th v-ift="$can('delete', 'ratecode')">
            {{ $t('Delete') }}
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="rate in rate_codes" :key="rate.id">
          <td v-if="rate.rate_code">
            {{ rate.rate_code }}
          </td>
          <td v-else>
            -
          </td>
          <td v-if="$i18n.locale === 'en'">
            {{ rate.name }}
          </td>
          <td v-else>
            {{ rate.name_loc }}
          </td>
          <td v-if="rate.start_date">
            {{ rate.start_date }}
          </td>
          <td v-else>
            -
          </td>
          <td v-if="rate.end_date">
            {{ rate.end_date }}
          </td>
          <td v-else>
            -
          </td>

          <td v-if="$can('edit', 'ratecode')">
            <VBtn @click="update(rate)">
              <VIcon icon="mdi-file-edit-outline" />
            </VBtn>
          </td>
          <td v-if="$can('delete', 'ratecode')">
            <VBtn @click="del(rate.id)" color="red" style="background: red;color: white;">
              <VIcon icon="mdi-delete" style="font-size: 135%;" />
            </VBtn>
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<script>
import { mapActions, mapStores } from 'pinia'
import Swal from "sweetalert2"
import { useLedgerStore } from '../stores/LedgerStore'
import { useMealsStore } from '../stores/MealsStore'
import { useRatecodeStore } from '../stores/RatecodeStore'
import { useRoomStore } from '../stores/RoomStore'

export default {
  name: "RateCode",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      testDate:'',
      start_date_hijri: null,
      end_date_hijri: null,

      isDialogStart: false,
      isDialogEnd: false,
      search: null,
      dialog: false,
      rate_code: null,
      rate_name: null,
      name_loc: null,
      start_date: null,
      end_date: null,
      active: false,
      meal_id: null,
      meal_package_id: null,
      ledger_id: null,
      room_types_ids: [],
      tab: 1,
      disableMeal: false,
      disableMealPackage: false,
      roomTypesPaxList: [],
      showRoomTypes: false,
      editFlag: false,
      fetched_id: null,
      currentStep: 0,
      pax1: [],
      pax2: [],
      pax3: [],
      pax4: [],
      pax5: [],
      pax6: [],
      extra: [],
      pax_array: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useMealsStore, useLedgerStore, useRatecodeStore, useRoomStore),
    meals() {
      return this.mealsStore.meals
    },
    meal_packages() {
      return this.mealsStore.meal_packages
    },
    ledgers() {
      return this.ledgerStore.ledgers
    },
    rate_codes() {
      return this.ratecodeStore.rate_codes
    },
    response() {
      return this.ratecodeStore.response
    },
    room_types() {
      return this.roomStore.room_types
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    start_date() {
      if (this.start_date != null) {
        this.start_date_hijri = this.$getHijriDate(this.start_date)
      }
      else {
        this.start_date_hijri = null
      }

    },
    end_date() {
      if (this.end_date != null) {
        this.end_date_hijri = this.$getHijriDate(this.end_date)
      }
      else {
        this.end_date_hijri = null
      }

    },
    meal_id() {
      this.meal_id ? this.disableMealPackage = true : this.disableMealPackage = false
    },
    meal_package_id() {
      this.meal_package_id ? this.disableMeal = true : this.disableMeal = false
    },
    dialog() {
      !this.dialog ? this.clearForm() : null
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useMealsStore, ["getMealsAction", "getMealPackagesAction"]),
    ...mapActions(useLedgerStore, ["getLedgers"]),
    ...mapActions(useRatecodeStore, ["getRateCodesAction", "addRateCodeAction", "attachRoomtypeRatecodeAction", "updateRateCodeAction", "deatachRoomtypeRatecode", "deleteRateCodeAction"]),
    ...mapActions(useRoomStore, ["getRoomTypesAction"]),
    async submitForm() {
      const isValid = await this.$refs.refForm.validate()
      if (isValid.valid) {
        this.submitRateCode()
      }
    },
    clearForm() {
      this.editFlag = false
      this.rate_code = null
      this.rate_name = null
      this.name_loc = null
      this.start_date = null
      this.end_date = null
      this.active = false
      this.meal_id = null
      this.meal_package_id = null
      this.ledger_id = null
      this.tab = 1
      this.disableMeal = false
      this.disableMealPackage = false
      this.showRoomTypes = false
      this.pax1 = []
      this.pax2 = []
      this.pax3 = []
      this.pax4 = []
      this.pax5 = []
      this.pax6 = []
      this.extra = []
      this.start_date_hijri = null
      this.end_date_hijri = null
      this.currentStep = 0
    },
    async submitRateCode() {
      if (!this.editFlag) {

        const response = await this.addRateCodeAction({
          rate_code: this.rate_code,
          name: this.rate_name,
          name_loc: this.name_loc,
          start_date: this.start_date,
          end_date: this.end_date,
          active: this.active,
          meal_id: this.meal_id != null ? this.meal_id.id : null,
          meal_package_id: this.meal_package_id != null ? this.meal_package_id.id : null,
          ledger_id: this.ledger_id.id,
          room_type_id: this.room_types.map(rt => rt.id),
        })

        if (response.status != undefined) {
          this.currentStep += 1
          this.insertAlert('Rate code created successfully')
          this.rate_code_id = response.data.ratecode_id
        }

      }
      else {
        const response = await this.updateRateCodeAction({
          rate_code: this.rate_code,
          name: this.rate_name,
          name_loc: this.name_loc,
          start_date: this.start_date,
          end_date: this.end_date,
          active: this.active,
          meal_id: this.meal_id != null ? this.meal_id.id : null,
          meal_package_id: this.meal_package_id != null ? this.meal_package_id.id : null,
          ledger_id: this.ledger_id.id,
        }, this.fetched_id)

        if (response.status != undefined) {
          this.currentStep += 1
          this.insertAlert('Rate code Updated successfully')
        }

      }
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    submitRooTypes() {
      if (!this.editFlag) {
        for (let i = 0; i < this.room_types.length; i++) {
          this.pax_array.push({
            room_type_id: this.room_types[i].id,
            pax1: this.pax1[i] != null ? this.pax1[i] : 0,
            pax2: this.pax2[i] != null ? this.pax2[i] : 0,
            pax3: this.pax3[i] != null ? this.pax3[i] : 0,
            pax4: this.pax4[i] != null ? this.pax4[i] : 0,
            pax5: this.pax5[i] != null ? this.pax5[i] : 0,
            pax6: this.pax6[i] != null ? this.pax6[i] : 0,
            extra_pax: this.extra[i] != null ? this.extra[i] : 0,
          })
        }
        this.attachRoomtypeRatecodeAction({ array: this.pax_array }, this.rate_code_id)
        this.pax_array = []
        this.rate_code_id = null
        this.insertAlert('Rate code Roomtypes successfully created')
      }
      else {
        for (let i = 0; i < this.room_types.length; i++) {
          this.pax_array.push({
            room_type_id: this.room_types[i].id,
            pax1: this.pax1[i] != null ? this.pax1[i] : 0,
            pax2: this.pax2[i] != null ? this.pax2[i] : 0,
            pax3: this.pax3[i] != null ? this.pax3[i] : 0,
            pax4: this.pax4[i] != null ? this.pax4[i] : 0,
            pax5: this.pax5[i] != null ? this.pax5[i] : 0,
            pax6: this.pax6[i] != null ? this.pax6[i] : 0,
            extra_pax: this.extra[i] != null ? this.extra[i] : 0,
          })
        }
        this.deatachRoomtypeRatecode({ array: this.pax_array }, this.fetched_id)
        this.pax_array = []
        this.insertAlert('Rate code Roomtypes successfully updated')
      }
      this.dialog = false
      this.clearForm()
    },
    update(rate) {
      this.editFlag = true
      this.dialog = true
      this.rate_code = rate.rate_code
      this.rate_name = rate.name
      this.name_loc = rate.name_loc
      this.start_date = rate.start_date
      this.end_date = rate.end_date
      this.active = rate.active
      this.meal_id = rate.meal
      this.meal_package_id = rate.meal_package
      this.ledger_id = rate.ledger
      this.roomTypesPaxList = rate.room_types
      this.fetched_id = rate.id
      for (let i = 0; i < this.roomTypesPaxList.length; i++) {
        this.pax1[i] = this.roomTypesPaxList[i].pivot.pax1
        this.pax2[i] = this.roomTypesPaxList[i].pivot.pax2
        this.pax3[i] = this.roomTypesPaxList[i].pivot.pax3
        this.pax4[i] = this.roomTypesPaxList[i].pivot.pax4
        this.pax5[i] = this.roomTypesPaxList[i].pivot.pax5
        this.pax6[i] = this.roomTypesPaxList[i].pivot.pax6
        this.extra[i] = this.roomTypesPaxList[i].pivot.extra_pax
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

    del(id) {
      this.$swal.fire({
        icon: "error",
        title: "Do you want to Delete",
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Delete`,
      }).then(result => {
        if (result.isDenied) {
          this.deleteRateCodeAction(id)

          const Toast = this.$swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
              toast.addEventListener("mouseenter", this.$swal.stopTimer)
              toast.addEventListener("mouseleave", this.$swal.resumeTimer)
            },
          })

          Toast.fire({
            icon: "success",
            title: `Data Deleted successfully`,
            color: "red",
            timer: 10000,
          })
        }
      })
    },
  },
  created() {
    this.getMealsAction()
    this.getMealPackagesAction()
    this.getLedgers()
    this.getRateCodesAction()
    this.getRoomTypesAction()
  },

}
</script>

<style scoped>
.filter {
  display: flex;
}
</style>

<route lang="yaml">
  meta:
    action: view
    subject: rate code
  </route> */
