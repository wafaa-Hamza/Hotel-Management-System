<script setup>
const currentTab = ref('Appetizers')
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VContainer v-for="item in Folio.FolioState">
      <VCardTitle class="text-center my-1">
        <span class="text-h5">Folio Num : <b>{{ item.folio_no }}</b></span>
      </VCardTitle>
      <VRow>
        <VSpacer />
        <VCol cols="6" sm="6" md="6">
          <VCard subtitle="Folio Data" :dir="$i18n.locale === 'en' ? 'rtl' : 'ltr'">
            <VCardText>
              <VList class="card-list">
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    Methods Of Payment
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Methods Of Payment</span>
                    <VAvatar color="primary" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    <!--                    {{ item.rate_code.name }}-->
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Rate Code</span>
                    <VAvatar color="success" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-link" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.rm_rate }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Room Rate</span>
                    <VAvatar color="info" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-click" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    <!--                    {{ item.meal ? item.meal.name : item.meal_package.name }}-->
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Meal/Package</span>
                    <VAvatar color="warning" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-alert-triangle" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.res_no ? item.res_no : 'No res number' }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Reserve number</span>
                    <VAvatar color="secondary" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-ban" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    transformer process
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">transformer process</span>
                    <VAvatar color="error" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>
        <VCol cols="6" sm="6" md="6">

          <VCard :dir="$i18n.locale === 'en' ? 'rtl' : 'ltr'">
            <VCardText>
              <VList class="card-list">
                <VListItem :key="item.room.rm_name_en">
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.room.rm_name_en }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">room name</span>
                    <VAvatar color="primary" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.profile.first_name }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">profile name</span>
                    <VAvatar color="success" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-link" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    group name
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">group name</span>
                    <VAvatar color="info" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-click" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.in_date }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Date Come</span>
                    <VAvatar color="warning" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-alert-triangle" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.out_date }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Date Leave</span>
                    <VAvatar color="secondary" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-ban" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    <!--                    {{ item.company.company_name }}-->
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Company Name</span>
                    <VAvatar color="error" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
                <VListItem>
                  <VListItemTitle class="font-weight-semibold">
                    {{ item.hotel_note ? item.hotel_note : 'No note' }}
                  </VListItemTitle>
                  <template #append>
                    <span class="font-weight-semibold">Note</span>
                    <VAvatar color="error" variant="tonal" size="34" rounded>
                      <VIcon icon="tabler-home" />
                    </VAvatar>
                  </template>
                </VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>

        <VCol cols="12" sm="12" md="12">
          <VCard>
            <VTabs v-model="currentItem" fixed-tabs>
              <VTab v-for="windows in item.window" :key="windows" :value="windows.id">
                {{ windows.window_name }}
              </VTab>
            </VTabs>
            <VWindow v-model="currentItem">
              <VWindowItem v-for="windows in item.window" :key="windows" :value="windows.id">
                <VCard>
                  <VCardText>
                    <VTable id="element-to-pdf" style="table-layout: fixed;">
                      <thead>
                        <tr>
                          <th>
                            no.
                          </th>
                          <th>
                            hotel_date
                          </th>
                          <th>
                            charge_amount
                          </th>
                          <th>
                            payment_amount
                          </th>
                          <th>
                            ledger name
                          </th>
                          <th>
                            description
                          </th>

                          <th>
                            created_by
                          </th>
                          <th>
                            View
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(transaction, index) in windows.transactions" :key="transaction"
                          :value="transaction.id">
                          <td>
                            {{ ++index }}
                          </td>
                          <td>
                            {{ transaction.hotel_date }}
                          </td>
                          <td>
                            {{ transaction.charge_amount }}
                          </td>

                          <td>
                            {{ transaction.payment_amount }}
                          </td>
                          <td>
                            {{ transaction.ledger.name }}
                          </td>
                          <td>
                            {{ transaction.description }}
                          </td>

                          <td>
                            {{ transaction.created_by.firstname }}
                          </td>
                          <td>
                            <VDialog v-model="transaction.transactionDialog" width="1024">
                              <template #activator="{ props }">
                                <VBtn v-bind="props" @click="selectTransaction(transaction)">
                                  {{ $t('view') }}
                                </VBtn>
                              </template>
                              <VCard>
                                <VCardTitle>
                                  <VRow>
                                    <VCol cols="12" sm="6" md="6">
                                      <span class="text-h5">{{ $t('Transaction Details') }}</span>
                                    </VCol>
                                  </VRow>
                                </VCardTitle>
                                <VCardText>
                                  <VContainer>
                                    <VRow>
                                      <VCol cols="12" sm="6" md="4">
                                        <VTextField v-model="transaction_charge_amount" label="Charge amount" readonly />
                                      </VCol>
                                      <VCol cols="12" sm="6" md="4">
                                        <VTextField v-model="transaction_charge_without_taxes"
                                          label="Charge without taxes" readonly />
                                      </VCol>
                                      <VCol cols="12" sm="6" md="4">
                                        <VTextField v-model="transaction_ledger_name" label="Ledger" readonly />
                                      </VCol>
                                      <VCol cols="12" sm="6" md="4">
                                        <VTextField v-model="transaction_hotel_date" label="Hotel date" readonly />
                                      </VCol>
                                      <VCol cols="12" sm="6" md="4">
                                        <VTextField v-model="transaction_description" label="Description" readonly />
                                      </VCol>
                                      <VCol cols="12" sm="6" md="4">
                                        <VCheckbox v-model="transaction_tax_included" label="Tax Included" readonly />
                                      </VCol>
                                    </VRow>
                                  </VContainer>
                                </VCardText>
                                <VCardActions>
                                  <VSpacer />
                                  <VBtn color="blue-darken-1" variant="text"
                                    @click="transaction.transactionDialog = false">
                                    Close
                                  </VBtn>
                                </VCardActions>
                              </VCard>
                            </VDialog>
                          </td>
                        </tr>
                      </tbody>
                    </VTable>
                  </VCardText>
                </VCard>
              </VWindowItem>
            </VWindow>
          </VCard>
        </VCol>

        <VCol cols="12" sm="12" md="12">
          <br>
          <VCard title="Transactions Of Windows">
            <template #append />
            <VCardText>
              <VRow style="display: flex;justify-content: space-around;">
                <VCol key="Sales" cols="6" md="3">
                  <div class="d-flex">
                    <VAvatar color="primary" variant="tonal" size="42" class="me-3">
                      <VIcon size="24" icon="tabler-chart-pie-2" />
                    </VAvatar>
                    <div class="d-flex flex-column">
                      <span v-for="windows in item.window" class="text-h6 font-weight-medium">
                        <p v-for="(transaction, index) in windows.transactions" :key="transaction">
                          {{ transaction.payment_amount }}</p>
                      </span>
                      <span class="text-caption">
                        Total payment of this Windows
                      </span>
                    </div>
                  </div>
                </VCol>

                <VCol key="Sales" cols="6" md="3">
                  <div class="d-flex">
                    <VAvatar color="error" variant="tonal" size="42" class="me-3">
                      <VIcon size="24" icon="tabler-shopping-cart" />
                    </VAvatar>

                    <div class="d-flex flex-column">
                      <span v-for="windows in item.window" class="text-h6 font-weight-medium">
                        <p v-for="(transaction, index) in windows.transactions" :key="transaction">
                          <!--                          {{ transaction.reduce(transaction.charge_amount)}}-->
                        </p>


                      </span> <span class="text-caption">
                        Total Charge of this Windows
                      </span>
                    </div>
                  </div>
                </VCol>
                <VCol key="Sales" cols="6" md="3">
                  <div class="d-flex">
                    <VAvatar color="info" variant="tonal" size="42" class="me-3">
                      <VIcon size="24" icon="tabler-currency-dollar" />
                    </VAvatar>

                    <div class="d-flex flex-column">
                      <span v-for="windows in item.window" class="text-h6 font-weight-medium">
                        <p v-for="(transaction, index) in windows.transactions" :key="transaction">
                          {{ transaction.charge_amount - transaction.payment_amount }}</p>
                      </span>

                      <span class="text-caption">
                        Current Balance of this windows
                      </span>
                    </div>
                  </div>
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
        </VCol>
        <VCol cols="12" sm="12" md="12">
          <br>
          <div style="display: flex;flex-wrap: wrap;justify-content: space-between;">

            <VBtn variant="flat"
              @click="this.$router.push({ name: `inhouse-update-guest`, params: { guest: this.Folio.FolioState[0].id } })">
              Guest data
            </VBtn>

            <VBtn variant="flat">
              print
            </VBtn>

            <VBtn variant="flat">
              Departure
            </VBtn>

            <VMenu location="bottom">
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t('Cashier') }}
                </VBtn>
              </template>

              <VList>
                <template v-for="item in cashier_items" :key="item.title">
                  <VListItem @click="callMethod(item.value)">
                    <VListItemTitle>{{ item.title }}</VListItemTitle>
                  </VListItem>
                </template>
              </VList>
            </VMenu>


            <VMenu location="bottom">
              <template #activator="{ props }">
                <VBtn v-bind="props">
                  {{ $t('Other options') }}
                </VBtn>
              </template>

              <VList>
                <template v-for="item in items" :key="item.title">
                  <VListItem @click="callMethod(item.value)">
                    <VListItemTitle>{{ item.title }}</VListItemTitle>
                  </VListItem>
                </template>
              </VList>
            </VMenu>

            <VBtn variant="flat">
              Exit
            </VBtn>
          </div>
        </VCol>
      </VRow>
    </VContainer>

    <VCol cols="12" sm="12" md="12">
      <br>
      <VCard title="All Windows">
        <VCardText>
          <VRow style="display: flex;justify-content: space-around;">
            <VCol key="Sales" cols="6" md="3">
              <div class="d-flex">
                <VAvatar color="primary" variant="tonal" size="42" class="me-3">
                  <VIcon size="24" icon="tabler-chart-pie-2" />
                </VAvatar>

                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ Total_payment_Amount }}</span>
                  <span class="text-caption">
                    ToTal Payment Amount
                  </span>
                </div>
              </div>
            </VCol>

            <VCol key="Sales" cols="6" md="3">
              <div class="d-flex">
                <VAvatar color="error" variant="tonal" size="42" class="me-3">
                  <VIcon size="24" icon="tabler-shopping-cart" />
                </VAvatar>

                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ Total_Charge_Amount }}</span>
                  <span class="text-caption">
                    Total Charge Amount
                  </span>
                </div>
              </div>
            </VCol>
            <VCol key="Sales" cols="6" md="3">
              <div class="d-flex">
                <VAvatar color="info" variant="tonal" size="42" class="me-3">
                  <VIcon size="24" icon="tabler-currency-dollar" />
                </VAvatar>

                <div class="d-flex flex-column">
                  <span class="text-h6 font-weight-medium">{{ Total_Charge_Amount - Total_payment_Amount }}</span>
                  <span class="text-caption">
                    Current Balance
                  </span>
                </div>
              </div>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>

    <br>
    <VDialog v-model="extendstayDialog" width="1024">
      <VCard>
        <VCardTitle>
          <span class="text-h5">Extend stay</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="room_no" label="Room" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="folio_no" label="Folio" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="departure_date" label="Departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <span>Update date</span>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="mt-2">
                <AppDateTimePicker v-model="extended_date" label="Change to" />
              </VCol>
              <VCol cols="12" sm="6" md="6" class="mt-2">
                <VTextField v-model="no_of_nights" label="Number of nights" type="number" />
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="blue-darken-1" variant="text" @click="PaymentDialog = false">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" @click="submitExtendStay">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="postingchargeDialog" width="1024">
      <VCard>
        <VCardTitle>
          <span class="text-h5">Posting charges</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="room_no" label="Room" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="departure_date" label="Departure date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="6" class="mt-8">
                <VCol cols="12" sm="6" md="9">
                  <VTextField v-model="ledger_id" label="Ledger account" />
                </VCol>
                <VCol cols="12" sm="6" md="9">
                  <VTextField v-model="ledger_name" label="Ledger name" />
                </VCol>
                <VCol cols="12" sm="6" md="12">
                  <VTextField v-model="amount" label="Amount" append-icon="tabler-cash" type="number" />
                </VCol>
                <VCol cols="12" sm="6" md="12">
                  <VTextarea v-model="description" label="Description" />
                </VCol>
                <VCol cols="12" sm="6" md="12">
                  <VCheckbox v-model="tax_included" label="Tax Included" />
                </VCol>

              </VCol>


              <VCol cols="12" sm="6" md="6" class="mt-8">
                <span>{{ $t('Ledger Categories') }}</span>
                <VList>
                  <template v-for="category in ledgerCats" :key="category.name">
                    <VListGroup :value="category.name" :title="category.name">
                      <template #activator="{ props }">
                        <VListItem v-bind="props" :title="category.name" />
                      </template>
                      <VListItem v-for="ledger in category.ledgers" :key="ledger.id" :value="ledger.id"
                        :title="ledger.id + ' - ' + ledger.name" @click="ledgerSelected(ledger)" />
                    </VListGroup>
                  </template>
                </VList>
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="blue-darken-1" variant="text" @click="closePostingChargeDialog">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disablePostingCharge" @click="submitPostingCharge">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="PaymentDialog" width="1024" @click:outside="PaymentDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Cacher Payment</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="room_no" label="Room" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="window_number" label="window name" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="12" md="12">
                <VTextarea v-model="Amount" label="Amount" type="number" />
              </VCol>
              <VCol cols="12" sm="12" md="12">
                <VTextarea v-model="Descriptions" label="Descriptions" />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VCombobox v-model="Payments_Selected" :items="Payments" item-title="name" item-value="Payments_Selected"
                  label="Way of Payments"  item-color="customColorValue" />
              </VCol>
              <VCol class="Date_inputs" cols="6" sm="6" md="6">
                <VTextField v-model="no_data" label="No Date" type="number" />
              </VCol>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click="PaymentDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" @click="submitPayment">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="ChangeRoomDialog" width="1024" @click:outside=" ChangeRoomDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Room</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_room" label="Current Room" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="4">
                <VCombobox v-model="changed_room" :label="$t('Changed Room*')"
                  :item-title="$i18n.locale === 'en' ? 'rm_name_en' : 'rm_name_loc'" item-value="room"   item-color="customColorValue" />
              </VCol>

              <VCol cols="12" sm="6" md="2">
                <VDialog v-model="room_dialog" width="1024">
                  <template #activator="{ props }">
                    <VBtn v-bind="props">
                      {{ $t('select Room') }}
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <VRow>
                        <VCol cols="12" sm="6" md="12">
                          <span>Select Room</span>
                        </VCol>
                        <VCol cols="12" sm="6" md="3">
                          <VCombobox v-model="changed_room_type" :label="$t('Changed Room type')"
                            :items="room_types"
                            :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'"
                            item-value="room_type"  item-color="customColorValue"  />
                        </VCol>
                        <VCol cols="12" sm="6" md="9">
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
                          <tr
                            v-for="                           item                           in                           filterData                           "
                            :key="item.id">
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
                              <VBtn color="primary" @click=" selectroom(item)">
                                <VIcon icon="mdi-check" />
                              </VBtn>
                            </td>
                          </tr>
                        </tbody>
                      </VTable>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text" @click=" room_dialog = false">
                        {{ $t('Close') }}
                      </VBtn>
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_room_type" label="Current Room type" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VCombobox item-color="customColorValue" v-model="changed_room_type" :label="$t('Changed Room type*')"
                  :item-title="$i18n.locale === 'en' ? 'rm_type_name' : 'rm_type_name_loc'" item-value="room_type"
                  readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_no_of_pax" label="Current No of pax" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="changed_no_of_pax" label="Changed No of pax*" type="number" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="12">
                <VTextField v-model="reason" label="Reason*" />
              </VCol>
              <VCol cols="12" sm="6" md="3" />
              <VCol cols="12" sm="6" md="6">
                <VRadioGroup v-model="room_old_status_radio" inline class="ml-12">
                  <span class="mt-1 mr-2">Change old status to: </span>
                  <VRadio label="Dirty" value="Dirty" />
                  <VRadio label="Clean" value="Clean" />
                </VRadioGroup>
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click="clearChangeRoom">
            Clear
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" @click=" ChangeRoomDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableChangeRoom" @click="submitChangeRoom">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="ChangeRateDialog" width="1024" @click:outside=" ChangeRateDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Rate</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="room_type" label="Room type" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="1">
                <VTextField v-model="no_of_pax" label="PAX" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="2">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="2">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_rate_code" label="Current Rate code" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VCombobox item-color="customColorValue" v-model="changed_rate_code" :label="$t('Changed Rate code*')" :items="rate_codes"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="rate_code" />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_meal" label="Current Meal/Meal Package" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="changed_meal" label="Changed Meal/Meal Package*" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="current_room_rate" label="Current Room Rate" readonly />
              </VCol>

              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="changed_room_rate" :label="$t('Changed Room Rate*')" type="number" />
              </VCol>
              <VCol cols="12" sm="6" md="12">
                <VTextField v-model="rate_reason" label="Reason*" />
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click="clearChangeRate">
            Clear
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" @click=" ChangeRateDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableChangeRate" @click="submitChangeRate">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="CreateWindowDialog" width="1024" @click:outside=" CreateWindowDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Rate</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="3" />
              <VCol cols="12" sm="6" md="6">
                <VTextField v-model="new_window_name" label="Window name" />
              </VCol>
            </VRow>
          </VContainer>

          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click=" CreateWindowDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableWindow" @click="submitWindow">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="changePropertiesDialog" width="1024" @click:outside=" changePropertiesDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Properties</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VCombobox item-color="customColorValue" v-model="market_segment" :label="$t('Market segment*')" :items="segments"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VCombobox item-color="customColorValue" v-model="source_of_business" :label="$t('Source of business*')" :items="sources"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VCombobox item-color="customColorValue" v-model="company" :label="$t('Company profile*')" :items="companies"
                  :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" item-value="room" />
              </VCol>
            </VRow>
          </VContainer>

          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click=" changePropertiesDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableProperties" @click="submitProperties">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="RoutingChargeDialog" width="1024" @click:outside=" RoutingChargeDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Routing Charge</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="current_room" label="Current Room" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6">
                <VCombobox item-color="customColorValue" v-model="selected_room_routing" :label="$t('Selected Room*')" :items="oc_rooms"
                  :item-title="$i18n.locale === 'en' ? 'room_name_en' : 'room_name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VCombobox item-color="customColorValue" v-model="selected_window" :label="$t('Selected Window*')" :items="room_windows"
                  :item-title="$i18n.locale === 'en' ? 'window_name' : 'window_name'" item-value="window" />
              </VCol>

              <VCol cols="12" sm="6" md="12">
                <VCol cols="12" sm="6" md="4">
                  <VBtn color="blue-darken-1" variant="text" @click="selectallLedgers">
                    select all
                  </VBtn>
                  <VBtn color="blue-darken-1" variant="text" @click=" selected_ledger = []">
                    unselect all
                  </VBtn>
                </VCol>
                <VTable height="200" equalcolumns="true">
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
                    <tr v-for=" ( item, index ) in ledgers " :key="item.id">
                      <td>
                        <VCheckbox v-model="selected_ledger[index]" :true-value="item.id" />
                      </td>
                      <td>{{ item.id }}</td>
                      <td>
                        <p v-if="$i18n.locale === 'en'">
                          {{ item.name }}
                        </p>
                        <p v-else>
                          {{ item.name_loc }}
                        </p>
                      </td>
                      <!--
                        <td>
                        <VBtn color="primary" @click=" selectroom(item) ">
                        <VIcon icon="mdi-check" />
                        </VBtn>
                        </td> 
                      -->
                    </tr>
                  </tbody>
                </VTable>
              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click=" RoutingChargeDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableRoutingCharge"
            @click=" submitRoutingCharge(selected_ledger)">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="changePropertiesDialog" width="1024" @click:outside=" changePropertiesDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Change Properties</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="4">
                <VCombobox item-color="customColorValue" v-model="market_segment" :label="$t('Market segment*')" :items="segments"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VCombobox item-color="customColorValue" v-model="source_of_business" :label="$t('Source of business*')" :items="sources"
                  :item-title="$i18n.locale === 'en' ? 'name' : 'name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="4">
                <VCombobox v-model="company" :label="$t('Company profile*')" :items="companies"
                  :item-title="$i18n.locale === 'en' ? 'company_name' : 'company_name_loc'" item-value="room" />
              </VCol>
            </VRow>
          </VContainer>

          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click=" changePropertiesDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableProperties" @click="submitProperties">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <VDialog v-model="RoutingChargeDialog" width="1024" @click:outside=" RoutingChargeDialog = false">
      <VCard>
        <VCardTitle class="text-center">
          <span class="text-h4 ">Routing Charge</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="guest_name" label="Guest name" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="arrival_date" label="Arrival date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="departure_date" label="departure date" readonly />
              </VCol>
              <VCol cols="12" sm="6" md="3">
                <VTextField v-model="current_room" label="Current Room" readonly />
              </VCol>
            </VRow>
          </VContainer>
          <hr>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6">
                <VCombobox v-model="selected_room_routing" :label="$t('Selected Room*')" :items="oc_rooms"
                  :item-title="$i18n.locale === 'en' ? 'room_name_en' : 'room_name_loc'" item-value="room" />
              </VCol>
              <VCol cols="12" sm="6" md="6">
                <VCombobox v-model="selected_window" :label="$t('Selected Window*')" :items="room_windows"
                  :item-title="$i18n.locale === 'en' ? 'window_name' : 'window_name'" item-value="window" />
              </VCol>

              <VCol cols="12" sm="6" md="12">
                <VCol cols="12" sm="6" md="4">
                  <VBtn color="blue-darken-1" variant="text" @click="selectallLedgers">
                    select all
                  </VBtn>
                  <VBtn color="blue-darken-1" variant="text" @click=" selected_ledger = []">
                    unselect all
                  </VBtn>
                </VCol>
                <VTable height="200" equalcolumns="true">
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
                    <tr v-for="  (          item, index          )   in             ledgers            " :key="item.id">
                      <td>
                        <VCheckbox v-model="selected_ledger[index]" :true-value="item.id" />
                      </td>
                      <td>{{ item.id }}</td>
                      <td>
                        <p v-if="$i18n.locale === 'en'">
                          {{ item.name }}
                        </p>
                        <p v-else>
                          {{ item.name_loc }}
                        </p>
                      </td>
                      <!--  <td>
                              <VBtn color="primary" @click=" selectroom(item) ">
                                <VIcon icon="mdi-check" />
                              </VBtn>
                            </td> -->
                    </tr>
                  </tbody>
                </VTable>


              </VCol>
            </VRow>
          </VContainer>
          <VContainer>
            <VRow>
              <VCol cols="12" sm="6" md="6" class="m-auto">
                <VCard>
                  <VCardText class="d-flex flex-column align-center justify-center">
                    <VAvatar size="42" variant="tonal" color="primary">
                      <VIcon icon="tabler-users" size="24" />
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
          <VBtn color="blue-darken-1" variant="text" @click=" RoutingChargeDialog = false; currentDateTime = null">
            Close
          </VBtn>
          <VBtn color="blue-darken-1" variant="text" :disabled="disableRoutingCharge"
            @click=" submitRoutingCharge(selected_ledger)">
            Submit
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <br /> {{ tot_payment_amount }}
  </div>
</template>

<script>
import axios from "@axios"

import moment from 'moment'
import Swal from 'sweetalert2'

import { mapActions, mapStores } from 'pinia'
import { useGeneralStore } from '../../stores/GeneralStore'
import { useLedgerStore } from '../../stores/LedgerStore'
import { useRatecodeStore } from '../../stores/RatecodeStore'
import { useRoomStore } from '../../stores/RoomStore'


export default {
  name: "FolioTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      room_windows: [],
      final_ledgers: [],
      selected_ledger: [],
      selected_room_routing: '',
      selected_window: '',
      avaliable_rooms: [],
      disableRoutingCharge: true,
      RoutingChargeDialog: false,
      changePropertiesDialog: false,
      market_segment: '',
      source_of_business: '',
      company: '',
      CreateWindowDialog: false,
      disableWindow: true,
      new_window_name: '',
      disableChangeRate: true,
      ChangeRateDialog: false,
      room_old_status_radio: 'Dirty',
      transactionDialog: false,
      currentItem: '',
      charge_amount_sum: 0,
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
      ],
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
          title: 'Charge Routing',
          value: 'chargeRouting',

        }

      ],
      search: '',
      Folio: [],
      Total_payment_Amount: '',
      Total_Charge_Amount: '',
      room_no: '',
      guest_name: '',
      guest_id: '',
      group_name: '',
      date_star: '',
      date_leave: '',
      note: '',
      full_meal: '',
      Method_of_Payment: '',
      Reservation_number: '',
      net_rent: '',
      rent_code: '',
      URL: window.location.origin,
      count: '',
      extendstayDialog: false,
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
      created_by_name: '',
      Payments: [],
      Payments_Selected: '',
      no_of_nights: 1,
      currentDateTime: null,
      postingchargeDialog: false,
      ledgerCats: [],
      tax_included: false,
      amount: '',
      ledger_id: '',
      ledger_name: '',
      tot_payment_amount: '',
      no_data: '',
      description: '',
      transaction_hotel_date: '',
      transaction_ledger_name: '',
      transaction_description: '',
      transaction_tax_included: '',
      disablePostingCharge: true,
      ChangeRoomDialog: false,
      current_room_type: '',
      current_room: '',
      current_no_of_pax: '',
      changed_room_type: '',
      changed_room: '',
      changed_no_of_pax: '',
      room_dialog: false,
      disableChangeRoom: true,
      reason: '',
      selected_rooms_from_room_type: [],
      current_rate_code: '',
      current_room_rate: '',
      current_meal: '',
      changed_rate_code: '',
      changed_room_rate: '',
      changed_meal: '',
      rate_reason: '',

    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {

    ...mapStores(useRoomStore, useRatecodeStore, useGeneralStore, useLedgerStore),
    filterData() {
      return this.rooms.filter(room => {
        return room.rm_name_en.toLowerCase().includes(this.search) ||
          room.rm_name_loc.toLowerCase().includes(this.search) ||
          room.rm_type.rm_type_name.toLowerCase().includes(this.search)
      })

    },
    oc_rooms() {
      return this.generalStore.oc_rooms
    },
    rooms() {
      return this.roomStore.rooms_filtered
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
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    selected_room_routing() {
      if (this.selected_room_routing == '') {
        this.selected_window = ''
      }
      else {
        this.room_windows = this.selected_room_routing.guests.window
        this.selected_window = ''
      }
    },
    extended_date() {
      if (this.no_of_nights != '') {
        var inn = moment(this.Folio.FolioState[0].out_date)
        var out = moment(this.extended_date)
        this.no_of_nights = out.diff(inn, 'days')
      }
    },
    no_of_nights() {
      if (this.no_of_nights != '') {
        var inn = moment(this.Folio.FolioState[0].out_date)
        inn.add(this.no_of_nights, 'days')
        this.extended_date = inn.format("YYYY-MM-DD")
      }
      if (this.no_of_nights == '') {
        this.no_of_nights = 1
      }
    },
    amount() {
      if (this.amount != '' && this.ledger_id != '' && this.ledger_name != '' && this.description != '') {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    ledger_id() {
      if (this.amount != '' && this.ledger_id != '' && this.ledger_name != '' && this.description != '') {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    ledger_name() {
      if (this.amount != '' && this.ledger_id != '' && this.ledger_name != '' && this.description != '') {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }
    },
    description() {
      if (this.amount != '' && this.ledger_id != '' && this.ledger_name != '' && this.description != '') {
        this.disablePostingCharge = false
      }
      else {
        this.disablePostingCharge = true
      }

    },
    changed_room_type() {
      if (this.changed_room_type != '' && this.changed_room != '' && this.changed_no_of_pax != '' && this.reason != '') {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }
    },
    changed_room() {
      if (this.changed_room != '' && this.changed_room_type == '') {
        this.changed_room_type = this.changed_room.room_type
        this.changed_no_of_pax = this.changed_room_type.def_pax
      }
      if (this.changed_room != '' && this.changed_room_type != '') {
        this.changed_room_type = this.changed_room.room_type
        this.changed_no_of_pax = this.changed_room_type.def_pax

      }
      if (this.changed_room_type != '' && this.changed_room != '' && this.changed_no_of_pax != '' && this.reason != '') {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }

    },
    changed_no_of_pax() {
      if (this.changed_room_type != '' && this.changed_room != '' && this.changed_no_of_pax != '' && this.reason != '') {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }
    },
    reason() {
      if (this.changed_room_type != '' && this.changed_room != '' && this.changed_no_of_pax != '' && this.reason != '') {
        this.disableChangeRoom = false
      }
      else {
        this.disableChangeRoom = true
      }

    },
    changed_room_type: function () {
      this.filterRooms()
    },
    // eslint-disable-next-line sonarjs/cognitive-complexity
    changed_rate_code() {


      if (this.changed_rate_code != '') {
        this.changed_meal = this.changed_rate_code.meal ? this.changed_rate_code.meal.name : this.changed_rate_code.meal_package.name
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
        this.changed_meal = ''
        this.changed_room_rate = ''

      }
      if (this.changed_rate_code != '' && this.changed_room_rate != '' && this.rate_reason != '') {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    changed_room_rate() {
      if (this.changed_rate_code != '' && this.changed_room_rate != '' && this.rate_reason != '') {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    rate_reason() {
      if (this.changed_rate_code != '' && this.changed_room_rate != '' && this.rate_reason != '') {
        this.disableChangeRate = false
      }
      else {
        this.disableChangeRate = true
      }
    },
    new_window_name() {
      if (this.new_window_name != '') {
        this.disableWindow = false
      } else {
        this.disableWindow = true
      }
    },


    selected_window() {
      if (this.selected_window != '') {
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
    // this.getledgerCats()
    this.getPayment()

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useRoomStore, ['getRoomTypesAction', 'getRoomsFilterAction']),
    ...mapActions(useLedgerStore, ['getLedgers']),
    ...mapActions(useRatecodeStore, ['getRateCodesAction']),

    ...mapActions(useGeneralStore, ['getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getOCRoomsAction']),
    submitRoutingCharge(ledgers) {

      for (let index = 0; index < ledgers.length; index++) {
        if (ledgers[index] != false && ledgers[index] !== undefined)
          this.final_ledgers.push(ledgers[index])
      }
      if (this.final_ledgers.length === 0) {
        Swal.fire({
          position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
          icon: 'warning',
          title: 'Please select a ledger',
          showConfirmButton: false,
          timer: 1500,
          toast: true,
        })
      }
      else {
        axios.post(`${this.URL}/api/chargeRout`, {
          'guestID_from': this.Folio.FolioState[0].id,
          'guestID_to': this.selected_room_routing.guests.guest_id,
          'window_id_from': this.currentItem,
          'window_id_to': this.selected_window.id,
          'ledgerID': this.final_ledgers,
        }, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },

        }).then(res => {
          this.RoutingChargeDialog = false
          this.getFolio()
          this.insertAlert('Routing charge has been recorded')
        })
        this.final_ledgers = []
      }

    },
    selectallLedgers() {
      this.selected_ledger = this.ledgers.map(item => item.id);
    },
    chargeRouting() {
      this.RoutingChargeDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.current_room = this.Folio.FolioState[0].room.rm_name_en
      if (this.ledgers == '') {
        this.getLedgers()
      }

      if (this.oc_rooms == '') {
        this.getOCRoomsAction()
      }


    },
    changeprop() {
      if (this.companies == '' && this.segments == '' && this.sources == '') {
        this.getCompaniesAction()
        this.getBusinessesAction()
        this.getSegmentsAction()
      }
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
      this.transaction_ledger_name = transaction.ledger.name
      this.transaction_description = transaction.description
      this.transaction_charge_amount = transaction.charge_amount
      this.transaction_charge_without_taxes = transaction.charge_without_taxes
      this.transaction_hotel_date = transaction.hotel_date
      this.transaction_tax_included = transaction.inc == 0 ? false : true
    },
    // getledgerCats() {
    //   axios.get('api/ledger-cats', {
    //     headers: {
    //       Accept: 'application/json',
    //       Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
    //     },
    //   })
    //     .then(res => {
    //       this.ledgerCats = res.data
    //
    //     })
    //     .catch(err => {
    //       console.log(err.message)
    //     })
    // },
    getFolio() {
      axios.post(`${this.URL}/api/folio_statement`, {
        id: 640,
      }).then(res => (this.Folio = res.data.data, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount))


    },
    getPayment() {
      axios.get(`${this.URL}/api/payment`).then(res => (this.Payments = res.data),
      )
    },
    addItem(item) {
      const removed = this.items.splice(0, 1)

      this.items.push(
        ...this.more.splice(this.more.indexOf(item), 1),
      )
      this.more.push(...removed)
      this.$nextTick(() => { this.currentItem = 'tab-' + item })
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
    changeRoom() {
      this.ChangeRoomDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.current_room = this.Folio.FolioState[0].room.rm_name_en
      this.current_room_type = this.Folio.FolioState[0].room.room_type.rm_type_name
      this.current_no_of_pax = this.Folio.FolioState[0].no_of_pax
    },
    CaherPayment() {
      this.PaymentDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.room_no = this.Folio.FolioState[0].room.rm_name_en
      this.folio_no = this.Folio.FolioState[0].folio_no
      this.guest_id = this.Folio.FolioState[0].id
      this.window_number = this.Folio.FolioState[0].window[0].window_name
      this.window_id = this.Folio.FolioState[0].window[0].id
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
        this.extendstayDialog = false
        this.getFolio()

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
        'hotel_date': '2023-04-04',
        'trans_type': 'P',
        'recd_type': 'REC',
        'res_id': 0,
        'is_reservation': 0,
        'payment_amount': this.Amount,
        'window_id': this.window_id,
        'payment_type_id': this.Payments_Selected.id,
      }).then(res => {
        this.PaymentDialog = false
        this.getFolio()
        this.insertAlert('Departure date has been Payment')
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
    },
    submitPostingCharge() {
      axios.post(`${this.URL}/api/transactions`, {
        'guest_id': this.Folio.FolioState[0].id,
        'room_id': this.Folio.FolioState[0].room_id,
        'hotel_date': moment().format("YYYY-MM-DD"),
        'window_id': this.currentItem,
        'ledger_id': this.ledger_id,
        'charge_amount': this.amount,
        'trans_type': 'C',
        'recd_type': 'CHR',
        'description': this.description,
        'is_reservation': this.Folio.FolioState[0].is_reservation,
        // eslint-disable-next-line sonarjs/no-redundant-boolean
        'inc': this.tax_included == true ? 1 : 0,
      }, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },

      }).then(res => {

        this.closePostingChargeDialog()
        this.getFolio()

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
    insertAlert(message) {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
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
        this.clearChangeRoom()
        this.ChangeRoomDialog = false
        this.getFolio()
        this.insertAlert('Room has been Changed')
      }).catch(err => {
        console.log(err)
      })
    },
    clearChangeRoom() {
      this.changed_no_of_pax = ''
      this.changed_room_type = ''
      this.changed_room = ''
      this.reason = ''
    },
    filterRooms() {

      this.getRoomsFilterAction({
        room_type_id: this.changed_room_type ? this.changed_room_type.id : null,
      })
    },
    changeRate() {

      this.ChangeRateDialog = true
      this.currentDateTime = new Date().toLocaleString()
      this.arrival_date = this.Folio.FolioState[0].in_date
      this.departure_date = this.Folio.FolioState[0].out_date
      this.room_type = this.Folio.FolioState[0].room.room_type.rm_type_name
      this.no_of_pax = this.Folio.FolioState[0].no_of_pax
      this.guest_name = this.Folio.FolioState[0].profile.first_name + ' ' + this.Folio.FolioState[0].profile.last_name
      this.created_by_name = this.Folio.FolioState[0].created_by.firstname + ' ' + this.Folio.FolioState[0].created_by.lastname
      this.current_rate_code = this.Folio.FolioState[0].rate_code.name
      this.current_room_rate = this.Folio.FolioState[0].rm_rate
      this.current_meal = this.Folio.FolioState[0].meal ? this.Folio.FolioState[0].meal.name : this.Folio.FolioState[0].meal_package.name
    },
    clearChangeRate() {
      this.changed_meal = ''
      this.changed_rate_code = ''
      this.changed_room_rate = ''
      this.rate_reason = ''
    },
    submitChangeRate() {
      axios.post(`${this.URL}/api/ratechange`, {
        'guest_id': this.Folio.FolioState[0].id,
        'raeson': this.rate_reason,
        'from_rate_code_id': this.Folio.FolioState[0].rate_code.id,
        'to_rate_code_id': this.changed_rate_code.id,
        'from_rm_rate': this.Folio.FolioState[0].rm_rate,
        'to_rm_rate': this.changed_room_rate,
        'meal_id': this.changed_rate_code.meal ? this.changed_rate_code.meal.id : null,
        'meal_package_id': this.changed_rate_code.meal_package ? this.changed_rate_code.meal_package.id : null,
        'hotel_date': new Date().toISOString().slice(0, 10),
        'room_id': this.Folio.FolioState[0].room_id,
      }, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },

      }).then(res => {
        this.clearChangeRate()
        this.ChangeRateDialog = false
        this.getFolio()
        this.insertAlert('Rate has been Changed')
      }).catch(err => {
        console.log(err)
      })
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
  },
  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  created() {
    this.getRoomTypesAction()
    this.getRoomsFilterAction()
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
</style>
