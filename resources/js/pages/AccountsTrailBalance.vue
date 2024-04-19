<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { avatarText } from '@/@core/utils/formatters'
import data from '@/views/demos/forms/tables/data-table/datatable'

const headers = [
  {
    title: '',
    key: 'data-table-expand',
  },
  {
    title: '#',
    key: 'index',
  },
  {
    title: 'Name',
    key: 'label',
  },
  {
    title: 'Open Debit',
    key: 'OpenDebit',
  },
  {
    title: 'Open Credit',
    key: 'OpenCredit',
  },
  {
    title: 'Total Debit',
    key: 'TotalDebit',
  },
  {
    title: 'Total Credit',
    key: 'TotalCredit',
  },
  {
    title: 'Balance',
    key: 'Balance',
  },
  // {
  //   title: 'account_type',
  //   key: 'status',
  // },
]

const resolveStatusVariant = status => {
  if (status === 1)
    return {
      color: 'primary',
      text: 'Current',
    }
  else if (status === 2)
    return {
      color: 'success',
      text: 'Professional',
    }
  else if (status === 3)
    return {
      color: 'error',
      text: 'Rejected',
    }
  else if (status === 4)
    return {
      color: 'warning',
      text: 'Resigned',
    }
  else
    return {
      color: 'info',
      text: 'Applied',
    }
}
const addIndexToItems = (items) => {
  return items.map((item, index) => {
    return { index: index + 1, ...item }
  })
}
</script>

<template>
  <div>


    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <VDataTable
      :headers="headers"

      :items="addIndexToItems(TrailBalance)"
      :items-per-page="10"
      expand-on-click
      fixed-header
    >

      <!-- Expanded Row Data -->
      <template #expanded-row="slotProps">

        <tr class="v-data-table__tr" v-for="(child,index) in slotProps.item.raw.children">
          <td></td>
          <td><h4>1.{{++index}}</h4></td>
          <td >
            <h4>{{ child.label }}</h4>

          </td>
          <td >
            <h4>{{ child.OpenDebit }}</h4>
          </td>
          <td >
            <h4>{{ child.OpenCredit }}</h4>
          </td>
          <td >
            <h4>{{ child.TotalDebit }}</h4>
          </td>
          <td >
            <h4>{{ child.TotalCredit }}</h4>
          </td>
          <td >
            <h4>{{ child.Balance }}</h4>
          </td>


        </tr>


        <tr v-for="(child,index) in slotProps.item.raw.children">
          <td></td>
          <td><h4>1.{{++index}}</h4></td>
          <td style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.label}}</h4>
          </td>
          <td  style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.OpenDebit}}</h4>
          </td>
          <td  style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.OpenCredit}}</h4>
          </td>
          <td  style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.TotalDebit}}</h4>
          </td>
          <td  style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.TotalCredit}}</h4>
          </td>

          <td  style="margin: 12px">
            <h4 v-for="childOfChild in child.children">{{childOfChild.Balance}}</h4>
          </td>

        </tr>




      </template>

      <!-- full name -->
      <template #item.full_name="{ item }">
        <div class="d-flex align-center">
          <VAvatar
            size="32"
            :color="item.raw.avatar ? '' : 'primary'"
            :class="item.raw.avatar ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.raw.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="item.raw.avatar"
              :src="item.raw.avatar"
            />
            <span v-else>{{ avatarText(item.raw.full_name) }}</span>
          </VAvatar>
          <div class="d-flex flex-column ms-3">
            <span class="d-block font-weight-medium text--primary text-truncate">{{ item.raw.full_name }}</span>
            <small>{{ item.raw.post }}</small>
          </div>
        </div>
      </template>

      <template #item.status="{ item }">
        <VChip
          :color="resolveStatusVariant(item.raw.status).color"
          class="font-weight-medium"
          size="small"
        >
          {{ resolveStatusVariant(item.raw.status).text }}
        </VChip>
      </template>
    </VDataTable>

  </div>
</template>

<script>
import axios from "@axios"

export default {
  data(){
    return{
      i:0,
      TrailBalance:[],
      ChildOfTrailBalance:[],
      ChildOfTrailBalance1:[],
      URL : window.location.origin,
    }
  },
  mounted() {
    this.getDaily()

  },
  // eslint-disable-next-line vue/component-api-style
  methods:{

    getDaily() {

      axios.post(`${this.URL}/api/account-balance`,{
        startDate:this.start_dates='2023-05-02',
        endDate:this.end_dates='2023-07-02',
      }).then(res =>(this.TrailBalance = res.data.data,this.ChildOfTrailBalance=res.data.data,this.ChildOfTrailBalance1=this.ChildOfTrailBalance.map((ele)=>ele.children)))
      this.Visability = true,

        this.$emit('getAllDaily')
    },


  },
}
</script>
