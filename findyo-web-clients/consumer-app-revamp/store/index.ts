// eslint-disable-next-line import/named
import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { IUser } from '~/interfaces/user'
import { IDrawerMenu } from '~/interfaces/ui'

export const state = () => ({
  user: {} as IUser,
  drawerMenus: [
    {
      icon: 'mdi-home',
      title: 'Welcome',
      to: '/',
      authentication: false,
      order: 1
    },
    {
      icon: 'mdi-account',
      title: 'Profile',
      to: '/profile',
      authentication: true,
      order: 2
    },
    {
      icon: 'mdi-bell',
      title: 'Notifications',
      to: '/notifications',
      authentication: true,
      order: 3
    }
  ] as IDrawerMenu[],
  snackbar: {
    text: '',
    color: '',
    timeout: -1
  }
})

export type RootState = ReturnType<typeof state>

export const getters: GetterTree<RootState, RootState> = {
  user: (state: any) => state.user,
  drawerMenus: (state: any) => state.drawerMenus,
  snacktext: (state: any) => state.snackbar.text
}

export const mutations: MutationTree<RootState> = {
  SET_USER(state: { user: IUser }, payload: any) {
    state.user = payload
  },
  SHOW_MESSAGE(
    state: { snackbar: { text: string; color: string; timeout: number } },
    payload: { text: string; color: string; timeout: number }
  ) {
    state.snackbar.text = payload.text
    state.snackbar.color = payload.color
    state.snackbar.timeout = payload.timeout
  }
}

export const actions: ActionTree<RootState, RootState> = {
  setUser({ commit }, payload) {
    const user = payload[0]
    const mappedUser = {
      about: user.about,
      address: user.address,
      apiToken: user.api_token,
      cid: user.cid,
      createdAt: user.create_at,
      displayname: user.displayname,
      email: user.email,
      emailVerifiedAt: user.email_verified_at,
      firstname: user.firstname,
      id: user.id,
      lastname: user.lastname,
      phone: user.phone,
      updatedAt: user.updated_at,
      userRoleId: user.user_role_id,
      username: user.username
    }
    commit('SET_USER', mappedUser)
  },
  showSnack({ commit }, payload) {
    commit('SHOW_MESSAGE', payload)
  }
}
