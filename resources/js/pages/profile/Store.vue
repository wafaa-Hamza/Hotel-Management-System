<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCard>
      <VCardTitle class="text-center my-5">
        <span class="text-h5">Add New Profile</span>
      </VCardTitle>

      <VForm ref="form">
      <VCardText>
        <VContainer>
          <VRow>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="firstname" label="firstname*" type="text"  @keyup.enter="goNext($event.target)"  />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="lastname" label="lastname*" type="text"  @keyup.enter="goNext($event.target)"  />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VSelect item-color="customColorValue" v-model="gender" :items="gender_data" label="gender" @keyup.enter="goNext($event.target)"  />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="id_no" label="id_no*" type="text"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="mobile" label="mobile*" type="number"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VSelect item-color="customColorValue" v-model="language" :items="lang_data" label="language"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="phone" label="phone" type="number"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VTextField v-model="email" label="email" type="email"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="3" md="4">
              <VCombobox   item-color="customColorValue"  v-model="country" :items="countriess" item-title="name" label="country"  @keyup.enter="goNext($event.target)" />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="city" label="city" type="text" @keyup.enter="goNext($event.target)"  />
            </VCol>
            <VCol cols="12" sm="6" md="6">
              <VTextField v-model="address" label="address" type="text" @keyup.enter="goNext($event.target)"  />
            </VCol>
<!--            <VCol cols="3" sm="3" md="2" style="display: inline-block;">-->
<!--              <VCombobox v-model="select_day" :items="days" :label="$t('days')" style="width: 100%;"-->
<!--                         clearable />-->
<!--            </VCol>-->
<!--            <VCol cols="3" sm="3" md="2" style="display: inline-block;">-->
<!--              <VCombobox v-model="select_month" :items="month" :label="$t('months')" style="width: 100%;"-->
<!--                         clearable />-->
<!--            </VCol>-->
<!--            <VCol cols="3" sm="3" md="2" style="display: inline-block;">-->
<!--              <VCombobox v-model="select_year" :items="years" :label="$t('Years')" style="width: 100%;"-->
<!--                         clearable />-->
<!--            </VCol>-->

            <VCol cols="12" sm="12" md="12" style="display: flex; text-align: center;">
              <VCol cols="2" sm="2" md="2">
                <b style="font-size: 140%;">Birthdate :</b>
              </VCol>
              <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                <VCombobox v-model="select_day" :items="days" :label="$t('days')" style="width: 100%;"
                           clearable  @keyup.enter="goNext($event.target)" />
              </VCol>
              <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                <VCombobox v-model="select_month" :items="month" :label="$t('months')" style="width: 100%;"
                           clearable @keyup.enter="goNext($event.target)"  />
              </VCol>
              <VCol cols="3" sm="3" md="3" style="display: inline-block;">
                <VCombobox v-model="select_year" :items="years" :label="$t('Years')" style="width: 100%;"
                           clearable  @keyup.enter="goNext($event.target)" />
              </VCol>
            </VCol>

          </VRow>
          <VBtn @click="Add" style="float: right;" class="mt-4 mb-4">
            {{ $t('Submit and return to reservation') }}
          </VBtn>
        </VContainer>
      </VCardText>
      </VForm>
    </VCard>

  </div>
</template>

<script>
import axios from "@axios"
import router from "@/router"
import axiosIns from "@axios"

export default {
  name: "Store",


  // eslint-disable-next-line vue/component-api-style
  data() {
    return {

      search: '',
      Profile: '',
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
      gender_data: ['male', 'female', 'no gender found'],
      gender: '',
      country_data: ['1', '2'],
      country: '',
      created_by: '',
      URL: window.location.origin,
      countriess:[],
      select_year: '',
      select_day: '',
      select_month: '',
      years: [],
      days: [],
      month: [],

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
    }
  },
mounted() {
this.GetCountries()
  this.YeardData()
  this.MonthData()
  this.DayData()
    },
  // eslint-disable-next-line vue/component-api-style
  methods: {

    goNext(elem) {
      const currentIndex = Array.from(elem.form.elements).indexOf(elem)
      elem.form.elements.item(
        currentIndex < elem.form.elements.length - 1 ?
          currentIndex + 1 :
          0,
      ).focus()
    },


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
    GetCountries() {
      axiosIns
        .get(`${window.location.origin}/api/getcountries`).then(res => {
           this.countriess = res.data
        }).catch(
        err => {
          console.log(err)
        },
      )
    },
    async Add() {
      try {
        const user = await axios.post(

          `${this.URL}/api/guestProfile`,
          {
            first_name: this.firstname,
            last_name: this.lastname,
            id_no: this.id_no,
            mobile: this.mobile,
            phone: this.phone,
            email: this.email,
            address: this.address,
            city: this.city,
            language: this.language,
            date_of_birth: this.date_of_birth_new,
            gender: this.gender,
            country_id: this.country.id,
             id_type_id: 1,
          },
          {
            headers: {
              Accept: 'application/json',
              Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
            },
          }).then(res => {
          this.Profile = res.data.data[0]
          // this.$router.push({ name: `reservation-create-profile`, params: { profile: this.Profile.profile_no } })
        })

        
        

      } catch (e) {
        console.log(e)
      }
    },
  },


}
</script>


