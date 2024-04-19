<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers_En =[

  {
    title:'id',
    key: 'permission_id',
  },
  {
    title: 'Permission name',
    key: 'permission_name',
  },
]

const headers_Ar = [

  {
    title: 'المعرف',
    key: 'permission_id',
  },
  {
    title: 'اسم القاعدة',
    key: 'permission_name',
  },
]

</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
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
               {{$t('Add Permissions')}}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">Add New Permissions</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <VTextField
                      v-model="Permiss_name"
                      :label="$t('Permiss name')"
                      persistent-placeholder
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
                  {{$t('Close')}}
              </VBtn>
              <VBtn
                color="blue-darken-1"
                variant="text"
                @click="Add"
              >
                {{$t('Submit')}}
              </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_En : headers_Ar"
      :items="Permissions"
      :search="search"
      :items-per-page="10"
    />
  </div>
</template>

<script>
import axios from "@axios"

export default {
  name: "PermissionTable",

  // eslint-disable-next-line vue/component-api-style
  data(){
    return {
      search: '',
      dialog: false,
      Permissions: [],
      Rolesid: '',

      Permiss_name: '',

      URL: window.location.origin,

    }
  },


  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllPermiss()
  },

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

    getAllPermiss(){
      axios
        .get(`${this.URL}/api/getAllPermissios`)
        .then(response => (this.Permissions = response.data.data))
    },

    async  Add(){

      try {
        const user = await axios.post(
          `${this.URL}/api/storePermission`,
          {
            name: this.Permiss_name,
          },
        )

        this.Permiss_name=''
        this.getAllPermiss()
        this.dialog = false
        this.insertAlert()
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



