<script setup>
const navigationTab = ref('item1')
const items = [
  {
    title: 'Option 1',
    value: 'Option 1',
  },
  {
    title: 'Option 2',
    value: 'Option 2',
  },
  {
    title: 'Option 3',
    value: 'Option 3',
  },
]
</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VCardText>
        <VContainer>
          <VCardTitle class="text-center">
            <span class="text-h6">Rooms by floor</span>
          </VCardTitle>
        </VContainer>
      </VCardText>
    </VCard>
    <VRow>
      <VCol>
        <VTabs
          v-model="navigationTab"

          class="v-tabs-pill"
        >

          <VTab
            v-for="floor in Floors.floorData"
            :key="floor"
            :value="floor"
             class="v-tabs-pill"
          >
            {{ floor.floor_name }}
          </VTab>
        </VTabs>

        <VDivider />

        <!-- tabs content -->
        <VWindow
          v-model="navigationTab"
          class="windows"
        >
          <VWindowItem
            v-for="floor in Floors.floorData"
            :key="floor"
            :value="floor"
             style="padding: 3%"
          >
            <VCol
              v-for="wow in floor.rooms"
              :key="wow"
              cols="2"
              md="2"
              sm="2"

              style="display: inline-block; "
            >

              <VMenu>
                <template #activator="{ props }">
                  <VCard
                    :style="{ backgroundColor: wow.color_status }"
                    class="CardRoom"
                    style="border: 3px solid black;height: 100px"
                    v-bind="props"
                    @click.right.prevent="test"
                  >
                    <div class="d-flex flex-no-wrap justify-space-between">
                      <div
                        @mouseover="showDataOC = true;showDataOO=true"
                        @mouseout="showDataOC = false;showDataOO=false"
                      >
                        <VCardTitle class="text-h6">
                        {{ wow.roomName }}
                        </VCardTitle>

                        <VCardSubtitle class="text-h6">
                          {{ wow.room_type.rm_type_code }}
                        </VCardSubtitle>


                        <VCardTitle
                          v-if="wow.status ==='OCCL'||wow.status ==='OCDI'"
                          class="text-h6"
                        >
                          <!--                      <Transition name="fade"> <p v-show="showDataOC">{{ wow.guest_details.profile.first_name }}</p></Transition> -->
                        </VCardTitle>

                        <VCardTitle
                          v-else-if="wow.status ==='OO'"
                          class="text-h6"
                        >
                          <!--                      <Transition name="fade"> <p  v-show="showDataOO">{{ wow.Ord_status.end_date }} <br> {{ wow.Ord_status.notes  }}</p></Transition> -->
                        </VCardTitle>
                      </div>
                    </div>
                  </VCard>
                </template>

                <VList :items="items" />
              </VMenu>
            </VCol>
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"
import moment from 'moment'
import Swal from 'sweetalert2'

export default {
  name: "RoomsByFloorTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      showDataOC: false,
      showDataOO: false,
      search: '',
      Total_payment_Amount: '',
      Total_Charge_Amount: '',
      URL: window.location.origin,
      Floors: [],
      navigationTab2: '',
      navigationTab: '',
      wow1: '',
    }
  },


  // eslint-disable-next-line vue/component-api-style
  watch: {
    wow1(){
      console.log("this.Floors")
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getFolio()
    this.getFloor()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    test(){
      console.log('wow')
    },
    getFolio() {
      axios.post(`${this.URL}/api/folio_statement`, {
        id: 1,
      }).then(res => (this.Folio = res.data.data, this.Total_payment_Amount = res.data.data.Total_Payment_Amount, this.Total_Charge_Amount = res.data.data.Total_Charge_Amount),
      )
    },
    getFloor() {
      axios.post(`${this.URL}/api/rack`).then(res => (this.Floors = res.data.data),
      )
    },
    addItem(item) {
      const removed = this.items.splice(0, 1)

      this.items.push(
        ...this.more.splice(this.more.indexOf(item), 1),
      )
      this.more.push(...removed)
      this.$nextTick(() => { this.currentItem = 'tab-' + item })
    },


    // eslint-disable-next-line vue/no-dupe-keys
    // navigation(){
    //   console.log(this.Floors)
    // },

  },
}
</script>

<style lang="css">
.v-textarea .v-field__field {
  --v-input-control-height: var(--v-textarea-control-height);
  height: 60px;
}

.z-10 {
  z-index: 10003;
}
.CardRoom{
  transform: scale(1);
  transition: .5s;
  height: 10%;
}
.CardRoom:hover{
  transform: scale(1.8,1.8);
  z-index: 999;
}
 .windows{

   display: inline;
 }
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
