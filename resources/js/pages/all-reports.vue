<template>
  <div>
    <VCard>
      <h3 class="ml-6 mt-3">
        {{ $t("Reports") }}
      </h3>
      <VExpansionPanels
        class="mt-3 pl-3 pr-3 mb-6"
        variant="accordion"
      >
        <VExpansionPanel
          v-for="(item, index) in reports"
          :key="index"
        >
          <VExpansionPanelTitle class="custom-title">
              {{ $t(item.name) }}
          </VExpansionPanelTitle>
          <VExpansionPanelText>
            <VRow>
              <VCol
                v-for="(report, index) in item.value"
                :key="index"
                cols="6"
              >
                <div class="link-container">
                  <RouterLink :to="report.link">
                   {{ $t(report.name) }}
                  </RouterLink>
<!--                  <span>{{ report.description }}</span>-->
                </div>
              </VCol>
            </VRow>
          </VExpansionPanelText>
        </VExpansionPanel>
      </VExpansionPanels>
    </VCard>
  </div>
</template>
    

<script>
export default {
  name: "AllReports",
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
       reports: [
        {
          name: 'Reception',
          value: [
            {
              name: 'Meal plan',
              description: 'This is Guest InHouse MealPlan report',
              link: '/Reports/Guest_InHouse_MealPlan',
            },
            {
              name: 'Room Change',
              description: 'This is voided report',
              link: '/reports/report_room_change',
            },
            {
              name: 'Rate Change',
              description: 'This is voided report',
              link: '/reports/report_rate_change',
            },
            {
              name: 'Guest Inhouse',
              description: 'This is voided report',
              link: '/reports/report_guest_inhouse',
            },
            {
              name: 'Actual Checked In',
              description: 'This is voided report',
              link: '/reports/report_actual',
            },
            {
              name: 'departure Checked Out',
              description: 'This is voided report',
              link: '/reports/report_departure',
            },
          ],
        },
        {
          name: 'Accounts',
          value: [
            {
              name: 'Guest Ledger',
              description: 'This is Guest ledger report',
              link: '/Reports/Reports',
            },

            {
              name: 'cashier close',
              description: 'This is cashier close report',
              link: '/Reports/cashier_close',
            },
            {
              name: 'cashier close summary',
              description: 'This is cashier close summary report',
              link: '/Reports/cashier_close_summary',
            },
            {
              name: 'Transactions Details',
              description: 'This is Report Transactions Details report',
              link: '/Reports/Report_Transactions_Details',
            },
            {
              name: 'Transactions Details Users',
              description: 'This is Report Transactions Details users report',
              link: '/Reports/Report_Transactions_Details_users',
            },
            {
              name: 'Transactions Taxes',
              description: 'This is Transactions tax report',
              link: '/Reports/Transactions_tax',
            },
            {
              name: 'Voided Transactions',
              description: 'This is Report Voided Transactions report',
              link: '/Reports/Report_Voided_Transactions',
            },
            {
              name: 'Reciept Voucher',
              description: 'This is Reciept Voucher report',
              link: '/Reports/Reciept_Voucher',
            },
            {
              name: 'Payment Voucher',
              description: 'This is Payment Voucher report',
              link: '/Reports/Payment_Voucher',
            },
            {
              name: 'Revenue By Dates',
              description: 'This is Revenue_By_Dates report',
              link: '/Reports/Revenue_By_Dates',
            },
            {
              name: 'Transactions List view',
              description: 'This is Transactions List view report',
              link: '/Reports/Transactions_List_view',
            },
            {
              name: 'voucher list',
              description: 'This is Voucher List report',
              link: '/Reports/Voucher_List',
            },
          ],
        },
        {
          name: 'Reservation',
          value: [
            {
              name: 'Reservation Overview',
              description: 'This is voided report',
              link: '/reports/reservation_overview',
            },
            {
              name: 'Reservation By Corporate',
              description: 'This is voided report',
              link: '/reports/reservation_by_corporate',
            },
          ],
        },
        {
          name: 'House keeping',
          value: [
            {
              name: 'OO and oS',
              description: 'This is voided report',
              link: '/reports/oo_and_os',
            },
            {
              name: 'House Keeping',
              description: 'This is voided report',
              link: '/reports/house_keeping',
            },
            {
              name: 'Current room',
              description: 'This is voided report',
              link: '/reports/current_room',
            },
          ],
        },
        {
          name: 'Marketing',
          value: [
            {
              name: 'Reservation By Corporate',
              description: 'This is voided report',
              link: '/reports/reservation_by_corporate',
            },
            {
              name: 'Prodictivity By Country',
              description: 'This is voided report',
              link: '/reports/prodictivity_by_country',
            },
            {
              name: 'Prodictivity By Company',
              description: 'This is voided report',
              link: '/reports/prodictivity_by_company',
            },
            {
              name: 'Prodictivity By Segment',
              description: 'This is voided report',
              link: '/reports/prodictivity_by_segment',
            },
            {
              name: 'Prodictivity By Sourcebussiness',
              description: 'This is voided report',
              link: '/reports/prodictivity_by_sourcebussiness',
            },
          ],
        },
        {
          name: 'Managers',
          value: [
            {
              name: 'Income Statement',
              description: 'This is voided report',
              link: '/reports/income_statement',
            },
            {
              name: 'Income Statement MTD',
              description: 'This is voided report',
              link: '/reports/income_statement_mtd',
            },
            {
              name: 'High Balance',
              description: 'This is voided report',
              link: '/reports/high_balance',
            },
            {
              name: 'Daily Sales',
              description: 'This is voided report',
              link: '/reports/daily_sales',
            },
            {
              name: 'Occupancy By Month',
              description: 'This is voided report',
              link: '/reports/occupancy_by_month',
            },
            {
              name: 'Occupancy By Month Date',
              description: 'This is voided report',
              link: '/reports/occupancy_by_month_date',
            },
            {
              name: 'History Forcast',
              description: 'This is voided report',
              link: '/reports/history_forcast',
            },
            {
              name: 'Manager 1 Report',
              description: 'This is voided report',
              link: '/reports/manager_1_report',
            },
            {
              name: 'Manager 3 Report',
              description: 'This is voided report',
              link: '/reports/manager_3_report',
            },
          ],
        },
      ],

    }
  },
}
</script>

<style scoped>
.link-container {
    display: flex;
    flex-direction: column;
}

.link-container>div {
    flex-basis: 50%;
}
</style>
