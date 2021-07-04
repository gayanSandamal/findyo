/* eslint-disable */
import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import firebase from 'firebase/app'
import 'firebase/auth'
// import {firebaseConfig} from './../firebaseConfig'

// firebase.initializeApp(firebaseConfig)
// let db = firebase.firestore()

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      requiredProfileCompletion: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/logout',
    name: 'logout',
    component: () => import('../views/Logout.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: () => import('../views/ResetPassword.vue')
  },
  {
    path: '/chat/',
    name: 'chat',
    component: () => import('../views/Chat.vue'),
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: ":id",
        name: 'chat-box',
        component: () => import('./../components/common/ChatBox.vue')
      }
    ]
  },
  {
    path: '/notifications',
    name: 'notifications',
    component: () => import('../views/Notifications.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/privacy-and-policy',
    name: 'privacy-and-policy',
    component: () => import('../views/PrivacyandPolicy.vue')
  },
  {
    path: '/settings/',
    name: 'settings',
    component: () => import('../views/Settings.vue'),
    meta: {
      requiresAuth: true,
      requiredProfileCompletion: true
    },
    children: [
      {
        name: 'profile-settings',
        path: "profile",
        component: () => import('./../components/settings/ProfileSettings.vue')
      },
      {
        name: 'security-settings',
        path: "security",
        component: () => import('./../components/settings/SecuritySettings.vue')
      }
    ]
  },
  {
    path: '/post/:id',
    name: 'post',
    component: () => import('../views/PostPage.vue')
  },
  {
    path: '/completion',
    name: 'completion',
    component: () => import('../views/Complete.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/:id/',
    name: 'admin',
    component: () => import('../views/Profile.vue'),
    meta: {
      requiredProfileCompletion: true
    },
    children: [
      {
        name: 'admin-timeline',
        path: "timeline",
        component: () => import('./../components/feed/Timeline.vue')
      },
      {
        name: "admin-portfolio",
        path: "portfolio",
        component: () => import('./../components/feed/Portfolio.vue')
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
  const userLoggedIn = firebase.auth().currentUser
  const requiredAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredProfileCompletion = to.matched.some(record => record.meta.requiredProfileCompletion)
  if (userLoggedIn) {
    if (requiredProfileCompletion) {
      if (isUserNameAllowed(userLoggedIn.displayName)) {
        next()
      } else {
        next({
          path: '/completion'
        })
      }
    } else {
      if (requiredAuth) {
        next()
      } else {
        next()
      }
    }
  } else {
    if (requiredAuth) {
      next({
        path: '/'
      })
    } else {
      next()
    }
  }
})

const isUserNameAllowed = (username) => {
  let includeSpaces = false
  let includeUppercases = false
  if (username !== undefined && username !== null && username.trim() !== '') {
    includeSpaces = !username.includes(" ")
    includeUppercases = !isCapitalLetterAvailable(username)
  }
  return includeSpaces && includeUppercases
}

const isCapitalLetterAvailable = (username) =>{
  var i = 0
  var char = ''
  var isCapitalFound = false
  while( (i < username.length) && !isCapitalFound){
    char = username.charAt(i)
    char !=  char.toLowerCase() ? isCapitalFound=true : i++
  }
  return isCapitalFound
}

export default router
