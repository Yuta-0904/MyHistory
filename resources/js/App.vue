<template>
  <v-app>
    <Header />
    <v-main>
      <v-container>
         <router-view/>
      </v-container>
    </v-main>
    <Footer/>
  </v-app>
</template>
<script>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import { INTERNAL_SERVER_ERROR } from './util'
export default {
  name: 'App',
  components: {
      Header,
      Footer
  },
   computed: {
    errorCode () {
      return this.$store.state.error.code
    }
  },
    watch: {
    errorCode: {
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
  }

};
</script>