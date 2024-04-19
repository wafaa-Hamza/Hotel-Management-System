<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { avatarText } from '@/@core/utils/formatters'
import {
  betweenValidator,
  requiredValidator,
} from '@validators'

// headers
const headers_en = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'jv_date',
    key: 'jv_date',
  },
  {
    title: 'fyear loc',
    key: 'fyear',
  },
  {
    title: 'mdescription',
    key: 'mdescription',
  },
  {
    title: 'total_debit room',
    key: 'total_debit',
  },
  {
    title: 'source_code',
    key: 'source_code',
  },
  {
    title: 'Sys_ip',
    key: 'Sys_ip',
  },
  {
    title: 'update',
    key: 'actions',
  },
]

// headers Ae
const headers_ar = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'jv_date',
    key: 'jv_date',
  },
  {
    title: 'fyear loc',
    key: 'fyear',
  },
  {
    title: 'mdescription',
    key: 'mdescription',
  },
  {
    title: 'total_debit room',
    key: 'total_debit',
  },
  {
    title: 'source_code',
    key: 'source_code',
  },
  {
    title: 'Sys_ip',
    key: 'Sys_ip',
  },
  {
    title: 'تعديل',
    key: 'actions',
  },
]

</script>

<template>


  <div >

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VCardText  class="AddRow">

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

    </VCardText>
    <!-- 👉 Datatable  -->
    <VDataTable
      :headers="$i18n.locale === 'en' ? headers_en : headers_ar"
      :items="vouch"
      :search="search"
      :items-per-page="10"
    >
      <template #item.actions="{ item }">
        <div class="d-flex gap-1">

          <VBtn
            color="primary"
            @click="Edit(item.raw.id)"
          >
            {{ $t('Update') }}
          </VBtn>

        </div>
      </template>
    </VDataTable>



  </div>

</template>

<script>
import axios from "@axios"

export default {
  name: "AllVoucher",

  // eslint-disable-next-line vue/component-api-style
  data(){
    return {
      search: '',
      URL: window.location.origin,
      vouch:[]


    }
  },

  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.journalVoucher()
  },

  // eslint-disable-next-line vue/component-api-style
  methods: {
    journalVoucher(){
      axios
        .get(`${this.URL}/api/journalVoucher`)
        .then(res => {
          this.vouch = res.data.data
        })
    },
  async  Edit(Getdata){


        await this.$router.push({ name: `voucher-data` , params: { data:Getdata } })


    }
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
