<template>
<floor-table/>
</template>

<script>
import floorTable from "@/components/tables/Rooms/FloorTable.vue"

export default {
  components: {
    floorTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: floor
  </route>
