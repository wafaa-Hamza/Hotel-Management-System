<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import avatar2 from '@images/avatars/avatar-2.png'
import pdf from '@images/icons/project-icons/pdf.png'
import pumaShoes from '@images/pages/puma-shoes.jpeg'
</script>

<template>
  <VCard :title="$i18n.locale ==='en'?'All Notifications':'كل الاشعارات'">
    <VCardText>
      <VTimeline
        side="end"
        align="start"
        truncate-line="both"
        density="compact"
        class="v-timeline-density-compact v-timeline-icon-only"
      >
        <!-- SECTION Flight -->
        <VTimelineItem  v-for="item in AllNotifications">
          <template #icon>
            <VIcon
              size="20"
              icon="tabler-send"
              color="error"
            />
          </template>

          <VCard
            class="bg-light-warning"
            variant="text"
            style="box-shadow: 1px 1px 4px 2px grey"
          >
            <VCardText >
              <div class="d-flex justify-space-between align-center mb-1">
                <span class="app-timeline-title">{{ item.message }}</span>
                <small class="app-timeline-meta">  {{ $t("Wednesday") }}</small>
              </div>

              <div class="app-timeline-text mb-1">
                <span>{{ item.added_by }}</span>
                <VIcon
                  size="20"
                  icon="tabler-arrow-right"
                  class="mx-2 flip-in-rtl"
                />
                <span>{{ item.redirect_to }}</span>
              </div>
              <p class="app-timeline-meta my-3">
                {{ item.start_date }}
              </p>

              <div class="app-timeline-text d-flex align-center gap-2">
                <div>
                  <VImg
                    :src="pdf"
                    :width="22"
                  />
                </div>

                <span>  {{ $t("booking-card.pdf") }}</span>
              </div>
            </VCardText>
          </VCard>
        </VTimelineItem>
      </VTimeline>
    </VCardText>
   </VCard>
</template>
<script>
 import axios from "@axios"

export default {
  data(){
    return{
       AllNotifications:[],
    }
  },
  methods: {


  },
  beforeCreate() {

      axios
        .get(`${window.location.origin}/api/notification`)
        .then(res => this.AllNotifications = res.data.data)

  },
}
</script>
