<template>

  <div class="demo-space-x" style="display: inline"  @click="To_sign">
    <VBtn color="primary">
      {{ $t('sign-up') }}
    </VBtn>
  </div>
</template>

<script>
import router from "@/router"

export default {
  name: "sign_up_butt",
  // eslint-disable-next-line vue/component-api-style
  methods:{
    To_sign(){
      router.push("sign-up")
    },
  },

}
</script>

<style scoped>

</style>
