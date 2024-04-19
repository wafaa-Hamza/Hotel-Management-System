<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable'

const headers_En = [

  {
    title: "mobile no",
    key: 'mobile_no',
  },
  {
    title: "blacklist_reason",
    key: 'blacklist_reason',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
  },
]

const headers_Profile = [

  {
    title: "profile id",
    key: 'id',
  },
  {
    title: "Profile name",
    key: 'first_name',
  },
  {
    title: "Id Number",
    key: 'id_number',
  },
  {
    title: "Mobile",
    key: 'mobile',
  },
  {
    title: "Phone",
    key: 'phone',
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
              {{ $t('Add New Blabk List') }}
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ $t('Add New Black List') }}</span>
            </VCardTitle>
            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="5"
                    sm="5"
                    md="5"
                  >
                    <VTextField
                      v-model="selectedRows.first_name"
                      :label="$t('Profile')"
                      @click="isDialogVisible = !isDialogVisible"
                    />
                  </VCol>
                  <VDialog
                    v-model="isDialogVisible"
                    scrollable
                    max-width="1050"
                     height="650"
                    persistent
                  >

                    <!-- Dialog Content -->
                    <VCard>
                      <VCardItem class="pb-5">
                        <VCardTitle>Select Profile</VCardTitle>
                      </VCardItem>

                      <VDivider />
                      <VCardText style="block-size: 500px;">
                        <VCol
                          cols="6"
                          md="4"
                        >
                          <AppTextField
                            v-model="search_profile"
                            density="compact"
                            :placeholder="$t('search_profile')"
                            append-inner-icon="tabler-search"
                            single-line
                            hide-details
                            dense
                            outlined
                          />
                        </VCol>
                        <VDataTable
                          :headers="headers_Profile"
                          :items="Profiles"
                          :search="search_profile"
                          :items-per-page="6"
                        >
<!--                          //id-->
                          <template v-slot:item.id="{ item }">
                            <b @dblclick="ProfileAllData(item.props.title)" style="cursor: pointer"
                            >{{item.props.title.id}}</b>
                          </template>
<!--//name-->
                          <template v-slot:item.first_name="{ item }">
                            <b @dblclick="ProfileAllData(item.props.title)" style="cursor: pointer"
                            >{{item.props.title.first_name}}</b>
                          </template>
<!--//id_number-->
                          <template v-slot:item.id_number="{ item }">
                            <b @dblclick="ProfileAllData(item.props.title)" style="cursor: pointer"
                            >{{item.props.title.id_number}}</b>
                          </template>
<!--//mobile-->
                          <template v-slot:item.mobile="{ item }">
                            <b @dblclick="ProfileAllData(item.props.title)" style="cursor: pointer"
                            >{{item.props.title.mobile}}</b>
                          </template>
<!--//phone-->
                          <template v-slot:item.phone="{ item }">
                            <b @dblclick="ProfileAllData(item.props.title)" style="cursor: pointer"
                            >{{item.props.title.phone}}</b>
                          </template>
                        </VDataTable>
                      </VCardText>

                      <VDivider />

                      <VCardText class="d-flex justify-end flex-wrap gap-3 pt-5">

                        <VBtn @click="CloseDialog">
                            {{ $t('close') }}
                        </VBtn>
                        <VSpacer />
                        <VBtn @click="SubmitData">
                           {{ $t('Save') }}
                        </VBtn>
                      </VCardText>
                    </VCard>
                  </VDialog>
                  <VCol
                    cols="4"
                    sm="4"
                    md="4"
                  >
                    <VTextField
                      v-model="selectedRows.mobile"
                      :label="$t('mobile')"
                      persistent-placeholder
                    />
                  </VCol>

                  <VCol
                    cols="3"
                    sm="3"
                    md="3"
                  >
                    <VTextField
                      v-model="selectedRows.id_number"
                      :label="$t('id no')"
                      persistent-placeholder
                    />
                  </VCol>

                  <VCol
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <VTextarea
                      v-model="blacklist_reason"
                      rows="4"

                      :label="$t('Note')"
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
      :headers="headers_En"
      :items="BlackList"
      :search="search"
      :items-per-page="10"
    >
      <!-- Actions -->
      <template #item.actions="{ item }">
        <div class="d-flex gap-2">
          <VBtn color="primary">
            <VRow>
              <VDialog
                v-model="item.dialog3"
                width="1024"
              >
                <template #activator="{ props }">
                  <div
                    v-bind="props"
                    @click="Updates(item.raw?item.raw:item)"
                  >
                    <VIcon icon="mdi-file-edit-outline" />
                  </div>
                </template>
                <VCard>
                  <VCardTitle>
                    <span class="text-h5">{{ $t('UPDATE Black List Note') }}</span>
                  </VCardTitle>
                  <VCardText>
                    <VContainer>
                      <VRow>

                        <VCol
                          cols="12"
                          sm="12"
                          md="12"
                        >
                          <VTextarea
                            v-model="blacklist_reason"
                            rows="4"

                            :label="$t('Note')"
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
                      @click="dialog3 = false"
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
          <VBtn
            color="red" style="background: red;color: white"
            @click="Delete(item.raw?item.raw.id:item.id)"
          >
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
  name: "BlackList",

  // eslint-disable-next-line vue/component-api-style
  data(){
    return {
      search: '',
      search_profile: '',
      dialog: false,
      dialog3: false,
      BlackList: [],
      Users: [],
      name: '',
      name_loc: '',
      Bussid: '',
      profile_id: '',
      id_no: '',
      mobile_no: '',
      blacklist_reason: '',
      user_id: '',
      created_by: '',
      fullname: '',
      Profiles:[],
      ProfileData:[],
      isDialogVisible : ref(false),
      URL: window.location.origin,
      selectedRows: [],
      selectedRowId: null
    }
  },


  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getBlackList()
    this.getUsers()
    this.getGuestProfile()

  },
// watch:{
//   ProfileData(wow){
//     console.log(wow)
//   }
//
// },
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
    getGuestProfile(){
      axios
        .get(`${this.URL}/api/guestProfile`)
        .then(response => this.Profiles = response.data.data)
    },
    getBlackList(){
      axios
        .get(`${this.URL}/api/black_list`)
        .then(response => this.BlackList = response.data)
    },
    getUsers(){
      axios
        .get(`${this.URL}/api/user`)
        .then(response => this.Users = response.data)
    },

    async  Add(){
      console.log(this.ProfileData)
      try {
        const user = await axios.post(
          `${this.URL}/api/black_list`,
          {
            profile_id: this.selectedRows.id,
            id_no: this.selectedRows.id_number,
            mobile_no: this.selectedRows.mobile,
            blacklist_reason: this.blacklist_reason,
            created_by: this.created_by.firstname,
          },
        )

        this.getBlackList()
        this.dialog = false
        this.insertAlert()
        this.selectedRows.first_name=''
        this.selectedRows.id_number=''
        this.selectedRows.mobile=''
        this.blacklist_reason=''
      } catch(e) {
        console.log(e)
      }
    },

    async  Delete(GetId){
      this.Bussid = GetId
      this.DeleteAlert()
      console.log(GetId)
    },

    deleteData(){
      axios
        .delete(`${this.URL}/api/delete/${this.Bussid}`)
        .then(response => (this.BlackList = response.data))
      this.getBlackList()
    },

    async  Updates(Getdata){

      this.Bussid = Getdata

      // this.profile_id= Getdata.profile_id,
      //   this.id_no= Getdata.id_no,
      //   this.mobile_no= Getdata.mobile_no,
        this.blacklist_reason= Getdata.blacklist_reason



    },

    async updateUser() {

      try {
        const user = await axios.put(
          `${this.URL}/api/black_list/${this.Bussid.id}`,
          {
            // profile_id: this.ProfileData.id,
            // id_no: this.id_no,
            // mobile_no: this.mobile_no,
            blacklist_reason: this.blacklist_reason,
            // user_id: this.user_id,
            // created_by: this.created_by,

          },
        )

        this.getBlackList()
        this.dialog3 = false
        this.UpdateAlert()
      } catch(e) {
        console.log(e)
      }

    },

    SubmitData(){
      this.isDialogVisible = false
      console.log(this.toggleSelection)
    },
    CloseDialog(){
      this.isDialogVisible = false
      this.ProfileData=[]
    },

    ProfileAllData(AllProfileData){
      this.selectedRows = AllProfileData;
      this.isDialogVisible = false
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


