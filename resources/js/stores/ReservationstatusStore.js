import { defineStore } from 'pinia'
import axiosIns from '@axios'

export const useReservationstatusStore = defineStore('reservationstatus', {
  state: () => ({
    reservation_statuses: [],
  }),
  actions: {
    GetReservationStatuses() {
      axiosIns.get('api/reservation-status')
        .then(res => {

          this.reservation_statuses = res.data.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    editReservationStatus(Reservation_status, id) {
      axiosIns.put(`api/reservation-status/${id}`, Reservation_status)
        .then(res => {
          this.GetReservationStatuses()
        })
        .catch(err => {
          console.log(err.message)
        })
    },


  },
})
