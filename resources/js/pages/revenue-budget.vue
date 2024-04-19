

<template>
  <div>

    <Breadcrumb></Breadcrumb>
    <div class="scrollable-div">
      <VCard width="1900">
        <VCardText>
          <VCol cols="12" ms="6" md="2" class="mb-5">
            <VCombobox v-model="year" label="Year" :items="years" />
          </VCol>
          <div v-for="(ledger, index) in ledgers" :key="ledger.id" class="text-field-container">
            <span class="mt-2">{{ ledger.name }}</span>
            <VTextField v-model="value[index]" :label="$t('Wanted total')" @update:modelValue="updateWantedTotal(index)"
              clearable />
            <VTextField v-model="jan[index]" :label="$t('January')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="feb[index]" :label="$t('February')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="mar[index]" :label="$t('March')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="apr[index]" :label="$t('April')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="may[index]" :label="$t('May')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="june[index]" :label="$t('June')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="july[index]" :label="$t('July')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="aug[index]" :label="$t('August')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="sep[index]" :label="$t('September')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="oct[index]" :label="$t('October')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="nov[index]" :label="$t('November')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="dec[index]" :label="$t('December')" @update:modelValue="updatetotal(index)" />
            <VTextField v-model="total[index]" :label="$t('Total')" readonly />
          </div>
          <VBtn class="mt-2 mb-5 mr-3" @click="submitRevenue" v-if="$can('edit', 'budget')"> {{ $t('submit') }} </VBtn>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<script>
import { useTaxesStore } from "@/stores/TaxesStore"
import { useLedgerStore } from "@/stores/LedgerStore"
import { mapStores, mapActions } from "pinia"
import axios from "@axios"
import Swal from "sweetalert2"
import moment from "moment"

export default {
  name: "RevenueBudget",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      value: [],
      years: [
        "2023",
        "2024",
        "2025",
        "2026",
        "2027",
        "2028",
        "2029",
        "2030",
        "2031",
        "2032",
        "2033",
        "2034",
        "2035",
        "2036",
        "2037",
        "2038",
        "2039",
        "2040",
        "2041",
        "2042",
        "2043",
      ],
      year: moment().format("YYYY"),
      budgets: [],
      search: "",
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      values: [],
      ledgerArray: [],
      jan: [],
      feb: [],
      mar: [],
      apr: [],
      may: [],
      june: [],
      july: [],
      aug: [],
      sep: [],
      oct: [],
      nov: [],
      dec: [],
      total: [],
    }
  },

  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  computed: {
    ...mapStores(useTaxesStore, useLedgerStore),
    ledgers() {
      return this.ledgerStore.ledgers
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {
    year() {
      this.clear()
      this.getbudget()
    },
  },
  // eslint-disable-next-line vue/component-api-style
  created() {
    this.getLedgers()
    this.getbudget()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    updateWantedTotal(index) {
      if (this.value[index] == '' || this.value[index] == null) {
        this.resetSpecificBudjet(index)
      }
      else {
        let each_month_value = (this.value[index] / 12).toFixed(2)
        this.jan[index] = each_month_value
        this.feb[index] = each_month_value
        this.mar[index] = each_month_value
        this.apr[index] = each_month_value
        this.may[index] = each_month_value
        this.june[index] = each_month_value
        this.july[index] = each_month_value
        this.aug[index] = each_month_value
        this.sep[index] = each_month_value
        this.oct[index] = each_month_value
        this.nov[index] = each_month_value
        this.dec[index] = each_month_value
        this.updatetotal(index)
      }
    },
    resetSpecificBudjet(index) {
      this.jan[index] = this.budgets[index].one.toFixed(2)
      this.feb[index] = this.budgets[index].tow.toFixed(2)
      this.mar[index] = this.budgets[index].three.toFixed(2)
      this.apr[index] = this.budgets[index].four.toFixed(2)
      this.may[index] = this.budgets[index].five.toFixed(2)
      this.june[index] = this.budgets[index].six.toFixed(2)
      this.july[index] = this.budgets[index].seven.toFixed(2)
      this.aug[index] = this.budgets[index].eight.toFixed(2)
      this.sep[index] = this.budgets[index].nine.toFixed(2)
      this.oct[index] = this.budgets[index].ten.toFixed(2)
      this.nov[index] = this.budgets[index].eleven.toFixed(2)
      this.dec[index] = this.budgets[index].twelve.toFixed(2)
      this.updatetotal(index)
    },
    resetBudgets() {
      for (let index = 0; index < this.budgets.length; index++) {
        this.jan[index] = this.budgets[index].one.toFixed(2)
        this.feb[index] = this.budgets[index].tow.toFixed(2)
        this.mar[index] = this.budgets[index].three.toFixed(2)
        this.apr[index] = this.budgets[index].four.toFixed(2)
        this.may[index] = this.budgets[index].five.toFixed(2)
        this.june[index] = this.budgets[index].six.toFixed(2)
        this.july[index] = this.budgets[index].seven.toFixed(2)
        this.aug[index] = this.budgets[index].eight.toFixed(2)
        this.sep[index] = this.budgets[index].nine.toFixed(2)
        this.oct[index] = this.budgets[index].ten.toFixed(2)
        this.nov[index] = this.budgets[index].eleven.toFixed(2)
        this.dec[index] = this.budgets[index].twelve.toFixed(2)
        this.updatetotal(index)
      }
    },
    ...mapActions(useLedgerStore, ["getLedgers"]),
    clear() {
      this.jan = []
      this.feb = []
      this.mar = []
      this.apr = []
      this.may = []
      this.june = []
      this.july = []
      this.aug = []
      this.sep = []
      this.oct = []
      this.nov = []
      this.dec = []
      this.total = []
    },
    updatetotal(index) {
      const janValue = parseFloat(this.jan[index]) || 0
      const febValue = parseFloat(this.feb[index]) || 0
      const marValue = parseFloat(this.mar[index]) || 0
      const aprValue = parseFloat(this.apr[index]) || 0
      const mayValue = parseFloat(this.may[index]) || 0
      const juneValue = parseFloat(this.june[index]) || 0
      const julyValue = parseFloat(this.july[index]) || 0
      const augValue = parseFloat(this.aug[index]) || 0
      const sepValue = parseFloat(this.sep[index]) || 0
      const octValue = parseFloat(this.oct[index]) || 0
      const novValue = parseFloat(this.nov[index]) || 0
      const decValue = parseFloat(this.dec[index]) || 0

      this.total[index] =
        janValue +
        febValue +
        marValue +
        aprValue +
        mayValue +
        juneValue +
        julyValue +
        augValue +
        sepValue +
        octValue +
        novValue +
        decValue

      this.total[index] = parseFloat(this.total[index]).toFixed(0)
    },
    submitRevenue() {
      const revenues = []
      for (let index = 0; index < this.ledgers.length; index++) {
        revenues.push({
          ledger_id: this.ledgers[index].id,
          year: this.year,
          one: this.jan[index],
          tow: this.feb[index],
          three: this.mar[index],
          four: this.apr[index],
          five: this.may[index],
          six: this.june[index],
          seven: this.july[index],
          eight: this.aug[index],
          nine: this.sep[index],
          ten: this.oct[index],
          eleven: this.nov[index],
          twelve: this.dec[index],
        })
      }

      axios
        .put(
          `${window.location.origin}/api/updateBudget`,
          {
            budgets: revenues,
          },
          {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("accessToken"),
            },
          }
        )
        .then((res) => {
          this.insertAlert("budget has been set successfully")
          this.getbudget()
        })
        .catch((err) => {
          console.log(err)
          this.DangerAlert("please enter all data correctly")
        })
    },
    getbudget() {
      axios
        .get(`${window.location.origin}/api/budget/${this.year}`, {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("accessToken"),
          },
        })
        .then((res) => {
          if (res.data != null) {
            this.budgets = res.data
            this.resetBudgets()
          } else {
            this.budgets = null
          }
        })
        .catch((err) => {
          console.log(err)
        })
    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      })
    },
  },
}
</script>

<style scoped>
.filter {
  display: flex;
}

.text-field-container {
  display: flex;
  flex-wrap: wrap;
}

.text-field-container>* {
  flex: 0 0 110px;
  /* Adjust the width as per your requirements */
  margin-right: 10px;
  /* Adjust the spacing between fields */
  margin-bottom: 30px;
  /* Adjust the vertical spacing if needed */
}

.table-container {
  overflow-y: auto;
}

.scrollable-div {
  overflow: auto;
  /* Enable both vertical and horizontal scrolling if needed */
  /* or */
  overflow-x: auto;
  /* Enable horizontal scrolling only */
  /* or */
  overflow-y: auto;
  /* Enable vertical scrolling only */

  /* Add additional styling as needed */
}

table td {
  padding-right: 20px;
  /* Adjust the padding as needed */
}

table tr+tr td {
  padding-top: 20px;
  /* Adjust the padding as needed */
}
</style>


<route lang="yaml">
  meta:
    action: view
    subject: budget
  </route>
