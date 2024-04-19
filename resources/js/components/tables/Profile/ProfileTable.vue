<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'

// headers
const headers_en = [
  {
    title: 'firstname',
    key: 'first_name',
  },
  {
    title: 'lastname',
    key: 'last_name',
  },

  {
    title: 'mobile',
    key: 'mobile',
  },
  {
    title: 'email',
    key: 'email',
  },
  {
    title: 'address',
    key: 'address',
  },
  {
    title: 'city',
    key: 'city',
  },

  {
    title: ' language',
    key: 'language',
  },

  {
    title: 'date_of_birth',
    key: 'date_of_birth',
  },
  {
    title: 'gender',
    key: 'gender',
  },

  {
    title: 'country name',
    key: 'country.name',
  },


  {
    title: 'Edit',
    key: 'actions',
  },
]

// headers
const headers_ar = [
  {
    title: 'firstname',
    key: 'first_name',
  },
  {
    title: 'lastname',
    key: 'last_name',
  },

  {
    title: 'mobile',
    key: 'mobile',
  },
  {
    title: 'email',
    key: 'email',
  },

  {
    title: 'address',
    key: 'address',
  },
  {
    title: 'city',
    key: 'city',
  },

  {
    title: ' language',
    key: 'language',
  },

  {
    title: 'date_of_birth',
    key: 'date_of_birth',
  },
  {
    title: 'gender',
    key: 'gender',
  },

  {
    title: 'country name',
    key: 'country.name',
  },


  {
    title: 'Edit',
    key: 'actions',
  },
]
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCol
      cols="12"
      md="12"
      style="display: flex"
    >
      <VCol
        cols="3"
        md="3"
      >
        <AppTextField

          v-model="fullName"

          density="compact"
          :placeholder="$t('Search by name')"
          append-inner-icon="tabler-search"
          single-line
          hide-details
          dense
          outlined
          @keydown.enter="getAllProfile"
        />
      </VCol>
      <VCol
        cols="2"
        md="2"
      >
        <AppTextField
          v-model="mobile_search"
          density="compact"
          :placeholder="$t('Search by mobile')"
          append-inner-icon="tabler-search"
          single-line
          hide-details
          dense
          outlined
          type="number"
          @keydown.enter="getAllProfile"
        />
      </VCol>
      <VCol
        cols="2"
        md="2"
      >
        <AppTextField
          v-model="id_no_search"
          density="compact"
          :placeholder="$t('Search by id no')"
          append-inner-icon="tabler-search"
          single-line
          hide-details
          dense
          outlined
          type="number"
          @keydown.enter="getAllProfile"
        />
      </VCol>
      <VSpacer />
      <VCol
        cols="1"
        md="2"
      >
        <VBtn @click="getAllProfile">
          Search
        </VBtn>
      </VCol>
      <VCol
        cols="1"
        md="2"
      >
        <VBtn
          v-if="$can('create', 'profile')"
          @click="GoToStore"
        >
          Add Profile
        </VBtn>
      </VCol>
    </VCol>
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="Profile"
      :search="search"
      :items-per-page="10"
    >
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn
            color="primary"
            @click="Updates(item.raw?item.raw:item)"
          >
            <VIcon icon="mdi-file-edit-outline" />
          </VBtn>
        </div>
      </template>
    </VDataTable>
  </div>
</template>



<script>
import axios from "@axios"
import router from "@/router"


export default {
  name: "ProfileTable",

  // eslint-disable-next-line vue/component-api-style
  data(){
    return {

      search: '',
      dialog: false,
      dialog3: false,
      Profile: [],
      Profileid: '',
      num_profile: '',
      firstname: '',
      lastname: '',
      id_no: '',
      mobile: '',
      phone: '',
      email: '',
      address: '',
      city: '',
      lang_data: ['ar', 'en', 'fr'],
      language: '',
      date_of_birth: '',
      gender_data: ['male', 'femal'],
      gender: '',
      country_data: ['1', '2'],
      country: '',
      user_data: ['mohamed', 'ahmed'],
      created_by: '',



      first_name: '',
      last_name: '',
      numProfile_edit: '',
      firstname_edit: '',
      lastname_edit: '',
      id_no_edit: '',
      mobile_edit: '',
      phone_edit: '',
      email_edit: '',
      address_edit: '',
      city_edit: '',
      language_edit: '',
      date_edit: '',
      gender_edit: '',
      country_edit: '',
      created_by_edit: '',
      mobile_search: '',
      name_search: '',
      id_no_search: '',
      fullName: '',

      count: 0,
      perPage: 10,
      pagi: [],
      page: 2,
      pageInfo: null,
      totalPages: 4,
      pageSize: 5,
      pageSizes: [5, 10, 15, 20, 25, 30],
      URL: window.location.origin,

    }
  },


  // eslint-disable-next-line vue/component-api-style



  // computed: {
  //   fullName: {
  //     get() {
  //       return this.first_name+ this.last_name;
  //     },
  //     set(value) {
  //       const parts = value.split(' ');
  //       this.first_name = parts[0];
  //       this.last_name = parts.slice(1).join(' ');
  //     }
  //   }
  // },



  // eslint-disable-next-line vue/component-api-style
  methods: {
    insertAlert() {
      const Toast = this.$swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer)
          toast.addEventListener('mouseleave', this.$swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: 'success',
        title: 'Data Added successfully',
        color: 'green',
        timer: 5000,
      })
    },

    DeleteAlert() {

      this.$swal.fire({
        icon: 'error',
        title: 'Do you want to Delete',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Deleted`,
      }).then(result => {

        if (result.isDenied) {
          this.deleteData()

          const Toast = this.$swal.mixin({
            toast: true,
            position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
              toast.addEventListener('mouseenter', this.$swal.stopTimer)
              toast.addEventListener('mouseleave', this.$swal.resumeTimer)
            },
          })

          Toast.fire({
            icon: 'success',
            title: `Data ${this.room_name_en} Deleted successfully`,
            color: 'red',
            timer: 10000,
          })
        }
      })
    },
    
    UpdateAlert() {
      const Toast = this.$swal.mixin({
        toast: true,
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', this.$swal.stopTimer)
          toast.addEventListener('mouseleave', this.$swal.resumeTimer)
        },
      })

      Toast.fire({
        icon: 'info',
        title: 'updateed is successfully',
        color: 'mediumpurple',
        timer: 5000,
      })
    },
    
    getAllProfile(){
      axios.post(`${this.URL}/api/seachProfile`, {
        mixedSearch: 1,
        spaceficSearch: 1,
        term: {
          first_name: this.fullName,
          last_name: this.fullName,
          id_no: this.id_no_search,
          mobile: this.mobile_search,
        },

      })
        .then(response => (this.Profile = response.data.data))
    },

    async GoToStore(){

      await router.push({ name: 'profile-Store' })

    },

    async  Updates(Getdata){
      console.log('Getdata',Getdata.id)
      await this.$router.push({ name: `profile-data`, params: { data: Getdata.id } })

    },

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
.dialog-bottom-transition-enter-active,
.dialog-bottom-transition-leave-active {
  transition: transform .2s ease-in-out;
}
.buttons_actions{
  width: 60%;
  margin: 2% auto;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}
</style>
