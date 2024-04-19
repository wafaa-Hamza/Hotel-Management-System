<script setup>
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

// Store
import { useInvoiceStore } from '@/views/apps/invoice/useInvoiceStore'

const invoiceListStore = useInvoiceStore()
const route = useRoute()
const invoiceData = ref()
const paymentDetails = ref()

// 👉 fetchInvoice
invoiceListStore.fetchInvoice(Number(route.params.id)).then(response => {
  invoiceData.value = response.data.invoice
  paymentDetails.value = response.data.paymentDetails
}).catch(error => {
  console.log(error)
})
</script>

<template>
  <div>



    <VCard v-show="Visability" style="padding-bottom:2% ">
      <br>
      <div
        id="element-to-print"
        style="padding: 0 2%"
      >
        <div style="display: flex;justify-content: space-between;margin-bottom: 2%">
          <h6>{{ localSetting.name }}</h6>
          <h6>MSS00174</h6>
        </div>
        <VRow>
          <VCol
            cols="12"
            sm="12"
            md="12"
          >
            <VCol

              cols="12"
              sm="12"
              md="12"
              style="display:flex; justify-content: space-around"
            >
              <div style="border: 1px solid black;border-radius: 10px;padding: 1.5%">
                <h5>
                  {{ $t('Date') }} : 27/04/2023
                </h5>
                <br>
                <h5>
                  {{ $t('Time') }} : 12:05:14
                </h5>
              </div>
              <div style="border: 1px solid black;border-radius: 10px;text-align: center;padding: 2%">

                <h2>{{ $t('Room Changed') }}</h2>
              </div>
              <div style="border: 1px solid black;border-radius: 10px;text-align: center;padding: 2%">
                <h5>
                  {{ $t('Hotel Date') }} : {{ localSetting.hotel_date }}
                </h5>
              </div>
            </VCol>

            <hr/>
            <VCol

              cols="12"
              sm="12"
              md="12"
              style="display:flex; justify-content:space-around"
            >
              <div>
                <h3>
                  {{ $t('Guest name') }} : {{ profile.first_name }}
                </h3>
                <br>
                <h3>
                  {{ $t('Family name') }} : {{ profile.last_name }}
                </h3>
                <br>
                <h3>
                  {{ $t('Pax') }} : {{ guest.no_of_pax }}
                </h3>
              </div>

              <div>
                <h3>
                  {{ $t('In Date') }} : {{ guest.in_date }}
                </h3>
                <br>
                <h3>
                  {{ $t('Out Date') }} : {{ guest.out_date }}
                </h3>
                <br>
                <h3>
                  {{ $t('Folio') }} : {{ guest.folio_no }}
                </h3>
              </div>
            </VCol>
            <hr/>

            <VDivider/>
            <VCol
              style="padding: 2px;display: flex;"
              cols="12"
              sm="12"
              md="12"
            >

              <VTable>
                <thead>
                <tr>

                  <th className="text-left">

                  </th>
                  <th className="text-left">
                    {{ $t('Room Number') }}
                  </th>
                  <th className="text-left">
                    {{ $t('Room Type') }}
                  </th>
                </tr>
                </thead>
                <tbody>

                <tr>
                  <td><h3>Current Room</h3></td>
                  <td>{{ room_change_from.rm_name_en }}</td>
                  <!--                  <td>{{ room_change_from.rm_name_en }}</td>-->
                </tr>
                <tr>
                  <td><h3>Change To</h3></td>
                  <td>{{ room_change_to.rm_name_en }}</td>
                  <!--                  <td>{{ room_change_to.rm_name_en }}</td>-->
                </tr>
                <tr>
                  <td><h3>Reason</h3></td>
                  <td>
                    {{ RoomChange.reason }}
                  </td>


                </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCol>
        </VRow>
        <h1 style="text-align: center;box-shadow: 5px 4px 8px 0px;padding: 1%" v-if="dataCount == 0">
          {{ $t('No Data Selected') }}</h1>
        <br><br>
        <VCard>
          <div style="width: 100%;display: flex;justify-content: space-around">
            <div className="text-center">
              <b> {{ userData.firstname }} {{ userData.lastname }}</b>
              <hr>
              <b>{{ $t('Changed By') }}</b>
            </div>

            <div className="text-center">
              <b>---------</b>
              <hr>
              <b>{{ $t('Approved By') }}</b>
            </div>
          </div>
        </VCard>
      </div>
    </VCard>
    <VCol
      cols="6"
      md="3"

      style="margin:2% auto"
      class="d-print-none"
    >
      <VCard>
        <VCardText>
          <!-- 👉 Send Invoice Trigger button -->

          <VBtn
            block
            class="mb-2"
            @click="printInvoice"
          >
            Print
          </VBtn>

        </VCardText>
      </VCard>
    </VCol>
  </div>
</template>
<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      Visability: false,
      URL: window.location.origin,
      RoomChange: [],
      RoomChangeData: [],
      guest: [],
      room_change_from: [],
      room_change_to: [],
      profile: [],
      room_id: '',
      dataCount: 0,
      myData: [],
      userData: [],
      AllSetData: [],
      SettingData: [],
      localSetting: [],
      currentDate: new Date()
    }
  },
  mounted() {
    this.getRoomChange()
    this.getAllRoomView()
    const storedData = localStorage.getItem('userData');
    if (storedData) {
      this.myData = JSON.parse(storedData);
      this.userData = this.myData.user
    }
    const SettingData = localStorage.getItem('keyinfo');
    if (SettingData) {
      this.SettingData = JSON.parse(SettingData);
      this.AllSetData = this.SettingData.Settings
      this.localSetting = this.AllSetData[0]
    }
  },
  computed: {
    formattedDate() {
      const day = String(this.currentDate.getDate()).padStart(2, '0');
      const month = String(this.currentDate.getMonth() + 1).padStart(2, '0'); // Note: Month is zero-based
      const year = this.currentDate.getFullYear();
      const date = this.currentDate.toLocaleDateString('en-GB');
      const time = this.currentDate.toLocaleTimeString();
      return `${year}-${month}-${day} ${time}`;
    }
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    getAllRoomView() {

      axios
        .get(`api/roomChange`)
        .then(response => (this.RoomChangeData = response.data.data))
    },
    getRoomChange() {
      axios.post(`${this.URL}/api/room-change-print`, {
        id: this.$route.params.data,
      }).then(res => (this.RoomChange = res.data.Change_Room_Print[0],
        this.guest = res.data.Change_Room_Print[0].guest[0],
        this.profile = res.data.Change_Room_Print[0].guest[0].profile,
        this.room_change_from = res.data.Change_Room_Print[0].room_change_from[0],
        this.room_change_to = res.data.Change_Room_Print[0].room_change_to[0],
        this.dataCount = res.data.Change_Room_Print.length))
      this.Visability = true
    },
    async printInvoice() {
      await this.getRoomChange();
      window.print()
    }
  },
}
</script>
<style>@media print {
  .v-application {
    background: none !important;
  }

  @page {
    margin: 0;
    size: auto;
  }

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
  background: rgba(147, 112, 219, .6);;
}
.layout-vertical-nav{
  display: none;
  width: 0;
}
.layout-navbar{
  display: none;
}
</style>
