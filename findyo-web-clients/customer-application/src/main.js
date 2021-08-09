import Vue from 'vue'
import App from './App.vue'
// import './registerServiceWorker'
import store from './store'
import firebase from 'firebase/app'
import 'firebase/auth'
// import 'firebase/messaging'
import router from './router'

import { firebaseConfig } from './firebaseConfig'
import { fbIinit } from './firebaseInit'

import VueAutosize from 'vue-autosize'

import VueContentPlaceholders from 'vue-content-placeholders'
const firebaseInitiated = firebase.initializeApp(firebaseConfig)
// import {firebaseMessaging} from './firebaseMessaging'

// firebaseMessaging(firebase)

fbIinit.obj = firebaseInitiated
Vue.config.productionTip = false
Vue.use(VueAutosize)
Vue.use(VueContentPlaceholders)

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
