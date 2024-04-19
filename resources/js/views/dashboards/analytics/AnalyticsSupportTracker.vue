<script setup>
import VueApexCharts from 'vue3-apexcharts'
import { useTheme } from 'vuetify'
import { hexToRgb } from '@layouts/utils'

const assignmentData = [
  {
    title: 'User Experience Design',
    tasks: 120,
    progress: 72,
    color: 'primary',
  },
  {
    title: 'Basic fundamentals',
    tasks: 32,
    progress: 48,
    color: 'success',
  },
  {
    title: 'React Native components',
    tasks: 182,
    progress: 15,
    color: 'error',
  },
  {
    title: 'Basic of music theory',
    tasks: 56,
    progress: 24,
    color: 'info',
  },
]

const vuetifyTheme = useTheme()


const chartOptions = computed(() => {
  const currentTheme = vuetifyTheme.current.value.colors
  const variableTheme = vuetifyTheme.current.value.variables

  return {
    labels: ['Occupancy'],
    chart: { type: 'radialBar' },
    plotOptions: {
      radialBar: {
        offsetY: 10,
        startAngle: -140,
        endAngle: 130,
        hollow: { size: '65%' },
        track: {
          background: currentTheme.surface,
          strokeWidth: '100%',
        },
        dataLabels: {
          name: {
            offsetY: -20,
            color: `rgba(${ hexToRgb(currentTheme['on-background']) },${ variableTheme['disabled-opacity'] })`,
            fontSize: '13px',
            fontWeight: '400',
            fontFamily: 'Public Sans',
          },
          value: {
            offsetY: 10,
            color: `rgba(${ hexToRgb(currentTheme['on-background']) },${ variableTheme['high-emphasis-opacity'] })`,
            fontSize: '38px',
            fontWeight: '400',
            fontFamily: 'Public Sans',
          },
        },
      },
    },

    colors: [currentTheme.success],

    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        shadeIntensity: 0.5,

        gradientToColors: [currentTheme.success],

        inverseColors: true,
        opacityFrom: 1,
        opacityTo: 0.6,
        stops: [
          30,
          70,
          100,
        ],
      },
    },
    stroke: { dashArray: 10 },
    grid: {
      padding: {
        top: -20,
        bottom: 5,
      },
    },
    states: {
      hover: { filter: { type: 'none' } },
      active: { filter: { type: 'none' } },
    },
    responsive: [{
      breakpoint: 960,
      options: { chart: { height: 340 } },
    }],
  }
})



</script>

<template>
  <VCard :title="$i18n.locale === 'en'?'Room Status':'حالة الغرف'"
  >

    <VCardText>

      <VRow>

        <VCol

          cols="9"
          md="9"
          sm="12"
          style="display:flex;padding-top: 4%;"

         >

           <VCol

            cols="3"
            md="3"
            sm="3">
          <VList class="card-list mb-4"   v-for="(assignment,key) in Dashboard1[0]"
                 :key="assignment" >

            <VListItem

            >

              <template #prepend>
                <VProgressCircular
                  v-model="assignment.per"

                  :size="54"
                  class="me-4"
                  :color="assignment.color"
                >
              <span class="text-body-1 text-high-emphasis font-weight-medium">
                {{ assignment.count}}
              </span>
                </VProgressCircular>
              </template>
              <VListItemTitle class="font-weight-medium mb-2">
                <h3>{{ assignment.status_code }}</h3>
              </VListItemTitle>

              <VListItemSubtitle>{{ $i18n.locale ==='en'? assignment.name : assignment.name_loc}}</VListItemSubtitle>

            </VListItem>
            {{assignment.Status}}
          </VList>
          </VCol>

             <VCol

              cols="3"
              md="3"
              sm="3">
            <VList class="card-list mb-7"   v-for="(assignment,key) in Dashboard2[0]"
                   :key="assignment" >

              <VListItem

              >

                <template #prepend>
                  <VProgressCircular
                    v-model="assignment.per"

                    :size="54"
                    class="me-4"
                    :color="assignment.color"
                  >
              <span class="text-body-1 text-high-emphasis font-weight-medium">
                {{ assignment.count}}
              </span>
                  </VProgressCircular>
                </template>
                <VListItemTitle class="font-weight-medium mb-2">
                  <h3>{{ assignment.status_code }}</h3>
                </VListItemTitle>

                <VListItemSubtitle>{{ $i18n.locale ==='en'? assignment.name : assignment.name_loc}}</VListItemSubtitle>

              </VListItem>
              {{assignment.Status}}
            </VList>
            </VCol>


           <VCol

            cols="3"
            md="3"
            sm="3">

            <VList class="card-list mb-7"   v-for="(assignment,key) in Dashboard3[0]"
                   :key="assignment" >

              <VListItem

              >

                <template #prepend>
                  <VProgressCircular
                    v-model="assignment.per"

                    :size="54"
                    class="me-4"
                    :color="assignment.color"
                  >
              <span class="text-body-1 text-high-emphasis font-weight-medium">
                {{ assignment.count}}
              </span>
                  </VProgressCircular>
                </template>
                <VListItemTitle class="font-weight-medium mb-2">
                  <h3>{{ assignment.status_code }}</h3>
                </VListItemTitle>

                <VListItemSubtitle>{{ $i18n.locale ==='en'? assignment.name : assignment.name_loc}}</VListItemSubtitle>

              </VListItem>
              {{assignment.Status}}
            </VList>
          </VCol>



          <VCol

            cols="3"
            md="3"
            sm="3">
            <VList class="card-list mb-7"   v-for="(assignment,key) in Dashboard4[0]"
                   :key="assignment" >

              <VListItem

              >

                <template #prepend>
                  <VProgressCircular
                    v-model="assignment.per"

                    :size="54"
                    class="me-4"
                    :color="assignment.color"
                  >
              <span class="text-body-1 text-high-emphasis font-weight-medium">
                {{ assignment.count}}
              </span>
                  </VProgressCircular>
                </template>
                <VListItemTitle class="font-weight-medium mb-2">
                  <h3>{{ assignment.status_code }}</h3>
                </VListItemTitle>

                <VListItemSubtitle>{{ $i18n.locale ==='en'? assignment.name : assignment.name_loc}}</VListItemSubtitle>

              </VListItem>
              {{assignment.Status}}
            </VList>
          </VCol>


         </VCol>
        <VCol
          cols="3"
          md="3"
          sm="12"
        >
          <VueApexCharts
            :options="chartOptions"
            :series="series"
            height="250"
           />
        </VCol>
      </VRow>
    </VCardText>


  </VCard>
</template>
<script>
import axios from "axios"
export default {
  data(){
    return{
      Dashboard: [],
      Dashboard1: [],
      Dashboard2: [],
      Dashboard3: [],
      Dashboard4: [],
      series: [],
      occupancy: 0,
    }
  },
  mounted() {
    this.getDashboardData()

  },
  computed: {
    fullName() {
      return this.occupancy
    },
  },
  methods:{
    getDashboardData() {
      axios
        .get(`${window.location.origin}/api/dashboard-calculations`, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },

        })
        .then(res => {
          this.Dashboard = res.data.Status,this.Dashboard1.push(this.Dashboard.slice(0,2)),this.Dashboard2.push(this.Dashboard.slice(2,4)),this.Dashboard3.push(this.Dashboard.slice(4,6)),this.Dashboard4.push(this.Dashboard.slice(6,8)), this.occupancy = Math.trunc(res.data.Calculations.occupancy),this.series.push(this.occupancy)
        })
    },
  },
}
</script>
<style lang="scss" scoped>

.card-list{
  --v-card-list-gap: 1.875rem;

}
</style>
