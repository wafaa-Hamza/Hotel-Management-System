
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

              <svg version="1.1" id="ComputerIcon" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">

  <symbol id="twitter" viewBox="0 0 40 40">
    <path x="35.31" y="44.138" d="M32 7.075c-1.175 0.525-2.444 0.875-3.769 1.031 1.356-0.813 2.394-2.1 2.887-3.631-1.269 0.75-2.675 1.3-4.169 1.594-1.2-1.275-2.906-2.069-4.794-2.069-3.625 0-6.563 2.938-6.563 6.563 0 0.512 0.056 1.012 0.169 1.494-5.456-0.275-10.294-2.888-13.531-6.862-0.563 0.969-0.887 2.1-0.887 3.3 0 2.275 1.156 4.287 2.919 5.463-1.075-0.031-2.087-0.331-2.975-0.819 0 0.025 0 0.056 0 0.081 0 3.181 2.263 5.838 5.269 6.437-0.55 0.15-1.131 0.231-1.731 0.231-0.425 0-0.831-0.044-1.237-0.119 0.838 2.606 3.263 4.506 6.131 4.563-2.25 1.762-5.075 2.813-8.156 2.813-0.531 0-1.050-0.031-1.569-0.094 2.913 1.869 6.362 2.95 10.069 2.95 12.075 0 18.681-10.006 18.681-18.681 0-0.287-0.006-0.569-0.019-0.85 1.281-0.919 2.394-2.075 3.275-3.394z"></path>
  </symbol>

                <!-- foot    -->
                <path class="foot" :fill="wow.color_status" d="M370.759,503.172H141.241c-9.754,0-17.655-7.901-17.655-17.655l0,0
                                                                c0-9.754,7.901-17.655,17.655-17.655h229.517c9.754,0,17.655,7.901,17.655,17.655l0,0
                                                                C388.414,495.272,380.513,503.172,370.759,503.172z" />

                <!-- stand    -->
                <path class="stand" :fill="wow.color_status" d="M300.138,397.241v-17.655h-88.276v17.655c0,39.036-13.93,70.621-52.966,70.621h194.207
	C314.068,467.862,300.138,436.277,300.138,397.241z" />

                <!-- bottom-screen    -->
                <path class="bottom-screen" :fill="wow.color_status" d="M485.508,397.241H26.492C11.864,397.241,0,385.377,0,370.75v-52.957h512v52.957
	C512,385.377,500.136,397.241,485.508,397.241z" />

                <!-- buttons?    -->
                <path class="buttons" fill="black" d="M282.483,370.759h-52.966c-4.873,0-8.828-3.955-8.828-8.828s3.955-8.828,8.828-8.828h52.966
	c4.873,0,8.828,3.955,8.828,8.828S287.356,370.759,282.483,370.759z" />

                <!-- screen   -->
                <path class="screen" :fill="wow.color_status" d="M441.379,8.828H70.621H26.492C11.864,8.828,0,20.692,0,35.319v291.302h70.621h132.414h105.931
	h132.414H512V35.319c0-14.627-11.864-26.492-26.492-26.492H441.379z" />

                <!--  Left side screen  -->
                <g class="left-screen">
    <!--  icon-bg  -->
    <rect class="screen-background" x="35.31" y="44.138" fill="white" width="220.69" height="282.483" />



  </g>

                <!-- right-screen    -->
                <g class="right-screen">
    <!--  code background -->
    <rect class="screen-background" x="238.345" y="44.138" fill="white" width="238.345" height="282.483" />

                  <text x="100" y="150" font-size="40" font-family="Arial" fill="black" stroke="black"  > {{ wow.roomName }} </text>

                  <text x="230" y="250" font-size="45" font-family="Arial" fill="black" stroke="black" style="text-align: center" > {{ wow.room_type.rm_type_code }}</text>

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

<style scoped>
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

svg {
  width: 15vmin;
  height: 15vmin;
}

.left-screen .screen-background {
  fill: white !important;
}

#twitter {
  fill: #1ca1f2;
}

.legal {
  text-align: center;
  position: absolute;
  bottom: 15px;
  color: #fff;
  font-family: Arial;
}
.legal a {
  color: #fff;
}
</style>
