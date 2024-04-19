<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'



const headers_Profile = [

  {
    title: "id",
    key: 'id',
  },
  {
    title: "in date",
    key: 'in_date',
  },
  {
    title: "company name",
    key: 'company_name',
  },
  {
    title: "customer type",
    key: 'customer_type',
  },
  {
    title: "profile",
    key: 'profile_id.first_name',
  },
  {
    title: "way of payment",
    key: 'way_of_payment',
  },

  {
    title: "Action",
    key: 'action',
  },



]
</script>
<template>
<div>
  <VDataTable
    :headers="headers_Profile"
    :items="finalData"
    :items-per-page="6"
  >
     <template v-slot:item.id="{ item }">
     {{item.id}}
    </template>

    <template v-slot:item.action="{ item }">

      <v-btn  @click="goToReservation(item)">Go To Reservation</v-btn>
<!--      <v-btn  @click="SendData">Go To Reservation</v-btn>-->
    </template>

  </VDataTable>

</div>
</template>

<script>
export default {
  name: "IndexedDb",
  data(){
    return{
      allData: [],
      finalData: [],
    }
  },
  mounted() {
    this.getDataFromIndexedDB()
    this.yourFunction()
  },
  methods:{
    async  getDataFromIndexedDB() {
      return new Promise((resolve, reject) => {
        const request = indexedDB.open('masa', 1);

        request.onerror = event => {
          console.error('Error opening IndexedDB:', event.target.error);
          reject(event.target.error);
        };

        request.onsuccess = event => {
          const db = event.target.result;

          const transaction = db.transaction(['myStore'], 'readonly');
          const store = transaction.objectStore('myStore');

          const getAllRequest = store.getAll();

          getAllRequest.onsuccess = event => {
            const allData = event.target.result;
            resolve(allData);
          };

          getAllRequest.onerror = event => {
            console.error('Error retrieving data from IndexedDB:', event.target.error);
            reject(event.target.error);
          };
        };
      });
    },


    async  yourFunction() {
      try {
        const data = await this.getDataFromIndexedDB()

        this.finalData = data

        console.log(this.finalData)
      } catch (error) {
        console.error('Error getting data from IndexedDB:', error)
      }
    },
    goToReservation(item) {
      this.$router.push({ name: 'group-reservation', query: { data: JSON.stringify(item) } })
    },

  },

}
</script>

<style scoped>

</style>
