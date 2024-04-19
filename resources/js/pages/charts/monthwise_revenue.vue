<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard height="600">
      <VCardTitle>
        <VRow class="mb-5">
          <VCol cols="12" ms="6" md="10">
            {{ $t('Monthwise Revenue') }}
          </VCol>
          <VCol cols="12" ms="6" md="2">
            <VCombobox v-model="year"  :label="$t('Year')" :items="years" />
          </VCol>
        </VRow>
      </VCardTitle>
      <VCardText dir="rtl">
        <apexchart type="bar" height="500" :options="chartOptions" :series="series"></apexchart>
      </VCardText>
    </VCard>
  </div>
</template>


<script>
import axios from "@axios";
import Swal from "sweetalert2";
import moment from "moment";
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "monthwise-revenue",
  components: {
    apexchart: VueApexCharts,
  },
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
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
      series: [],
      chartOptions: {
        chart: {
          type: 'bar',
          height: '100%',

        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '90%',
            endingShape: 'rounded',
          },
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: undefined,
        },
        stroke: {
          show: true,
          width: 3,
          colors: ['transparent'],
        },
        xaxis: {
          categories: this.$i18n.locale === 'en' ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] : ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سيبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'],
        },
        yaxis: {
          max: 5000,
          opposite: this.$i18n.locale === 'en' ? false : true,
        },
        fill: {
          opacity: 1,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$" + val
            },
          },
        },
      },



    };
  },

  // eslint-disable-next-line vue/component-api-style, vue/order-in-components
  computed: {
    // ...mapStores(useTaxesStore, useLedgerStore),
    ledgers() {
      return this.ledgerStore.ledgers;
    },
  },
  // eslint-disable-next-line vue/component-api-style
  watch: {

    year() {
      this.getbudget();
    },
  },
  // eslint-disable-next-line vue/component-api-style
  created() {
    // this.getLedgers();
    this.getbudget();

  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    // ...mapActions(useLedgerStore, ["getLedgers"]),


    getbudget() {
      axios
        .post(`${window.location.origin}/api/monthWise_revenue`, { year: this.year }, {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("accessToken"),
          },
        })
        .then((res) => {
          if (res.data != null) {
            this.budgets = res.data.MonthWise_revenue;
          } else {
            this.budgets = null;
          }
          this.series = []
          this.transformData()
          console.log(this.budgets);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    transformData() {
      for (let index = 0; index < this.budgets.length; index++) {
        let data = []
        let y = 0
        for (let i = 1; i <= 12; i++) {
          const month = i.toString().padStart(2, '0');
          if (this.budgets[index].day_close_sales_led_cat[y].Month != month) {
            data.push(0)
          }
          else {
            data.push(this.budgets[index].day_close_sales_led_cat[y].amount)
            if (y < this.budgets[index].day_close_sales_led_cat.length - 1)
              y++
          }
        }
        if (this.$i18n.locale === 'en') {
          this.series.push({
            name: this.budgets[index].name,
            data: data,
          })
          console.log(this.$i18n.locale)
        }
        else {
          this.series.push({
            name: this.budgets[index].name_loc,
            data: data,
          })
        }


      }
      console.log(this.series)

    },
    insertAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      });
    },
    DangerAlert(message) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 2000,
      });
    },
  },
};
</script>
