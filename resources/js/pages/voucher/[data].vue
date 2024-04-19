<template>
  <div style="height: 1000px">

    <VCard style="display: flex;justify-content: space-between">

      <br>

      <VCol style="float: right;display: flex;justify-content: space-between;align-content: space-between;"  md="8" sm="8">

        <VCol >
          <VTextField
            v-model="voucher_type"
            label="voucher type"
          />



        </VCol>
        <VCol>

          <AppDateTimePicker
            v-model="voucher_date"
            placeholder="voucher date"
            :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"

          />

        </VCol>


      </VCol>
      <VCol style="float: right;display: flex;justify-content: space-between;align-content: space-between;" md="4" sm="4">

        <VCol>


          <VTextarea
            v-model="Descriptions"
            label="Descriptions"
            rows="1"

          />
        </VCol>


      </VCol >



    </VCard>
    <br>
    <div

      style="display: flex; justify-content: space-between"
    >
      <br>
      <VCard
        style="width: 85%;position: relative;left: 0%;padding:  1%"
      >



        <VTable>
          <thead>
          <tr>
            <th class="text-center">
              Account Code
            </th>
            <th class="text-center">
              Debit
            </th>
            <th class="text-center">
              Credit
            </th>

            <th class="text-center">
              Reference
            </th>
            <th class="text-center">
              Description
            </th>
            <th class="text-center">
              Account Name
            </th>


          </tr>
          </thead>
          <tbody>

          <tr
            class="text-center"

          >

            <td>
              <VTextField  label="Account_Code"  v-model="Account_Code"    />
            </td>
            <td>
              <VTextField  label="Debit" v-model="Debit"  />
            </td>
            <td>
              <VTextField  label="Credit" v-model="Credit"/>
            </td>

            <td>
              <VTextField  label="Reference" v-model="Reference"  />
            </td>
            <td>
              <VTextField  label="Description" v-model="Description"    />
            </td>
            <td>
              <VTextField  label="Account_Name" v-model="Account_Name"   />
            </td>
          </tr>
          </tbody>
        </VTable>




      </VCard>

      <VCard style="width: 14%;height: 15%">
        <VCol>


          <VTextField
            v-model="total_debit"
            label="Total Debit"
          />

          <br>
          <br>
          <VTextField
            v-model="total_credit"
            label="Total Credit"

          />

        </VCol>
      </VCard>
    </div>

    <VBtn @click="UpdateData()" style="float: right">Submit Update</VBtn>

  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"

export default {
  name: "[data]",
  data(){
    return{
      date: '',
      ref_no: '',
      total_debit: '',
      total_credit: '',
      voucher_type:'',
      voucher_date:'',
      Acount_codes:'',
      Financial_year:'',
      Voucher_no:'',
      Rate:'',
      Currency:'',
      period:'',
      Debit:'',
      credit:'',
      Descriptions:'',
      first_name: '',
      guest_data:[],

      URL : window.location.origin,


          Account_Name: '',
          Reference: '',
          Cost: '',
          Credit: '',
          Description: '',
          Account_Code: '',


    }
  },

  mounted(){
    this.EditData()
  },
  methods:{
    UpdateAlert() {
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
        icon: 'info',
        title: 'updateed is successfully',
        color: 'mediumpurple',
        timer: 5000,
      })
    },
    EditData(){

      axios
        .get(`${this.URL}/api/journalVoucher/${this.$route.params.data}`)
        .then(response =>{
          console.log('without 0',response)
          console.log('with 0',response.data.data[0])
            this.voucher_date = response.data.data[0].jv_date
            this.voucher_type = response.data.data[0].jv_type_id.voucher_type
            this.Descriptions = response.data.data[0].mdescription
            this.total_debit = response.data.data[0].total_debit
            this.total_credit = response.data.data[0].total_credit
            this.Account_Code = response.data.data[0].details[0].jv_srl
            this.Reference = response.data.data[0].details[0].reference
            this.Description = response.data.data[0].details[0].descriptions
            this.Credit = response.data.data[0].details[0].credit
            this.Debit = response.data.data[0].details[0].debit
            this.Account_Name = response.data.data[0].details[0].jv_srl

        })
    },

    async  UpdateData(){


      const user = await   axios.put(
        `${this.URL}/api/journalVoucher/${this.$route.params.data}`,
        {
          jv_date: this.voucher_date,
          mdescription: this.voucher_date,

          credit: this.Credit,
          reference: this.Reference,
          descriptions: this.Description,
          debit: this.Debit,
          total_debit: this.total_debit,
          total_credit: this.total_credit,


        },
      )

      this.UpdateAlert()


    },
    // BackToProfile() {
    //   router.push('/profile/Profile')
    // },
  }
}
</script>

<style scoped>

</style>
