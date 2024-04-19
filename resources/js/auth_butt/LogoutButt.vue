<template>
<div   @click="logout">
      {{ $t('log out') }}
  </div>
</template>

<script>
import router from "@/router"
import { isUserLoggedIn } from "@/router/utils"
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      localToken: "",
      status: "",
    }
  },
  name: "LogoutButt",
  // eslint-disable-next-line vue/component-api-style
  methods: {
    logout(){
      axios.post(`${this.URL}/api/user-logout`, {
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      })
      localStorage.clear()
      
      //router.push("login")
    },
  },
}
</script>
