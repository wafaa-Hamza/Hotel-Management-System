<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
</script>

<template>
  <div style="margin-top: 5%">

    <Breadcrumb></Breadcrumb>
    <VRow>
      <VCol cols="12" sm="6" md="2">
        <VSelect item-color="customColorValue" v-model="request_type" :label="$t('Type')" :items="types" />
      </VCol>
      <VCol cols="12" sm="6" md="4">
        <VTextField v-model="url" :label="$t('URL')" />
      </VCol>
      <VCol cols="12" sm="6" md="1">
        <VBtn @click="test">
          {{ $t('Submit') }}
        </VBtn>
      </VCol>
      <VCol cols="12" sm="6" md="1">
        <VBtn @click="clear">

          {{ $t('clear') }}
        </VBtn>
      </VCol>



      <VCol cols="12" sm="6" md="6">
        <VTextarea v-model="body" :label="$t('Body')" type="json" rows="20" />
      </VCol>
      <VCol cols="12" sm="6" md="6">
        <VTextarea v-model="response" :label="$t('Response')" type="json" rows="20" />
      </VCol>
    </VRow>

  </div>
</template>

<script>
import axiosIns from "axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      request_type: 'get',
      types: [
        'post',
        'get',
        'put',
        'delete',
      ],
      url: '',
      body: '',
      response: '',
      data: '',
    }
  },
  // eslint-disable-next-line vue/component-api-style
  methods:
  {
    clear() {
      this.request_type = 'get'
      this.url = ''
      this.body = ''
      this.response = ''
    },

    async test() {
      console.log(this.body)
      if (this.body != '') {
        this.data = JSON.parse(this.body)
      }
      else {
        this.data = null
      }

      axiosIns.request({
        method: this.request_type,
        url: `https://masaweb.co//api/${this.url}`,
        data: this.data,
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('accessToken'),
        },
      }).then(
        res => {
          this.response = JSON.stringify(res.data, null, 2)
        },
      ).catch(
        err => {
          this.response = JSON.stringify(err, null, 2)
        },
      )
    },
  },
  mounted() {

  },
}
</script>
