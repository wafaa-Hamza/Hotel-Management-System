<template>
<ProfileTable />
</template>

<script>
import ProfileTable from "@/components/tables/Profile/ProfileTable.vue"

export default {
  components:{
    ProfileTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: profile
  </route>
