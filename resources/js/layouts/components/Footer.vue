<template>
  <div class="h-100 d-flex align-center justify-space-between" >

<VCard style="width: 100%;height: 100%;margin:4% 0 4% 0;padding: 1%;">

  <h4 style="float: left">    {{ localSetting?.hotel_date }}
 {{ currentTime }} </h4>
  <h4 style="float: right">{{userData.firstname}} {{userData.lastname}}</h4>
</VCard>
  </div>
</template>
<script>
import axios from "@axios"
import { f } from "vue3-treeselect"
export default {
  data(){
    return{
      myData:[],
      userData:[],
      AllSetData:[],
      SettingData:[],
      localSetting:[],
      currentTime: this.getCurrentTime()
    }
  },

  mounted() {
    const storedData = localStorage.getItem('userData')
    if (storedData) {
      this.myData = JSON.parse(storedData)
      this.userData = this.myData.user
    }
    const SettingData = localStorage.getItem('keyinfo')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
  },
methods:{
  getCurrentTime() {
    const now = new Date()
    const hours = now.getHours().toString().padStart(2, '0')
    const minutes = now.getMinutes().toString().padStart(2, '0')
    const seconds = now.getSeconds().toString().padStart(2, '0')

    return `${hours}:${minutes}:${seconds}`
  },
}
}
</script>
