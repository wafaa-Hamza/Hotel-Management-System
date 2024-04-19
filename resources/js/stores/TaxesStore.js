import { defineStore } from 'pinia'
import axios from '@axios'

export const useTaxesStore = defineStore('taxes', {
  state: () => ({
    taxes: [],
  }),
  actions: {
    getTaxes() {
      axios.get(`${window.location.origin}/api/tax`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.taxes = res.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    async addTax(tax) {
      return await axios.post(`${window.location.origin}/api/tax`, tax)
        .then(res => {
          this.getTaxes()

          return res
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    async editTax(tax, id) {
      return await axios.put(`${window.location.origin}/api/tax/${id}`, tax)
        .then(res => {
          this.getTaxes()

          return res
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    deleteTax(id) {
      axios.delete(`${window.location.origin}/api/tax/${id}`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.getTaxes()
        })
        .catch(err => {
          console.log(err.message)
        })
    },
  },
})
