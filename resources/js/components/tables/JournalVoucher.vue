<script setup>
const isDialogVisible = ref(false)
</script>
<template>
  <div style="height: 1000px">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard style="display: flex;justify-content: space-between">

      <br>

      <VCol style="float: right;display: flex;justify-content: space-between;align-content: space-between;"  md="8" sm="8">

        <VCol >
          <VTextField
            v-model="voucher_type"
            :label="$t('voucher type')"
          />



        </VCol>
        <VCol>

          <AppDateTimePicker
            v-model="voucher_date"
            :placeholder="$t('voucher date')"
            :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"

          />

        </VCol>


      </VCol>
      <VCol style="float: right;display: flex;justify-content: space-between;align-content: space-between;" md="4" sm="4">

        <VCol>


          <VTextarea
            v-model="Descriptions"
            :label="$t('Descriptions')"
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
                        {{ $t('Account Code') }}
                      </th>
                      <th class="text-center">
                        {{ $t('Debit') }}
                      </th>
                      <th class="text-center">

                        {{ $t('Credit') }}
                      </th>
                      <th class="text-center">

                        {{ $t('Cost') }}
                      </th>
                      <th class="text-center">

                        {{ $t('Reference') }}
                      </th>
                      <th class="text-center">

                        {{ $t('Description') }}
                      </th>
                      <th class="text-center">

                        {{ $t('Account Name') }}
                      </th>
                      <th class="text-right">

                        {{ $t('Remove') }}
                      </th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr
                      class="text-center"
                      v-for="(input,ip) in inputs" :key="ip"
                    >

                      <td>
                      <VTextField  :label="$t('account_id')"  v-model="input.account_id"    />
                      </td>
                      <td>
                      <VTextField  :label="$t('Debit')" v-model="input.debit"  />
                      </td>
                      <td>
                      <VTextField   :label="$t('Credit')" v-model="input.credit"/>
                      </td>
                      <td>
                      <VTextField  :label="$t('Cost')" v-model="input.Cost"  />
                      </td>
                      <td>
                      <VTextField   :label="$t('Reference')" v-model="input.reference"  />
                      </td>
                      <td>
                      <VTextField   :label="$t('Description')" v-model="input.descriptions"    />
                      </td>
                      <td>
                      <VTextField  :label="$t('jv_srl')" v-model="input.jv_srl"   />
                      </td>

                    <td style="display: flex;justify-content: flex-end;align-items: center">
                     <VBtn class="fas fa-plus-circle" @click="add(ip)" v-show="ip == inputs.length-1">
                       {{ $t('Add') }}</VBtn>
                      <VBtn class="fas fa-minus-circle" @click="remove(ip)" v-show="ip || ( !ip && inputs.length > 1)" style="margin-left: 15px">{{ $t('Delete')}}</VBtn>
                      </td>
                    </tr>
                    </tbody>
                  </VTable>




          </VCard>

          <VCard style="width: 14%;height: 15%">
            <VCol>


              <VTextField
                v-model="total_debit"
                :label="$t('Total Debit')"
              />

              <br>
              <br>
              <VTextField
                v-model="total_credit"
                :label="$t('Total Credit')"

              />

            </VCol>
        </VCard>
      </div>
    <br><br>
    <VBtn @click="fetch" style="float: right">{{ $t('fetch')}}</VBtn>
    <VBtn @click="GoToEditPage">{{ $t('fetch all voucher') }}</VBtn>


  </div>
</template>

<script>
import 'jspdf-autotable'
import axios from "@axios"

export default {


  // eslint-disable-next-line vue/component-api-style
  data () {
    return {
      URL: window.location.origin,
      search_guests: [],
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
      inputs: [
        {
          jv_srl: '',
          reference: '',
          Cost: '',
          credit: '',
          descriptions: '',
          debit: '',
          account_id: '',
        }
      ],
      rows:1,
      ip:[],
      ips:[],
      vouch:[],
    }

  },
  mounted() {
    this.journalVoucher()
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
        title: 'Data Added successfully',
        color: 'green',
        timer: 5000,
      })
    },

    remove(index) {
      this.rows--
      this.inputs.splice(index, 1);
    },
    add(index) {
      this.rows++
      this.inputs.push({ jv_srl: '' , reference: '',Cost: '',credit: '',descriptions: '',debit: '',account_id: ''});

    },


    async  fetch(){
        // console.log('inputs',this.inputs),
        // console.log('voucher_type',this.voucher_type),
        // console.log('voucher_date',this.voucher_date),
        // console.log('Descriptions',this.Descriptions),
        // console.log('total_debit',this.total_debit),
        // console.log('total_credit',this.total_credit)
      try {
          const user = await axios.post(

            `${this.URL}/api/journalVoucher`,
            {

              details:this.inputs,
              jv_type_id: this.voucher_type,
              jv_date: this.voucher_date,
              mdescription: this.Descriptions,
              total_debit: this.total_debit,
              total_credit: this.total_credit,
            },
          )

        this.insertAlert()
      } catch(e) {
        console.log(e)
      }
    },

    journalVoucher(){
      axios
        .get(`${this.URL}/api/journalVoucher`)
        .then(res => {

          this.vouch = res.data.data

        })
    },

    async  GoToEditPage(){

      await this.$router.push({ name: `voucher-AllVoucher` })

    },
  },


}
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
