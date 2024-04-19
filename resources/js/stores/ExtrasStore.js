import { defineStore } from 'pinia'
import axios from '@axios'
import Swal from 'sweetalert2'

export const useExtrasStore = defineStore('extras', {
  state: () => ({
    extras: [],
    guest_extras: [],
  }),
  actions: {
    async deleteGuestExtra(id, guest_id) {
      return await axios.delete(`${window.location.origin}/api/destroyExtraGuestPivot/${id}`)
        .then(res => {
          this.getExtraByGuestId(guest_id)
        })
        .catch(err => {
          console.log(err)
        })
    },
    async addGuestExtra(extra) {
      await axios.post(`${window.location.origin}/api/storeExtraqForGuest`, extra)
        .then(res => {
          this.getExtraByGuestId(extra.data[0].guest_id)

          return res
        })
        .catch(err => {
          console.log(err)
        })

    },
    getExtraByGuestId(id) {
      axios.get(`${window.location.origin}/api/getExtraByGuestID/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.guest_extras = res.data.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    async getExtras() {
      return await axios.get(`${window.location.origin}/api/ExtraBedAndMeal`)
        .then(res => {
          this.extras = res.data.data

        })
        .catch(err => {
          console.log(err)
        })
    },

    async addExtra(extra) {
      return await axios.post(`${window.location.origin}/api/ExtraBedAndMeal`, extra)
        .then(res => {
          this.getExtras()

          return res
        })
        .catch(err => {
          console.log(err)
        })
    },

    async editExtra(extra, id) {
      return await axios.put(`${window.location.origin}/api/ExtraBedAndMeal/${id}`, extra)
        .then(res => {
          this.getExtras()

          return res
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    deleteExtra(id) {
      axios.delete(`${window.location.origin}/api/ExtraBedAndMeal/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.getExtras()
        })
        .catch(err => {
          console.log(err.message)
        })
    },
    insertAlert(message) {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: message,
        timer: 2000,
      })
    },
  },
})
