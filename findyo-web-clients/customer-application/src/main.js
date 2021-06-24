import Vue from 'vue'
import App from './App.vue'
// import './registerServiceWorker'
import store from './store'
import firebase from 'firebase/app'
import 'firebase/auth'
// import 'firebase/messaging'
import router from './router'

import {firebaseConfig} from './firebaseConfig'
let firebaseInitiated = firebase.initializeApp(firebaseConfig)
import {fbIinit} from './firebaseInit'
// import {firebaseMessaging} from './firebaseMessaging'

// firebaseMessaging(firebase)

fbIinit.obj = firebaseInitiated
Vue.config.productionTip = false

import VueAutosize from 'vue-autosize'
Vue.use(VueAutosize)

import VueContentPlaceholders from 'vue-content-placeholders'
Vue.use(VueContentPlaceholders)

let app
firebase.auth().onAuthStateChanged(() => {
  if(!app){
    app = new Vue({
      router,
      store,
      render: h => h(App)
    }).$mount('#app')    
  }
})