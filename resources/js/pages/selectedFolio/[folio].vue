<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
const desserts = [
  {
    dessert: 'Frozen Yogurt',
    calories: 159,
    fat: 6,

  },
  {
    dessert: 'Ice cream sandwich',
    calories: 237,
    fat: 6,

  },
  {
    dessert: 'Eclair',
    calories: 262,
    fat: 6,

  },
  {
    dessert: 'Cupcake',
    calories: 305,
    fat: 6,

  },
  {
    dessert: 'Gingerbread',
    calories: 356,
    fat: 6,

  },
]

// Headers
const headers = [
  {
    title: '',
    key: 'data-table-expand',
  },
  {
    title: 'no.',
    key: 'id',
  },
  {
    title: 'hotel_date',
    key: 'hotel_date',
  },
  {
    title: 'charge_amount',
    key: 'charge_amount',
  },
  {
    title: 'payment_amount',
    key: 'payment_amount',
  },
  {
    title: 'Led & Pay',
    key: 'Ledger_Payment',
  },
  {
    title: 'description',
    key: 'description',
  },
  {
    title: 'created_by',
    key: 'created_by',
  },
  {
    title: 'is_void',
    key: 'status',
  },
  {
    title: 'View',
    key: 'View',
  },
]

const resolveStatusVariant = status => {
  if (status === 6)
    return {
      color: 'primary',
      text: 'Current',
    }
  else if (status === 1)
    return {
      color: 'success',
      text: 'Professional',
    }
  else if (status === 3)
    return {
      color: 'error',
      text: 'Rejected',
    }
  else if (status === 4)
    return {
      color: 'warning',
      text: 'Resigned',
    }
  else
    return {
      color: 'error',
      text: 'Voided',
    }
}

import {
  floatValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div>
    <VContainer v-for="item in Folio.FolioState">
      <VCardTitle
        class="text-center my-1"
        style="display: flex;align-items: center;justify-content: space-between;"
      >
        <VCard
          v-if="item.checked_out == 1"
          color="error"
          style="display: flex;width: 100%;height: 100px;align-items: center;justify-content: space-around;"
        >
          <b>{{ $t('this Guest is Checked out') }}</b>
          <b> {{ $t('Folio Num') }} </b>: <b>{{ item.id }}</b>
        </VCard>
        <VCol
          cols="12"
          sm="12"
          md="12"
        >
          <br>
          <VCard
            v-if="item.checked_out == 0 && item.is_checked_in == 1"
            style="box-shadow: 2px 2px 5px 3px green"
          >
            <template #append />
            <VCardText>
              <VRow style="display: flex;justify-content: space-around;">
                <VCol
                  key="Sales"
                  cols="6"
                  md="3"
                >
                  <div class="d-flex">
                    <div class="d-flex flex-column">
                      <span class="text-h6 font-weight-medium">
                        <b>{{ $t('In House') }}</b>
                      </span>
                      <span class="text-caption">
                        <b> {{ $t('status') }} </b>
                      </span>
                    </div>
                  </div>
                </VCol>

                <VCol
                  key="Sales"
                  cols="6"
                  md="3"
                >
                  <div class="d-flex">
                    <div class="d-flex flex-column">
                      <span class="text-h6 font-weight-medium">
                        <b>{{ item.id }}</b>
                      </span>
                      <span class="text-caption">
                        <b> {{ $t('Folio Num') }} </b>
                      </span>
                    </div>
                  </div>
                </VCol>

                <VCol
                  key="Sales"
                  cols="6"
                  md="3"
                >
                  <div class="d-flex">
                    <div class="d-flex flex-column">
                      <span class="text-h6 font-weight-medium">
                        <b>{{ item.in_date }}</b>
                      </span>
                      <span class="text-caption">
                        <b> {{ $t('Arrival date') }} </b>
                      </span>
                    </div>
                  </div>
                </VCol>

                <VCol
                  key="Sales"
                  cols="6"
                  md="3"
                >
                  <div class="d-flex">
                    <div class="d-flex flex-column">
                      <span class="text-h6 font-weight-medium">
                        <b>{{ item.out_date }}</b>
                      </span>
                      <span class="text-caption">
                        <b> {{ $t('Departure date') }} </b>
                      </span>
                    </div>
                  </div>
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
        </VCol>
      </VCardTitle>

      <VRow style="width: 100%;">
        <VSpacer />
        <VCol
          cols="6"
          sm="6"
          md="6"
        >
          <VCard
            subtitle="Folio Data"
            :dir="$i18n.locale === 'en' ? 'rtl' : 'ltr'"
          >
            <VCardText>
              <VList class="card-list">
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ $t('Methods Of Payment') }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold"> {{ $t('Methods Of Payment') }}</span>
                    <VAvatar
                      color="primary"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.rate_code != null ? item.rate_code.name + ' -' : "" }}  {{ item.rm_rate }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('Rate') }}</span>
                    <VAvatar
                      color="success"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-link" />
                    </VAvatar>
                  </template>
                </VListItem>

                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.meal != null ? item.meal.name : "" }}
                    {{ item.meal_package != null ? item.meal_package.name : "" }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('Meal/Package') }}</span>
                    <VAvatar
                      color="warning"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-alert-triangle" />
                    </VAvatar>
                  </template>
                </VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>
        <VCol
          cols="6"
          sm="6"
          md="6"
        >
          <VCard :dir="$i18n.locale === 'en' ? 'rtl' : 'ltr'">
            <VCardText>
              <VList class="card-list">
                <VListItem :key="item.room">
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.room != null ? item.room.rm_name_en : '' }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold"> {{ $t('room') }}</span>
                    <VAvatar
                      color="primary"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.profile?.first_name + ' ' + item.profile?.last_name }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('Guest name') }}</span>
                    <VAvatar
                      color="success"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-link" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ $t('group name') }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('group name') }}</span>
                    <VAvatar
                      color="info"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-click" />
                    </VAvatar>
                  </template>
                </VListItem>


                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.company?item.company.company_name:'' }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('Company Name') }}</span>
                    <VAvatar
                      color="error"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.hotel_note ? item.hotel_note : 'No note' }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">{{ $t('Note') }}</span>
                    <VAvatar
                      color="error"
                      variant="tonal"
                      size="34"
                      rounded
                    >
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>

        <VCol
          cols="12"
          sm="12"
          md="12"
          style="padding: 0px;"
        >
          <VCard>
            <VTabs
              id="tab_id"
              v-model="currentItem"

              fixed-tabs
            >
              <VTab
                v-for="windows in item.window"
                :key="windows.id"
                :value="windows.id"
              >
                {{ windows.window_name }}
              </VTab>
            </VTabs>
            <VWindow v-model="currentItem">
              <VWindowItem
                v-for="windows in item.window"
                :key="windows"
                :value="windows.id"
              >
                <VCard>
                  <VCardText>
                    <VDataTable
                      :headers="headers"
                      :items="windows.transactions"
                      :items-per-page="500"
                      expand-on-click
                    >
                      <template #expanded-row="slotProps">

                        <tr v-if="slotProps.item.taxes.length > 0">
                          <td />
                          <td>
                            <b
                              v-for="TaxData in slotProps.item.taxes"
                              style="display:flex;width: 130px;"
                            >{{ TaxData.name }} => {{ parseFloat(TaxData.pivot.amount).toFixed(2) }}</b>
                          </td>
                        </tr>
                        <br>

                        <tr v-if="slotProps.item.trans_collection.length > 0">
                          <td>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-corner-down-right"
                              width="44"
                              height="44"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="#2c3e50"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path
                                stroke="none"
                                d="M0 0h24v24H0z"
                                fill="none"
                              />
                              <path d="M6 6v6a3 3 0 0 0 3 3h10l-4 -4m0 8l4 -4" />
                            </svg>
                          </td>



                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex;width: 90px; "
                            >
                              {{ TaxData.id }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex;width: 90px; "
                            >
                              {{ TaxData.hotel_date }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex; "
                            >
                              {{ TaxData.charge_amount }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex; "
                            >
                              {{ TaxData.payment_amount }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex;width: 120px; "
                            >
                              {{ TaxData.ledger != null ? TaxData.ledger.name :TaxData.payment_type.name }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex;width: 140px; "
                            >
                              {{ TaxData.description }}
                            </p>
                          </td>
                          <td>
                            <p
                              v-for="TaxData in slotProps.item.trans_collection"
                              style="display:flex;width: 90px; "
                            >
                              {{ TaxData.created_by.firstname }} {{ TaxData.created_by.lastname }}
                            </p>
                          </td>
                        </tr>
                      </template>


                      <template #item.id="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.id }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.hotel_date="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.hotel_date }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.charge_amount="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.charge_amount }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.payment_amount="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.payment_amount }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.Ledger_Payment="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.ledger != null ? item.ledger.name : item.payment_type.name }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.description="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.description }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.created_by="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.created_by.firstname }} {{ item.created_by.lastname }}</span>
                          </div>
                        </div>
                      </template>

                      <template #item.View="{ item }">
                        <div class="d-flex align-center">
                          <div class="d-flex flex-column ms-3">
                            <VDialog
                              v-model="item.transactionDialog"
                              width="1024"
                              scroll-strategy="none"
                              z-index="1000"
                            >
                              <template #activator="{ props }">
                                <VBtn
                                  v-bind="props"
                                  @click="selectTransaction(item)"
                                >
                                  {{ $t('view') }}
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
                                      <span class="text-h5">{{ $t('Transaction Details') }}</span>
                                    </VCol>
                                  </VRow>
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
                                          v-model="transaction_charge_amount"
                                          :label="$t('Charge amount')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      >
                                        <VTextField
                                          v-model="transaction_charge_without_taxes"
                                          :label="$t('Charge without taxes')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      >
                                        <VTextField
                                          v-model="transaction_ledger_name"
                                          :label="$t('Ledger')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      >
                                        <VTextField
                                          v-model="transaction_hotel_date"
                                          :label="$t('Hotel date')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      >
                                        <VTextField
                                          v-model="transaction_description"
                                          :label="$t('Description')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      >
                                        <VCheckbox
                                          v-model="transaction_tax_included"
                                          :label="$t('Tax Included')"
                                          readonly
                                        />
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="4"
                                      />
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="2"
                                      >
                                        <VBtn
                                          color="error"
                                          :disabled="Folio.FolioState[0].checked_out"
                                          @click="voidTransaction"
                                        >
                                          {{ $t('Void Transaction') }}
                                        </VBtn>
                                      </VCol>
                                      <VCol
                                        cols="12"
                                        sm="6"
                                        md="2"
                                      >
                                        <VBtn
                                          color="success"
                                          :disabled="Folio.FolioState[0].checked_out || Folio.FolioState[0].is_reservation"
                                          @click="transferTransaction"
                                        >
                                          {{ $t('Transfer') }}
                                        </VBtn>
                                      </VCol>
                                    </VRow>
                                  </VContainer>
                                </VCardText>
                                <VCardActions>
                                  <VSpacer />
                                  <VBtn
                                    color="blue-darken-1"
                                    variant="text"
                                    @click="item.transactionDialog = false"
                                  >
                                    {{ $t('Close') }}
                                  </VBtn>
                                </VCardActions>
                              </VCard>
                            </VDialog>
                          </div>
                        </div>
                      </template>

                      <template #item.status="{ item }">
                        <VChip
                          v-if="item.is_void==1"
                          :color="resolveStatusVariant(item.status).color"
                          class="font-weight-medium"
                          size="small"
                        >
                          {{ resolveStatusVariant(item.status).text }}
                        </VChip>
                        <VChip
                          v-else
                          color="info"
                          class="font-weight-medium"
                          size="small"
                        >
                          Not Voided
                        </VChip>
                      </template>
                    </VDataTable>
                  </VCardText>
                </VCard>
                <VCol
                  cols="12"
                  sm="12"
                  md="12" id="vvvvvvvvvvvv"
                >
                  <br>
                  <VCard
                    id="card_hight"
                    title="Transactions Of Windows"

                  >
                    <template #append />
                    <VCardText>
                      <VRow
                        id="row_hight"
                        style="display: flex;justify-content: space-around;"
                      >
                        <VCol
                          key="Sales"
                          cols="6"
                          md="3"
                        >
                          <div class="d-flex">
                            <VAvatar
                              color="primary"
                              variant="tonal"
                              size="42"
                              class="me-3"
                            >
                              <VIcon
                                size="24"
                                icon="tabler-chart-pie-2"
                              />
                            </VAvatar>
                            <div class="d-flex flex-column">
                              <span class="text-h6 font-weight-medium">
                                {{ parseFloat(windows.transactions_sum_payment_amount).toFixed(2) }}
                              </span>
                              <span class="text-caption">
                                {{ $t('Total payment of this Windows') }}
                              </span>
                            </div>
                          </div>
                        </VCol>
                        <VCol
                          key="Sales"
                          cols="6"
                          md="3"
                        >
                          <div class="d-flex">
                            <VAvatar
                              color="error"
                              variant="tonal"
                              size="42"
                              class="me-3"
                            >
                              <VIcon
                                size="24"
                                icon="tabler-shopping-cart"
                              />
                            </VAvatar>
                            <div class="d-flex flex-column">
                              <span class="text-h6 font-weight-medium">
                                {{ parseFloat(windows.transactions_sum_charge_amount).toFixed(2) }}
                              </span> <span class="text-caption">
                                {{ $t('Total Charge of this Windows') }}
                              </span>
                            </div>
                          </div>
                        </VCol>
                        <VCol
                          key="Sales"
                          cols="6"
                          md="3"
                        >
                          <div class="d-flex">
                            <VAvatar
                              color="info"
                              variant="tonal"
                              size="42"
                              class="me-3"
                            >
                              <VIcon
                                size="24"
                                icon="tabler-currency-dollar"
                              />
                            </VAvatar>
                            <div class="d-flex flex-column">
                              <span class="text-h6 font-weight-medium">
                                <p>
                                  {{ parseFloat(windows.transactions_sum_charge_amount -
                                  windows.transactions_sum_payment_amount).toFixed(2) }}
                                </p>
                              </span>
                              <span class="text-caption">
                                {{ $t('Current Balance of this windows') }}
                              </span>
                            </div>
                          </div>
                        </VCol>
                        <!--                        <VCol-->
                        <!--                          key="Sales"-->
                        <!--                          cols="6"-->
                        <!--                          md="3"-->
                        <!--                        >-->
                        <!--                          <div class="d-flex">-->
                        <!--                            <VAvatar-->
                        <!--                              color="primary"-->
                        <!--                              variant="tonal"-->
                        <!--                              size="42"-->
                        <!--                              class="me-3"-->
                        <!--                            >-->
                        <!--                              <VIcon-->
                        <!--                                size="24"-->
                        <!--                                icon="tabler-chart-pie-2"-->
                        <!--                              />-->
                        <!--                            </VAvatar>-->
                        <!--                            <div class="d-flex flex-column">-->
                        <!--                              <span class="text-h6 font-weight-medium">-->
                        <!--                                {{ parseFloat(calculateTotalPayAmount(windows.transactions)).toFixed(2) }}-->
                        <!--                              </span>-->
                        <!--                              <span class="text-caption">-->
                        <!--                                {{ $t('Total payment of this Windows') }}-->
                        <!--                              </span>-->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </VCol>-->
                        <!--                        <VCol-->
                        <!--                          key="Sales"-->
                        <!--                          cols="6"-->
                        <!--                          md="3"-->
                        <!--                        >-->
                        <!--                          <div class="d-flex">-->
                        <!--                            <VAvatar-->
                        <!--                              color="error"-->
                        <!--                              variant="tonal"-->
                        <!--                              size="42"-->
                        <!--                              class="me-3"-->
                        <!--                            >-->
                        <!--                              <VIcon-->
                        <!--                                size="24"-->
                        <!--                                icon="tabler-shopping-cart"-->
                        <!--                              />-->
                        <!--                            </VAvatar>-->
                        <!--                            <div class="d-flex flex-column">-->
                        <!--                              <span class="text-h6 font-weight-medium">-->
                        <!--                                {{ parseFloat(calculateTotalChargeAmount(windows.transactions)).toFixed(2) }}-->
                        <!--                              </span> <span class="text-caption">-->
                        <!--                                {{ $t('Total Charge of this Windows') }}-->
                        <!--                              </span>-->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </VCol>-->
                        <!--                        <VCol-->
                        <!--                          key="Sales"-->
                        <!--                          cols="6"-->
                        <!--                          md="3"-->
                        <!--                        >-->
                        <!--                          <div class="d-flex">-->
                        <!--                            <VAvatar-->
                        <!--                              color="info"-->
                        <!--                              variant="tonal"-->
                        <!--                              size="42"-->
                        <!--                              class="me-3"-->
                        <!--                            >-->
                        <!--                              <VIcon-->
                        <!--                                size="24"-->
                        <!--                                icon="tabler-currency-dollar"-->
                        <!--                              />-->
                        <!--                            </VAvatar>-->
                        <!--                            <div class="d-flex flex-column">-->
                        <!--                              <span class="text-h6 font-weight-medium">-->
                        <!--                                <p>-->
                        <!--                                  {{ parseFloat(calculateTotalChargeAmount(windows.transactions) - -->
                        <!--                                    calculateTotalPayAmount(windows.transactions)).toFixed(2) }}-->
                        <!--                                </p>-->
                        <!--                              </span>-->
                        <!--                              <span class="text-caption">-->
                        <!--                                {{ $t('Current Balance of this windows') }}-->
                        <!--                              </span>-->
                        <!--                            </div>-->
                        <!--                          </div>-->
                        <!--                        </VCol>-->
                        <VCol
                          key="Sales"
                          cols="6"
                          md="3"
                        >
                          <div class="d-flex">
                            <VAvatar
                              color="info"
                              variant="tonal"
                              size="42"
                              class="me-3"
                            >
                              <VIcon
                                size="24"
                                icon="tabler-line"
                              />
                            </VAvatar>
                            <div class="d-flex flex-column">
                              <span class="text-h6 font-weight-medium">
                                <p>
                                  {{ parseFloat(calculateTotaltaxes(windows.transactions)) }}
                                </p>
                              </span>
                              <span class="text-caption">
                                {{ $t('Total Balance of Taxes') }}
                              </span>
                            </div>
                          </div>
                        </VCol>
                      </VRow>
                    </VCardText>
                  </VCard>
                </VCol>
              </VWindowItem>
            </VWindow>
          </VCard>
        </VCol>

        <VCol
          cols="12"
          sm="11"
          md="11"
        >
          <br />

          <div
            id="btn_hight"
            style="display: flex;flex-wrap: wrap;justify-content: space-between;"
          >
            <VBtn
              variant="flat"
              @click="$router.push({ name: `inhouse-update-guest`, params: { guest: Folio.FolioState[0].id } })"
            >
              {{ $t('Guest data') }}
            </VBtn>
            <VBtn
              variant="flat"
              :disabled="Folio.FolioState[0].is_reservation"
              @click="openPageInNewTab"
            >
              print
            </VBtn>
            <VBtn
              variant="flat"
              :disabled="Folio.FolioState[0].checked_out || Folio.FolioState[0].is_reservation"
              @click="CheckoutDialog = true"
            >
              {{ $t('Checkout') }}
            </VBtn>

            <VBtn

              :disabled="Folio.FolioState[0].checked_out"
              @click="CashierDialog = true"
            >
              {{ $t('Cashier') }}
            </VBtn>

            <VBtn @click="otherDialog = true">
              {{ $t('Other options') }}
            </VBtn>

            <VBtn variant="flat">
              {{ $t('Exit') }}
            </VBtn>
          </div>
        </VCol>
      </VRow>
    </VContainer>

    <VCol

      cols="12"
      sm="12"
      md="12"
    >
      <br>
      <VCard title="All Windows">
        <VCardText>
          <VRow style="display: flex;justify-content: space-around;">
            <VCol
              key="Sales"
              cols="6"
              md="3"
            >
              <div class="d-flex">
                <VAvatar
                  color="primary"
                  variant="tonal"
                  size="42"
                  class="me-3"
                >
                  <VIcon
                    size="24"
                    icon="tabler-chart-pie-2"
                  />
                </VAvatar>
                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ parseFloat(Total_payment_Amount).toFixed(2) }}</span>
                  <span class="text-caption">
                    {{ $t('ToTal Payment Amount') }}
                  </span>
                </div>
              </div>
            </VCol>

            <VCol
              key="Sales"
              cols="6"
              md="3"
            >
              <div class="d-flex">
                <VAvatar
                  color="error"
                  variant="tonal"
                  size="42"
                  class="me-3"
                >
                  <VIcon
                    size="24"
                    icon="tabler-shopping-cart"
                  />
                </VAvatar>

                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ parseFloat(Total_Charge_Amount).toFixed(2) }}</span>
                  <span class="text-caption">
                    {{ $t('Total Charge Amount') }}
                  </span>
                </div>
              </div>
            </VCol>
            <VCol
              key="Sales"
              cols="6"
              md="3"
            >
              <div class="d-flex">
                <VAvatar
                  color="info"
                  variant="tonal"
                  size="42"
                  class="me-3"
                >
                  <VIcon
                    size="24"
                    icon="tabler-currency-dollar"
                  />
                </VAvatar>
                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ parseFloat(Total_Charge_Amount -
                    Total_payment_Amount).toFixed(2) }}</span>
                  <span class="text-caption">
                    {{ $t('Current Balance') }}
                  </span>
                </div>
              </div>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
    <br>

    <VDialog
      v-model="extendstayDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
    >
      <VCard>
        <VCardTitle>
          <span class="text-h5">{{ $t('Extend stay') }}</span>
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
                  v-model="guest_name"
                  :label="$t('Guest name')"
                  readonly
                />
              </VCol>
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
                  v-model="folio_no"
                  :label="$t('Folio')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="arrival_date"
                  :label="$t('Arrival date')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="departure_date"
                  :label="$t('Departure date')"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <span> {{ $t('Update date ') }}</span>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mt-2"
              >
                <AppDateTimePicker
                  v-model="extended_date"
                  :label="$t('Change to')"
                  :config="{ inline: true, altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d' }"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mt-2"
              >
                <VTextField
                  v-model="no_of_nights"
                  :label="$t('Number of nights')"
                  type="number"
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
            @click="extendstayDialog = false"
          >
            {{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="submitExtendStay"
          >
            {{ $t('Submit') }}
          </VBtn>
        </VCardActions>
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
          <span class="text-h5">{{ $t('Posting charges') }}</span>

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
                  :label="$t('Guest name')"
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
                  :label="$t('Room')"
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
                  :label="$t('Arrival date')"
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
                  :label="$t('Departure date')"
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
                    :label="$t('Ledger account')"
                    readonly
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="9"
                >
                  <VTextField
                    v-model="ledger_name"
                    :label="$t('Ledger name')"
                    readonly
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VTextField
                    v-model="amount"
                    :label="$t('Amount')"
                    append-icon="tabler-cash"
                    type="number"
                    clearable
                    @keyup="amount_key"
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VTextarea
                    v-model="description"
                    :label="$t('Description')"
                    clearable
                  />
                </VCol>
                <VCol
                  cols="12"
                  sm="6"
                  md="12"
                >
                  <VCheckbox
                    v-model="tax_included"
                    :label="$t('Tax Included')"
                  />
                </VCol>
                {{tax_included}}
              </VCol>


              <VCol
                cols="12"
                sm="6"
                md="6"
                class="mt-8"
                style="box-shadow: 0px 0px 4px 0px;border-radius: 10px;max-height: 400px;overflow-y: scroll"
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
                        :title="ledger.id + ' - ' + ledger.name + ' ( ' + ledger.type + ' ) '"
                        @click="ledgerSelected(ledger)"
                      />
                    </VListGroup>
                  </template>
                </VList>
              </VCol>
            </VRow>
            <br>

          </VContainer>
        </VCardText>
        <VCol cols="12" style="display:flex;justify-content: space-around">

          <VCol cols="8">
            <VTable
              density="compact"
              class="text-no-wrap"
            >
              <thead>
              <tr>
                <th class="text-uppercase">
                  Name
                </th>
                <th class="text-uppercase">
                  Value
                </th>


              </tr>
              </thead>

              <tbody>
              <tr
                v-for="item in total_with_taxes.inctaxes"

              >
                <td>
                  {{ item.name }}
                </td>
                <td>
                  {{ item.value }}
                </td>

              </tr>
              <tr
                v-for="item in total_with_taxes.exctaxes"
              >
                <td>
                  {{ item.name }}
                </td>
                <td>
                  {{ item.value }}
                </td>


              </tr>
              </tbody>
            </VTable>
          </VCol>
          <VCol cols="4"   >
            <div style="height: 50%;width: 100%; display: flex;justify-content: center;align-items: center" class="text-2xl" >Total : {{tax_included==false?parseFloat(total_with_taxes.total - total_with_taxes.total_tax_amount).toFixed(2): total_with_taxes.total }} </div>
            <div style="height: 50%;width: 100%; display: flex;justify-content: center;align-items: center" class="text-2xl">Total taxes : {{tax_included==false?parseFloat(total_with_taxes.total_tax_amount).toFixed(3):parseFloat(total_with_taxes.total_with_tax).toFixed(3)}}</div>
          </VCol>
        </VCol>
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
            {{ $t('Submit') }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="PaymentDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="closeCashierPayment"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">{{ $t('Cashier Payment') }}</span>
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
                <VCombobox item-color="customColorValue"
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
                  style="height: 170%;"
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
            @click="closeCashierPayment"
          >
            {{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="submitPayment"
          >
            {{ $t('Submit') }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="ChangeRoomDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="ChangeRoomDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 "> {{ $t('Change Room') }}</span>
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
                  v-model="guest_name"
                  :label="$t('Guest name')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="arrival_date"
                  :label="$t('Arrival date')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="departure_date"
                  :label="$t('departure date')"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_room"
                  :label="$t('Guest')"
                  readonly
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VCombobox item-color="customColorValue"
                           v-model="changed_room"
                           :label="$t('Changed Room*')"
                           :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'"
                           item-value="room"
                />
              </VCol>


              <VCol
                cols="12"
                sm="6"
                md="2"
              >
                <VBtn @click="room_dialog = true">
                  {{ $t('select Room') }}
                </VBtn>
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_room_type"
                  :label="$t('Current Room type')"
                  readonly
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox item-color="customColorValue"
                           v-model="changed_room_type"
                           :label="$t('Changed Room type*')"
                           :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                           item-value="room_type"
                           readonly
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_no_of_pax"
                  label="Current No of pax"
                  readonly
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="changed_no_of_pax"
                  :label="$t('Changed No of pax*')"
                  type="number"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VTextField
                  v-model="reason"
                  :label="$t('Reason*')"
                />
              </VCol>
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
                <VRadioGroup
                  v-model="room_old_status_radio"
                  inline
                  class="ml-12"
                >
                  <span class="mt-1 mr-2">Change old status to: </span>
                  <VRadio
                    :label="$t('Dirty')"
                    value="Dirty"
                  />
                  <VRadio
                    :label="$t('Clean')"
                    value="Clean"
                  />
                </VRadioGroup>
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="m-auto"
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
            @click="clearChangeRoom"
          >
            {{ $t('Clear') }}
          </VBtn>

          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="ChangeRoomDialog = false; currentDateTime = null"
          >
            {{ $t('Close') }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disableChangeRoom"
            @click="submitChangeRoom"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="ChangeRateDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="ChangeRateDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Rate</span>
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
                  v-model="guest_name"
                  :label="$t('Guest name')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="room_type"
                  :label="$t('Room type')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="1"
              >
                <VTextField
                  v-model="no_of_pax"
                  :label="$t('PAX')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="2"
              >
                <VTextField
                  v-model="arrival_date"
                  :label="$t('Arrival date')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="2"
              >
                <VTextField
                  v-model="departure_date"
                  :label="$t('departure date')"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_rate_code"
                  :label="$t('Current Rate code')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox item-color="customColorValue"
                           v-model="changed_rate_code"
                           :label="$t('Changed Rate code*')"
                           :items="rate_codes"
                           :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_meal"
                  :label="$t('Current Meal/Meal Package')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="changed_meal"
                  :label="$t('Changed Meal/Meal Package*')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VTextField
                  v-model="current_room_rate"
                  :label="$t('Current Room Rate')"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VTextField
                  v-model="changed_room_rate"
                  :label="$t('Changed Room Rate*')"
                  type="number"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="2"
              >
                <VBtn
                  :disabled="disableChangeRate"
                  @click="changeResRateDays"
                >
                  {{ $t("Reservation days") }}
                </VBtn>
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VTextField
                  v-model="rate_reason"
                  :label="$t('Reason')"
                />
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="m-auto"
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
                    {{ $t("Created by") }}
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
            @click="clearChangeRate"
          >
            {{ $t("Clear") }}
          </VBtn>

          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="ChangeRateDialog = false; currentDateTime = null"
          >
            {{ $t("Close") }}
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disableChangeRate"
            @click="submitChangeRate"
          >
            {{ $t("Submit") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="CreateWindowDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="CreateWindowDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 "> {{ $t("Change Rate") }}</span>
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
                  v-model="guest_name"
                  label="Guest name"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
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
                md="4"
              >
                <VTextField
                  v-model="departure_date"
                  label="departure date"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
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
                <VTextField
                  v-model="new_window_name"
                  label="Window name"
                />
              </VCol>
            </VRow>
          </VContainer>

          <VContainer>
            <VRow class="d-flex flex-column align-center justify-center">
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="m-auto"
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
                    Created by
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
            @click="CreateWindowDialog = false; currentDateTime = null"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disableWindow"
            @click="submitWindow"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="changePropertiesDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="changePropertiesDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Properties</span>
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
                  v-model="guest_name"
                  label="Guest name"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
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
                md="4"
              >
                <VTextField
                  v-model="departure_date"
                  label="departure date"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VCombobox item-color="customColorValue"
                           v-model="market_segment"
                           :label="$t('Market segment*')"
                           :items="segments"
                           :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                           item-value="room"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VCombobox
                  v-model="source_of_business"
                  :label="$t('Source of business*')"
                  :items="sources"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'"
                  item-value="room"
                  item-color="customColorValue"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="4"
              >
                <VCombobox
                  v-model="company"
                  :label="$t('Company profile*')"
                  :items="companies"
                  :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'"
                  item-value="room"
                  item-color="customColorValue"
                />
              </VCol>
            </VRow>
          </VContainer>

          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="m-auto"
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
                    Created by
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
            @click="changePropertiesDialog = false; currentDateTime = null"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disableProperties"
            @click="submitProperties"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="RoutingChargeDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="RoutingChargeDialog = false"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Routing Charge</span>
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
                  label="departure date"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="current_room"
                  label="Current Room"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox
                  v-model="selected_room_routing"
                  :label="$t('Selected Room*')"
                  :items="oc_rooms"
                  :item-title="$i18n.locale === 'en' ? 'room_name_en' : 'room_name_loc'"
                  item-value="room"
                  item-color="customColorValue"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox
                  v-model="selected_window"
                  :label="$t('Selected Window*')"
                  :items="room_windows"
                  :item-title="$i18n.locale === 'en' ? 'window_name' : 'window_name'"
                  item-value="window"
                  item-color="customColorValue"
                />
              </VCol>

              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VCol
                  cols="12"
                  sm="6"
                  md="4"
                >
                  <VBtn
                    color="blue-darken-1"
                    variant="text"
                    @click="selectallLedgers"
                  >
                    select all
                  </VBtn>
                  <VBtn
                    color="blue-darken-1"
                    variant="text"
                    @click="selected_ledger = []"
                  >
                    unselect all
                  </VBtn>
                </VCol>
                <VTable
                  height="200"
                  equalcolumns="true"
                >
                  <thead>
                  <tr>
                    <th>
                      {{ $t('select') }}
                    </th>
                    <th>
                      {{ $t('ID') }}
                    </th>
                    <th>
                      {{ $t('Ledger name') }}
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                    v-for=" ( item, index ) in ledgers "
                    :key="item.id"
                  >
                    <td>
                      <VCheckbox
                        v-model="selected_ledger[index]"
                        :true-value="item.id"
                        @change="selectLedgerCheck(index)"
                      />

                    </td>
                    <td>{{ item.id }}</td>
                    <td>
                      {{ $i18n.locale === 'en' ? item.name : item.name_loc }}
                    </td>
                    <td>
                      {{ guest_routed_to[index] != null
                      ? (guest_routed_to[index].id != null ? guest_routed_to[index].room.rm_name_en + ' - ' +
                        guest_routed_to[index].profile?.first_name + ' ' + guest_routed_to[index].profile.last_name :
                        guest_routed_to[index].room_name_en + ' - ' + guest_routed_to[index].guests.profile?.first_name +
                        ' ' + guest_routed_to[index].guests.profile.last_name) : null }}
                    </td>
                    <td>
                      {{ guest_routed_to[index] != null ? window_routed_to[index].window_name : null }}
                    </td>
                  </tr>
                  </tbody>
                </VTable>
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
                class="m-auto"
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
                    Created by
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
            @click="RoutingChargeDialog = false; currentDateTime = null"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            :disabled="disableRoutingCharge"
            @click="submitRoutingCharge(selected_ledger)"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="extra_dialog"
      width="1300"
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
    <VDialog
      v-model="CheckoutDialog"
      width="1000"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="CheckoutDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment"
    >
      <VCard>
        <VCardTitle>
          Checkout options
        </VCardTitle>
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                :loading="cash_checkout_button"
                :disabled="cash_checkout_button"
                @click="cashCheckout"
              >
                {{ $t("Cash checkout") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                :loading="ledger_checkout_button"
                :disabled="ledger_checkout_button"
                @click="cityLedgerCheckout"
              >
                {{ $t("City ledger checkout") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn @click="CheckoutDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="CashierDialog"
      width="1000"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="CashierDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment"
    >
      <VCard>
        <VCardTitle>
          Checkout options
        </VCardTitle>
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block

                @click="CaherPayment"
              >
                {{ $t("Cashier Payment") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="chargeRouting"
              >
                {{ $t("Charge Routing") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="postingCharges"
              >
                {{ $t("Posting Charges") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn @click="CheckoutDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment,CashierDialog=false">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="otherDialog"
      width="1000"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="otherDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment"
    >
      <VCard>
        <VCardTitle>
          Checkout options
        </VCardTitle>
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block

                @click="extendStay"
              >
                {{ $t("Extend stay") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="changeRoom"
              >
                {{ $t("Change Room") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="changeRate"
              >
                {{ $t("Change Rate") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="newWindow"
              >
                {{ $t("New window") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="changeprop"
              >
                {{ $t("Change Business and segment") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="changeExtras"
              >
                {{ $t("Change Extras") }}
              </VBtn>
            </VCol>
            <VCol
              cols="12"
              sm="12"
              md="6"
            >
              <VBtn
                block
                @click="changeResRateDays"
              >
                {{ $t("Change Res rate days") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn @click="CheckoutDialog = false, ledger_checkout_button = false, cash_checkout_button = false, closeCashierPayment,otherDialog=false">
            {{ $t("Close") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="SubmitWindowsDialog"
      width="1300"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="SubmitWindowsDialog = false, cash_checkout_button = false, ledger_checkout_button = false, closeCashierPayment"
    >
      <VCard>
        <VCardTitle>
          Folio windows
        </VCardTitle>
        <VCardText>
          <VTable height="200">
            <thead>
            <tr>
              <!--
                <th>
                {{ $t("Select") }}
                </th>
              -->
              <th>
                {{ $t("Window") }}
              </th>
              <th>
                {{ $t("Balance") }}
              </th>
              <th>
                {{ $t("Pay") }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr
              v-for="(item, index) in filterWindows"
              :key="index"
            >
              <!--
                <td>
                <VCheckbox v-model="selected_windows[index]" :true-value="item" false-value="0" />
                </td>
              -->
              <td>{{ item.window_name }}</td>
              <td>{{ item.transactions_sum_charge_amount - item.transactions_sum_payment_amount }}</td>
              <td v-if="item.transactions_sum_charge_amount - item.transactions_sum_payment_amount != 0">
                <VBtn @click="payPayments(index)">
                  <VIcon icon="tabler-checkbox" />
                </VBtn>
              </td>
              <td v-else>
                {{ $t('Closed') }}
              </td>
            </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn @click="SubmitWindowsDialog = false, cash_checkout_button = false, ledger_checkout_button = false, closeCashierPayment">
            {{ $t("Close") }}
          </VBtn>
          <VBtn
            v-if="cash_checkout_button"
            @click="SubmitAllClosedWindows"
          >
            {{ $t("Submit") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="reservation_days_dialog"
      width="1024"
      z-index="1000"

      @click:outside="clearChangeRate"
      scroll-strategy="none"

    >
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
              <VBtn @click="ChangeRateDialog ? fill_res_days(null, null) : fill_res_days(Folio.FolioState[0].rate_code, Folio.FolioState[0].rm_rate)">
                {{ $t("Fill from entered data") }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardTitle>
        <VCardText>
          <VRow
            v-for="index in Folio.FolioState[0].no_of_nights"
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
                :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'
                "
                item-value="rate_code"
                item-color="customColorValue"
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
            {{ $t("Clear") }}
          </VBtn>
          <VBtn @click="reservation_days_dialog = false, clearChangeRate()">
            {{ $t("Close") }}
          </VBtn>
          <VBtn @click="ChangeRateDialog ? save_res_days() : submitChangeRateHome()">
            {{ $t("Save") }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="room_dialog"
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
              md="12"
            >
              <span> {{ $t('select Room') }}</span>
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VCombobox
                v-model="changed_room_type"
                :label="$t('Changed Room type')"
                :items="available_roomtypes"
                :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                item-color="customColorValue"
              />
            </VCol>
            <!--
              <VCol cols="12" sm="6" md="9">
              <VTextField v-model="search" type="text" :label="$t('Search')" style="width:50%;"
              class="float-end " clearable />
              </VCol>
            -->
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
            <tr
              v-for="item in filterData"
              :key="item.id"
            >
              <td>{{ item.id }}</td>
              <td>
                <p v-if="$i18n.locale === 'en'">
                  {{ item.rm_name_en ? item.rm_name_en : '' }}
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
            @click="room_dialog = false"
          >
            {{ $t('Close') }}
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog
      v-model="transactionTransferDialog"
      width="1024"
      scroll-strategy="none"
      z-index="1000"
      @click:outside="transactionTransferDialog = false, selected_room_routing = null, selected_window = null, transfer_reason = null"
    >
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Transfer Transaction</span>
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
                  v-model="current_window"
                  label="Current window"
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
                  label="departure date"
                  readonly
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="3"
              >
                <VTextField
                  v-model="current_room"
                  label="Current Room"
                  readonly
                />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol
                cols="12"
                sm="6"
                md="6"
              >
                <VCombobox
                  v-model="selected_room_routing"
                  :label="$t('Selected Room*')"
                  :items="oc_rooms"
                  :item-title="$i18n.locale === 'en' ? 'room_name_en' : 'room_name_loc'"
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
                  v-model="selected_window"
                  :label="$t('Selected Window*')"
                  :items="room_windows"
                  :item-title="$i18n.locale === 'en' ? 'window_name' : 'window_name'"
                  clearable
                  item-color="customColorValue"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
                md="12"
              >
                <VTextField
                  v-model="transfer_reason"
                  :label="$t('Reason')"
                  clearable
                />
              </VCol>
            </VRow>
          </VContainer>
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
                    Created by
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
            @click="transactionTransferDialog = false, selected_room_routing = null, selected_window = null, transfer_reason = null"
          >
            Close
          </VBtn>
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="submitTransferTransaction"
          >
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script>
import axios from "@axios"
import moment from 'moment'
import { mapActions, mapStores } from 'pinia'
import Swal from 'sweetalert2'
import { useExtrasStore } from "../../stores/ExtrasStore"
import { useGeneralStore } from '../../stores/GeneralStore'
import { useGuestStore } from "../../stores/GuestStore"
import { useLedgerStore } from '../../stores/LedgerStore'
import { useRatecodeStore } from '../../stores/RatecodeStore'
import { useRoomStore } from '../../stores/RoomStore'

export default {
  name: "Folio",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      total_with_taxes:[],
      scroll_hight:0,
      FolioStateData: [],
      SumTaxes: 0,
      currentTab: null,
      SettingData: null,
      extend_id: '',
      current_window: null,
      transactionTransferDialog: false,
      transfer_reason: null,
      window_routed_to: [],
      guest_routed_to: [],
      is_void: false,
      transaction_id: null,
      day: [],
      formValues: [],
      ratecode: [],
      roomrate: [],
      reservation_days_dialog: false,
      res_rate_days_changed: false,
      payment_type_for_checkout: null,
      selected_windows: [],
      SubmitWindowsDialog: false,
      cash_checkout_button: false,
      ledger_checkout_button: false,
      CheckoutDialog: false,
      CashierDialog: false,
      otherDialog: false,
      extra_dialog: false,
      extra_amount: null,
      selected_extra: null,
      room_windows: [],
      final_ledgers: [],
      selected_ledger: [],
      rate_change_id: '',
      selected_room_routing: null,
      selected_window: null,
      avaliable_rooms: [],
      ledger_categ: [],
      disableRoutingCharge: true,
      RoutingChargeDialog: false,
      changePropertiesDialog: false,
      market_segment: null,
      source_of_business: null,
      company: null,
      CreateWindowDialog: false,
      disableWindow: true,
      new_window_name: null,
      disableChangeRate: true,
      ChangeRateDialog: false,
      room_old_status_radio: 'Dirty',
      transactionDialog: false,

      currentItem: null,
      items: [
        {
          title: 'Extend stay',
          value: 'extendStay',
        },
        {
          title: 'Change Room',
          value: 'changeRoom',
        },
        {
          title: 'Change Rate',
          value: 'changeRate',
        },
        {
          title: 'New window',
          value: 'newWindow',
        },
        {
          title: 'Change Business and segment',
          value: 'changeprop',
        },
        {
          title: 'Change Extras',
          value: 'changeExtras',
        },

        {
          title: 'Change Res rate days',
          value: 'changeResRateDays',
        },
      ],

      search: null,
      Folio: [],
      trans_length: '',
      Total_payment_Amount: null,
      Total_Charge_Amount: null,
      room_no: null,
      guest_name: null,
      room_change_id: '',
      guest_id: null,
      group_name: null,
      date_star: null,
      date_leave: null,
      page_hight: 0,
      note: null,
      full_meal: null,
      Method_of_Payment: null,
      Reservation_number: null,
      net_rent: null,
      rent_code: null,
      URL: window.location.origin,
      count: null,
      extendstayDialog: false,
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
      created_by_name: null,
      Payments: [],
      Payments_Selected: null,
      no_of_nights: 1,
      currentDateTime: null,
      postingchargeDialog: false,
      ledgerCats: [],
      tax_included: false,
      amount: null,
      ledger_id: null,
      ledger_name: null,
      no_data: null,
      description: null,
      transaction_hotel_date: null,
      transaction_ledger_name: null,
      transaction_description: null,
      transaction_tax_included: null,
      disablePostingCharge: true,
      ChangeRoomDialog: false,
      current_room_type: null,
      current_room: null,
      current_no_of_pax: null,
      changed_room_type: null,
      changed_room: null,
      changed_no_of_pax: null,
      room_dialog: false,
      disableChangeRoom: true,
      reason: null,
      selected_rooms_from_room_type: [],
      current_rate_code: null,
      current_room_rate: null,
      current_meal: null,
      changed_rate_code: null,
      changed_room_rate: null,
      changed_meal: null,
      rate_reason: null,
      FolioTans: [],
      Foliowindow: [],
      FolioTransaction: [],
      AllChargAmount: [],
      localSetting: [],
      tot_sum_charge: 0,
      All_tot_sum_charge: 0,
      tot_sum_payment: 0,
      All_tot_sum_payment: 0,
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useRoomStore, useRatecodeStore, useGeneralStore, useLedgerStore, useExtrasStore, useGuestStore),
    filterPaymentTypeDR() {
      return this.Payments.filter(pay => {
        return pay.type == 'DR'
      })
    },
    filterPaymentTypeCR() {
      return this.Payments.filter(pay => {
        return pay.type == 'CR'
      })
    },
    filterData() {
      return this.changed_room_type.rooms
    },
    filterWindows() {
      return this.Folio.FolioState[0].window.filter(window => {
        return window.invoice_number == null

      })

    },
    sum() {



      this.tot_sum_charge = 0
      this.tot_sum_payment = 0

      for (let i = 0; i < this.FolioTans.length; i++) {

        this.Foliowindow = this.FolioTans[i].transactions

        this.Foliowindow.forEach((value, index) => { this.tot_sum_charge += value.charge_amount })
        this.Foliowindow.forEach((value, index) => { this.tot_sum_payment += value.payment_amount })



        for (let i = 0; i < this.Foliowindow.length; i++) {
          // copyItems.push(items[i]);
          this.FolioTransaction = this.Foliowindow[i]



        }
      }

    },
    oc_rooms() {
      return this.generalStore.oc_rooms
    },
    room_types() {
      return this.roomStore.room_types
    },
    rate_codes() {
      return this.ratecodeStore.rate_codes
    },
    companies() {
      return this.generalStore.companies
    },
    sources() {
      return this.generalStore.sources
    },
    segments() {
      return this.generalStore.segments
    },
    prefs() {
      var p = {
        folio_no: this.folio_no,
        departure_date: this.departure_date,
        extended_date: this.extended_date,
      }
      localStorage.setItem('myData', JSON.stringify(p))

      return p
    },
    ledgers() {
      return this.ledgerStore.ledgers
    },
    extras() {
      return this.extrasStore.extras
    },
    guest_extras() {
      return this.extrasStore.guest_extras
    },
    guest_charge_routings() {
      return this.generalStore.guest_charge_routings
    },
    available_roomtypes() {
      return this.guestStore.available_rooms
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    available_roomtypes() {
      if (this.available_roomtypes != null) {
        this.changed_room_type = this.available_roomtypes[0]
      }
    },
    selected_room_routing() {
      if (this.selected_room_routing == null) {
        this.selected_window = null
        this.room_windows = []
        this.selected_ledger = []
      }
      else {
        this.room_windows = this.selected_room_routing.guests.window
        this.selected_window = null
      }
    },
    extended_date() {
      if (this.no_of_nights != null) {
        var inn = moment(this.Folio.FolioState[0].out_date)
        var out = moment(this.extended_date)
        this.no_of_nights = out.diff(inn, 'days')
      }
    },
    no_of_nights() {
      if (this.no_of_nights != null) {
        var inn = moment(this.Folio.FolioState[0].out_date)
        inn.add(this.no_of_nights, 'days')
        this.extended_date = inn.format("YYYY-MM-DD")
      }
      if (this.no_of_nights == null) {
        this.no_of_nights = 1
      }
    },
    amount() {
      if (this.amount != null && this.ledger_id != null && this.ledger_name != null) {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    ledger_id() {
      if (this.amount != null && this.ledger_id != null && this.ledger_name != null) {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    ledger_name() {
      if (this.amount != null && this.ledger_id != null && this.ledger_name != null) {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    changed_room_type() {
      if (this.changed_room_type != null && this.changed_room != null && this.changed_no_of_pax != null && this.reason != null) {
        this.disableChangeRoom = false
      }
      else {

        this.disableChangeRoom = true
      }
    },
    changed_room() {
      if (this.changed_room != null && this.changed_room_type == null) {
        this.changed_room_type = this.changed_room.room_type
        this.changed_no_of_pax = this.changed_room_type.def_pax
      }
      if (this.changed_room != null && this.changed_room_type != null) {
        this.changed_room_type = this.changed_room.room_type
        this.changed_no_of_pax = this.changed_room_type.def_pax

      }
      if (this.changed_room_type != null && this.changed_room != null && this.changed_no_of_pax != null && this.reason != null) {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }

    },
    changed_no_of_pax() {
      if (this.changed_room_type != null && this.changed_room != null && this.changed_no_of_pax != null && this.reason != null) {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }
    },
    reason() {
      if (this.changed_room_type != null && this.changed_room != null && this.changed_no_of_pax != null && this.reason != null) {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }

    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    changed_rate_code() {
      if (this.changed_rate_code != null) {
        this.changed_meal = this.changed_rate_code.meal != null ? this.changed_rate_code.meal.name : this.changed_rate_code.meal_package != null ? this.changed_rate_code.meal_package.name : null
        this.rate_reason = this.changed_rate_code.name
        for (var i = 0, len = this.changed_rate_code.room_types.length; i < len; i++) {

          // eslint-disable-next-line sonarjs/no-collapsible-if
          if (this.changed_rate_code.room_types[i].id === this.Folio.FolioState[0].room_type_id) {

            if (this.Folio.FolioState[0].no_of_pax == 1) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax1
            }
            if (this.Folio.FolioState[0].no_of_pax == 2) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax2
            }
            if (this.Folio.FolioState[0].no_of_pax == 3) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax3
            }
            if (this.Folio.FolioState[0].no_of_pax == 4) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax4
            }
            if (this.Folio.FolioState[0].no_of_pax == 5) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax5
            }
            if (this.Folio.FolioState[0].no_of_pax == 6) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.pax6
            }
            if (this.Folio.FolioState[0].no_of_pax > 6) {
              this.changed_room_rate = this.changed_rate_code.room_types[i].pivot.extra_pax
            }

          }
        }
      }
      else {
        this.changed_meal = null
        this.changed_room_rate = null

      }
      if (this.changed_rate_code != null && this.changed_room_rate != null && this.rate_reason != null) {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    changed_room_rate() {
      if (this.changed_rate_code != null && this.changed_room_rate != null && this.rate_reason != null) {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    rate_reason() {
      if (this.changed_rate_code != null && this.changed_room_rate != null && this.rate_reason != null) {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    new_window_name() {
      if (this.new_window_name != null) {
        this.disableWindow = false
      } else {
        this.disableWindow = true
      }
    },
    Folio() {
    },

    selected_window() {
      if (this.selected_window != null) {
        this.disableRoutingCharge = false
      }
      else {
        this.disableRoutingCharge = true

      }
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getFolio()
    this.getledgerCats()
    this.getPayment()

    const storedData = localStorage.getItem('keyinfo')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.Settings
      this.localSetting = this.userData[0].hotel_date
    }


  },

  // eslint-disable-next-line vue/component-api-style
  methods: {

    ...mapActions(useGuestStore, [
      'getAvaliableRooms',
    ]),
    clearPostingCharge() {
      this.tax_included = null
      this.description = null
      this.amount = null
      this.ledger_name = null
      this.ledger_id = null
    },
    selectLedgerCheck(index) {
      if ((this.selected_ledger[index] != false) && (this.selected_room_routing == null || this.selected_window == null)) {
        this.selected_ledger[index] = false
        this.guest_routed_to[index] = null
        this.window_routed_to[index] = null
        this.alert('please select room and window', false)
      }
      else if (this.selected_ledger[index] == false) {
        this.guest_routed_to[index] = null
        this.window_routed_to[index] = null
      }
      else {
        this.guest_routed_to[index] = this.selected_room_routing
        this.window_routed_to[index] = this.selected_window
      }
    },
    submitRoutingCharge(ledgers) {
      let routing_to = []
      let selected_ledgers = []
      let changed_index = []
      for (let index = 0; index < ledgers.length; index++) {
        if (ledgers[index] != false && ledgers[index] != undefined) {
          selected_ledgers.push(ledgers[index])
          changed_index.push(index)
        }
      }
      for (let index = 0; index < selected_ledgers.length; index++) {
        routing_to.push({
          ledgerID: selected_ledgers[index],
          guestID_to: this.guest_routed_to[changed_index[index]].room_name_en != undefined ? this.guest_routed_to[[changed_index[index]]].guests.guest_id : this.guest_routed_to[[changed_index[index]]].id,
          window_id_to: this.window_routed_to[changed_index[index]].id,
        })
      }
      console.log('this.currentItem',this.currentItem)
      axios.post(`${this.URL}/api/storechargeRoutwithoutStatus`, {
        'guestID_from': this.Folio.FolioState[0].id,
        'window_id_from': this.currentItem,
        'routingTo': routing_to,
      }).then(res => {
        this.RoutingChargeDialog = false
        this.getFolio()
        this.guest_routed_to = []
        this.window_routed_to = []
        this.insertAlert('Routing charge has been recorded')
      })
    },
    selectallLedgers() {
      if ((this.selected_room_routing == null || this.selected_window == null)) {
        this.alert('please select room and window', false)
      }
      else {
        this.selected_ledger = this.ledgers.map(item => item.id)
      }
    },
    async chargeRouting() {
      this.RoutingChargeDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile?.first_name + ' ' + this.Folio.FolioState[0].profile?.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.current_room = this.Folio.FolioState[0].room.rm_name_en
      this.getLedgers()
      this.getOCRoomsAction()
      await this.getGuestChargeRoutingAction(this.Folio.FolioState[0].id)
      if (this.guest_charge_routings.length != 0) {
        for (let index = 0; index < this.guest_charge_routings.length; index++) {
          for (let i = 0; i < this.ledgers.length; i++) {
            if (this.guest_charge_routings[index].ledgerID.id == this.ledgers[i].id) {
              this.selected_ledger[i] = this.ledgers[i].id
              this.guest_routed_to[i] = this.guest_charge_routings[index].guestID_to
              this.window_routed_to[i] = this.guest_charge_routings[index].windowTo
            }
          }
        }
      }

    },
    async voidTransaction() {
      if (this.is_void) {
        this.DangerAlert('This transaction is already voided')
      }
      else {
        Swal.fire({
          icon: 'warning',
          title: 'Are you sure you want to void this transaction?',
          showDenyButton: true,
          showCancelButton: true,
          showConfirmButton: false,
          denyButtonText: `Void`,
        }).then(result => {
          if (result.isDenied) {
            axios.delete(`${this.URL}/api/transactions/${this.transaction_id}`).then(res => {
              this.getFolio()
              this.transaction_id = null
              this.DangerAlert('Transaction has been voided', true)
            })
          }
          if (result.isDismissed) {
          }
        })
      }
    },
    transferTransaction() {
      this.currentDateTime = new Date().toLocaleString()
      this.transactionTransferDialog = true
      this.current_room = this.Folio.FolioState[0].room.rm_name_en
      this.current_window = this.currentItem.window_name
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.getOCRoomsAction()
    },
    async submitTransferTransaction() {
      axios.post(`${this.URL}/api/transfer-transaction`, {
        'trans_id': this.transaction_id,
        'trf_from_guest_id': this.Folio.FolioState[0].id,
        'trf_to_guest_id': this.selected_room_routing.guests.guest_id,
        'trf_from_window_id': this.currentItem.id,
        'trf_to_window_id': this.selected_window.id,
        'reason': this.transfer_reason,
      }).then(res => {
        this.getFolio()
        this.transactionDialog = false
        this.transactionTransferDialog = false
        this.selected_room_routing = null
        this.selected_window = null
        this.transfer_reason = null
        this.insertAlert('Transaction has been transfered successfully')
      }).catch(err => {
        console.log(err)
      })
    },
    calculateTotalChargeAmount(transactions) {

      return transactions.reduce((total, trans) => total + trans.charge_amount, 0)
    },
    calculateTotalPayAmount(transactions) {
      return transactions.reduce((total, trans) => total + trans.payment_amount, 0)
    },
    calculateTotaltaxes(transactions) {
      // for(let i=0;i<=transactions.length-1;i++){
      //   return transactions.reduce((total, trans) =>  total + trans.taxes[0].pivot.amount, 0)
      // }
      // console.log(transactions.forEach((tot,some)=>console.log('tot',tot)))
      // console.log('tra',transactions[0].taxes[0].pivot.amount)

      // return transactions.reduce((total, trans) => total + trans.taxes[0].pivot.amount, 0)
      // return transactions.reduce((total, trans) => console.log(trans.taxes[0].pivot.amount))
      // transactions.map((ele)=>console.log(ele.taxes[0]))
      // .forEach((value, index) =>  { this.SumTaxes += value.taxes[0].pivot.amount })
    },
    ...mapActions(useRoomStore, ['getRoomTypesAction', 'getRoomsFilterAction', 'getRoomsAction']),
    ...mapActions(useLedgerStore, ['getLedgers']),
    ...mapActions(useRatecodeStore, ['getRateCodesAction']),
    ...mapActions(useGeneralStore, ['getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getOCRoomsAction', 'getGuestChargeRoutingAction']),
    ...mapActions(useExtrasStore, ['getExtras', 'getExtraByGuestId', 'addGuestExtra', 'deleteGuestExtra']),
    cashierPaymentCheckout(amount, payment, type, window) {
      this.PaymentDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.room_no = this.Folio.FolioState[0].room.rm_name_en
      this.folio_no = this.Folio.FolioState[0].folio_no
      this.guest_id = this.Folio.FolioState[0].id
      this.window_number = window.window_name
      this.window_id = window.id
      this.Descriptions = payment[0]?.name
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.no_of_nights = 1
      var inn = moment(this.Folio.FolioState[0].out_date)
      inn.add(this.no_of_nights, 'days')
      this.extended_date = inn.format("YYYY-MM-DD")
      this.Payments_Selected = payment[0]
      this.Amount = amount
      this.payment_type_for_checkout = type
    },
    closeCashierPayment() {
      this.PaymentDialog = false
      this.currentDateTime = null
      this.room_no = null
      this.folio_no = null
      this.guest_id = null
      this.window_number = null
      this.window_id = null
      this.departure_date = null
      this.guest_name = null
      this.created_by_name = null
      this.Payments_Selected = null
      this.Amount = null
      this.payment_type_for_checkout = null
      this.Descriptions = null
      this.no_data = null
      this.cash_checkout_button = false
    },
    payPayments(index) {
      if (this.cash_checkout_button == true) {

        if (this.filterWindows[index].transactions_sum_charge_amount - this.filterWindows[index].transactions_sum_payment_amount < 0) {
          this.cashierPaymentCheckout((this.filterWindows[index].transactions_sum_charge_amount - this.filterWindows[index].transactions_sum_payment_amount) * -1, this.filterPaymentTypeDR, 'DR', this.filterWindows[index])
        }
        if (this.filterWindows[index].transactions_sum_charge_amount - this.filterWindows[index].transactions_sum_payment_amount > 0) {
          this.cashierPaymentCheckout(this.filterWindows[index].transactions_sum_charge_amount - this.filterWindows[index].transactions_sum_payment_amount, this.filterPaymentTypeCR, 'CR', this.filterWindows[index])
        }
      }
      else {
        axios.post(`${this.URL}/api/cityLedgerCheckout`, {
          'guest_id': this.Folio.FolioState[0].id,
          'window_id': [this.filterWindows[index].id],
        }).then(res => {
          if (res.data.data == 'Limt Not Enough') {
            this.DangerAlert('Limit not enough')
          }
          else {
            this.getFolio()
            this.insertAlert('City ledger checkout Done')
          }

        }).catch(err => {
          console.log(err)
        })

      }

    },
    SubmitAllClosedWindows() {
      let window_ids = []
      for (let i = 0; i < this.filterWindows.length; i++) {
        if (this.filterWindows[i].transactions_sum_charge_amount - this.filterWindows[i].transactions_sum_payment_amount == 0) {
          window_ids.push(this.filterWindows[i].id)
        }
      }
      if (window_ids.length > 0) {
        axios.post(`${this.URL}/api/checkOut`, {
          'guest_id': this.Folio.FolioState[0].id,
          'window_id': window_ids,
        }).then(res => {
          this.getFolio()
          this.cash_checkout_button = false
          this.insertAlert('Cash checkout Done')
        }).catch(err => {
          console.log(err)
          this.cash_checkout_button = false
        })
      }
      else {
        this.DangerAlert('No closed windows found')
      }
    },
    cashCheckout() {

      if (this.filterWindows.length > 1) {
        this.SubmitWindowsDialog = true
        this.cash_checkout_button = true
      }
      if (this.filterWindows.length == 1) {
        this.cash_checkout_button = true
        if (this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount < 0) {
          this.cashierPaymentCheckout((this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount) * -1, this.filterPaymentTypeDR, 'DR', this.filterWindows[0])
        }
        if (this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount > 0) {
          this.cashierPaymentCheckout(this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount, this.filterPaymentTypeCR, 'CR', this.filterWindows[0])
        }
        if (this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount == 0) {
          axios.post(`${this.URL}/api/checkOut`, {
            'guest_id': this.Folio.FolioState[0].id,
            'window_id': [this.filterWindows[0].id],
          }).then(res => {
            this.getFolio()
            this.cash_checkout_button = false
            this.insertAlert('Cash checkout Done')
          }).catch(err => {
            console.log(err)
            this.cash_checkout_button = false
          })
        }
      }
      if (this.filterWindows.length == 0) {
        this.DangerAlert('All windows are paid')
      }
    },
    cityLedgerCheckout() {
      if (this.filterWindows.length > 1) {
        this.SubmitWindowsDialog = true
        this.ledger_checkout_button = true
      }
      if (this.filterWindows.length == 1) {
        this.ledger_checkout_button = true
        if (this.filterWindows[0].transactions_sum_charge_amount - this.filterWindows[0].transactions_sum_payment_amount != 0) {
          axios.post(`${this.URL}/api/cityLedgerCheckout`, {
            'guest_id': this.Folio.FolioState[0].id,
            'window_id': [this.filterWindows[0].id],
          }).then(res => {
            this.getFolio()
            this.ledger_checkout_button = false
            this.insertAlert('City ledger checkout Done')
          }).catch(err => {
            console.log(err)
            this.ledger_checkout_button = false
          })
        }
      }
      if (this.filterWindows.length == 0) {
        this.DangerAlert('All windows are paid')
      }
    },
    changeExtras() {
      this.extra_dialog = true
    },
    addExtraForGuest() {
      this.addGuestExtra({
        data: [{
          guest_id: this.$route.params.folio,
          extra_id: this.selected_extra.id,
          amount: this.extra_amount,
        }],
      })
      this.selected_extra = null
      this.extra_amount = null
    },
    deleteExtraGuest(item) {
      this.deleteGuestExtra(item, this.$route.params.folio)
    },
    // submitRoutingCharge(ledgers) {
    //
    //   for (let index = 0; index < ledgers.length; index++) {
    //     if (ledgers[index] != false && ledgers[index] !== undefined)
    //       this.final_ledgers.push(ledgers[index])
    //   }
    //   if (this.final_ledgers.length === 0) {
    //     Swal.fire({
    //       position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
    //       icon: 'warning',
    //       title: 'Please select a ledger',
    //       showConfirmButton: false,
    //       timer: 1500,
    //       toast: true,
    //     })
    //   }
    //   else {
    //     axios.post(`${this.URL}/api/chargeRout`, {
    //       'guestID_from': this.Folio.FolioState[0].id,
    //       'guestID_to': this.selected_room_routing.guests.guest_id,
    //       'window_id_from': this.currentItem,
    //       'window_id_to': this.selected_window.id,
    //       'ledgerID': this.final_ledgers,
    //     }, {
    //       headers: {
    //         Accept: 'application/json',
    //         Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
    //       },
    //
    //     }).then(res => {
    //       this.RoutingChargeDialog = false
    //       this.getFolio()
    //       this.insertAlert('Routing charge has been recorded')
    //     })
    //     this.final_ledgers = []
    //   }
    //
    // },
    // selectallLedgers() {
    //   this.selected_ledger = this.ledgers.map(item => item.id)
    // },
    // chargeRouting() {
    //   this.RoutingChargeDialog = true
    //   this.currentDateTime = new Date().toLocaleString()
    //   this.arrival_date = this.Folio.FolioState[0].in_date
    //   this.departure_date = this.Folio.FolioState[0].out_date
    //   this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
    //   this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
    //   this.current_room = this.Folio.FolioState[0].room.rm_name
    //   this.getLedgers()
    //   this.getOCRoomsAction()
    // },
    changeprop() {

      this.getCompaniesAction()
      this.getBusinessesAction()
      this.getSegmentsAction()

      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.changePropertiesDialog = true
      this.market_segment = this.Folio.FolioState[0].market_segment
      this.source_of_business = this.Folio.FolioState[0].source_business
      this.company = this.Folio.FolioState[0].company
    },
    submitProperties() {
      axios.put(`${this.URL}/api/reservation/${this.Folio.FolioState[0].id}`, {
        'company_id': this.company.id,
        'source_of_business_id': this.source_of_business.id,
        'market_segment_id': this.market_segment.id,
      }, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },

      }).then(res => {
        this.changePropertiesDialog = false
        this.getFolio()
        this.insertAlert('guest properties has been Changed')
      }).catch(err => {
        console.log(err)
      })
    },
    selectTransaction(transaction) {
      console.log('trans', transaction)
      this.transaction_id = transaction.id
      this.is_void = transaction.is_void
      this.transaction_ledger_name = transaction.ledger != null ? transaction.ledger.name : transaction.payment_type.name
      this.transaction_description = transaction.description
      this.transaction_charge_amount = transaction.ledger != null ? transaction.charge_amount : transaction.payment_amount
      this.transaction_charge_without_taxes = transaction.ledger != null ? transaction.charge_without_taxes : transaction.payment_amount
      this.transaction_hotel_date = transaction.hotel_date
      this.transaction_tax_included = transaction.inc == 0 ? false : true
    },
    getledgerCats() {
      axios.get(`${this.URL}/api/ledger-cats`, {
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


    async getFolio() {

      let windowNow=  this.currentItem


      await  axios.post(`${this.URL}/api/folio_statement`, {
        id: this.$route.params.folio,
      }).then(res => (this.Folio = res.data.data, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount,
            this.FolioStateData = res.data.data.FolioState[0], windowNow===null?.res.data.data.FolioState[0].window[0].id




        ),
      )
      if(windowNow == null ){
        windowNow = this.FolioStateData.window[0].id
      }
      this.currentItem = windowNow


      if(windowNow == null ){
        windowNow = this.FolioStateData.window[0].id
      }
      this.currentItem = windowNow

      this.scroll_hight = document.getElementById('btn_hight').scrollHeight
      console.log(this.scroll_hight)
      if (this.scroll_hight){
        window.scrollTo({ top: this.scroll_hight+1200, behavior: "smooth" })

      }

    },

    getPayment() {
      axios.get(`${this.URL}/api/payment`).then(res => (this.Payments = res.data),
      )

    },
    callMethod(methodName) {
      this[methodName]()
    },
    extendStay() {
      this.extendstayDialog = true
      this.room_no = this.Folio.FolioState[0].room.rm_name_en
      this.folio_no = this.Folio.FolioState[0].folio_no
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.no_of_nights = 1
      var inn = moment(this.Folio.FolioState[0].out_date)
      inn.add(this.no_of_nights, 'days')
      this.extended_date = inn.format("YYYY-MM-DD")

    },
    async changeRoom() {
      const response = await this.getAvaliableRooms(this.SettingData.hotel_date, this.Folio.FolioState[0].out_date)
      if (response.status != undefined) {
        this.ChangeRoomDialog = true
        this.currentDateTime = new Date().toLocaleString()
        this.arrival_date = this.Folio.FolioState[0].in_date
        this.departure_date = this.Folio.FolioState[0].out_date
        this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name

        // this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
        this.current_room = this.Folio.FolioState[0].room.rm_name_en
        this.current_room_type = this.Folio.FolioState[0].room.room_type.rm_type_name
        this.current_no_of_pax = this.Folio.FolioState[0].no_of_pax
      }


      /* this.getRoomTypesAction()
      this.getRoomsFilterAction()
      this.getRoomsAction() */



    },
    CaherPayment() {

      this.PaymentDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.room_no = this.Folio.FolioState[0].room?.rm_name_en
      this.folio_no = this.Folio.FolioState[0].folio_no
      this.guest_id = this.Folio.FolioState[0].id
      this.window_number = this.Folio.FolioState[0].window[0].window_name
      this.window_id = this.currentItem.id
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.no_of_nights = 1
      var inn = moment(this.Folio.FolioState[0].out_date)
      inn.add(this.no_of_nights, 'days')
      this.extended_date = inn.format("YYYY-MM-DD")

    },
    submitExtendStay() {
      // eslint-disable-next-line promise/valid-params
      axios.post(`${this.URL}/api/extend-stay`, {
        'guest_id': this.Folio.FolioState[0].id,
        'out_date_from': this.Folio.FolioState[0].out_date,
        'out_date_to': this.extended_date,
      }).then(res => {
        this.extend_id = res.data.data.id
        this.extendstayDialog = false

        this.getFolio()

        const routeData = {
          name: 'selectedFolio-extend-data',
          params: { data: this.extend_id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')

        // this.$router.push({ name: `selectedFolio-extend-data` , params: { data:this.extend_id } })
        this.insertAlert('Departure date has been extended')
      })
    },
    submitPayment() {
      // eslint-disable-next-line promise/valid-params
      axios.post(`${this.URL}/api/transactions`, {
        'guest_id': this.guest_id,
        'room_id': this.Folio.FolioState[0].room.id,
        'ref_id': this.no_data,
        'description': this.Descriptions,
        'inc': 0,
        'hotel_date': this.localSetting,
        'trans_type': 'P',
        'recd_type': 'REC',
        'res_id': 0,
        'is_reservation': 0,
        'payment_amount': this.payment_type_for_checkout == 'CR' || this.payment_type_for_checkout == null ? this.Amount : this.Amount * -1,
        'window_id': this.currentItem,
        'payment_type_id': this.Payments_Selected.id,
      }).then(res => {


        if (this.cash_checkout_button == true) {
          axios.post(`${this.URL}/api/checkOut`, {
            'guest_id': this.Folio.FolioState[0].id,
            'window_id': [this.window_id],
          }).then(res => {
            this.getFolio()
            this.closeCashierPayment()
          }).catch(err => {
            console.log(err)
          })
        }
        else if (this.ledger_checkout_button == true) {
          axios.post(`${this.URL}/api/cityLedgerCheckout`, {
            'guest_id': this.Folio.FolioState[0].id,
            'window_id': [this.window_id],
          }).then(res => {
            this.getFolio()
            this.closeCashierPayment()
          }).catch(err => {
            console.log(err)
          })
        }
        else {
          this.closeCashierPayment()
          this.getFolio()
        }

        this.insertAlert('Cashier Payment has been recorded')

        const routeData = {
          name: 'voucher-vprint-data',
          params: { data: res.data.transaction.id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')

      })




    },
    postingCharges() {
      this.postingchargeDialog = true
      this.room_no = this.Folio.FolioState[0].room.rm_name_en
      this.folio_no = this.Folio.FolioState[0].folio_no
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
    },
    ledgerSelected(ledger) {
      this.ledger_id = ledger.id
      this.ledger_name = ledger.name
      this.description = ledger.name
      this.ledger_categ = ledger
    },



    amount_key() {

      if (this.tax_included == false) {

        this.total_with_taxes = {
          total: 0,
          total_tax_amount: 0,
          total_with_tax: 0,
          inctaxes: [],
          total_inc: 0,
          exctaxes: [],
          total_exc: 0
        };

        let total = this.amount;
        total = parseFloat(total);
        let total_tax = 0;
        let whole_tax = 0;
        let inctaxes_array = [];
        let exctaxes_array = [];

        this.ledger_categ.inctaxes.forEach(inctax => {
          if (inctax.per !== null) {
            let tax = total * (inctax.per / 100);

            total_tax += tax;
            let inctax_array = {
              id: inctax.id,
              name: inctax.name,
              name_loc: inctax.name_loc,
              value: tax,
              type: 'percentage'
            };
            inctaxes_array.push(inctax_array);
          } else {
            total_tax += inctax.amount;
            let inctax_array = {
              id: inctax.id,
              name: inctax.name,
              name_loc: inctax.name_loc,
              value: inctax.amount,
              type: 'amount'
            };
            inctaxes_array.push(inctax_array);
          }
        });
        total += total_tax;
        whole_tax = total_tax;

        this.total_with_taxes.total_inc = total_tax;
        this.total_with_taxes.inctaxes = inctaxes_array;
        total_tax = 0;

        this.ledger_categ.taxes.forEach(exctax => {
          if (exctax.per !== null) {
            let tax = total * (exctax.per / 100);

            total_tax += tax;
            let exctax_array = {
              id: exctax.id,
              name: exctax.name,
              name_loc: exctax.name_loc,
              value: tax,
              type: 'percentage'
            };
            exctaxes_array.push(exctax_array);
          } else {
            total_tax += exctax.amount;
            let exctax_array = {
              id: exctax.id,
              name: exctax.name,
              name_loc: exctax.name_loc,
              value: exctax.amount,
              type: 'amount'
            };
            exctaxes_array.push(exctax_array);
          }
        });

        total += total_tax;
        whole_tax +=   parseFloat(total_tax);

        this.total_with_taxes.total_tax_amount = whole_tax;
        this.total_with_taxes.total = total;
        this.total_with_taxes.total_exc = total_tax;
        this.total_with_taxes.exctaxes = exctaxes_array;
      } else {
        this.total_with_taxes = {
          total: this.amount,
          total_with_tax: 0,
          inctaxes: [],
          exctaxes: []
        };

        let total = this.amount;
        let total_tax = 0;
        let whole_tax = 0;
        let total_per_inc = 0;
        let total_per_exc = 0;
        let total_without_inc = 0;
        let total_without_exc = 0;
        let inctaxes_array = [];
        let exctaxes_array = [];

        this.ledger_categ.taxes.forEach(exctax => {
          if (exctax.per !== null) {
            total_per_exc += exctax.per;
          }
        });

        this.ledger_categ.inctaxes.forEach(inctax => {
          if (inctax.per !== null) {
            total_per_inc += inctax.per;
          }
        });

        total_without_exc = this.amount / ((total_per_exc / 100) + 1);
        total_without_inc = total_without_exc / ((total_per_inc / 100) + 1);

        this.ledger_categ.inctaxes.forEach(inctax => {
          if (inctax.per !== null) {
            let tax = total_without_inc * (inctax.per / 100);
            total_tax += tax;
            let inctax_array = {
              id: inctax.id,
              name: inctax.name,
              name_loc: inctax.name_loc,
              value: tax,
              type: 'percentage'
            };
            inctaxes_array.push(inctax_array);
          }
        });

        this.ledger_categ.taxes.forEach(exctax => {
          if (exctax.per !== null) {
            let tax = total_without_exc * (exctax.per / 100);
            total_tax += tax;
            let exctax_array = {
              id: exctax.id,
              name: exctax.name,
              name_loc: exctax.name_loc,
              value: tax,
              type: 'percentage'
            };
            exctaxes_array.push(exctax_array);
          }
        });

        this.total_with_taxes.total_with_tax = total_without_inc;
        this.total_with_taxes.inctaxes = inctaxes_array;
        this.total_with_taxes.exctaxes = exctaxes_array;
      }

      console.log('Total with taxes:', this.total_with_taxes);


    },


    async submitPostingCharge() {


      await axios.post(`${this.URL}/api/transactions`, {
        'guest_id': this.Folio.FolioState[0].id,
        'room_id': this.Folio.FolioState[0].room_id,
        'hotel_date': this.localSetting,
        'window_id': this.currentItem,
        'ledger_id': this.ledger_id,
        'charge_amount': this.amount,
        'trans_type': 'C',
        'recd_type': 'CHR',
        'description': this.description,
        'is_reservation': this.Folio.FolioState[0].is_reservation,
        // eslint-disable-next-line sonarjs/no-redundant-boolean
        'inc': this.tax_included == true ? 1 : 0,
      }).then(res => {

        this.closePostingChargeDialog()
        this.getFolio()


      })

    },
    closePostingChargeDialog() {
      this.postingchargeDialog = false
      this.amount = null
      this.ledger_id = null
      this.ledger_name = null
      this.description = null
      this.tax_included = null
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
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    selectroom(room) {
      this.room_dialog = false
      this.changed_room = room
    },
    submitChangeRoom() {
      axios.post(`${this.URL}/api/roomChange`, {
        'guest_id': this.Folio.FolioState[0].id,
        'from_room_number': this.Folio.FolioState[0].room_id,
        'to_room_number': this.changed_room.id,
        'reason': this.reason,
      }, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },

      }).then(res => {
        this.room_change_id = res.data.data[0].id

        const routeData = {
          name: 'selectedFolio-room-data',
          params: { data: this.room_change_id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')

        // this.$router.push({ name: `selectedFolio-room-data` , params: { data:this.room_change_id } })
        this.clearChangeRoom()
        this.ChangeRoomDialog = false
        this.getFolio()
        this.insertAlert('Room has been Changed')
      }).catch(err => {
        console.log(err)
      })
    },
    clearChangeRoom() {
      this.changed_no_of_pax = null
      this.changed_room_type = null
      this.changed_room = null
      this.reason = null
      this.changed_room_type = null
    },
    changeRate() {
      this.ChangeRateDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.room_type = this.Folio.FolioState[0].room?.room_type.rm_type_name
      this.no_of_pax = this.Folio.FolioState[0].no_of_pax
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name

      this.current_rate_code = this.Folio.FolioState[0].rate_code?.name
      this.current_room_rate = this.Folio.FolioState[0].rm_rate
      this.current_meal = this.Folio.FolioState[0].rate_code ? (this.Folio.FolioState[0].meal?.name || this.Folio.FolioState[0].meal_package?.name) : null
    },
    clearChangeRate() {
      this.changed_meal = null
      this.changed_rate_code = null
      this.changed_room_rate = null
      this.rate_reason = null
      this.clear_res_days()
    },
    async submitChangeRateHome() {
      this.save_res_days()
      await axios.post(`${this.URL}/api/updateRateDays`, {
        'guest_id': this.Folio.FolioState[0].id,
        'res_rate_days': this.formValues,
      }).then(res => {
        this.ChangeRateDialog = false
        this.formValues = []
        this.clear_res_days()
        this.insertAlert('Res rate days has been Changed')
        this.getFolio()
      }).catch(err => {
        console.log(err)
      })
    },
    async submitChangeRate() {
      if (this.formValues.length == 0) {
        this.fill_res_days(null, null)
        this.save_res_days()
      }
      await axios.post(`${this.URL}/api/ratechange`, {
        'guest_id': this.Folio.FolioState[0].id,
        'raeson': this.rate_reason,
        'from_rate_code_id': this.Folio.FolioState[0].rate_code?.id,
        'to_rate_code_id': this.changed_rate_code?.id,
        'from_rm_rate': this.Folio.FolioState[0].rm_rate,
        'to_rm_rate': this.changed_room_rate,
        'meal_id': this.changed_rate_code.meal?.id,
        'meal_package_id': this.changed_rate_code.meal_package?.id,
        'hotel_date': new Date().toISOString().slice(0, 10),
        'room_id': this.Folio.FolioState[0].room_id,
        'res_rate_days': this.formValues,
      }).then(res => {
        this.rate_change_id = res.data.data.id
        this.clearChangeRate()
        this.ChangeRateDialog = false
        this.formValues = []
        this.clear_res_days()
        this.insertAlert('Rate has been Changed')
        this.getFolio()

        const routeData = {
          name: 'selectedFolio-rate-data',
          params: { data: this.rate_change_id },
        }

        const routeURL = this.$router.resolve(routeData).href

        window.open(routeURL, '_blank')

      }).catch(err => {
        console.log(err)
      })
    },
    changeResRateDays() {
      this.reservation_days_dialog = true

      ///make condition if there is res_rate_days already with folio
      if (this.ChangeRateDialog) {
        this.fill_res_days(null, null)
      }
      else {
        this.fill_res_days(null, 1)
      }
    },
    clear_res_days() {
      this.ratecode = []
      this.roomrate = []
    },
    fill_res_days(ratecode, roomrate) {
      if (roomrate != null) {
        for (let i = 0; i <= this.Folio.FolioState[0].res_rate_day.length - 1; i++) {
          this.ratecode[i + 1] = this.Folio.FolioState[0].res_rate_day[i].rate_code
          this.roomrate[i + 1] = this.Folio.FolioState[0].res_rate_day[i].rm_rate
        }
      } else {
        for (let i = 1; i <= this.Folio.FolioState[0].no_of_nights; i++) {
          this.ratecode[i] = this.changed_rate_code
          this.roomrate[i] = this.changed_room_rate
        }
      }
    },
    fill_rest_res_days(index) {
      for (let i = index; i <= this.Folio.FolioState[0].no_of_nights; i++) {
        this.ratecode[i] = this.ratecode[index]
        this.roomrate[i] = this.roomrate[index]
      }
    },
    getStartDate(index) {
      const startDate = new Date(this.Folio.FolioState[0].in_date)

      startDate.setDate(startDate.getDate() + index - 1)

      return startDate.toISOString().substr(0, 10)
    },
    save_res_days() {
      let empty_flag = 1
      this.formValues = [] // clear the array before adding new values
      for (let i = 1; i <= this.Folio.FolioState[0].no_of_nights; i++) {
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

        this.formValues.push(formValue) // add the form values to the array
      }
      this.res_rate_days_changed = true
      this.reservation_days_dialog = false
    },
    submitWindow() {
      axios.post(`${this.URL}/api/window`, {
        'guest_id': this.Folio.FolioState[0].id,
        'window_name': this.new_window_name,
      }, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },

      }).then(res => {
        this.CreateWindowDialog = false
        this.getFolio()
        this.insertAlert('New window has been Created')
        this.new_window_name = ''
      }).catch(err => {
        console.log(err)
      })
    },
    newWindow() {
      this.CreateWindowDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
    },
    openPageInNewTab({ commit }) {


      const newTab = window.open(null, '_blank')
      if (newTab) {
        newTab.location.href = '/invoice/' + this.Folio.FolioState[0].id +'-'+this.currentItem

        this.$router.push({ name: `invoice-invoice`, params: { invoice: this.Folio.FolioState[0].id } })
      }
    },
    alert(message, type) {
      const Toast = Swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: type ? 'success' : 'error',
        title: message,
      })
    },

    // async invoice(Getdata){
    //
    //   await this.$router.push({ name: `voucher-data` ,params: { guest: this.Folio.FolioState[0].id } })
    //
    // }

  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.getExtras()
    this.getExtraByGuestId(this.$route.params.folio)
    this.getRateCodesAction()
  },


}
</script>

<style>
.v-textarea .v-field__field {
  --v-input-control-height: var(--v-textarea-control-height);

  block-size: 60px;
}

.z-10 {
  z-index: 10003;
}

.Date_inputs {
  display: inline-flex;
}
/* width */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: mediumpurple;
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, .6);  ;
}
.dialog-bottom-transition-enter-active,
.dialog-bottom-transition-leave-active {
  transition: transform .2s ease-in-out;
}
</style>
