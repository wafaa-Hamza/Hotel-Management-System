<script setup>


const radioGroup = ref(1)
const rules = [value => value !== 'manual' ? true : 'Select The Payment As You Want']

</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>

    <VCard>

      <br>

      <div style="float: right;width: 100%;display: flex;justify-content: space-between;align-content: space-between">

        <VCol cols="6" >
          <VTextField
            :placeholder="$t('Receipt Voucher No')"
            readonly
          />
          <br>
          <br>
          <VCombobox
            v-model="Company_id"
            :items="company_profiles"
            :label="$t('Company')"
            item-title="company_name"

          >
            <template #selection="{ item }">
              <VChip>
                {{ item.title }}
              </VChip>
            </template>
          </VCombobox>

          <br>
          <br>
          <VCombobox
            v-model="Way_id"
            :items="payments"
            :label="$t('Way of payment*')"
            item-title="name"
          >
            <template #selection="{ item }">
              <VChip>
                {{ item.title }}
              </VChip>
            </template>
          </VCombobox>

          <br>
          <br>
          <VTextField
            :label="$t('Ref no.')"
            v-model="ref_no"

          />

        </VCol>


        <VCol cols="6" >

          <AppDateTimePicker
            :config="{altInput: true, altFormat: 'Y-m-d l', dateFormat: 'Y-m-d'}"
            :placeholder="$t('Date')"
            v-model="date"
          />





          <br>
          <VTextarea
            v-model="description"
            :label="$t('Descriptions')"
            rows="7"
          />
          <br>


          <VTextField
            v-model="amount"
            :label="$t('Amount')"
          />

        </VCol>

      </div>

    </VCard>

    <VCol style="display: flex;align-items: flex-end;justify-content: right" cols="12">
      <VBtn @click="Add" >
        {{$t('Submit')}}
      </VBtn>
    </VCol>

  </div>
</template>

<script>
import axios from "@axios"


export default {
  name: "Company_Credit_Note",


  // eslint-disable-next-line vue/component-api-style
  data () {
    return {
      URL: window.location.origin,
      list_view: [],
      date: '',
      trans_no: '',
      in: '',
      out: '',
      description: '',
      Way_id: [],
      payments: [],
      amount: '',
      start_dates: '',
      end_dates: '',
      ref_no: '',
      voucher_no: null,
      user_id: null,
      payment_type_id: [],
      company_profiles: [],
      room_no: null,
      Company_id: [],
      dataCount: 0,
      tot_sum_charge: 0,
      radio:['Auto Earlier to Recent','Auto Recent to Earlier','manual']
    }

  },
  mounted() {
    this.GetCompanyProfiles()
    this.Getpayment()
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

    GetCompanyProfiles() {
      axios
        .get(`${window.location.origin}/api/companyProfile`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
            'Accept': 'application/json',
          },
        })
        .then(res => {
          this.company_profiles = res.data
          console.log(res.data)
        }).catch(
        err => {
          console.log(err)
        },
      )
    },
    Getpayment() {
      axios
        .get(`${window.location.origin}/api/payment`, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
            'Accept': 'application/json',
          },
        })
        .then(res => {
          this.payments = res.data
          console.log(res.data)
        }).catch(
        err => {
          console.log(err)
        },
      )
    },

    async  Add(){

      try {
        const user = await axios.post(
          `${this.URL}/api/adv-pay`,
          {
            company_id: this.Company_id.id,
            trans_date: this.date,
            ref_no: this.ref_no,
            description: this.description,
            debit_amount: 0,
            credit_amount: this.amount,
            payment_type_id: this.Way_id.id,
          },
        )


        this.insertAlert()
      } catch(e) {
        console.log(e)
      }
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
