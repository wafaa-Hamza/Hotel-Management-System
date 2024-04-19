<template>
    <div>

      <Breadcrumb  class="d-print-none" ></Breadcrumb>
        <VCard>
            <VCardTitle>
                <VRow class="mb-2">
                    <VCol cols="12" ms="6" md="10">
                      {{ $t('Monthwise Occupancy') }}
                    </VCol>
                    <VCol cols="12" ms="6" md="2">
                        <VCombobox v-model="year" label="Year"  :label="$t('Reservation number')" :items="years" />
                    </VCol>
                </VRow>
            </VCardTitle>
            <VCardText>
                <VRow>
                    <VCol cols="12" ms="6" md="10">
                        <apexchart type="bar" height="600" :options="chartOptions" :series="series"></apexchart>
                    </VCol>
                    <VCol cols="12" ms="6" md="2">
                        <VTextField v-model="jan" :label="$t('January')" readonly class="mt-2" />
                        <VTextField v-model="feb" :label="$t('February')" readonly class="mt-2" />
                        <VTextField v-model="mar" :label="$t('March')" readonly class="mt-2" />
                        <VTextField v-model="apr" :label="$t('April')" readonly class="mt-2" />
                        <VTextField v-model="may" :label="$t('May')" readonly class="mt-2" />
                        <VTextField v-model="jun" :label="$t('June')" readonly class="mt-2" />
                        <VTextField v-model="jul" :label="$t('July')" readonly class="mt-2" />
                        <VTextField v-model="aug" :label="$t('August')" readonly class="mt-2" />
                        <VTextField v-model="seb" :label="$t('September')" readonly class="mt-2" />
                        <VTextField v-model="oct" :label="$t('October')" readonly class="mt-2" />
                        <VTextField v-model="nov" :label="$t('November')" readonly class="mt-2" />
                        <VTextField v-model="dec" :label="$t('December')" readonly class="mt-2" />
                    </VCol>
                </VRow>
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
                        columnWidth: '60%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 3,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                },
                yaxis: {
                    max: 100,
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " %"
                        }
                    }
                }
            },
            jan: null,
            feb: null,
            mar: null,
            apr: null,
            may: null,
            jun: null,
            jul: null,
            aug: null,
            seb: null,
            oct: null,
            nov: null,
            dec: null,
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
            this.clear()
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
        clear()
        {
            this.jan = null
            this.feb = null
            this.mar = null
            this.apr = null
            this.may = null
            this.jun = null
            this.jul = null
            this.aug = null
            this.seb = null
            this.oct = null
            this.nov = null
            this.dec = null
        },
        getbudget() {
            axios
                .post(`${window.location.origin}/api/monthWise_occupancy`, { year: this.year }, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + localStorage.getItem("accessToken"),
                    },
                })
                .then((res) => {
                    if (res.data != null) {
                        this.budgets = res.data;
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

            let data = []
            let y = 0
            for (let i = 1; i <= 12; i++) {
                const month = i.toString().padStart(2, '0');
                if (this.budgets.MonthwiseOccupancy[y].Month != month) {
                    data.push(0)
                    this.fillMonths(i, 0)
                }
                else {
                    data.push(this.budgets.MonthwiseOccupancy[y].OccupancyPercentage)
                    this.fillMonths(i, this.budgets.MonthwiseOccupancy[y].OccupancyPercentage)
                    if (y < this.budgets.MonthwiseOccupancy.length - 1)
                        y++
                }
            }
            this.series.push({
                name: 'Month occupancy',
                data: data,
            })


            console.log(this.series)

        },
        fillMonths(index, value) {
            if (index == 1) {
                this.jan = value
            }
            if (index == 2) {
                this.feb = value
            }
            if (index == 3) {
                this.mar = value
            }
            if (index == 4) {
                this.apr = value
            }
            if (index == 5) {
                this.may = value
            }
            if (index == 6) {
                this.jun = value
            }
            if (index == 7) {
                this.jul = value
            }
            if (index == 8) {
                this.aug = value
            }
            if (index == 9) {
                this.seb = value
            }
            if (index == 10) {
                this.oct = value
            }
            if (index == 11) {
                this.nov = value
            }
            if (index == 12) {
                this.dec = value
            }

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
