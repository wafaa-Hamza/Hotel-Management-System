import { defineStore } from "pinia"
import axios from "@axios"
import { reactive } from 'vue';

const state = reactive({
  room_id: null,
});

const mutations = {
  setRoomId(roomDataId) {
    state.room_id = roomDataId.room_id;
  },
};

  export const useRoomStore = defineStore('room', {
  id: 'room',
  state: () => ({
    room_types: [],
    rooms: [],
    rooms_filtered: [],
    room_statuses: [],
    floors: [],
    roomId: [],
  }),
  actions: {
    setRoomId(roomId) {
      this.roomId = roomId;

    },
    getRoomTypesAction() {
      axios.get(`${window.location.origin}/api/room_types`)
        .then(res => {
          this.room_types = res.data
        
        })
        .catch(err => {
          console.log(err)
        })
    },
    getFloorsAction() {
      axios.get(`${window.location.origin}/api/floor`)
        .then(res => {
          this.floors = res.data
        
        })
        .catch(err => {
          console.log(err)
        })
    },
    getRoomsAction() {
      axios.get(`${window.location.origin}/api/rooms`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
        .then(res => {
          this.rooms = res.data.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getRoomsFilterAction(data) {
      axios.post(`${window.location.origin}/api/filter-rooms`, data)
        .then(res => {
          this.rooms_filtered = res.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    getRoomStatusAction() {
      axios.get(`${window.location.origin}/api/room-status`)
        .then(res => {
          this.room_statuses = res.data.data
        })
        .catch(err => {
          console.log(err)
        })
    },
  },
})
