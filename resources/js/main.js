

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import Swal from 'sweetalert2'

import i18n from '@/plugins/i18n'
import ability from '@/plugins/casl/ability'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'

// eslint-disable-next-line import/no-unresolved
import '@core-scss/template/index.scss'
import '@styles/styles.scss'

import App from '@/App.vue'
import router from '@/router'
import { abilitiesPlugin } from '@casl/vue'




function getHijriDate(date) {
  var newdate = new Date(date)
  var hijriDateStr = new Intl.DateTimeFormat('en-TN-u-ca-islamic', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(newdate)
  var [month, day, year] = hijriDateStr.split('/')
  var [yearr] = year.split(' ')

  return `${yearr}-${month}-${day}`
}

function alert(message, type) {
  const Toast = Swal.mixin({
    toast: true,
    position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    },
  })

  Toast.fire({
    icon: type ? 'success' : 'error',
    title: message,
  })
}



loadFonts()




// Create Vue app
const app = createApp(App)


// Use plugins
app.use(i18n)
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(layoutsPlugin)
app.use(VueSweetalert2)

app.config.globalProperties.$getHijriDate = getHijriDate
app.config.globalProperties.$alert = alert

app.use(abilitiesPlugin, ability, {
  useGlobalProperties: true,
})

app.mount('#app')
