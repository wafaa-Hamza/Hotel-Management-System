<template>
<planTable />
</template>

<script>
import planTable from "@/components/tables/FeaturePlan/PlanTable.vue";

export default {
  name: "plan",
  components:{
    planTable,
  }
}
</script>


<route lang="yaml">
  meta:
    action: view
    subject: plan
  </route>

