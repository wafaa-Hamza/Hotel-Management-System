

<template>
  <div>

    <VTable height="500">
      <thead>
        <tr>
          <th>
            {{ $t("Res status") }}
          </th>
          <th>
            {{ $t("User") }}
          </th>
          <th>
            {{ $t("Res no") }}
          </th>
          <th>
            {{ $t("Guest name") }}
          </th>
          <th>
            {{ $t("Arrival date") }}
          </th>
          <th>
            {{ $t("Departure date") }}
          </th>
          <th>
            {{ $t("Created date") }}
          </th>
          <th>
            {{ $t("Room name") }}
          </th>
          <th>
            {{ $t("NOR") }}
          </th>
          <th>
            {{ $t("Company") }}
          </th>
          <th>
            {{ $t("Deposit") }}
          </th>
          <th>
            {{ $t("No of pax") }}
          </th>
          <th>
            {{ $t("Type") }}
          </th>
          <th>
            {{ $t("Select") }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td :style="{ backgroundColor: item.reservation_status.color }">
            {{ item.reservation_status.res_status_code }}
          </td>
          <td>{{ item.created_by.firstname }}</td>
          <td>{{ item.id }}</td>
          <td>
            {{ item.profile.first_name + " " + item.profile.last_name }}
          </td>
          <td>{{ item.in_date }}</td>
          <td>{{ item.out_date }}</td>
          <td>{{ moment(item.created_at).format("YYYY-MM-DD") }}</td>
          <td v-if="$i18n.locale === 'en' && item.room != null">
            {{ item.room.rm_name_en }}
          </td>
          <td v-if="$i18n.locale !== 'en' && item.room != null">
            {{ item.room.rm_name_loc }}
          </td>
          <td v-if="item.room == null">none</td>
          <td>{{ 1 }}</td>
          <td v-if="$i18n.locale === 'en'">
            {{ item.company.company_name }}
          </td>
          <td v-else>
            {{ item.company.company_name_loc }}
          </td>
          <td>{{ 0 }}</td>
          <td>{{ item.no_of_pax }}</td>
          <td>{{ item.is_group == 0 ? 'I' : 'Group' }}</td>
          <td>
            <VBtn color="primary" class="mt-1" @click="selectreservation(item)">
              <VIcon icon="mdi-check" />
            </VBtn>
          </td>

        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'
import moment from 'moment'
import { useGeneralStore } from '@/stores/GeneralStore'
import { mapStores, mapActions } from 'pinia'

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      mobile: null,
      reservation_no: null,
      reference_no: null,


    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    ...mapStores(useGeneralStore),

    searched_reservations() {
      return this.generalStore.searched_reservations
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    searched_reservations() {
      console.log(this.searched_reservations)
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    ...mapActions(useGeneralStore, ['searchReservationsAction', 'resetSearchedReservations']),
    showReservation(id) {
      console.log(id)
    },
    returnToFind() {
      this.resetSearchedReservations()
    },
    searchReservation() {
      this.searchReservationsAction({
        arrival_date_from: moment().format("YYYY-MM-DD"),
        arrival_date_to: moment().format("YYYY-MM-DD"),
        res_no_from: this.reservation_no != null && this.reservation_no != '' ? this.reservation_no : null,
        res_no_to: this.reservation_no != null && this.reservation_no != '' ? this.reservation_no : null,
        ref_no: this.reference_no != null && this.reference_no != '' ? this.reference_no : null,
      })
    },
    clear() {
      this.company = null
      this.in_date = null
      this.out_date = null
      this.results = []
      this.debit_total = null
      this.credit_total = null
      this.balance = null

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
    warnAlert() {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Total payment should not be greater than actual amount",
        showConfirmButton: false,
        timer: 2000,
      })
    },

  },
}
</script>

<route lang="yaml">
meta:
  layout: blank
</route>

<style>
.card-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh
}
</style>
