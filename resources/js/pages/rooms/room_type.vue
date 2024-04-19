<template>
  <RoomTypeTable/>
</template>

<script>
import RoomTypeTable from "@/components/tables/Rooms/RoomTypeTable.vue"

export default {
  components: {
    RoomTypeTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: roomtype
  </route>
