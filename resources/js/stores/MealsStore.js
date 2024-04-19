import { defineStore } from 'pinia'
import axios from '@axios'

export const useMealsStore = defineStore('meals', {
  state: () => ({
    meals: [],
    meal_packages: [],
  }),
  actions: {
    getMealsAction() {
      axios.get(`${window.location.origin}/api/meals`)
        .then(res => {
          this.meals = res.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },

    getMealPackagesAction() {
      axios.get(`${window.location.origin}/api/meal-packages`)
        .then(res => {
          this.meal_packages = res.data
        })
        .catch(err => {
          console.log(err.message)
        })
    },
  },
})
