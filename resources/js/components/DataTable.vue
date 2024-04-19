<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { avatarText } from '@/@core/utils/formatters'
import data from '@/views/demos/forms/tables/data-table/datatable'

const search = ref('')

const Types = ref([])

const options = ref({
  page: 1,
  itemsPerPage: 5,
  sortBy: [''],
  sortDesc: [false],
})

// headers
const headers = [
  {
    title: 'id',
    key: 'id',
  },
  {
    title: 'NAME',
    key: 'rm_type_name',
  },
  {
    title: 'name loc',
    key: 'rm_type_name_loc',
  }, 
  {
    title: 'pax',
    key: 'def_pax',
  },
  {
    title: 'no room',
    key: 'no_of_rooms',
  },
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

onMounted(() => {
  userList.value = JSON.parse(JSON.stringify(data))
})
</script>

<template>
  <div>
    <VCardText>
      <VRow>
        <VCol
          cols="12"
          offset-md="8"
          md="4"
        >
          <AppTextField
            v-model="search"
            density="compact"
            placeholder="Search"
            append-inner-icon="tabler-search"
            single-line
            hide-details
            dense
            outlined
          />
        </VCol>
      </VRow>
    </VCardText>
    <VDataTable
      :headers="headers"
      :items="Types"
      :search="search"
      :items-per-page="options.itemsPerPage"
      :page="options.page"
      @update:options="options = $event"
    >
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

      <template #bottom>
        <VCardText class="pt-2">
          <VRow>
            <VCol
              lg="2"
              cols="3"
            >
              <VTextField
                v-model="options.itemsPerPage"
                label="Rows per page:"
                type="number"
                min="-1"
                max="15"
                hide-details
                variant="underlined"
              />
            </VCol>

            <VCol
              lg="10"
              cols="9"
              class="d-flex justify-end"
            >
              <VPagination
                v-model="options.page"
                total-visible="5"
                :length="Math.ceil(userList.length / options.itemsPerPage)"
              />
            </VCol>
          </VRow>
        </VCardText>
      </template>
    </VDataTable>
    </div>
</template>

<script>
import axios from "@axios"

export default {
  // eslint-disable-next-line vue/component-api-style
  data(){
    return{
      Types: [],
      URL: window.location.origin,

    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getAllTypes()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    getAllTypes(){
      axios
        .get(`${this.URL}/api/room_types`)
        .then(res => {
          this.Types = res.data
        })
    },
  },
}
</script>
