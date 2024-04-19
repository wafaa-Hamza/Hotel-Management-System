<script setup>
import { VIcon } from 'vuetify/components/VIcon'
import sliderBar1 from '@images/illustrations/sidebar-pic-1.png'
import sliderBar2 from '@images/illustrations/sidebar-pic-2.png'
import sliderBar3 from '@images/illustrations/sidebar-pic-3.png'

const websiteAnalytics = [
  {
    name: 'Traffic',
    slideImg: sliderBar1,
    data: [
      {
        number: '1.5k',
        text: 'Sessions',
      },
      {
        number: '3.1k',
        text: 'Page Views',
      },
      {
        number: '1.2k',
        text: 'Leads',
      },
      {
        number: '12%',
        text: 'Conversions',
      },
    ],
  },

  // {
  //   name: 'Traffic',
  //   slideImg: sliderBar1,
  //   data: [
  //     {
  //       number: '1.5k',
  //       text: 'Sessions',
  //     },
  //     {
  //       number: '3.1k',
  //       text: 'Page Views',
  //     },
  //     {
  //       number: '1.2k',
  //       text: 'Leads',
  //     },
  //     {
  //       number: '12%',
  //       text: 'Conversions',
  //     },
  //   ],
  // },


]
</script>

<template>

  <div>
    <VCard color="primary">
      <VCarousel
        cycle
        :continuous="false"
        :show-arrows="false"
        hide-delimiter-background
        :delimiter-icon="() => h(VIcon, { icon: 'fa-circle', size: '10' })"
        height="260"
        class="carousel-delimiter-top-end web-analytics-carousel"
      >
        <VCarouselItem
          v-for="item in websiteAnalytics"
          :key="item.name"

        >
          <VCardText >
            <VRow >
              <VCol cols="12" >
                <h5 class="text-h2  text-white mb-1">
                   {{ $i18n.locale === 'en' ? SettingData.name : SettingData.name_loc }}
                </h5><br><br>
                <h3 class="text-white" style="width: 30%;display: flex;justify-content: space-between; ">
                  <p>{{ SettingData?.hotel_date }}</p>

                 <p>{{ currentTime }}</p>
                </h3>
              </VCol>

              <VCol
                cols="12"
                sm="6"
                order="2"
                order-sm="1"
              >
              </VCol>

              <VCol
                cols="12"
                sm="6"
                order="1"
                order-sm="2"
                class="position-relative text-center"
              >
                <img
                  :src="item.slideImg"
                  class="card-website-analytics-img"
                  style="filter: drop-shadow(0 4px 40px rgba(0, 0, 0, 40%));"
                >
              </VCol>
            </VRow>
          </VCardText>
        </VCarouselItem>
      </VCarousel>
    </VCard>
  </div>
</template>

<script>

export default {
  data(){
    return{

      SettingData: null,
      timer: null,
      currentTime: this.getCurrentTime(),
    }
  },

  mounted() {

    const SettingData = localStorage.getItem('settings')
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData)
    }
    this.timer = setInterval(() => {
      this.currentTime = this.getCurrentTime()
    }, 1000)
  },
  beforeUnmount() {
    // Clear the timer when the component is destroyed to prevent memory leaks
    clearInterval(this.timer)
  },

  methods: { 
    getCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')

      return `${hours}:${minutes}:${seconds}`
    },
  },
}
</script>


<style lang="scss">
.card-website-analytics-img {
  block-size: 150px;
}

@media screen and (min-width: 600px) {
  .card-website-analytics-img {
    position: absolute;
    margin: auto;
    inset-block-end: 40px;
    inset-block-start: -1rem;
    inset-inline-end: 1rem;
  }
}

.web-analytics-carousel {
  .v-carousel__controls {
    .v-btn:not(.v-btn--active) {
      opacity: 0.4;
    }
  }
}
</style>
