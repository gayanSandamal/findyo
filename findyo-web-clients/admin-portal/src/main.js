import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'

import firebase from 'firebase/app'
import 'firebase/auth'

import { firebaseConfig } from './firebaseConfig'
import { fbIinit } from './firebaseInit'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './assets/css/custom.scss'
const firebaseInitiated = firebase.initializeApp(firebaseConfig)
Vue.config.productionTip = false

fbIinit.obj = firebaseInitiated

Vue.use(BootstrapVue)

let app
firebase.auth().onAuthStateChanged(() => {
  if (!app) {
    app = new Vue({
      router,
      store,
      render: h => h(App)
    }).$mount('#app')
  }
})
