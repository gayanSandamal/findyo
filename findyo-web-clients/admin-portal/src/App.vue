<template>
  <div id="app">
    <div class="container-fluid mt-3">
      <div class="row">
        <div v-if="isLoggedIn" id="nav" class="col-md-2 pt-0 pr-0 pb-0">
          <b-list-group>
            <b-list-group-item v-for="(menuRoute, index) in menuRoutes" :key="index">
              <b-link :to="menuRoute.path" v-text="menuRoute.name" :class="menuRoute.name"></b-link>
            </b-list-group-item>
            <!-- <b-list-group-item><b-link to="/">Home</b-link></b-list-group-item>
            <b-list-group-item><b-link to="/locations">Locations</b-link></b-list-group-item>
            <b-list-group-item><b-link to="/categories">Categories</b-link></b-list-group-item> -->
            <b-list-group-item><b-link @click="logout">Logout</b-link></b-list-group-item>
          </b-list-group>
        </div>
        <div class="" :class="isLoggedIn ? 'col-md-10' : 'col-md-12'">
          <router-view :db="db" :alertSettings="alertSettings"/>
        </div>
      </div>
    </div>
    <Alert :settings="alertSettings" v-if="alertSettings.show"/>
  </div>
</template>

<script>
import firebase from 'firebase/app'
import 'firebase/auth'
import 'firebase/firestore'

export default {
  data() {
    return {
      isLoggedIn: false,
      firebaseConfig: {
        apiKey: "AIzaSyDkwSysVahS_1DAkXCawbjMC-kM0-6E5go",
        authDomain: "findyo-staging.firebaseapp.com",
        databaseURL: "https://findyo-staging.firebaseio.com",
        projectId: "findyo-staging",
        storageBucket: "findyo-staging.appspot.com",
        messagingSenderId: "554549914414",
        appId: "1:554549914414:web:d882f705ebf33726"
      },
      db: {},
      alertSettings: {
        show: false,
        type: 'alert-warning',
        msg: ''
      }
    }
  },
  components: {
    Alert: () => import ('@/components/Alert')
  },
  computed: {
    menuRoutes() {
      let mappedMenues = []
      this.$router.options.routes.forEach(o => {
        if (o.meta.requiresAuth) {
          mappedMenues.push(o)
        }
      })
      return mappedMenues
    }
  },
  methods: {
    logout () {
      firebase.auth().signOut().then(()=>{
        this.$router.push('/login')
        this.isLoggedIn = undefined
      }).catch((error)=>{
        alert('Error in logout' + error)
      })
    }
  },
  created () {
    this.db = firebase.firestore()
    this.isLoggedIn = firebase.auth().currentUser
  }
}
</script>
<style lang="scss">
* {
  font-size: 14px;
}
#nav {
  a {
    font-weight: bold;
    color: #2c3e50;
    &.router-link-active {
      &:not(.Home) {
        color: #42b983;
      }
    }
    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
