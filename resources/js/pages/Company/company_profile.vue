<script setup>
import {
  requiredValidator
} from '@validators'
const form = ref()
</script>

<template>
  <div style="margin-top: 5%;">

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div style="display: flex;width: 45%;justify-content: space-between;float: inline-end;">
      <VRow justify="">

        <div style="width: 65%;display: flex;justify-content: space-between">


          <VTextField v-model="search" type="text" :label="$t('Search')" style="width: 70%;" />
        </div>
        <VDialog v-model="dialog" width="1024" scroll-strategy="none" z-index="1000">
          <template #activator="{ props }">
            <VBtn v-bind="props" class="ml-5 mr-5" v-if="$can('create', 'companyprofile')">
              {{ $t('Add Company') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Details') }}</span>
            </VCardTitle>
            <VForm ref="form">
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12">
                      <VCombobox v-model="country_id" :items="countriess"
                                 :item-title="$i18n.locale === 'en' ? 'name_loc' : 'name'" item-value="id" :label="$t('Country')"
                                 :rules="[requiredValidator]" @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="company_code" :label="$t('Company code')" :rules="[requiredValidator]" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="company_name" :label="$t('Company name')" :rules="[requiredValidator]" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">

                      <VTextField v-model="company_name_loc" :label="$t('Company name AR')"
                                  :rules="[requiredValidator]" @keyup.enter="goNext($event.target)"  />

                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="mobile" :label="$t('Mobile')" type="number" :rules="[requiredValidator]"  @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="max_credit_limit" :label="$t('Maximum credit limit')" type="number"
                                  :rules="[requiredValidator]"  @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="city" :label="$t('City')"  @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="address" :label="$t('Address')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="phone_1" :label="$t('First phone')" type="number"  @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="phone_2" :label="$t('Second phone')" type="number" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="fax" :label="$t('Fax')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="email" :label="$t('E-mail')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="p_o_box" :label="$t('P o box')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="zip_number" :label="$t('Zip number')" type="number" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="tax_no" :label="$t('Tax number')"  @keyup.enter="goNext($event.target)" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="contact_person" :label="$t('Contact person')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="position" :label="$t('Position')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="contact_phone" :label="$t('Contact phone')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="contact_mobile" :label="$t('Contact mobile')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                    <VCol cols="12" sm="6" md="12">
                      <VTextField v-model="def_rate_code" :label="$t('Default rate code')" @keyup.enter="goNext($event.target)"  />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="dialog = false, clear()">
                  {{ $t('Close') }}
                </VBtn>
                <VBtn type="submit" color="blue-darken-1" variant="text"
                      @click.prevent="form?.validate().then(res => { res.valid ? CreateCompanyProfile() : null })">
                  {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VForm>
          </VCard>
        </VDialog>
      </VRow>
    </div>
    <div style="display: flex;width: 14%;justify-content: space-between;float: inline-start;">
      <VBtn class="btn" @click="exportExecl">
        {{ $t('export') }}
      </VBtn>
      <VBtn class="btn" @click="Downpdf">
        {{ $t('PDF') }}
      </VBtn>
    </div>
    <br>
    <br>
    <br>
    <VTable>
      <thead>
      <tr>
        <th>
          {{ $t('Company code') }}
        </th>
        <th>
          {{ $t('Company name') }}
        </th>
        <th>
          {{ $t('Country') }}
        </th>
        <th>
          {{ $t('City') }}
        </th>
        <th>
          {{ $t('Mobile') }}
        </th>
        <th>
          {{ $t('Fax') }}
        </th>
        <th>
          {{ $t('E-mail') }}
        </th>
        <th>
          {{ $t('Contact person') }}
        </th>
        <th>
          {{ $t('Contact mobile') }}
        </th>
        <th>
          {{ $t('Maximum credit limit') }}
        </th>
        <th>
          {{ $t('Current balance') }}
        </th>
        <th>
          {{ $t('Active') }}
        </th>
        <th v-if="$can('edit', 'companyprofile')">
          {{ $t('Show and edit') }}
        </th>
        <th v-if="$can('delete', 'companyprofile')">
          {{ $t('Delete') }}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in company_profiles" :key="item.id">
        <td>{{ item.company_code }}</td>
        <td>
          {{ $i18n.locale === 'en' ? item.company_name : item.company_name_loc }}
        </td>
        <td>
          {{ $i18n.locale === 'en' ? item.country.name_loc : item.country.name }}
        </td>
        <td>{{ item.city }}</td>
        <td>{{ item.mobile }}</td>
        <td>{{ item.fax }}</td>
        <td>{{ item.email }}</td>
        <td>{{ item.contact_person }}</td>
        <td>{{ item.contact_mobile }}</td>
        <td>{{ item.max_credit_limit }}</td>
        <td>{{ item.current_balance }}</td>
        <td>
          <VChip class="ma-2">
            <VIcon v-if="item.active" icon="mdi-check" color="success" />
            <VIcon v-if="!item.active" icon="mdi-close-octagon" color="error" />
          </VChip>
        </td>
        <td v-if="$can('edit', 'companyprofile')">
          <VBtn color="primary">
            <VRow>
              <VDialog v-model="item.dialogVisible" width="1024" z-index="1000" scroll-strategy="none">
                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('Update') }}</span>
                  </VCardTitle>
                  <VForm ref="form">
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12">
                            <VCombobox v-model="country_id_edit" :items="countriess"
                                       :item-title="$i18n.locale === 'en' ? 'name_loc' : 'name'" item-value="id"
                                       :label="$t('Country')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="company_code_edit" :label="$t('Company code')"
                                        :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="company_name_edit" :label="$t('Company name')"
                                        :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="company_name_loc_edit" :label="$t('Other company name')"
                                        :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="mobile_edit" :label="$t('Mobile')" type="number"
                                        :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="max_credit_limit_edit" type="number"
                                        :label="$t('Maximum credit limit')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="city_edit" :label="$t('City')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="address_edit" :label="$t('Address')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="phone_1_edit" :label="$t('First phone')" type="number" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="phone_2_edit" :label="$t('Second phone')" type="number" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="fax_edit" :label="$t('Fax')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="email_edit" :label="$t('E-mail')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="p_o_box_edit" :label="$t('P o box')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="zip_number_edit" type="number" :label="$t('Zip number')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="tax_no_edit" :label="$t('Tax number')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="contact_person_edit" :label="$t('Contact person')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="position_edit" :label="$t('Position')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="contact_phone_edit" :label="$t('Contact phone')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="contact_mobile_edit" :label="$t('Contact mobile')" />
                          </VCol>
                          <VCol cols="12" sm="6" md="12">
                            <VTextField v-model="def_rate_code_edit" :label="$t('Default rate code')" />
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text"
                            @click="item.dialogVisible = false, update_errors = null">
                        {{ $t('Close') }}
                      </VBtn>
                      <VBtn type="submit" color="blue-darken-1" variant="text"

                            @click.prevent="form[0]?.validate().then(res => { res.valid ? UpdateCompanyProfile() : null })">

                        {{ $t('Update') }}
                      </VBtn>
                    </VCardActions>
                  </VForm>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
        </td>
        <td v-if="$can('delete', 'companyprofile')">
          <VBtn color="red" style="background: red;color: white;" @click="Delete(item.id)">
            <VIcon icon="mdi-delete" style="font-size: 135%;" />
          </VBtn>
        </td>
      </tr>
      </tbody>
      <br>
    </VTable>
  </div>
</template>

<script>
import axiosIns from "@axios"
import Swal from 'sweetalert2'

export default {

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: null,
      page: 1,
      dialog: false,
      dialog3: false,
      errors: null,
      update_errors: null,
      isSnackbarVisible: ref(false),
      countriess: [],
      company_profiles: null,
      company_code: null,
      company_name: null,
      company_name_loc: null,
      city: null,
      address: null,
      phone_1: null,
      phone_2: null,
      mobile: null,
      fax: null,
      email: null,
      p_o_box: null,
      zip_number: null,
      tax_no: null,
      contact_person: null,
      position: null,
      contact_phone: null,
      contact_mobile: null,
      max_credit_limit: 0,
      current_balance: null,
      def_rate_code: null,
      country_id: null,
      company_profile_id: null,
      company_profile_id_edit: null,
      company_code_edit: null,
      company_name_edit: null,
      company_name_loc_edit: null,
      city_edit: null,
      address_edit: null,
      phone_1_edit: null,
      phone_2_edit: null,
      mobile_edit: null,
      fax_edit: null,
      email_edit: null,
      p_o_box_edit: null,
      zip_number_edit: null,
      tax_no_edit: null,
      contact_person_edit: null,
      position_edit: null,
      contact_phone_edit: null,
      contact_mobile_edit: null,
      max_credit_limit_edit: null,
      current_balance_edit: null,
      def_rate_code_edit: null,
      country_id_edit: null,
      package_code_edit: null,
      name_edit: null,
      name_loc_edit: null,
      meal_id_edit: [],
      meals_edit: null,
      packge_id_delete: null,
      package_id: null,
      meal_id_array: [],
    }
  },
  // eslint-disable-next-line vue/component-api-style
  computed: {
    filterData() {
      return this.getusers.filter(user => user.firstname.includes(this.search))
    },
    hasErrors() {
      return this.errors !== null
    },
    hasupdateErrors() {
      return this.update_errors !== null
    },
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.GetCompanyProfiles()
    this.GetCountries()
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
    clear() {
      this.company_profile_id_edit = null
      this.company_code = null
      this.company_name = null
      this.company_name_loc = null
      this.city = null
      this.address = null
      this.phone_1 = null
      this.phone_2 = null
      this.mobile = null
      this.fax = null
      this.email = null
      this.p_o_box = null
      this.zip_number = null
      this.tax_no = null
      this.contact_person = null
      this.position = null
      this.contact_phone = null
      this.contact_mobile = null
      this.max_credit_limit = null
      this.current_balance = null
      this.def_rate_code = null
      this.country_id = null
    },
    emailPhoneValidator(value) {
      if (this.phone_1 === null || this.phone_1.trim() === '') {
        return !!value || this.$t('Field is required when phone is empty.');
      }
      return true;
    },
    PhoneEmailValidator(value) {
      if (this.email === null || this.email.trim() === '') {
        return !!value || this.$t('Field is required when email is empty.');
      }
      return true;
    },
    GetCountries() {
      axiosIns
        .get(`${window.location.origin}/api/getcountries`)
        .then(res => {
          console.log(res)
          this.countriess = res.data
        }).catch(
        err => {
          console.log(err)
        },
      )
    },
    GetCompanyProfiles() {
      axiosIns
        .get(`${window.location.origin}/api/companyProfile`)
        .then(res => {
          this.company_profiles = res.data
        }).catch(
        err => {
          console.log(err)
        },
      )
    },
    async CreateCompanyProfile() {
      const data = {
        company_code: this.company_code,
        company_name: this.company_name,
        company_name_loc: this.company_name_loc,
        city: this.city,
        address: this.address,
        phone_1: this.phone_1,
        phone_2: this.phone_2,
        mobile: this.mobile,
        fax: this.fax,
        email: this.email,
        p_o_box: this.p_o_box,
        zip_number: this.zip_number,
        tax_no: this.tax_no,
        contact_person: this.contact_person,
        position: this.position,
        contact_phone: this.contact_phone,
        contact_mobile: this.contact_mobile,
        max_credit_limit: this.max_credit_limit,
        def_rate_code: this.def_rate_code,
        country_id: this.country_id.id,
      }

      await axiosIns.post(`${window.location.origin}/api/companyProfile`, data).then(
        res => {
          if (res.status != undefined) {
            this.GetCompanyProfiles()
            this.dialog = false
            this.insertAlert()
          }
        },
      ).catch(
        err => {
          this.errors = err
          console.log(err)
        },
      )
    },
    Delete(GetId) {
      this.company_profile_id = GetId
      this.DeleteAlert()
    },
    async deleteData() {
      axiosIns
        .delete(`${window.location.origin}/api/companyProfile/${this.company_profile_id}`, {
          headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
          },
        })
        .then(response => (this.GetCompanyProfiles()))

    },
    Updates(Getdata) {
      this.company_profile_id_edit = Getdata.id
      this.company_code_edit = Getdata.company_code
      this.company_name_edit = Getdata.company_name
      this.company_name_loc_edit = Getdata.company_name_loc
      this.city_edit = Getdata.city
      this.address_edit = Getdata.address
      this.phone_1_edit = Getdata.phone_1
      this.phone_2_edit = Getdata.phone_2
      this.mobile_edit = Getdata.mobile
      this.fax_edit = Getdata.fax
      this.email_edit = Getdata.email
      this.p_o_box_edit = Getdata.p_o_box
      this.zip_number_edit = Getdata.zip_number
      this.tax_no_edit = Getdata.tax_no
      this.contact_person_edit = Getdata.contact_person
      this.position_edit = Getdata.position
      this.contact_phone_edit = Getdata.contact_phone
      this.contact_mobile_edit = Getdata.contact_mobile
      this.max_credit_limit_edit = Getdata.max_credit_limit
      this.current_balance_edit = Getdata.current_balance
      this.def_rate_code_edit = Getdata.def_rate_code
      this.country_id_edit = Getdata.country
    },
    async UpdateCompanyProfile() {
      const data = {
        company_code: this.company_code_edit,
        company_name: this.company_name_edit,
        company_name_loc: this.company_name_loc_edit,
        city: this.city_edit,
        address: this.address_edit,
        phone_1: this.phone_1_edit,
        phone_2: this.phone_2_edit,
        mobile: this.mobile_edit,
        fax: this.fax_edit,
        email: this.email_edit,
        p_o_box: this.p_o_box_edit,
        zip_number: this.zip_number_edit,
        tax_no: this.tax_no_edit,
        contact_person: this.contact_person_edit,
        position: this.position_edit,
        contact_phone: this.contact_phone_edit,
        contact_mobile: this.contact_mobile_edit,
        max_credit_limit: this.max_credit_limit_edit,
        def_rate_code: this.def_rate_code_edit,
        country_id: this.country_id_edit.id,
        current_balance: this.current_balance_edit,
      }

      await axiosIns.put(`${window.location.origin}/api/companyProfile/${this.company_profile_id_edit}`, data).then(
        res => {
          if (res.status != undefined) {
            this.GetCompanyProfiles()
            this.dialog3 = false
            this.UpdateAlert()
          }

        },
      ).catch(
        err => {
          console.log(err)
          this.update_errors = err.response.data.errors
        },
      )
    },
    exportExecl() {
      const XLSX = xlsx
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.aoa_to_sheet(this.framework1)

      XLSX.utils.book_append_sheet(workbook, worksheet, "framework")
      XLSX.writeFile(workbook, "users.xlsx")
    },
    Downpdf() {
      let docDefinition = {
        content: [
          {
            layout: 'lightHorizontalLines',
            table: {
              headerRows: 1,
              widths: ['*', "auto", 100, "*"],

              body: this.framework1,

            },
          },
        ],
      }

      // pdfMake.createPdf(docDefinition).open()
      pdfMake.createPdf(docDefinition).download()

      // pdfMake.createPdf(docDefinition).print()

    },
    insertAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'companyprofile has been created',
        showConfirmButton: false,
        timer: 2000,
      })
    },
    DeleteAlert() {

      Swal.fire({
        icon: 'error',
        title: 'Delete this Meal companyprofile?',
        showDenyButton: true,
        showCancelButton: true,
        showConfirmButton: false,
        denyButtonText: `Delete`,
      }).then(result => {

        if (result.isDenied) {

          Swal.fire({
            position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
            icon: 'success',
            title: 'companyprofile has been deleted',
            showConfirmButton: false,
            timer: 2000,
          })
          this.deleteData()
        }
      })
    },
    UpdateAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'companyprofile has been updated',
        showConfirmButton: false,
        timer: 2000,
      })
    },
  },
}
</script>


<route lang="yaml">
meta:
action: view
subject: companyprofile
</route>
