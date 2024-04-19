<script setup>
import HelpCenterLandingArticlesOverview from '@/views/pages/help-center/HelpCenterLandingArticlesOverview.vue'
import axios from '@axios'

const apiData = ref()

// fetching data from the @fake-db
const fetchHelpCenterData = () => {
  return axios.get('/pages/help-center/landing').then(res => {
    apiData.value = res.data
  })
}

fetchHelpCenterData()
</script>

<template>
  <VCard v-if="apiData">
     <AppSearchHeader title="Hello, how can we help?"
      subtitle="Common troubleshooting topics: eCommerce, Blogging to payment" custom-class="rounded-0" />

    <!-- 👉 Popular Articles -->
    <VCardText class="py-12">
      <h5 class="text-h5 text-center my-6">
        Popular Articles
      </h5>

      <HelpCenterLandingArticlesOverview :articles="apiData.popularArticles" />
    </VCardText>


  </VCard>
</template>


<route lang="yaml">
  meta:
   layout: blank
  </route>
