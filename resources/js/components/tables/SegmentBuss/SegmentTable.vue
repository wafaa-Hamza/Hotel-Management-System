<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import {
  requiredValidator,
} from '@validators'
const refForm = ref()

const headers_En = [

  {
    title: 'segment name',
    key: 'name',
  },
  {
    title: 'Actions',
    key: 'actions',
  },
]

const headers_Ar = [

  {
    title: 'الاسم الشريحة',
    key: 'name_loc',
  },
  {
    title: 'العمليات',
    key: 'actions',
  },
]

</script>

<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCardText class="AddRow">
      <VCol cols="6" md="4">
        <AppTextField v-model="search" density="compact" placeholder="Search" append-inner-icon="tabler-search"
          single-line hide-details dense outlined />
      </VCol>
      <VCol cols="6" md="4">
        <VDialog v-model="dialog" width="1024">
          <template #activator="{ props }">
            <VBtn v-bind="props" v-if="$can('create', 'market segment')">
              {{ $t('Add New segment') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add New segment') }}</span>
            </VCardTitle>
            <VForm ref="refForm">
              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name"   :label="$t('segment name')" persistent-placeholder
                        :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" sm="6" md="6">
                      <VTextField v-model="name_loc"   :label="$t('segment name loc')" persistent-placeholder
                        :rules="[requiredValidator]" />
                    </VCol>
                  </VRow>
                </VContainer>
              </VCardText>
              <VCardActions>
                <VSpacer />
                <VBtn color="blue-darken-1" variant="text" @click="dialog = false">
                    {{ $t('Close') }}
                </VBtn>
                <VBtn type="submit" color="blue-darken-1" variant="text"
                  @click.prevent="refForm?.validate().then(res => { res.valid ? Add() : null })">
                     {{ $t('Submit') }}
                </VBtn>
              </VCardActions>
            </VForm>
          </VCard>
        </VDialog>
      </VCol>
    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable :headers="$i18n.locale === 'en' ? headers_En : headers_Ar" :items="Segment" :search="search"
      :items-per-page="10">
      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">
          <VBtn color="primary" v-if="$can('edit', 'market segment')">

            <VRow>
              <VDialog v-model="item.dialog3" width="1024">
                <template #activator="{ props }">
                  <VBtn v-bind="props" @click="Updates(item.raw?item.raw:item)">
                    <VIcon icon="mdi-file-edit-outline" />
                  </VBtn>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">  {{ $t('UPDATE New Room Type') }}</span>
                  </VCardTitle>
                  <VForm ref="refForm">
                    <VCardText>
                      <VContainer>
                        <VRow>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="name_edit"   :label="$t('name edit')" :rules="[requiredValidator]" />
                          </VCol>
                          <VCol cols="12" sm="6" md="6">
                            <VTextField v-model="name_loc_edit"  :label="$t('name loc edit')" :rules="[requiredValidator]" />
                          </VCol>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text" @click="dialog3 = false">
                          {{ $t('Close') }}
                      </VBtn>
                      <VBtn type="submit" color="blue-darken-1" variant="text"
                        @click.prevent="refForm?.validate().then(res => { res.valid ? submitLdger() : null })">
                         {{ $t('Submit') }}
                      </VBtn>
                    </VCardActions>
                  </VForm>
                </VCard>
              </VDialog>
            </VRow>
          </VBtn>
          <VBtn color="red" style="background: red;color: white" @click="Delete(item.raw?item.raw.id:item.id)" v-if="$can('delete', 'market segment')">
            <VIcon icon="mdi-delete"   style="font-size: 135%"/>
          </VBtn>
        </div>
      </template>
    </VDataTable>
  </div>
</template>

<script>
import axios from "@axios"

export default {
  name: "Segment",

  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      search: '',

      dialog: false,
      dialog3: false,

      segmentid: '',
      Segment: [],
      segment_id: '',

      name: '',
      name_loc: '',


      name_edit: '',
      name_loc_edit: '',


      URL: window.location.origin,


    }
  },



  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllSegment()

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
            title: `Data Deleted successfully`,
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

    getAllSegment() {
      axios
        .get(`${this.URL}/api/segment`)
        .then(response => (this.Segment = response.data))
    },




    async Add() {

      try {
        const response = await axios.post(
          `${this.URL}/api/segment`,
          {
            name: this.name,
            name_loc: this.name_loc,
          },

        )
        if (response.status != undefined) {
          this.name = ''
          this.name_loc = ''
          this.getAllSegment()
          this.dialog = false
          this.insertAlert()
        }

      } catch (e) {
        console.log(e)
      }
    },

    async Delete(GetId) {
      this.segmentid = GetId
      this.DeleteAlert()
    },

    deleteData() {
      axios
        .delete(`${this.URL}/api/segment/${this.segmentid}`)
        .then(response => (this.Segment = response))
      this.getAllSegment()
    },

    async Updates(Getdata) {

      this.segmentid = Getdata



      this.name_edit = Getdata.name
      this.name_loc_edit = Getdata.name_loc

    },

    async submitLdger() {

      try {
        const response = await axios.put(
          `${this.URL}/api/segment/${this.segmentid.id}`,
          {
            name: this.name_edit,
            name_loc: this.name_loc_edit,

          },
        )
        if (response.status != undefined) {
          this.name_edit = ''
          this.name_loc_edit = ''
          this.getAllSegment()
          this.dialog3 = false
          this.UpdateAlert()
        }


      } catch (e) {
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
  background: rgba(147, 112, 219, .6);
  ;
}

.AddRow {
  display: flex;
  flex-wrap: wrap;
  flex: 1 1 auto;
  margin: -12px;
  justify-content: right;

}
</style>
