import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import firebase from 'firebase/app'
import 'firebase/auth'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/locations',
    name: 'Locations',
    component: () => import('../views/Locations.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/categories',
    name: 'Categories',
    component: () => import('../views/Categories.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/posts/',
    name: 'Posts',
    component: () => import('../views/Posts.vue'),
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'pending',
        name: 'Pending',
        component: () => import('../views/posts/Pending.vue')
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
  // Check for requiredAuth guard
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Check if NOT logged in
    if (!firebase.auth().currentUser) {
      // Go to home page
      next({
        path: '/login'
      })
    } else {
      // Proceed to the route
      next()
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    // Check if logged in
    if (firebase.auth().currentUser) {
      // Go to home page
      next({
        path: '/'
      })
    } else {
      // Proceed to the route
      next()
    }
  } else {
    // Proceed to the route
    next()
  }
})

export default router
