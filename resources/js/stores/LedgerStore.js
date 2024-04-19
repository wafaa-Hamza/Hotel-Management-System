import { defineStore } from 'pinia'
import axios from '@axios'

export const useLedgerStore = defineStore('ledger', {
  state: () => ({
    ledgerCats: [],
    ledgers: [],
    URL: window.location.origin,
    payments:[],
  }),
  actions: {
    getPaymentTypes()
    {
      axios.get(`${this.URL}/api/payment`)
        .then(res => {
          this.payments = res.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },
    getledgerCats() {
      axios.get(`${this.URL}/api/ledger-cats`)
        .then(res => {
          this.ledgerCats = res.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    async addLedger(ledger) {
      return await axios.post('api/ledger', ledger)
        .then(res => {
          this.getLedgers()
          
          return res
        })
        .catch(err => {
          console.log(err)
        })
    },

    getLedgers() {
      axios.get(`${this.URL}/api/ledger`)
        .then(res => {
          this.ledgers = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },

    async editLedger(ledger, id) {
      return await axios.put(`${this.URL}/api/ledger/${id}`, ledger)
        .then(res => {
          this.getLedgers()

          return res
        })
        .catch(err => {
          console.log(err)
        })
    },
  },
})
