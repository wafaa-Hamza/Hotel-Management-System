<template>
<SourceBusinessTable />
</template>

<script>
import SourceBusinessTable from "@/components/tables/SegmentBuss/SourceBusinessTable.vue"

export default {
  name: "sourcebusiness",

  components:{
    SourceBusinessTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: source of business
  </route>
