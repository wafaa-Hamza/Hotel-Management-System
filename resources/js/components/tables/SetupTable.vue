<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>


  <br>
  <br>
  <br>

  <VTable style="table-layout: fixed">
    <thead>
      <tr>

        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('Setting name') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('Setting type') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('Setting cr_no') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('Setting phone') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('Setting mobile') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('email') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('city') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('address') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('vat_no') }}
        </th>
        <th class="text-center" style="border-bottom: 2px solid;">
          {{ $t('update') }}
        </th>
      </tr>
    </thead>


    <tbody>
      <tr


        style="text-align: left"
      >


        <td>{{ Settings.name }}</td>
        <td>{{ Settings.type }}</td>
        <td>{{ Settings.cr_no }}</td>
        <td>{{ Settings.phone }}</td>
        <td>{{ Settings.mobile }}</td>
        <td>{{ Settings.email }}</td>
        <td>{{ Settings.city }}</td>
        <td>{{ Settings.address }}</td>
        <td>{{ Settings.vat_no }}</td>
        <td>
          <VBtn
            color="primary"
          >
            <VRow>
              <VDialog
                v-model="Settings.dialog3"
                width="1024"
              >
                <template #activator="{ props }">
                  <VBtn
                    v-bind="props"
                    @click="Updates(Settings)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">Update Feature data</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="name_edit"
                            :label="$t('Name edit')" 
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="name_loc_edit"
                             :label="$t('Name loc edit')" 
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="type_edit"
                             :label="$t('type edit')" 
                          />
                        </VCol>



                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="email_edit"
                             :label="$t('email edit')" 
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="phone_edit"
                             :label="$t('phone edit')" 
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="mobile_edit"
                             :label="$t('mobil edit')" 
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="cr_no_edit"
                             :label="$t('Cr No edit')" 
                          />
                        </VCol>



                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="city_edit"
                             :label="$t('city edit')" 
                          />
                        </VCol>



                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="address_edit"
                             :label="$t('address edit')" 
                          />
                        </VCol>


                        <VCol
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <VTextField
                            v-model="vat_no_edit"
                             :label="$t('Vat No edit')" 
                          />
                        </VCol>
                      </VRow>
                    </VContainer>
                  </VCardText>
                  <VCardActions>
                    <VSpacer />
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="item.dialog3 = false"
                    >
                        {{ $t('Close') }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="updateUser"
                    >
                       {{ $t('Update') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
        </td>
      </tr>
    </tbody>
  </VTable>
  </div>
</template>

<script>
import axios from "@axios"

export default {


  // eslint-disable-next-line vue/component-api-style
  data(){
    return {

      search:'',
      dialog: false,
      dialog3: false,
      Settings:[],
      Settingid:'',


      name_edit:'',
      name_loc_edit:'',
      type_edit:'',
      email_edit:'',
      phone_edit:'',
      cr_no_edit:'',
      city_edit:'',
      address_edit:'',
      vat_no_edit:'',
      mobile_edit:'',



    }
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllSetting()
  },

  // eslint-disable-next-line vue/component-api-style
  methods:{
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


    getAllSetting(){
      axios
        .get('api/settings')
        .then(response => (this.Settings = response.data))
    },


    async  Updates(Getdata){

      this.Settingid = Getdata




      this.name_edit= Getdata.name,
      this.name_loc_edit= Getdata.name_loc,
      this.type_edit= Getdata.type,
      this.email_edit= Getdata.email,
      this.phone_edit= Getdata.phone,
      this.mobile_edit= Getdata.mobile,
      this.address_edit= Getdata.address,
      this.city_edit= Getdata.city,
      this.cr_no_edit= Getdata.cr_no,
      this.vat_no_edit= Getdata.vat_no

    },


    async updateUser() {


      try {
        const user = await axios.put(
          `api/settings/${this.Settingid.id}`,
          {
            name: this.name_edit,
            name_loc: this.name_loc_edit,
            type: this.type_edit,
            cr_no: this.cr_no_edit,
            phone: this.phone_edit,
            mobile: this.mobile_edit,
            email: this.email_edit,
            city: this.city_edit,
            address: this.address_edit,
            vat_no: this.vat_no_edit,

          },
        )

        console.log(user)

        this.getAllSetting()
        this.dialog3 = false
        this.UpdateAlert()
      } catch(e) {
        console.log(e)
      }

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
</style>
