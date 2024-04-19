import { defineStore } from 'pinia'
import axiosIns from '@axios'

export const useRoomstatusStore = defineStore('roomstatus', {
  state: () => ({
    room_statuses: [],
  }),
  actions: {
    GetRoomStatuses() {
      axiosIns.get('api/room-status')
        .then(res => {
          this.room_statuses = res.data.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    editRoomStatus(Room_status, id) {
      axiosIns.put(`api/room-status/${id}`, Room_status)
        .then(res => {
          this.GetRoomStatuses()
        })
        .catch(err => {
          console.log(err.message)
        })
    },


  },
})
