<script setup>
import NavBarI18n from '@core/components/I18n.vue';
import { useThemeConfig } from '@core/composable/useThemeConfig';


const { isAppRtl } = useThemeConfig()



const i18nCompLanguages = [
  {
    label: 'English',
    i18nLang: 'en',
  },

  {
    label: 'Arabic',
    i18nLang: 'ar',
  },
]



const handleLangChange = lang => {
  //console.log('change',lang)
  localStorage.setItem('language', JSON.stringify(lang))
  isAppRtl.value = lang === 'ar'
  localStorage.setItem('language', lang)

}
</script>

<template>
  <NavBarI18n :languages="i18nCompLanguages" @change="handleLangChange" />
</template>


<script>
export default {
  methods: {
    change(param) {
      console.log(param);
    }
  }
}
</script>

