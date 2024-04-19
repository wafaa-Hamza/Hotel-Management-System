<template>

   <user_table/>
 </template>

<script>
import user_table from "@/components/tables/user_table.vue"


export default {

  components: {
    user_table,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: user
  </route>
