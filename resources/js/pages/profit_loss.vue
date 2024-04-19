<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <VCard>
      <br>
      <VCol>
        <div style="float: right;width: 25%;display: flex;justify-content: space-between;">
          <VCol
            cols="6"

            md="4"
          >
            <VDialog
              v-model="dialog"
              fullscreen
            >
              <template #activator="{ props }">

                <VBtn v-bind="props" style="float: right" @click="AddCrDr">
                  Setup
                </VBtn>

              </template>
              <VCard>

                <VCardTitle>
                  <span class="text-h5">PL/BS Setup ---> PL</span>
                </VCardTitle>
                <VCol cols="12" style="display: flex;justify-content: space-around;align-content: space-around;">

                  <VCol cols="5">
                    <div style="height: 700px;width: 100%;box-shadow: 0px 0px 40px 44px inset lightgray;background: ghostwhite;padding: 2%">
                      <draggable class="dragArea list-group w-full" :list="Chart"  @change="log" group="group"  ghost-class="ghost" style="height: 100%;width: 100%;" animation="200">

                        <p v-for="data in Chart" style="width: 100%;height: 12%;box-shadow: 0px 6px 10px 0px;margin: 2% auto;background: #ececec;cursor: pointer;display: flex;justify-content: center;align-items: center;border-radius: 10px;font-weight: bold" :key="data.id">{{data.label}}</p>
                      </draggable>
                    </div>
                  </VCol>

                  <VCol cols="5" style="height: 700px;width: 100%;">
                    <h2 style="text-align: center">Credit</h2>
                    <div style="height: 46%;box-shadow: lightgray 0px 0px 18px 8px inset;background: ghostwhite;padding: 2%;overflow-y: scroll">
                      <draggable class="dragArea list-group w-full" :list="result_cr" @change="log" group="group"  style="height: 100%;width: 100%;" animation="200">

                        <p v-for="data in result_cr" style="width: 100%;height: 10%;box-shadow: 0px 6px 10px 0px;margin: 2% auto;background: #ececec;cursor: pointer;display: flex;justify-content: center;align-items: center;border-radius: 10px;font-weight: bold" :key="data">{{data.label}}</p>
                      </draggable>
                    </div>
                    <br />
                    <h2 style="text-align: center">Debit</h2>
                    <div  style="height: 46%;box-shadow: lightgray 0px 0px 18px 8px inset;background: ghostwhite;padding: 2%;overflow-y: scroll">
                      <draggable class="dragArea list-group w-full" :list="result_dr" @change="log" group="group"  style="height: 100%;width: 100%;" animation="200">

                        <p v-for="data in result_dr" style="width: 100%;height: 10%;box-shadow: 0px 6px 10px 0px;margin: 2% auto;background: #ececec;cursor: pointer;display: flex;justify-content: center;align-items: center;border-radius: 10px;font-weight: bold" :key="data">{{data.label}}</p>
                      </draggable>
                    </div>
                  </VCol>

                </VCol>

                <VCol style="display: flex;justify-content: space-between">
                  <VBtn
                    @click="dialog = false"
                  >
                    Close
                  </VBtn>
                  <VBtn
                    @click="Add"
                  >
                    Save
                  </VBtn>
                </VCol>

              </VCard>
            </VDialog>
          </VCol>
        </div>
        <div style="float: left;width: 75%;display: flex;justify-content: space-around;">
          <VCol>
            <AppDateTimePicker
              v-model="start_dates"
              :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
               
              :placeholder="$t('Start date')"
            />
          </VCol>
          <VCol>
            <AppDateTimePicker
              v-model="end_dates"
               :placeholder="$t('end date')"
              :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
            />
          </VCol>

          <VCol>
            <VBtn @click="getDetails"  >
              Refresh
            </VBtn>
          </VCol>
        </div>
      </VCol>
      <br><br><br>
    </VCard>
    <br>
    <VCol cols="12" style="display: flex;justify-content: space-between">
      <VCard
        v-show="Visability"
        style="padding-bottom:2%;width: 47% "
      >
        <br>
        <div
          id="element-to-print"
          style="padding: 0 2%"
        >

          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="12"
            >
              <VCol
                style="border: 1px solid black;border-radius: 10px;text-align: center"
                cols="12"
                sm="12"
                md="12"
              >

                <h2>  {{ $t('Revenue') }}</h2>

              </VCol>
              <br>

              <VDivider />
              <VCol
                style="padding: 2px"
                cols="12"
                sm="12"
                md="12"
              >
                <VTable>
                  <thead>
                  <tr>
                    <th class="text-left">
                        {{ $t('Description') }}
                    </th>
                    <th class="text-left">
                        {{ $t('Amount') }}
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                    v-for="item in Credit"
                    :key="item.id"
                  >
                    <td>{{ item.name }}</td>
                    <td>{{ item.balance.Balance }}</td>
                  </tr>

                  </tbody>
                </VTable>
              </VCol>
            </VCol>
          </VRow>
          <h1
            v-if="dataCount == 0"
            style="text-align: center;box-shadow: 5px 4px 8px 0px;padding: 1%"
          >
              {{ $t('No Data Selected') }}
          </h1>

        </div>
      </VCard>
      <VCard
        v-show="Visability"
        style="padding-bottom:2%;width: 47% "
      >
        <br>
        <div
          id="element-to-print"
          style="padding: 0 2%"
        >

          <VRow>
            <VCol
              cols="12"
              sm="12"
              md="12"
            >
              <VCol
                style="border: 1px solid black;border-radius: 10px;text-align: center"
                cols="12"
                sm="12"
                md="12"
              >

                <h2>  {{ $t('Expenses') }}</h2>

              </VCol>
              <br>

              <VDivider />
              <VCol
                style="padding: 2px"
                cols="12"
                sm="12"
                md="12"
              >
                <VTable>
                  <thead>
                  <tr>
                    <th class="text-left">
                        {{ $t('Description') }}
                    </th>
                    <th class="text-left">
                        {{ $t('Amount') }}
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                    v-for="item in Debit"
                    :key="item.id"
                  >
                    <td>{{ item.name }}</td>
                    <td>{{ item.balance.Balance }}</td>
                  </tr>

                  </tbody>
                </VTable>
              </VCol>
            </VCol>
          </VRow>
          <h1
            v-if="dataCount == 0"
            style="text-align: center;box-shadow: 5px 4px 8px 0px;padding: 1%"
          >
              {{ $t('No Data Selected') }}
          </h1>

        </div>
      </VCard>

    </VCol>
    <VCol cols="12" style="display: flex;justify-content: space-around">
      <div cols="3">Total => <b style="box-shadow: 0px 5px 1px 0px;text-align: center;">254</b></div>
      <div cols="3" > <b style="box-shadow: 0px 5px 1px 0px;text-align: center">75432,324</b></div>
      <div cols="3">Total => <b style="box-shadow: 0px 5px 1px 0px;text-align: center">75432,324</b></div>
    </VCol>

  </div>
</template>

<script>
import axios from "@axios"
import { defineComponent } from 'vue'
import { VueDraggableNext } from 'vue-draggable-next'
export default defineComponent({
  components: {
    draggable: VueDraggableNext,
  },

  // eslint-disable-next-line vue/component-api-style
  data () {
    return {
      enabled: true,
      dragging: false,
      dialog:false,
      Visability: true,
      URL: window.location.origin,
      Prof_Loss: [],
      date: '',
      in: '',
      out: '',
      start_dates: '',
      end_dates: '',
      company_id: null,
      dataCount: 0,
      tot_sum_charge: 0,
      country: [],
      todo1:[],
      CrDraggable:[],
      DrDraggable:[],
      Chart:[],
      CrArray:[],
      DrArray:[],
      Credit:[],
      DrID:[],
      CrID:[],
      CrData:[],
      DrData:[],
      CrDrId:[],
      FData:[],
      UpdateChart:[],
      AllCreditBalance:null,
      result_cr:[],
      result_dr:[],
      AllCrData:[],
      AllDrData:[],
      spliced_index:[],

    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getDetails()
    this.getChart()
    this.getCrDrId()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    insertAlert() {
      const Toast = this.$swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer)
          toast.addEventListener('mouseleave', this.$swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: 'success',
        title: 'Data Moved successfully',
        color: 'green',
        timer: 5000,
      })
    },
    // log(event) {
    //   // console.log(event)
    //   this.FData = event
    //   console.log('Add',this.FData.added.element)
    //
    //   console.log('chart',this.Chart)
    //   this.UpdateChart.push(...this.Chart)
    //
    //   console.log('update',this.UpdateChart)
    // },
    getDetails() {
      axios.post(`${this.URL}/api/account-profit-loss`, {
        startDate: this.start_dates,
        endDate: this.end_dates,
      }).then(res =>(this.Prof_Loss = res.data,this.Credit = res.data.Credit,this.Debit = res.data.Debit, this.dataCount = res.data.length))
      this.Visability = true
    },

    getChart() {
      axios.get(`${this.URL}/api/ChartOfAccounts`).then(res =>(this.Chart = res.data.data))
    },
    getCrDrId(){
      axios.post(`${this.URL}/api/get-cr-dr`,{
        type:'P&L',
      }).then(res =>{
        this.CrDrId = res.data
        this.CrData=res.data.CR
        this.DrData=res.data.DR
      })
    },

    AddCrDr(){
      // console.log('CrData',this.CrData)
      // console.log('DrData',this.DrData)
      // console.log('DrDraggable',this.DrDraggable)
      //
      // console.log('CrDraggable',this.CrDraggable)


      // this.CrDraggable.push(...this.CrData)
      //
      //
      // this.DrDraggable.push(...this.DrData)


      for(let i=0;i<this.Chart.length;i++)
      {
        if( this.CrData.includes(this.Chart[i].id))
        {
          this.result_cr.push(this.Chart[i])
          this.spliced_index.push(this.Chart[i].id)
        }
         if( this.DrData.includes(this.Chart[i].id))
        {
          this.result_dr.push(this.Chart[i])
          this.spliced_index.push(this.Chart[i].id)
        }
      }
      for (let i = 0; i < this.spliced_index.length; i++) {
        this.Chart.some((ele,index) => {
          if(ele.id === this.spliced_index[i])
          {this.Chart.splice(index,1)}
        })
      }
    },

    DataId(){
      for (let CrId = 0;CrId<=this.result_cr.length-1;CrId++){

        this.AllCrData.push(this.result_cr[CrId].id)
        // this.CrDraggable.push(this.result_cr[CrId])
      }
      for (let DrId = 0;DrId<=this.result_dr.length-1;DrId++){

        this.AllDrData.push(this.result_dr[DrId].id)
        // this.DrDraggable.push(this.result_dr[DrId])
      }
    },
    async Add() {
      this.DataId()

      try {
        const user = await axios.post(`${this.URL}/api/account-customize`, {
          type: 'P&L',
          CR: this.AllCrData,
          DR: this.AllDrData,
        })

        this.dialog = false
        this.insertAlert()
        this.getDetails()
      } catch (e) {
        console.log(e)
      }
    },

  },

})
</script>

<style>
/* width */
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: mediumpurple;
  border-radius: 10px;
}
/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 112, 219, .6);  ;
}
</style>
