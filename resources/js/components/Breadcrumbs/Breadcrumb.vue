<template>
  <div style="display: flex;padding: 2%">
    <h3 style="margin-top: .7%">{{ $t(url) }} <span style="font-weight: 100;">|</span>
      <v-icon style="margin-left: 8px;" size="30" icon="mdi-home" @click="GoToHome"></v-icon>

      <v-icon v-if="items[0].title" style="margin: 2% 0 0 10px;color: black;" size="25"
        :icon="$i18n.locale === 'en' ? 'mdi-chevron-right' : 'mdi-chevron-left'"></v-icon>
    </h3>
    <v-breadcrumbs>
      <p @click="goToBack" style="color: gray;cursor: pointer;font-size: 100%;font-weight: bold;">{{ $t(items[0].title) }}</p>
      <v-icon style="margin: -4% 0 0 10px;color: black;" size="25"
        :icon="$i18n.locale === 'en' ? 'mdi-chevron-right' : 'mdi-chevron-left'"></v-icon>


      <p @click="ReloadPage" style="color: #686ac1;cursor: pointer;font-size: 110%;font-weight: bold;">{{ $t(items[1].title) }}</p><v-icon v-if="items[2].title" style="margin: -5% 0 0 10px;color: black;" size="25"
        :icon="$i18n.locale === 'en' ? 'mdi-chevron-right' : 'mdi-chevron-left'"></v-icon>


      <p @click="goToForw" style="color: gray;cursor: pointer;font-size: 110%;font-weight: bold;">{{ $t(items[2].title) }}</p>
    </v-breadcrumbs>
  </div>
</template>
<script>
export default {
  data: () => ({
    url: '',
    two: window.history.state.current,
    three: window.history.state.forward,
    items: [
      {
        title: window.history.state.back ? window.history.state.back.split('/')[window.history.state.back.split('/').length - 1] : '',
        disabled: window.history.state.back ? false : true,
        href: window.history.state.back,
        icon: 'mdi-chevron-right',
      },
      {
        title: window.history.state.current.split('/')[window.history.state.current.split('/').length - 1],
        disabled: false,
        href: window.history.state.current,

        icon: window.history.state.forward ? 'dsfdsf' : '',
      },
      {
        title: window.history.state.forward ? window.history.state.forward.split('/')[window.history.state.forward.split('/').length - 1] : '',
        disabled: window.history.state.forward ? false : true,
        href: window.history.state.forward,

      },
    ],
  }),
  mounted() {

    this.URL()
  },
  methods: {
    GoToHome() {
      this.$router.push({ name: `index` })
    },
    URL() {
      this.url = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1]

    },
    goToBack() {
      this.$router.go(-1);
    },
    goToForw() {
      this.$router.go(1);
    },
    ReloadPage() {
      this.$router.go(0);
    }
  }
}
</script>
<style scoped></style>
