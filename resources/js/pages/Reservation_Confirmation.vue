<script setup>
// 👉 Print Invoice
const printInvoice = () => {
  window.print()
}
</script>
<template>
  <div style="padding: 2%">

    <Breadcrumb></Breadcrumb>
    <VRow>

      <VCol
        cols="12"
        md="9"
      >
        <VCard>
        <div style="width: 65%">
          <VCol>
            <tr>
              <td class="pe-16 pb-3">
               {{Setting.name}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{Setting.name_loc}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{Setting.address}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{Setting.phone}}
              </td>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{Setting.email}}
              </td>
            </tr>
          </VCol>
        </div>
        </VCard>
        <hr class="my-1" />
        <VCard>
          <VCardText>
            <div class="mx-auto text-center" >
              <h2 class="font-weight-semibold me-1">
               <b style="border-bottom:5px double ">{{ $t('Reservation Confirmation') }}</b>
              </h2>

            </div>
          </VCardText>
        </VCard>
        <hr class="my-1" />
        <VCard style="text-align: center;padding: 3%;display: flex;justify-content: space-around">
          <div style="width: 65%">
            <h2 style="display: inline">
                {{ $t('Reservation') }} :
            </h2>

            <h3 style="width: 50px;display: inline;color: black ;border-bottom:2px solid ">
              {{GuestData.id}}
            </h3>
          </div>
          <div style="width: 25%;margin: 1%">
            <h3 style="display: inline;color: black"><b>Status </b> : <b style="border-bottom:2px solid ">{{ GuestData.res_status }}</b></h3>
          </div>
        </VCard>
        <hr class="my-1" />
        <!-- 👉 Payment Details -->


        <VCard   style="display: flex;justify-content: space-around">
          <div  style="padding: 3%;">
                      <tr>
                        <td class="pe-16 pb-3">
                          {{ $t('Check In Date') }} :
                        </td>

                        <h4>{{ GuestData.in_date  }}</h4>
                      </tr>
                      <tr>
                        <td class="pe-16 pb-3">
                          {{ $t('Trace Date')}} :
                        </td>
                        <h4>??????</h4>
                      </tr>
                      <tr>
                        <td class="pe-16 pb-3">
                          {{$t('Nationality')}} :
                        </td>

                        <h4>{{ Country.name }}</h4>
                      </tr>

                      <tr>
                        <td class="pe-16 pb-3">
                          {{$t('Company')}}:
                        </td>

                        <h4>{{ company.company_name }}</h4>
                      </tr>
                    </div>

          <div style="padding: 3%;">
            <tr>
              <td class="pe-16 pb-3">
                {{$t('Guest Name')}} :
              </td>

              <h4>{{ Profile.first_name }} {{ Profile.last_name }}</h4>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{$t('Night')}} :
              </td>

              <h4>{{ GuestData.no_of_nights }}</h4>
            </tr>
            <tr>
              <td class="pe-16 pb-3">
                {{$t('No of Room')}} :
              </td>

              <h4>{{ room_type.no_of_rooms }}</h4>
            </tr>

            <tr>
              <td class="pe-16 pb-3">
                {{$t('Rate Code')}}:
              </td>
              <h4>{{ Rate.rate_code }}</h4>
            </tr>

          </div>

          <div style="padding: 3%;">
              <tr>
                <td class="pe-16 pb-3">
                  {{$t('Check Out Date')}} :
                </td>

                <h4>{{ GuestData.out_date }}</h4>
              </tr>
              <tr>
                <td class="pe-16 pb-3">
                  {{$t('No of PAX')}} :
                </td>

                <h4>{{ GuestData.no_of_pax }}</h4>
              </tr>
              <tr>
                <td class="pe-16 pb-3">
                  {{$t('Booked On')}} :
                </td>

                <h4>{{ GuestData.checkout_at }}</h4>
              </tr>

              <tr>
                <td class="pe-16 pb-3">
                  {{$t('Booked By')}}:
                </td>
                <h4>{{ created_by.firstname }}</h4>
              </tr>

          </div>
        </VCard>

        <hr class="my-1" />

        <VCard style="padding: 1%">
          <div style="width: 100%;display: flex;justify-content: space-around;">
            <div style="width: 80%">
            <b>Meal Plan / Pax / Day</b> <br><br>
              <VTable
                density="compact"
                class="text-no-wrap text-center"
                style="border: 1px solid black"
              >

                <thead>
                <tr>
                  <th class="text-uppercase">
                      {{$t('BreakFast')}}
                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Lunch')}}
                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Dinner')}}
                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Iftar')}}
                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Sahour')}}
                  </th>
                </tr>
                </thead>

                <tbody>
                <tr

                >
                  <td>
                    <VChip color="success" variant="outlined" v-if="MealType=='BF'">{{ GuestData.no_of_pax }}</VChip>
                    <VChip  color="success" variant="outlined"  v-else>0</VChip>
                  </td>
                  <td>
                    <VChip color="success" variant="outlined" v-if="MealType=='Ln'">{{ GuestData.no_of_pax }}</VChip>
                    <VChip color="success" variant="outlined" v-else>0</VChip>
                  </td>
                  <td>
                    <VChip color="success" variant="outlined" v-if="MealType=='Di'">{{ GuestData.no_of_pax }}</VChip>
                    <VChip color="success" variant="outlined" v-else>0</VChip>
                  </td>
                  <td>
                    <VChip color="success" variant="outlined" v-if="MealType=='IF'">{{ GuestData.no_of_pax }}</VChip>
                    <VChip color="success" variant="outlined" v-else>0</VChip>
                  </td>
                  <td>
                    <VChip color="success" variant="outlined" v-if="MealType=='Sr'">{{ GuestData.no_of_pax }}</VChip>
                      <VChip color="success" variant="outlined" v-else>0</VChip>
                  </td>

                </tr>
                </tbody>
              </VTable>
            </div>
          </div>
          <div style="width: 100%;display: flex;justify-content: space-around;">
            <div style="width: 80%">
            <br><br>
              <VTable
                density="compact"
                class="text-no-wrap text-center"
                style="border: 1px solid black"
              >

                <thead>
                <tr>
                  <th class="text-uppercase">

                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Amount')}}
                  </th>
                  <th class="text-uppercase text-center">
                     {{$t('Night')}}
                  </th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="i in 5">
                  <td class="text-center">
                   tur
                  </td>
                  <td class="text-center">
                    52
                  </td>
                  <td class="text-center">
                  523
                  </td>
                </tr>
                </tbody>
              </VTable>
            </div>
          </div>
        </VCard>
        <hr class="my-2" />


          <div style="width: 100%;display: flex;justify-content: flex-start;padding-left: 2%">
            <div class="text-center">
              <b>Notes </b> :- <b> {{ $t('Cashier') }}</b>
            </div>


          </div>

      </VCol>

      <VCol
        cols="6"
        md="3"
        class="d-print-none"
      >
        <VCard>
          <VCardText>
            <!-- 👉 Send Invoice Trigger button -->

            <VBtn
              block
              prepend-icon="tabler-send"
              variant="tonal"
              color="secondary"
              class="mb-2"
            >
               {{$t('Send Invoice')}}
            </VBtn>
            <VBtn
              block
              variant="tonal"
              color="secondary"
              class="mb-2"
            >
              {{ $t('Download') }}
            </VBtn>

            <VBtn
              block
              class="mb-2"
              @click="printInvoice"
            >
              {{ $t('Print') }}
            </VBtn>

            <VBtn
              block
              color="secondary"
              variant="tonal"
              class="mb-2"
            >
               {{$t('Edit Invoice')}}
            </VBtn>

            <!-- 👉  Add Payment trigger button  -->
            <VBtn
              block
              prepend-icon="tabler-currency-dollar"
              variant="tonal"
              color="secondary"
            >
               {{$t('Add Payment')}}
            </VBtn>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  data() {
    return {
      guest_id:'',
      URL: window.location.origin,
      AllTransactions:[],
      guests:[],
      GuestData:[],
      rooms:[],
      Profile:[],
      company:[],
      Country:[],
      Setting:[],
      room_type:[],
      Rate:[],
      MealType:'',
      created_by:[]
    }
  },
  computed: {

  },
  mounted() {
    this.getSetting()
    this.getGuest()
  },
  methods: {

    getSetting(){
      axios
        .get(`${this.URL}/api/settings`)
        .then(response =>this.Setting = response.data)
    },

    getGuest(){
      axios
        .post(`${this.URL}/api/search_guests`,{
          guest_id : this.guest_id
        })
        .then(response => (this.GuestData = response.data[0],this.MealType = response.data[0].meal.meal_type,this.Rate = response.data[0].rate_code,this.Profile = response.data[0].profile,this.created_by = response.data[0].created_by,this.Country = response.data[0].profile.country,this.rooms = response.data[0].room,this.company = response.data[0].company,this.room_type = response.data[0].room_type))
    },
  },
}
</script>

<style lang="scss">
@media print {
  .v-application {
    background: none !important;
  }

  @page { margin: 0; size: auto; }

  .layout-page-content,
  .v-row,
  .v-col-md-9 {
    padding: 0;
    margin: 0;
  }

  .product-buy-now {
    display: none;
  }

  .v-navigation-drawer,
  .layout-vertical-nav,
  .app-customizer-toggler,
  .layout-footer,
  .layout-navbar,
  .layout-navbar-and-nav-container {
    display: none;
  }

  .v-card {
    box-shadow: none !important;

    .print-row {
      flex-direction: row !important;
    }
  }

  .layout-content-wrapper {
    padding-inline-start: 0 !important;
  }
}
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
