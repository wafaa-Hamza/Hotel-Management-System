
<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>


<template>
  <div>
    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VContainer>
        <VForm
          ref="refForm"
          @submit.prevent="SearchVoucher"
        >
          <VRow    class="d-print-none">
            <!--
              <VCol cols="12" sm="6" md="6">
              <VRadioGroup v-model="inlineRadio" inline>
              <VRadio :label="$t('By arrival date')" value="1" />
              <VRadio :label="$t('By Created date')" value="0" />
              </VRadioGroup>
              </VCol>
            -->
            <VSnackbar
              v-model="isSnackbarVisible"
              location="top"
              :timeout="2000"
            >
              Please Enter right data
            </VSnackbar>

            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="date_from"
                :label="$t('Date From')"
                :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <AppDateTimePicker
                v-model="date_to"
                :label="$t('Date To')"
                :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
              />
            </VCol>
            <!--
              <VCol cols="12" sm="3" md="3">
              <VTextField v-model="voucher_from" :label="$t('Voucher From')" type="number" />
              </VCol>
              <VCol cols="12" sm="3" md="3">
              <VTextField v-model="voucher_to" :label="$t('Voucher To')" type="number" />
              </VCol>
            -->
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VTextField
                v-model="folio_num"
                :label="$t('folio num')"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VCombobox
                v-model="recd_type"
                :label="$t('Record type')"
                :items="recd_types"
                item-title="name"
                item-value="name"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
              md="3"
            >
              <VTextField
                v-model="trans_no"
                :label="$t('Transaction no')"
              />
            </VCol>



            <VCol
              cols="12"
              sm="6"
              md="12"
              class="mt-6"

            >
              <VBtn
                class="float-end "
                type="submit"
                @click="refForm?.validate()"
              >
                {{ $t('Search') }}
              </VBtn>

                <VBtn @click="printInvoice"
                      class="float-end mr-4"
                >
                  {{ $t('print') }}
                </VBtn>

              <VBtn
                class="float-end mr-4"
                @click="Clear"
              >
                {{ $t('Clear') }}
              </VBtn>

            </VCol>
          </VRow>
          <br>
          <VTable height="500">
            <thead>
              <tr>
                <th>
                  {{ $t('Res no') }}
                </th>
                <th>
                  {{ $t('Room') }}
                </th>
                <th>
                  {{ $t('Guest name') }}
                </th>
                <th>
                  {{ $t('Date') }}
                </th>
                <th>
                  {{ $t('Description') }}
                </th>
                <th>
                  {{ $t('Amount') }}
                </th>
                <th>
                  {{ $t('Select') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in voucher_list.voucher_list"
                :key="item.id"
              >
                <td>{{ item.id }}</td>
                <td v-if="$i18n.locale === 'en' && item.room != null">
                  {{ item.room.rm_name_en }}
                </td>
                <td v-if="$i18n.locale !== 'en' && item.room != null">
                  {{ item.room.rm_name_loc }}
                </td>
                <td>{{ item.guest.profile.first_name + ' ' + item.guest.profile.last_name }}</td>
                            
                <td>{{ item.hotel_date }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.payment_amount }}</td>
                            
                <td>
                  <VBtn
                    color="primary"
                    class="mt-1"
                    @click="selectreservation(item)"
                  >
                    <VIcon icon="mdi-check" />
                  </VBtn>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VForm>
      </VContainer>
    </VCard>
  </div>
</template>

<script>
import { useGeneralStore } from '@/stores/GeneralStore'
import { useRoomStore } from '@/stores/RoomStore'
import { mapStores, mapActions } from 'pinia'
// import moment from 'moment'

export default {
  name: 'Guest',
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',
      isSnackbarVisible: false,
      inlineRadio: '1',
      refForm: ref(),
      date_from: null,
      date_to: null,
      res_no_from: null,
      res_no_to: null,
      ref_no: '',
      company: '',
      segment: '',
      country: '',
      created_by: '',
      res_status: [],
      selected_status: [],
      folio_num: '',
      room: '',
      invoice_from: '',
      invoice_to: '',
      voucher_from: '',
      voucher_to: '',
      recd_types: [
        {
          name: 'REC',
        },
        {
          name: 'PAY',
        },

      ],
      recd_type: '',
      trans_no: '',
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore, useRoomStore),


    reservations() {
      return this.generalStore.reservations
    },
    response() {
      return this.generalStore.response
    },
    users() {
      return this.generalStore.users
    },
    voucher_list() {
      return this.generalStore.voucher_list
    },
    rooms() {
      return this.roomStore.rooms
    },



  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    voucher_list() {
    },
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchVoucherList', 'searchCheckoutInvoicesAction', 'getAllusersAction', 'getReservationstatusesAction', 'getCompaniesAction', 'getBusinessesAction', 'getSegmentsAction', 'getCountriesAction', 'searchReservationsAction']),
    ...mapActions(useRoomStore, ['getRoomsAction']),
    Clear() {

      this.date_from = null
      this.date_to = null
      this.res_no_from = null
      this.res_no_to = null
      this.ref_no = ''
      this.invoice_from = ''
      this.invoice_to = ''
      this.folio_num = ''
      this.recd_type=''
      this.trans_no= ''


    },


    // eslint-disable-next-line sonarjs/cognitive-complexity
    SearchVoucher() {
      this.searchVoucherList({
                

        startDate: this.date_from != null ? this.date_from : null,
        endDate: this.date_to != null ? this.date_to : this.date_from != '' ? this.date_from : null,
        recd_type: this.recd_type.name,
        trans_no: this.trans_no!=''?this.trans_no:null,


        /* res_no_from: this.res_no_from,
                res_no_to: this.res_no_to != null ? this.res_no_to : this.res_no_from, */

      })

    },

    selectreservation(reservation) {
      this.$router.push({ name: `selectedFolio-folio`, params: { folio: reservation.id } })
    },
    alert() {
      this.$swal.fire({
        icon: 'error',
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        title: 'Please Enter right data',
        timer: 2000,
        showConfirmButton: false,

      })
    },

    printInvoice(){
      window.print()
    }
  },
  // eslint-disable-next-line vue/component-api-style
  created() {

  },

}
</script>

<style scoped>
.v-selection-control__input input {

    position: absolute;
    left: 9px;
    top: 9px;
    width: 50%;
    height: 50%;
    opacity: 1;
}

.custom-combobox .v-select-list {
    max-height: 50px;
    /* Set your desired fixed height here */
    overflow-y: auto;
    /* Enable vertical scrolling if the content exceeds the height */
}
</style>
