<template>
  <div>

    {{this.$route.params.data}}

    <VCard>


      <VCardTitle class="text-center my-5">
      <span class="text-h5" style="  ">
        <VCard  style="width: 28%;height: 100px;display: flex;justify-content: space-around;align-items: center;margin: auto">
          <VAvatar color="success" variant="tonal" size="34" rounded>
            <VIcon icon="tabler-link" />
          </VAvatar> <b>Update this Profile</b>
          <b>{{this.num_profile}}</b>
        </VCard>

      </span>
      </VCardTitle>
      <VCardText>
        <VContainer>
          <VRow>
            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="firstname"
                label="firstname*"
                cols="6"
                sm="6"
                md="6"
                type="text"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VTextField
                v-model="lastname"
                label="lastname*"
                cols="6"
                sm="6"
                md="6"
                type="text"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="id_no"
                label="id_no*"
                cols="6"
                sm="6"
                md="6"
                type="number"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="mobile"
                label="mobile*"
                cols="6"
                sm="6"
                md="6"
                type="number"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VTextField
                v-model="phone"
                label="phone"
                cols="6"
                sm="6"
                md="6"
                type="number"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="email"
                label="email"
                cols="6"
                sm="6"
                md="6"
                type="email"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VTextField
                v-model="address"
                label="address"
                cols="6"
                sm="6"
                md="6"
                type="text"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="city"
                label="city"
                cols="6"
                sm="6"
                md="6"
                type="text"
                persistent-placeholder
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VSelect item-color="customColorValue"
                v-model="language"
                :items="lang_data"
                label="language"
                cols="6"
                sm="6"
                md="6"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="date_of_birth"
                label="Date"
                cols="6"
                sm="6"
                md="6"
                type="date"
              />
            </VCol>

            <VCol cols="3" sm="3" md="2" style="display: inline-block;">
              <VCombobox v-model="select_day" :items="days" :label="$t('days')" style="width: 100%;"
                         clearable  item-color="customColorValue"/>
            </VCol>
            <VCol cols="3" sm="3" md="2" style="display: inline-block;">
              <VCombobox v-model="select_month" :items="month" :label="$t('months')" style="width: 100%;"
                         clearable  item-color="customColorValue"/>
            </VCol>
            <VCol cols="3" sm="3" md="2" style="display: inline-block;">
              <VCombobox v-model="select_year" :items="years" :label="$t('Years')" style="width: 100%;"
                         clearable  item-color="customColorValue"/>
            </VCol>



            <VCol
              cols="12"
              sm="6"
              md="6"
            >
              <VSelect item-color="customColorValue"
                v-model="gender"
                :items="gender_data"
                label="gender"
                cols="6"
                sm="6"
                md="6"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VCombobox
                v-model="country"
                :items="couuntry"
                item-title="name"
                cols="6"
                sm="6"
                md="6"
                item-color="customColorValue"
              />
            </VCol>

            <VCol
              cols="12"
              sm="6"
              md="6"
              style="margin:0px 0px 3% 0px"
            >
              <VTextField
                v-model="created_by.firstname"

                readonly
                cols="6"
                sm="6"
                md="6"
              />
            </VCol>

          </VRow>
        </VContainer>
      </VCardText>
      <div class="buttons_actions"   >
        <VBtn
          color="black"
          variant="tonal"
          @click="UpdateData"
        >
          Submit
        </VBtn>

        <VBtn
          color="black"
          variant="tonal"
          @click="BackToProfile"
        >
          Back To Profile page
        </VBtn>
      </div>
    </VCard>
  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"
import axiosIns from "@axios"

export default {
  name: "Update",
  // eslint-disable-next-line vue/component-api-style
  data(){
    return {


      Profile:[],
      users:[],
      Profileid:'',
      num_profile:'',
      firstname:'',
      lastname:'',
      id_no:'',
      mobile:'',
      phone:'',
      email:'',
      address:'',
      city:'',
      lang_data:['ar','en','fr'],
      language:'',
      date_of_birth:'',
      gender_data:['male','femal'],
      gender:'',
      select_year: '',
      select_day: '',
      select_month: '',
      years: [],
      days: [],
      month: [],
      country:'',
      user_data:['mohamed','ahmed'],
      created_by:'',
      main_data:[],
      couuntry:[],
      URL : window.location.origin,
    }
  },
  watch:{
    select_month() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_day() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
    select_year() {
      this.date_of_birth_new = this.select_year + '-' + String(this.select_month).padStart(2, '0') + '-' + String(this.select_day).padStart(2, '0')
      console.log(this.date_of_birth_new)
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted(){
    this.EditData()
    this.getAllusersAction()
    this.getAllcountry()
    this.DayData()
    this.YeardData()
    this.MonthData()

  },
  // eslint-disable-next-line vue/component-api-style
  methods:{
    YeardData() {
      const startYear = 1900;
      const endYear = 2024;
      for (let year = startYear; year <= endYear; year++) {
        this.years.push(year)
      }
    },

    MonthData() {
      const starMonth = 1;
      const endMonth = 12;
      for (let months = starMonth; months <= endMonth; months++) {
        this.month.push(months)
      }
    },

    DayData() {
      const statday = 1;
      const endday = 31;
      for (let day = statday; day <= endday; day++) {
        this.days.push(day)
      }
    },
    getAllusersAction() {
      axiosIns.get(`${window.location.origin}/api/users`)
        .then(res => {
          this.users = res.data
        })
        .catch(err => {
        })
    },
    getAllcountry() {
      axiosIns.get(`${window.location.origin}/api/getcountries`)
        .then(res => {
          this.couuntry = res.data
        })
        .catch(err => {
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
    EditData(){
      axios
        .get(`${this.URL}/api/guestProfile/${this.$route.params.data}`)
        .then(response =>{
          console.log(response)
          // this.firstname = response.data.data.first_name,
          //   this.lastname = response.data.data.last_name,
          //   this.id_no = response.data.data.id_number,
          //   this.num_profile = response.data.data.profile_no,
          //   this.mobile = response.data.data.mobile,
          //   this.phone = response.data.data.phone,
          //   this.email = response.data.data.email,
          //   this.address = response.data.data.address,
          //   this.city = response.data.data.city,
          //   this.language = response.data.data.language,
          //   this.date_of_birth = response.data.data.date_of_birth,
          //   this.gender = response.data.data.gender,
          //   this.country = response.data.data.country,
          //   this.created_by = response.data.data.created_by
        })
    },
    async  UpdateData(){


      const user = await   axios.put(
        `${this.URL}/api/guestProfile/${this.$route.params.data}`,
        {
          first_name: this.firstname,
          last_name: this.lastname,
          id_no: this.id_no,
          Profile_no: this.num_profile,
          mobile: this.mobile,
          phone: this.phone,
          email: this.email,
          address: this.address,
          city: this.city,
          language: this.language,
          date_of_birth: this.date_of_birth_new,
          gender: this.gender,
          country_id: this.country.id,
          created_by: this.created_by.id,


        },
      )

      this.UpdateAlert()
      await router.push('/profile/Profile')

    },
    BackToProfile() {
      router.push('/profile/Profile')
    },


  },

}
</script>
