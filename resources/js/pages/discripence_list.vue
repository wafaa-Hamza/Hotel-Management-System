<template>
<div>
  <VCard>
    <VCardTitle class="text-center">
      <span class="text-h4 ">Discripancy</span>
    </VCardTitle>
    <VCardText>
      <VContainer>
        <VTable class="mt-5"  fixed-header>
          <thead >
          <tr >
            <th>

              {{ $t("Solved") }}
            </th>
            <th>
              {{ $t("Room") }}
            </th>

            <th>
              {{ $t("hotel_date") }}
            </th>
            <th>
              {{ $t("Status") }}
            </th>
            <th>
              {{ $t("created by") }}
            </th>
            <th>
              {{ $t("update") }}
            </th>
          </tr>
          </thead>

          <tbody v-for="item in Alldiscrepancy"  >

          <tr>

            <td >

              <p  v-if="item.solved_by ===1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled" width="44" height="44" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" stroke-width="0" fill="green" />
                </svg>
              </p>
              <p  v-if="item.solved_by !==1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" stroke-width="0" fill="red" />
                </svg>
              </p>
            </td>

            <td >
              {{ item.room_id }}
            </td>
            <td >
              {{ item.hotel_date }}
            </td>
            <td >
              {{ item.status_fo }}{{ item.status_hk }}
            </td>
            <td >
              {{ item.created_by }}
            </td>
            <td >

              <div v-if="item.solved_by !==1">
                <VBtn @click="updateStatus(item)"
                >
                  <VIcon icon="mdi-file-edit-outline" />
                </VBtn>
              </div>
              <div v-else>
                <VBtn @click="updateStatus(item)" disabled
                >
                  <VIcon icon="mdi-file-edit-outline" />
                </VBtn>
              </div>
            </td>
          </tr>


          </tbody>
        </VTable>
      </VContainer>
    </VCardText>
    <VCardActions>
      <VSpacer />
      <VBtn color="blue-darken-1" variant="tonal" @click="GoDescripency">
        Add Describency
      </VBtn>

    </VCardActions>
  </VCard>
 </div>
</template>

<script>
import axios from '@axios'
export default {
  name: "discripence_list",
  data(){
    return{

      URL: window.location.origin,
      Alldiscrepancy:[],
    }
  },
  mounted() {

    this.getAlldiscrepancy()
  },
  methods:{
    getAlldiscrepancy() {
      axios
        .get(`${this.URL}/api/discrepancy`)

        .then(response => (this.Alldiscrepancy = response.data.data))
    },
    GoDescripency() {
      this.$router.push("/rooms/discrepancy")
    },
  },
}
</script>

<style scoped>

</style>
