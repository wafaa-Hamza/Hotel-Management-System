import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    ...setupLayouts(routes),
  ],
})


router.beforeEach((to, from, next) => {

  // let details = localStorage.getItem('hotel-details')
  // if (details == undefined || details == null) {

  //   axios.get(`${window.location.origin}/api/public-home-page/${1}`)
  //     .then(res => {
  //       localStorage.setItem('hotel-details', JSON.stringify(res.data))
  //       // eslint-disable-next-line promise/no-callback-in-promise

  //     })
  //     .catch(err => {
  //       console.log(err)
  //     })

  // }

  next()
})


function hasAbilities(userAbilities, requiredAbilities) {

  // Check if the user has the required ability
  return userAbilities.some(
    userAbility =>
      userAbility.action === requiredAbilities.action && userAbility.subject === requiredAbilities.subject,
  )

}

// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
export default router


// const router = createRouter({
//   history: createWebHistory('/'),
//   routes: [
//     {
//       path: '/',
//       name: 'Login',
//       component: () => import('@/pages/login.vue'),
//     },
//     {
//       path: '/dashboard', // Define the route for the dashboard page
//       name: 'Dashboard',
//       component: () => import('@/pages/Dashboards.vue'), // Load the dashboard component
//       meta: { requiresAuth: true }, // Add a meta field to require authentication
//     },
//   ],
// });
// router.beforeEach((to, from, next) => {
//   // You can add authentication logic here
//   const isAuthenticated ;
//   if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
//     // If the route requires authentication and the user is not authenticated, redirect to the login page
//     next({ name: 'Login' });
//   } else {
//     // Otherwise, proceed to the requested route
//     next();
//   }
// });


// router.beforeEach((to, from, next) => {
//   const userAbilities = JSON.parse(localStorage.getItem('userAbilities'))
//
//
//
//   // Check if the user has the necessary abilities to access the route
//   if (to.meta.action && !hasAbilities(userAbilities, to.meta)) {
//     // User doesn't have the required abilities, restrict access
//     next('misc/not-authorized')
//
//   } else {
//     // User has the required abilities, allow access
//     next()
//
//   }
//
//
//
// })

/* router.beforeEach((to, from, next) => {
  const userAbilities = JSON.parse(localStorage.getItem('userAbilities'))



  // Check if the user has the necessary abilities to access the route
  if (to.meta.action && !hasAbilities(userAbilities, to.meta)) {
    // User doesn't have the required abilities, restrict access
    next('misc/not-authorized')

  } else {
    // User has the required abilities, allow access
    next()

  }



}) */
