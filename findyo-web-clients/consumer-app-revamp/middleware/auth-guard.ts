import * as Cookies from 'js-cookie'

export default function ({ store }: any) {
  store.app.router.beforeEach((to: any, _from: any, next: (arg0: boolean | undefined) => void) => {
    getUserObj(store, to, next)
    // if (userId) {}
    // if (!store.getters.user.username && to.fullPath !== '/profile/edit') {
    //   store.$router.push({ path: '/profile/edit' })
    //   next(false)
    // } else {
    //   next(true)
    // }
    next(true)
  })
}

const getUserObj = async (store: any, to: any, next: (arg0: boolean | undefined) => void) => {
  const userId = Cookies.get('userId')
  if (userId) {
    const user = await store.$axios.$get(`GetUser/${userId}`)
    // setUser(user)
    // if (user && !user[0].username) {
    //   router.push({ path: '/profile/edit' })
    // }
  } else {
    store.$router.push({ path: '/' })
  }
}
