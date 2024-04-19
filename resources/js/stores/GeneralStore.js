import { defineStore } from "pinia"
import axios from "@axios"

export const useGeneralStore = defineStore('general', {
  state: () => ({
    users: [],
    reservations: [],
    companies: [],
    segments: [],
    sources: [],
    countries: [],
    searched_reservations: [],
    errors: null,
    response: null,
    oc_rooms: [],
    searched_checkout_guests: [],
    voucher_list: [],
    expense_categories: [],
    expenses: [],
    Tax_invoice: [],
    print_invoice: [],
    windows: [],
    transactions: [],
    Rooms: [],
    profile: '',
    room: '',
    roomtype: '',
    company: '',
    pasts: [],
    future: [],
    Allpast: [],
    tot_sum_charge: [],
    guest_charge_routings: [],
    cashier_closer: [],
  }),
  actions: {
    
    async searchCashierCloser(data)
    {
      await axios.post(`${window.location.origin}/api/Tot_Payment_Of_Cashier_Closer`,data)
      .then(res => {
        this.cashier_closer = res.data.Total_Closer_Payment
      })
      .catch(err => {
        console.log(err)
      })
    },
    async getGuestChargeRoutingAction(id) {
      await axios.get(`${window.location.origin}/api/chargeRout/${id}`)
        .then(res => {
          this.guest_charge_routings = res.data.data
        })
    },
    resetSearchedReservations() {
      this.searched_reservations = []
    },
    functionnn() {
      axios.post(`${window.location.origin}/api/print-invoice`, {
        guest_id: this.guest_id,
        window_id: this.window_id,
      }).then(res => {
        (this.Tax_invoice = res.data.data, this.print_invoice = res.data.data.print_invoice[0], this.profile = res.data.data.print_invoice[0].profile, this.room = res.data.data.print_invoice[0].room, this.company = res.data.data.print_invoice[0].company, this.roomtype = res.data.data.print_invoice[0].roomtype, this.windows = res.data.data.print_invoice[0].window[0], this.transactions = res.data.data.print_invoice[0].window[0].transactions)

      })
    },


    getHistoryForcast() {
      axios.post(`${window.location.origin}/api/history-and-forecast`, {
        startDate: this.startDate = '2023-06-07',
        endDate: this.endDate = '2023-06-31',
      }).then(res => {
        (this.pasts = res.data.History_And_Forecast, this.future = res.data.History_And_Forecast[1], this.Allpast.forEach((value, index) => { console.log(value) }))



      })
    },

    getAllusersAction() {
      axios.get(`${window.location.origin}/api/users`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.users = res.data

        })
        .catch(err => {

        })
    },
    getReservationstatusesAction() {
      axios.get(`${window.location.origin}/api/reservation-status`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.reservations = res.data.data
        })
        .catch(err => {

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
    searchReservationsAction(data) {
      axios.post(`${window.location.origin}/api/reservation_search`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.searched_reservations = res.data
          this.response = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getOCRoomsAction() {
      axios.get(`${window.location.origin}/api/getAllInhousRooms`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.oc_rooms = res.data.data

        })
        .catch(err => {

        })
    },
    searchCheckoutInvoicesAction(data) {
      axios.post(`${window.location.origin}/api/guest_filter`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.searched_checkout_guests = res.data.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    searchVoucherList(data) {
      axios.post(`${window.location.origin}/api/voucher-list`, data, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.voucher_list = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getAllExpensesCategoreisAction() {
      axios.get(`${window.location.origin}/api/expensesLedger`)
        .then(res => {
          this.expense_categories = res.data.data

        })
        .catch(err => {

        })
    },
    getAllExpensesAction() {
      axios.get(`${window.location.origin}/api/expenses`)
        .then(res => {
          this.expenses = res.data.data

        })
        .catch(err => {

        })
    },
  },
})
