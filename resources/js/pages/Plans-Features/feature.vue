<template>
<FeatureTable />
</template>

<script>
import FeatureTable from "@/components/tables/FeaturePlan/FeatureTable.vue";

export default {
  components:{
    FeatureTable,
  },
}
</script>

<route lang="yaml">
  meta:
    action: view
    subject: feature
  </route>
