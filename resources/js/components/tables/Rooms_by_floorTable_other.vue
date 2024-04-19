<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VCardText>
        <VContainer>
          <VCardTitle class="text-center">
            <span class="text-h6">Rooms by floor </span>
          </VCardTitle>
        </VContainer>
      </VCardText>
    </VCard>
<VRow>

    <VCol>
      <VCard>
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
          style="padding: 2% 4%"
        >
          <VWindowItem
            v-for="floor in Floors.floorData"
            :key="floor"
            :value="floor"
          >
            <VCol
              v-for="wow in floor.rooms"
              :key="wow"
              cols="2"
              :value="floor"
              style="display: inline-block;"
            >


              <div class="icon-home" >
                <div class="home-body" :style="{ backgroundColor: wow.color_status }">

                  <h6 style="font-size: 60%;margin-top: 40%;text-align: center;"> {{ wow.roomName }}</h6>

                  <h5 style="font-size: 80%;margin-top: 1%;text-align: center;">{{ wow.room_type.rm_type_code }}</h5>
                </div>
                <div class="home-roof-cut-left"></div>
                <div class="home-roof-cut-right"></div>
                <div class="home-door"></div>
                <div class="home-chimney" :style="{ backgroundColor: wow.color_status }"></div>
                <div class="home-roof-container">
                  <div class="home-roof-left" :style="{ backgroundColor: wow.color_status }"></div>
                  <div class="home-roof-right" :style="{ backgroundColor: wow.color_status }"></div>
                </div>
              </div>
<!--              <VCard-->
<!--                :style="{ backgroundColor: wow.color_status }"-->
<!--                class="CardRoom"-->
<!--              >-->

<!--                <div class="d-flex flex-no-wrap justify-space-between">-->
<!--                  <div-->
<!--                    @mouseover="showDataOC = true;showDataOO=true"-->
<!--                    @mouseout="showDataOC = false;showDataOO=false">-->
<!--                    <VCardTitle class="text-h6">-->
<!--                      {{ wow.roomName }}-->
<!--                    </VCardTitle>-->

<!--                    <VCardSubtitle  class="text-h6">{{ wow.room_type.rm_type_code }}</VCardSubtitle>-->


<!--                    <VCardTitle class="text-h6"  v-if="wow.status ==='OCCL'||wow.status ==='OCDI'">-->
<!--&lt;!&ndash;                      <Transition name="fade"> <p v-show="showDataOC">{{ wow.guest_details.profile.first_name }}</p></Transition>&ndash;&gt;-->
<!--                    </VCardTitle>-->

<!--                    <VCardTitle class="text-h6"   v-else-if="wow.status ==='OO'">-->
<!--                      <Transition name="fade"> <p  v-show="showDataOO">{{ wow.Ord_status.end_date }} <br> {{ wow.Ord_status.notes  }}</p></Transition>-->
<!--                     </VCardTitle>-->

<!--                  </div>-->
<!--                </div>-->

<!--              </VCard>-->
            </VCol>
          </VWindowItem>
        </VWindow>
      </VCard>
    </VCol></VRow>





  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"

import moment from 'moment'
import Swal from 'sweetalert2'

export default {
  name: "Rooms_by_floorTable",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      showDataOC:false,
      showDataOO:false,
      search: '',
      Total_payment_Amount: '',
      Total_Charge_Amount: '',
      URL: window.location.origin,
      Floors:[],
      navigationTab2:'',
      navigationTab : '' ,
      wow1:'',
    }
  },


  // eslint-disable-next-line vue/component-api-style
  watch:{
    wow1(){
      console.log("this.Floors")
    },
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getFolio()
    this.getFloor()
    // this.navigation()
    // this.wow1()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
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

<style>
.v-textarea .v-field__field {
  --v-input-control-height: var(--v-textarea-control-height);
  height: 60px;
}

.z-10 {
  z-index: 10003;
}
.CardRoom{
  transform: scale(1);
  transition: 1.5s;
  height: 10%;
}
.CardRoom:hover{
  transform: scale(1.8,1.8);
  z-index: 999;
}


.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

.icon-home {
  width: 200px;
  height: 200px;
  position: relative;
  overflow: hidden;
  margin-left: 25px;
  margin-bottom: 25px;
  background-color: white;
}

.icon-home .home-chimney {
  width: 12%;
  height: 18%;
  position: absolute;
  left: 20%;
  top: 21%;
  background-color: #000;
  border-bottom-left-radius: 10%;
  border-bottom-right-radius: 10%;
}

.icon-home .home-roof-container {
  width: 60%;
  height: 60%;
  position: absolute;
  left: 50%;
  margin-left: -30%;
  top: 20%;
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
  overflow: hidden;
}

.icon-home .home-roof-left {
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: #000;
  left: -84%;
}

.icon-home .home-roof-right {
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: #000;
  top: -84%;
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  -ms-transform: rotate(90deg);
  transform: rotate(90deg);
}

.icon-home .home-body {
  width: 60%;
  height: 70%;
  position: absolute;
  left: 50%;
  margin-left: -30%;
  bottom: 10%;
  background-color: #000;
}

.icon-home .home-roof-cut-left {
  width: 60%;
  height: 70%;
  position: absolute;
  left: -8%;
  bottom: 55%;
  background-color: white;
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

.icon-home .home-roof-cut-right {
  width: 60%;
  height: 70%;
  position: absolute;
  right: -8%;
  bottom: 55%;
  background-color: white;
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

.icon-home .home-door {
  width: 22%;
  height: 25%;
  position: absolute;
  left: 50%;
  margin-left: -11%;
  bottom: 00%;
  background-color: white;
  border-radius: 15%;
}
</style>
