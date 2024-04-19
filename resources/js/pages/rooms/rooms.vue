<template>
  <RoomsTable/>
</template>

<script>
import RoomsTable from "@/components/tables/Rooms/RoomsTable.vue"

export default {
  components: {
    RoomsTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: room
    
  </route>
