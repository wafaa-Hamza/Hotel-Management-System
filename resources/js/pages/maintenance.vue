<template>
<MaintenanceTable />
</template>

<script>
import MaintenanceTable from "@/components/tables/MaintenanceTable.vue"

export default {
  name: "maintenance",
  components:{
    MaintenanceTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: maintenance
  </route>
