
<template>
  <div>


    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard style="padding: 2%">
      <br>

      <VTable
        height="650"
        fixed-header
        class="text-no-wrap"
      >
        <thead>
        <tr>
          <th class="text-uppercase">

            {{ $t('Select')}}
          </th>
          <th class="text-uppercase">

            {{ $t('Hotel Date')}}
          </th>
          <th class="text-uppercase text-center">

            {{ $t('Folio No')}}
          </th>

          <th class="text-uppercase text-center">

            {{ $t('Ledger Code')}}
          </th>
          <th class="text-uppercase text-center">

            {{ $t('Ledger Name')}}
          </th>
          <th class="text-uppercase text-center">

            {{ $t('Guest Amount')}}
          </th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="item in PrechargeData">
          <td>
            <VCheckbox
              v-model="selectedCheckbox"
              :value="item"
              @click="add(item)"
            />
          </td>
          <td>
            {{ item.hotel_date }}
          </td>
          <td class="text-center">
            {{ item.guest.id }}
          </td>

          <td class="text-center">
            {{ item.ledger.code }}
          </td>
          <td class="text-center">
            {{ item.ledger.name }}
          </td>
          <td class="text-center">
            {{ item.amount }}
          </td>
        </tr>
        </tbody>
      </VTable>

    </VCard>
    <br><br>




        <VBtn
          style="float: right"
          @click="PushData(selectedCheckbox)"
        >

          {{ $t('Excute')}}
        </VBtn>

<!--    {{para}}-->
  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"

export default {
  // eslint-disable-next-line vue/component-api-style
  data () {
    return {

      URL: window.location.origin,
      PrechargeData: [],
      Hotel_Date: '',
      dataCount: 0,
      selectedCheckbox:[],
      para: [],
      AllId: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPrechargeData()
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
        title: 'Data Moved To Transaction successfully',
        color: 'green',
        timer: 5000,
      })
    },

    getPrechargeData() {
      axios.get(`${this.URL}/api/getPrechargeDataTransfearToTransaction`).then(res =>this.PrechargeData = res.data.data)
    },

    async   PushData(get){
      this.para = get
      for (let i=0;i<this.para.length;i++){
        this.AllId.push(this.para[i].id)
      }
      await axios.post(`${this.URL}/api/storeFromPreChargeToTransaction`, {
              pre_charge_id: this.AllId
            }).then((this.insertAlert(),this.getPrechargeData()))

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
