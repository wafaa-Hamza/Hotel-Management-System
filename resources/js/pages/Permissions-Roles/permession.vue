<template>
  <VForm>
    <div style="width: 60%;">

      <Breadcrumb  class="d-print-none" ></Breadcrumb>
      <div
        style="display: flex;flex-wrap: wrap;justify-content: space-between;"
        class="mb-10"
      >
        <p class="mx-14">
          Name
        </p>

        <VTextField
          label="Editor"
        />
      </div>
      <div style="display: flex;flex-wrap: wrap;justify-content: space-between; ">
        <p class="mx-14">
          Slug
        </p>
        <VTextField
          label="editor"
        />
      </div>
    </div>
    <br><br><br>
    <div style="display: flex;flex-wrap: wrap;justify-content: space-between">
      <p>Permissions</p>
      <div style="width: 90%;display: flex;flex-wrap: wrap;justify-content: space-between;">
        <h3
          v-for="Roles in Role"
          :key="Roles.id"
          class="my-16"
        >
          {{ Roles.name }}
        </h3><br>

        <div style="display: flex;flex-wrap: wrap;justify-content: space-between;width: 90%">
          <div class="demo-space-x">
            <VCheckbox
              v-for="permissData in permiss"
              :key="permissData.id"
              v-model="selectedPermisson"
              :label="permissData.name"
              :value="permissData.name"
            />
          </div>



          <div class="demo-space-x">
            <VCheckbox
              v-for="Categories in Category"
              :key="Categories"
              v-model="selectedCategory"
              :label="Categories"
              :value="Categories"
            />
          </div>

          <div class="demo-space-x">
            <VCheckbox
              v-for="Orders in Order"
              :key="Orders"
              v-model="selectedOrder"
              :label="Orders"
              :value="Orders"
            />
          </div>


          <div class="demo-space-x">
            <VCheckbox
              v-for="Users in User"
              :key="Users"
              v-model="selectedUser"
              :label="Users"
              :value="Users"
            />
          </div>



          <div class="demo-space-x">
            <VCheckbox
              v-for="Roles in Role"
              :key="Roles"
              :label="Roles"
              :value="Roles"
            />
          </div>
        </div>
      </div>
    </div>
    <div
      style="float: right;width: 50%;"
      class="mt-16"
    >
      <VRow>
        <VCol
          cols="8"
          sm="8"
        >
          <VBtn block>
            Update & Continue Editing
          </VBtn>
        </VCol>

        <VCol
          cols="8"
          sm="4"
        >
          <VBtn

            block
            type="submit"
          >
            Add
          </VBtn>
        </VCol>
      </VRow>
    </div>
  </VForm>
</template>

<script>
import axios from "@axios"
import { ABILITY_TOKEN } from '@casl/vue'

export default {
  // eslint-disable-next-line vue/component-api-style
  inject: {
    $ability: { from: ABILITY_TOKEN },
  },

  // eslint-disable-next-line vue/component-api-style
  data(){

    return{

      Role:[],



      permiss:[],

      selectedPermisson : [],


      product :[
        'Primary',
        'fas',
        'ew',
        'qe',
        'fs',
        'wq',
      ],
      selectedProduct : [],



      Category :[
        'Primary',
        'fas',
        'ew',
        'qe',
        'fs',
        'wq',
      ],
      selectedCategory : [],



      Order :[
        'Primary',
        'fas',
        'ew',
        'qe',
        'fs',
        'wq',
      ],
      selectedOrder : [],



      User :[
        'Primary',
        'fas',
        'ew',
        'qe',
        'fs',
        'wq',
      ],
      selectedUser : [],






    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPermiss()
    this.getRole()
  },
  // eslint-disable-next-line vue/component-api-style
  methods:{
    getPermiss(){
      axios.get('http://127.0.0.1:8000/api/getAllRoles')
        .then(({ data })=>{
          this.permiss=data.data
        })
    },
    getRole(){
      axios.get('http://127.0.0.1:8000/api/getAllRoles')
        .then(({ data })=>{
          this.Role=data.data
        })
    }, 

  },
}
</script>

<style scoped>
.demo-space-x {

  flex-wrap: wrap;
  align-items: center;
  margin-block-start: -16px;
  display: inline-block;
}
</style>
