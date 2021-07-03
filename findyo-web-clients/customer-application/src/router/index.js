/* eslint-disable */
import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import firebase from 'firebase/app'
import 'firebase/auth'
import store from '@/store'
import common from '../assets/javascript/common/common'
// import {firebaseConfig} from './../firebaseConfig'

// firebase.initializeApp(firebaseConfig)
// let db = firebase.firestore()

Vue.use(VueRouter)

const routes = [{
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      isRequiredProfileCompletion: true,
      isRequiredAuth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import( /* webpackChunkName: "Login"*/ '../views/Login.vue')
  },
  {
    path: '/logout',
    name: 'logout',
    component: () => import( /* webpackChunkName: "Logout"*/ '../views/Logout.vue'),
    meta: {
      isRequiredAuth: true
    }
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: () => import( /* webpackChunkName: "ResetPassword"*/ '../views/ResetPassword.vue')
  },
  {
    path: '/chat/',
    name: 'chat',
    component: () => import( /* webpackChunkName: "Chat"*/ '../views/Chat.vue'),
    meta: {
      isRequiredAuth: true
    },
    children: [{
      path: ":id",
      name: 'chat-box',
      component: () => import( /* webpackChunkName: "ChatBox"*/ './../components/common/ChatBox.vue')
    }]
  },
  {
    path: '/notifications',
    name: 'notifications',
    component: () => import( /* webpackChunkName: "Notifications"*/ '../views/Notifications.vue'),
    meta: {
      isRequiredAuth: true
    }
  },
  {
    path: '/privacy-and-policy',
    name: 'privacy-and-policy',
    component: () => import( /* webpackChunkName: "PrivacyandPolicy"*/ '../views/PrivacyandPolicy.vue')
  },
  {
    path: '/settings/',
    name: 'settings',
    component: () => import( /* webpackChunkName: "Settings"*/ '../views/Settings.vue'),
    meta: {
      isRequiredAuth: true,
      isRequiredProfileCompletion: true
    },
    children: [{
        name: 'profile-settings',
        path: "profile",
        component: () => import( /* webpackChunkName: "ProfileSettings"*/ './../components/settings/ProfileSettings.vue')
      },
      {
        name: 'security-settings',
        path: "security",
        component: () => import( /* webpackChunkName: "SecuritySettings"*/ './../components/settings/SecuritySettings.vue')
      }
    ]
  },
  {
    path: '/post/:id',
    name: 'post',
    component: () => import( /* webpackChunkName: "PostPage"*/ '../views/PostPage.vue')
  },
  {
    path: '/completion',
    name: 'completion',
    component: () => import( /* webpackChunkName: "Complete"*/ '../views/Complete.vue'),
    meta: {
      isRequiredAuth: true
    }
  },
  {
    path: '/:id/',
    name: 'admin',
    component: () => import( /* webpackChunkName: "Profile"*/ '../views/Profile.vue'),
    meta: {
      isRequiredProfileCompletion: true
    },
    children: [{
        name: 'admin-timeline',
        path: "timeline",
        component: () => import( /* webpackChunkName: "Timeline"*/ './../components/feed/Timeline.vue')
      },
      {
        name: "admin-portfolio",
        path: "portfolio",
        component: () => import( /* webpackChunkName: "Portfolio"*/ './../components/feed/Portfolio.vue')
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  /* eslint-disable */
  const userLoggedIn = store.state.user.user
  console.log('in router', userLoggedIn)
  const isRequiredAuth = to.matched.some(record => record.meta.isRequiredAuth)
  const isRequiredProfileCompletion = to.matched.some(record => record.meta.isRequiredProfileCompletion)
  if (userLoggedIn) {
    if (isRequiredProfileCompletion) {
      if (checkProfileCompletion(userLoggedIn)) {
        next()
      } else {
        next({
          path: '/completion'
        })
      }
    } else {
      if (isRequiredAuth) {
        next()
      } else {
        next()
      }
    }
  } else {
    if (isRequiredAuth) {
      next({
        path: '/login'
      })
    } else {
      next()
    }
  }
})

const isUserNameAllowed = (username) => {
  if (!username) {
    return false
  }
  let includeSpaces = false
  let includeUpperCases = false
  if (username !== undefined && username !== null && username.trim() !== '') {
    includeSpaces = !username.includes(" ")
    includeUpperCases = !isCapitalLetterAvailable(username)
  }
  return includeSpaces && includeUpperCases
}

const isCapitalLetterAvailable = (username) => {
  var i = 0
  var char = ''
  var isCapitalFound = false
  while ((i < username.length) && !isCapitalFound) {
    char = username.charAt(i)
    char != char.toLowerCase() ? isCapitalFound = true : i++
  }
  return isCapitalFound
}

const checkProfileCompletion = (user) => {
  let isCompleted = true
  if (!user.displayName) {
    isCompleted = false
  }
  if (user.displayName && !isUserNameAllowed(user.displayName)) {
    isCompleted = false
  }
  return isCompleted
}

export default router