import { Middleware } from '@nuxt/types'
import includes from 'lodash/includes'
import { IUser } from '~/interfaces/user'

const authMiddleware: Middleware = (context) => {
  const allowedRoutesWhenNotLoggedIn = [
    'index',
    'login',
    'register'
  ]
  const currentRouteName = context.route.name
  // const fromUrl = context.from ? context.from.fullPath : ''
  const loggedIn = context.$auth.$storage.state.loggedIn
  const userAuth = context.$auth.$storage.state.user
  let user: IUser
  const redirect = context.redirect

  const getUserObj = async (id: number) => {
    user = await context.$axios.$get(`GetUser/${id}`)
    context.store.dispatch('setUser', user)
    context.$auth.$storage.setCookie('user', user[0])
    if (user && !user[0].username) {
      redirect('/profile/edit')
    }
  }

  // check if the user is logged in
  if (!loggedIn) {
    // when user is not looged in
    if (!includes(allowedRoutesWhenNotLoggedIn, currentRouteName)) {
      // when current route is not allowed for none logged in users
      // redirect to login page
      redirect('/login')
    }
  } else {
    // when user is logged in
    user && user.username ? getUserObj(userAuth.id)
  }
}

export default authMiddleware
