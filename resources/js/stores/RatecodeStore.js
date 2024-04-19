import { defineStore } from 'pinia'
import axios from '@axios'

export const useRatecodeStore = defineStore('ratecode', {
  state: () => ({
    rate_codes: [],
    rate_code_id: null,
    room_types_from_add: [],
    response: null,
  }),
  actions: {
    nullResponse()
    {
      this.response=null
    },
    getRateCodesAction() {
      axios.get(`${window.location.origin}/api/rate-code`)
        .then(res => {
          this.rate_codes = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },

    async addRateCodeAction(rate_code) {
      try {
        return await axios.post(`${window.location.origin}/api/rate-code`, rate_code) // Return the response data to the caller
      } catch (error) {
        console.error('An error occurred:', error)
        throw error // Re-throw the error to be caught by the caller
      }
    },

    async updateRateCodeAction(rate_code, id) {
      return await axios.put(`${window.location.origin}/api/rate-code/${id}`, rate_code)
       
    },

    attachRoomtypeRatecodeAction(body, id) {
      axios.post(`${window.location.origin}/api/attach-roomtype-ratecode/${id}`, body)
        .then(res => {
          console.log(res.data)
          this.getRateCodesAction()
        })
        .catch(err => {
          console.log(err)
        })
    },

    deatachRoomtypeRatecode(body, id) {
      axios.post(`${window.location.origin}/api/deatach-roomtype-ratecode/${id}`, body)
        .then(res => {
          console.log(res.data)
          this.getRateCodesAction()
        })
        .catch(err => {
          console.log(err)
        })
    },

    deleteRateCodeAction(id) {
      axios.delete(`${window.location.origin}/api/rate-code/${id}`)
        .then(res => {
          console.log(res.data)
          this.getRateCodesAction()
        })
        .catch(err => {
          console.log(err)
        })
    },
  },
})
