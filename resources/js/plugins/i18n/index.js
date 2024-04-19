import { createI18n } from 'vue-i18n'

const storeduser = localStorage.getItem('userData')
let lang

if (storeduser) {
  let myData = JSON.parse(storeduser)
  const userData = myData.user

  lang = userData.language
}



let storageLang = localStorage.getItem('language')
if (storageLang != null) {
  if (lang !== storageLang) {
    lang = storageLang
  }
}

const messages = Object.fromEntries(Object.entries(
  import.meta.glob('./locales/*.json', { eager: true }))
  .map(([key, value]) => [key.slice(10, -5), value.default]))


export default createI18n({
  legacy: false,
  locale: lang,
  fallbackLocale: 'en',
  messages,
})
