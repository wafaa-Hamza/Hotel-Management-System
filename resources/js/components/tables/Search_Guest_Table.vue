
<template>
  <div style="min-height: 1000px">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>

    <VCard>
       <br>


      <div style="float: right;width: 100%;display: flex;justify-content: space-between;align-content: space-around" >
        <VCol  style="text-align: center" >
        <p
          style="width: 39%;height: 40px;display: flex;justify-content:center;align-items: center;border: 2px solid #abc9dc;border-radius: 10px"
        >
          <b >{{dataCount}}</b>

        </p>
        </VCol>
        <VCol>
          <VTextField
            v-model="ref_no"
            label="ref_no"
          />

        </VCol>
        <VCol>
          <VTextField
            v-model="mobile"
            label="mobile"

          />
        </VCol>

        <VCol>
          <VTextField
            v-model="first_name"
            label="first_name"

          />
        </VCol>


        <VCol>
          <VBtn @click="getSearchGuest" >
           Find / Search
          </VBtn>
        </VCol>
      </div>
      <br><br><br>
    </VCard>
    <br>
    <VCard
      v-show="Visability"
      style="padding:1% 1%;height: 35% "
    >
      <br>
      <div
        style="padding: 0 2%"
      >

        <VRow>
          <VCol
            cols="12"
            sm="12"
            md="12"
          >

            <VCol
              style="padding: 2px"
              cols="12"
              sm="12"
              md="12"
            >
              <table style=" width: 100%;  color: black;font-size: 12px;">
                <thead>
                  <tr>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Guest Name
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Arrival Date
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Departure Date
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Booking Voucher
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Mobile
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Phone
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Room No
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Rate
                    </th>
                    <th class="text-center" style="border-bottom: 2px solid;">
                      Updated User ID
                    </th>
                  </tr>
                </thead>
                <tbody>

                  <tr
                    v-for="item in search_guests"
                    @click="mo(item)"
                    class="text-center"
                  >
                    <td>{{ item.profile?item.profile.first_name:'' }} {{ item.profile?item.profile.last_name:'' }}</td>
                    <td>{{ item.in_date }}</td>
                    <td>{{ item.out_date }}</td>
                    <td>{{ item.ref_no }}</td>
                    <td>{{ item.profile?item.profile.mobile:'' }}</td>
                    <td>{{ item.profile?item.profile.phone:'' }}</td>
                    <td  >{{ item.room?item.room.rm_name_en: ' ' }}</td>
                    <td>{{ item.rm_rate }}</td>
                    <td></td>
                  </tr>

                </tbody>
              </table>
            </VCol>
          </VCol>
        </VRow>


      </div>
    </VCard>

    <br>
    <br>




        <VRow  v-show="Visability_data">
          <VCol
            cols="6"
            sm="6"
            md="6"
          >
            <VCard>
              <VCardText dir="rtl">
                <VList class="card-list">
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold">
                      {{ guest_data?guest_data.folio_no:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Folio No</span>
                      <VAvatar
                        color="primary"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-home" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold">
                      {{ guest_data?guest_data.rm_rate:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Room Rate</span>
                      <VAvatar
                        color="info"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-click" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold" v-if="Visability_data">
                      {{ guest_data?guest_data.profile.email:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">email</span>
                      <VAvatar
                        color="success"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-link" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold">
                      {{ guest_data?guest_data.no_of_pax:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Pax</span>
                      <VAvatar
                        color="warning"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-ban" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold" v-if="Visability_data">
                      {{ guest_data.rate_code?guest_data.rate_code.name:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Rate</span>
                      <VAvatar
                        color="error"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-click" />
                      </VAvatar>
                    </template>
                  </VListItem>
                </VList>
              </VCardText>
            </VCard>
          </VCol>
          <VCol
            cols="6"
            sm="6"
            md="6"
          >
            <VCard>
              <VCardText dir="rtl">
                <VList class="card-list">
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold" v-if="Visability_data">
                      {{ guest_data.room?guest_data.room.rm_name_en:'' }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">room name</span>
                      <VAvatar
                        color="primary"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-home" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold" v-if="Visability_data">
                      {{ guest_data.profile.first_name }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">profile name</span>
                      <VAvatar
                        color="success"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-link" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold">
                      {{ guest_data.in_date }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Arrival Date</span>
                      <VAvatar
                        color="warning"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-alert-triangle" />
                      </VAvatar>
                    </template>
                  </VListItem>
                  <VListItem>
                    <VListItemTitle class="font-weight-semibold">
                      {{ guest_data.out_date }}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">Departure Date</span>
                      <VAvatar
                        color="secondary"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-ban" />
                      </VAvatar>
                    </template>
                  </VListItem>

                  <VListItem>
                    <VListItemTitle class="font-weight-semibold" v-if="Visability_data">
                      {{ guest_data.room_type.rm_type_name}}
                    </VListItemTitle>
                    <template #append>
                      <span class="font-weight-semibold">room type</span>
                      <VAvatar
                        color="info"
                        variant="tonal"
                        size="34"
                        rounded
                      >
                        <VIcon icon="tabler-click" />
                      </VAvatar>
                    </template>
                  </VListItem>



                </VList>
              </VCardText>
            </VCard>
          </VCol>

          <VCol
            cols="6"
            sm="6"
            md="6"
            v-if="Visability_data"
            style="margin: auto"
          >
            <VCard>
              <VCardText class="d-flex flex-column align-center justify-center">
                <VAvatar
                  size="42"
                  variant="tonal"
                  color="primary"
                >
                  <VIcon
                    icon="tabler-users"
                    size="24"
                  />
                </VAvatar>
                Created by
                <h6 class="text-h6 font-weight-semibold my-2">
                  {{ guest_data.created_by.firstname }}
                </h6>
                <span class="text-body-2">212</span>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>

  </div>
</template>

<script>
import axios from "@axios"
import { tr } from "vuetify/locale"
import { jsPDF } from 'jspdf'
import 'jspdf-autotable'
import autoTable from "jspdf-autotable"



export default {


  // eslint-disable-next-line vue/component-api-style
  data () {
    return {
      Visability: false,
      Visability_data: false,
      URL: window.location.origin,
      search_guests: [],
      date: '',
      ref_no: '',
      mobile: '',
      first_name: '',
      guest_data:[],
      dataCount:0,

    }

  },


  // eslint-disable-next-line vue/component-api-style
  methods: {
    getSearchGuest() {
      axios.post(`${this.URL}/api/search_guests`, {
        ref_no: this.ref_no,
        mobile_no: this.mobile,
        guest_name: this.first_name,
      }).then(res =>(this.search_guests = res.data,this.dataCount = this.search_guests.length)) .catch(err => {
        console.log(err)
      })
      this.Visability = true
    },
    mo(id){
      this.Visability_data = true,
      this.guest_data = id
    }

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
