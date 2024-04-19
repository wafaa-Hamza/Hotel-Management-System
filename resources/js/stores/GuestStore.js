import axios from "@axios"
import { defineStore } from "pinia"
import Swal from 'sweetalert2'
import router from "@/router"

export const useGuestStore = defineStore('guest', {

  state: () => ({
    companies: [],
    segments: [],
    sources: [],
    countries: [],
    guests: [],
    guest_profile: null,
    errors: null,
    response: null,
    reservation: null,
    checkin: false,
    resMorenames: null,
    CheckInId: null,
    companion_updated: false,
    group_reservation: null,
    guest_extra: false,
    available_rooms: [],
    selected_profile_for_reservation: null,
  }),
  actions: {
    selectProfileForReservation(id) {
      this.selected_profile_for_reservation = id
    },
    nullSelectProfileForReservation() {
      this.selected_profile_for_reservation = null
    },
    async getAvaliableRooms(start_date, end_date) {
      return await axios.post(`${window.location.origin}/api/availability`, {
        in_date: start_date,
        out_date: end_date,
      })
        .then(res => {
          this.available_rooms = res.data.data[0]

          return res
        })
        .catch(err => {

         })
    },
    async updateReservationFromOnlyNameToProfile(guest) {
      return await axios.post(`${window.location.origin}/api/convertOnlyNameToNormalReservision`, guest)
        .then(res => {
          this.response = res.data

          return res
        })
        .catch(err => {
          console.log(err)
        })

    },
    resetResponse() {
      this.response = null
    },
    insertAlert(message) {

      Swal.fire({
        position: 'top-start',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    getCompaniesAction() {
      axios.get(`${window.location.origin}/api/companyProfile`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.companies = res.data

        })
        .catch(err => {
          console.log(err.message)
        })
    },
    getSegmentsAction() {
      axios.get(`${window.location.origin}/api/segment`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.segments = res.data

        })
        .catch(err => {
          console.log(err.message)
        })
    },
    getBusinessesAction() {
      axios.get(`${window.location.origin}/api/sourcebusiness`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.sources = res.data

        })
        .catch(err => {
          console.log(err.message)
        })
    },
    getCountriesAction() {
      axios.get(`${window.location.origin}/api/getcountries`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.countries = res.data

        })
        .catch(err => {
          console.log(err.message)
        })
    },
    CreateGuestAction(guest) {

      axios.post(`${window.location.origin}/api/reservation`, guest, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.response = res.data
            if(this.response[0].res_type == 'M'){

              console.log('Month',this.response[0].res_type)
              router.push({
                name: `reservation-update-monthyear-reservation`,
                params: { reservation: this.response[0].id },
              })
            }else if(this.response[0].res_type == 'Y'){

              console.log('Year',this.response[0].res_type)
              router.push({
                name: `reservation-update-monthyear-reservation`,
                params: { reservation: this.response[0].id },
              })

            }else{
               console.log('Day',this.response[0].res_type)
              router.push({
                name: `reservation-update-reservation`,
                params: { reservation: this.response[0].id },
              })
            }


        })
        .catch(err => {
          this.errors = err
          console.log('errooors', this.errors)

        })
    },
    CreateGuestDummay(guest) {
      return new Promise((resolve, reject) => {
        axios.post(`${window.location.origin}/api/reservation`, guest, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },
        })
          .then(res => {
            this.response = res.data
            this.CheckInId = res.data[0].id
            console.log('CheckInId', this.CheckInId)
            resolve(this.CheckInId)
          })
          .catch(err => {
            this.errors = err
            console.log('errors', this.errors)
            reject(err)
          })
      })
    },
    getGuestsAction() {
      axios.get(`${window.location.origin}/api/guest`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.guests = res.data

        })
        .catch(err => {
          console.log(err)
        })
    },
    updateGuestAction(guest, id) {
      axios.put(`${window.location.origin}/api/guest/${id}`, guest, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {

          this.response = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getGuestprofileAction(id) {
      axios.get(`${window.location.origin}/api/guestProfile/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.guest_profile = res.data.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    async getSearchguestAction(info) {
      return await axios.post(`${window.location.origin}/api/guest_profile_search`, info, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.guests = res.data?.data

          return res
        })
    },
    getReservationAction(id) {
      axios.get(`${window.location.origin}/api/reservation/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.reservation = res.data.data
        })
        .catch(err => {
          this.errors = err
          console.log(err)
        })
    },
    async UpdateReservationAction(id, guest) {
      return await axios.put(`${window.location.origin}/api/reservation/${id}`, guest)
        .then(res => {
          this.response = res.data.data[0]

          return res
        })
        .catch(err => {
          this.errors = err
          console.log(err)
        })
    },
    GuestCheckinAction(id) {
      console.log('top',id)
      axios.put(`${window.location.origin}/api/checkIn/${id}`, {}, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.checkin = true

        })
        .catch(err => {
          console.log(err)
        })
    },
    CreateGuestMoreNamesAction(data) {
      axios.post(`${window.location.origin}/api/storeMoreName`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.resMorenames = res.data
        })
        .catch(err => {
          this.errors = err
          console.log(err)
        })
    },
    updateGuestMoreNamesAction(guest, id) {
      axios.put(`${window.location.origin}/api/updateMoreName/${id}`, guest, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.companion_updated = true
        })
        .catch(err => {
          console.log(err)
        })
    },
    updateGuestProfileAction(data, id) {
      axios.put(`${window.location.origin}/api/guestProfile/${id}`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.insertAlert('profile has been updated')
        })
        .catch(err => {
          console.log(err)
        })
    },
    setCompanionUpdated() {
      this.companion_updated = false
    },
    CreateGroupReservationAction(guest) {
      axios.post(`${window.location.origin}/api/storeGroupReservision`, guest, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.insertAlert('Group Reservation created successfully')
          this.guest_extra = true
          this.response = res.data

          router.push({
            name: `reservation-update-groupp-new`,
            params: { new: this.response.groupMasterID },
          })
        })
        .catch(err => {

          console.log(err)
        })
    },
    async updateGroupReservation(guest) {
      return await axios.post(`${window.location.origin}/api/updateGroupGuest`, guest)
    },
    async getSelectedGroupReseravtion(id) {
      try {
        const response = await axios.get(`${window.location.origin}/api/showGroupRservisionForSelected/${id}`)
        if (response.data.status === 200) {
          this.group_reservation = response.data.data[0]
        }

        return response
      } catch (error) {
        // Handle errors here if needed
        throw error // Rethrow the error to be handled at the caller's end if necessary
      }
    },
    getGroupReservation(id) {
      axios.get(`${window.location.origin}/api/showGroupRservision/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.group_reservation = res.data.data[0]
        })
        .catch(err => {

          console.log(err)
        })
    },
    deleteReservation(id) {
      axios.delete(`${window.location.origin}/api/reservation/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.insertAlert('reservation deleted successfully')

          return 1
        })
        .catch(err => {
          console.log(err)
        })
    },
  },
})
