<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
</script>

<template>
  <div>


      <VIcon @click="getCashierCloser" icon="tabler-wallet" size="30" color="green" />
      <VMenu activator="parent">
        <VList>
          <VListItem>
            <VListItemTitle  style="font-size: larger;" class="mt-5">
              {{ $t('Cash') + ': ' }} <span style="color: green;"> {{ total_cash }}</span>
            </VListItemTitle>
            <VListItemTitle style="font-size: larger;" class="mt-5">
              {{ $t('Cash out') + ': ' }}<span style="color: red;"> {{ total_cash_out }}</span>
            </VListItemTitle>
            <VListItemTitle style="font-size: larger;" class="mt-5">
              {{ $t('Cards') + ': ' }}<span style="color: blue;"> {{ total_card }}</span>
            </VListItemTitle>
            <VListItemTitle style="font-size: larger;" class="mt-5">
              {{ $t('Transfer') + ': ' }}<span style="color: orange;"> {{ total_transfer }}</span>
            </VListItemTitle>
            <VListItemTitle style="font-size: larger;" class="mt-5">
              {{ $t('Total cash on hand') + ': ' }}<span v-if="(total_cash + total_cash_out) > 0" style="color: green;">
                {{ (total_cash + total_cash_out) }}</span>
              <span v-else style="color: red;"> {{ (total_cash + total_cash_out) }}</span>
            </VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
    
  </div>
</template>

<script>
import LogoutButt from "@/auth_butt/LogoutButt.vue"
import axios from '@axios'

export default {
  components: {
    // eslint-disable-next-line vue/no-unused-components
    LogoutButt,
  },

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      SettingData: null,
      cashier_closer: [],
      total_cash: null,
      total_cash_out: null,
      total_card: null,
      total_transfer: null,
    }
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getCashierCloser() {
      const SettingData = localStorage.getItem('settings')
      const userData = localStorage.getItem('userData')
      let user
      if (SettingData) {
        this.SettingData = JSON.parse(SettingData)
      }
      if (userData) {
        user = JSON.parse(userData)
      }
      console.log(user)
      await axios.post(`${window.location.origin}/api/Tot_Payment_Of_Cashier_Summary`,
        {
          user_id: user?.user.id,
          Date_From: this.SettingData.hotel_date,
          Date_To: this.SettingData.hotel_date,
        })
        .then(res => {
          this.cashier_closer = res.data.Total_Summary_Payment[0]
          this.transformTransactions(this.cashier_closer?.transactions)
        })
        .catch(err => {
          console.log(err)
        })
    },
    transformTransactions(transactions) {
      console.log(transactions)
      for (let index = 0; index < transactions.length; index++) {
        if (transactions[index].payment_type.payment_modes_id == 1 && transactions[index].payment_type.type == 'CR') {
          this.total_cash = transactions[index].total_payment_amount
        }
        if (transactions[index].payment_type.payment_modes_id == 1 && transactions[index].payment_type.type == 'DR') {
          this.total_cash_out = transactions[index].total_payment_amount
        }
        if (transactions[index].payment_type.payment_modes_id == 2) {
          this.total_card = transactions[index].total_payment_amount
        }
        if (transactions[index].payment_type.payment_modes_id == 3) {
          this.total_transfer = transactions[index].total_payment_amount
        }
      }
    },
  },
}
</script>

<style>
.jjj {
  font-size: 110%;
}
</style>
