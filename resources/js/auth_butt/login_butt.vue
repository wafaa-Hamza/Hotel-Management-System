<template>

  <div class="demo-space-x" style="display: inline" @click="To_login">
    <VBtn color="success">
      {{ $t('log in') }}
    </VBtn>
  </div>
</template>

<script>
import router from "@/router"

export default {
  // eslint-disable-next-line vue/component-api-style
  data(){

  },
  name: "login_butt",
  // eslint-disable-next-line vue/component-api-style
  methods:{
    To_login(){
      router.push("login")
      this.localToken = localStorage.getItem('accessToken')
      this.localToken = localStorage.getItem('accessToken')
      this.status = localStorage.getItem('status')
      this.status = localStorage.getItem('status')


    },
  },

}
</script>

<style scoped>

</style>
