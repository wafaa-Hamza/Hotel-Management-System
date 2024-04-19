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
              <svg viewBox="60 60">

                <g>

                  <rect   x="20" y="40" rx="10" ry="10px" width="150" height="110" :fill="wow.color_status" :stroke="wow.color_status" stroke-width="1" />

                  <rect   x="35" y="55" rx="7" ry="7" width="100" height="80" fill="white" stroke="black" stroke-width="1"   />
                  <text x="45" y="80" font-size="10" font-family="Arial" fill="black" stroke="black" > {{ wow.roomName }} </text>
                  <text x="80" y="110" font-size="15" font-family="Arial" fill="black" stroke="black" style="text-align: center" > {{ wow.room_type.rm_type_code }}</text>

                  <circle class="rotator-one" cx="150" cy="70" r="8" fill="white"/>
                  <circle class="rotator-two" cx="150" cy="95" r="8" fill="white"/>
                  <line class="speaker-one" x1="142" y1="118" x2="160" y2="118" stroke="white" stroke-width="3" stroke-linecap="round"/>
                  <line class="speaker-two" x1="142" y1="125" x2="160" y2="125" stroke="white" stroke-width="3" stroke-linecap="round"/>
                  <line class="antena-one" x1="85" y1="40" x2="75" y2="20" :stroke="wow.color_status" stroke-width="4" stroke-linecap="round"/>
                  <line class="antena-two" x1="87" y1="40" x2="95" y2="12" :stroke="wow.color_status" stroke-width="4" stroke-linecap="round"/>
                </g>
              </svg>

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
  name: "Rooms_by_floorTable_tv",

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
