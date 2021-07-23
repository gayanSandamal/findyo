import Vue from 'vue'
import user from './modules/user';
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import * as Cookies from 'js-cookie'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    user
  },
  plugins: [createPersistedState({
  paths: ['user'],
  storage: {
    getItem: (key) => Cookies.get(key),
    setItem: (key, value) => {
      Cookies.set(key, value, { expires: 3, secure: true })
    },
    removeItem: (key) => Cookies.remove(key)
  }
  })],
})
