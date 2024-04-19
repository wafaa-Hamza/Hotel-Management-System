<template>
<SegmentTable />
</template>

<script>
import SegmentTable from "@/components/tables/SegmentBuss/SegmentTable.vue"

export default {
  name: "Segment",

  components:{
    SegmentTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: market segment
  </route>
