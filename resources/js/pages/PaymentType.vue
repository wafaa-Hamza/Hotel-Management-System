<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { avatarText } from '@/@core/utils/formatters'
import { MoreThanZero } from "@validators"

// headers
const headers_en = [
  {
    title: 'code',
    key: 'code',
  },
  {
    title: 'NAME',
    key: 'name',
  },
  {
    title: 'type',
    key: 'type',
  },
  {
    title: 'payment modes',
    key: 'modes.name',
  },
  {
    title: 'is_cash',
    key: 'is_cash',
  },
  {
    title: 'gl account',
    key: 'gl_account',
  },
  {
    title: 'Update',
    key: 'actions',
  },
]

const headers_ar = [
  {
    title: 'الكود',
    key: 'code',
  },
  {
    title: 'الأسم',
    key: 'name_loc',
  },
  {
    title: 'النوع',
    key: 'type',
  },

  {
    title: 'طريقة الدفع',
    key: 'payment_modes_id',
  },
  {
    title: 'كاش',
    key: 'is_cash',
  },
  {
    title: 'gl_account',
    key: 'gl_account',
  },
  {
    title: 'تعديل',
    key: 'actions',
  },
]

const resolveStatusVariant = is_cash => {
  if (is_cash == 1)
    return {
      color: 'primary',
      text: 'true',
    }

  else
    return {
      color: 'error',
      text: 'false',
    }
}
</script>

<template>
  <div>
    <Breadcrumb />
    <VCardText class="AddRow">
      <VCol
        cols="6"
        md="4"
      >
        <AppTextField
          v-model="search"
          density="compact"
          :placeholder="$t('Search')"
          append-inner-icon="tabler-search"
          single-line
          hide-details
          dense
          outlined
        />
      </VCol>
      <VCol
        cols="6"
        md="4"
      >
        <VDialog
          v-model="dialog"
          width="1024"
        >
          <template #activator="{ props }">
            <VBtn v-bind="props">
              {{ $t('Add  Payment type') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add Payment type') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VTextField
                      v-model="payment_code"
                      persistent-placeholder
                      :label="$t('Code')"
                      type="number"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VTextField
                      v-model="PaymentName"
                      persistent-placeholder
                      type="text"
                      :label="$t('Payment Name en')"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VTextField
                      v-model="PaymentName_loc"
                      persistent-placeholder
                      type="text"
                      :label="$t('Payment Name ar')"
                    />
                  </VCol>


                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VCombobox
                      v-model="payment_type_mode"
                      :label="$t('payment_modes')"
                      persistent-placeholder
                      :items="payment_modes"
                      item-title="name"
                      item-color="customColorValue"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VCombobox
                      v-model="payment_type"
                      :label="$t('payment_type')"
                      persistent-placeholder
                      :items="payment_types"
                      item-title="name"
                      item-color="customColorValue"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="5"
                    md="5"
                  >
                    <VTextField
                      v-model="gl_account"
                      persistent-placeholder
                      type="number"
                      :label="$t('gl account')"
                      :rules="[MoreThanZero]"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="3"
                    md="3"
                  >
                    <VCheckbox
                      v-model="is_cash"
                      :label="`is_cash: ${is_cash.toString()}`"
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
                @click="dialog = false"
              >
                {{ $t('Close') }}
              </VBtn>
              <VBtn
                color="blue-darken-1"
                variant="text"
                @click="Add"
              >
                {{ $t('Submit') }}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="Payments"
      :search="search"
      :items-per-page="10"
    >

      <!-- status -->
      <template #item.is_cash="{ item }">

        <VChip
          :color="item.raw?resolveStatusVariant(item.raw.is_cash).color:resolveStatusVariant(item.is_cash).color"
          size="small"
        >
          {{ resolveStatusVariant(item.is_cash).text }}
        </VChip>
      </template>

      <!-- Actions -->
      <template #item.actions="{ item }">

        <div class="d-flex gap-1">

          <VBtn color="primary">
            <VRow>
              <VDialog
                v-model="item.updatedialog"
                width="1024"
                scroll-strategy="none"
              >

                <template #activator="{ props }">

                  <VBtn
                    v-bind="props"
                    @click="item.raw?Updates(item.raw):Updates(item)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('UPDATE Payment type') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>
                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VTextField
                            v-model="payment_code_edit"
                            :label="$t('Payment Code')"
                            type="number"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VTextField
                            v-model="PaymentName_edit"
                            :label="$t('payment Name edit en')"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VTextField
                            v-model="PaymentName_loc_edit"
                            :label="$t('payment Name edit ar')"
                          />
                        </VCol>

                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VCombobox
                            v-model="payment_mode_edit"
                            :label="$t('payment modes edit')"
                            persistent-placeholder
                            :items="payment_modes"
                            item-title="name"
                            item-color="customColorValue"
                          />

                        </VCol>

                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VCombobox
                            v-model="payment_type_edit"
                            :label="$t('payment_type edit')"
                            persistent-placeholder
                            :items="payment_types"
                            item-title="name"
                            item-color="customColorValue"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <VTextField
                            v-model="gl_account_edit"
                            persistent-placeholder
                            type="number"
                            :label="$t('gl account edit')"
                            :rules="[MoreThanZero]"
                          />
                        </VCol>
                        <VCol
                          cols="12"
                          sm="3"
                          md="3"
                        >
                          <VCheckbox
                            v-model="is_cash_edit"
                            :label="`is cash edit: ${is_cash_edit.toString()}`"
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
                      @click="item.updatedialog = false"
                    >
                      {{ $t('Close') }}
                    </VBtn>
                    <VBtn
                      color="blue-darken-1"
                      variant="text"
                      @click="updateUser"
                    >
                      {{ $t('Submit') }}
                    </VBtn>
                  </VCardActions>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
        </div>
      </template>
    </VDataTable>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data(){
    return {
      search: '',
      dialog: false,
      updatedialog: false,
      Payments: [],
      typeid: '',
      id: '',

      is_cash: true,
      payment_code: '',
      PaymentName: '',
      gl_account: 0,
      PaymentName_loc: '',

      is_cash_edit: false,
      payment_type_mode: '',
      payment_mode_edit: '',
      gl_account_edit: 0,
      payment_type_edit: '',
      payment_modes: [],
      payment_type: [],
      payment_types: ['CR', 'DR'],





      payment_code_edit: '',
      PaymentName_edit: '',
      PaymentName_loc_edit: '',
      fyear_edit: '',
      room_type_pax_edit: '',
      voucher_type_edit: [],
      room_type_number_edit: '',
      room_type_rent_edit: '',

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
  mounted() {
    this.getAllTypes()
    this.getPaymentMode()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {

    getAllTypes(){
      axios
        .get(`${this.URL}/api/payment`)
        .then(res => {
          this.Payments = res.data
        })
    },
    getPaymentMode(){
      axios
        .get(`${this.URL}/api/paymentmode`)
        .then(res => {
          this.payment_modes = res.data
        })
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
    async  Add(){

      try {
        const user = await axios.post(
          `${this.URL}/api/payment`,
          {
            code: this.payment_code,
            name: this.PaymentName,
            name_loc: this.PaymentName_loc,

            is_cash: this.is_cash,
            type: this.payment_type,
            payment_modes_id: this.payment_type_mode.id,
            gl_account: this.gl_account,


          },
        )



        this.payment_code=''
        this.PaymentName=''
        this.PaymentName_loc=''
        this.payment_type=''
        this.payment_type_mode=''
        this.gl_account=''


        this.getAllTypes()
        this.dialog = false
        this.insertAlert()
      } catch(e) {
        console.log(e)
      }
    },



    async  Updates(Getdata){
      this.typeid = Getdata

      this.payment_code_edit = Getdata.code
      this.PaymentName_edit = Getdata.name
      this.PaymentName_loc_edit = Getdata.name_loc

      this.is_cash_edit= Getdata.is_cash==0?false:true
      this.payment_type_edit = Getdata.type
      this.payment_mode_edit = Getdata.modes
      this.gl_account_edit = Getdata.gl_account

    },

    async updateUser() {
      try {
        const user = await axios.put(

          `${this.URL}/api/payment/${this.typeid.id}`,
          {

            code: this.payment_code_edit,
            name: this.PaymentName_edit,
            name_loc: this.PaymentName_loc_edit,

            is_cash: this.is_cash_edit,
            type: this.payment_type_edit,
            payment_modes_id: this.payment_mode_edit.id,
            gl_account: this.gl_account_edit,
          },
        )

        this.getAllTypes()
        this.updatedialog = false
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
.AddRow{
  display: flex;
  flex-wrap: wrap;
  flex: 1 1 auto;
  margin: -12px;
  justify-content: right;

}
</style>
