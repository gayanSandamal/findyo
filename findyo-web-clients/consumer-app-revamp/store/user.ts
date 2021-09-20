// eslint-disable-next-line import/named
import { GetterTree, ActionTree, MutationTree } from 'vuex'

export const state = () => ({
  user: {}
})

export type RootState = ReturnType<typeof state>

export const getters: GetterTree<RootState, RootState> = {
  getUser: (state: any) => {
    debugger
    return state.user
  }
}

export const mutations: MutationTree<RootState> = {
  SET_USER(state: { user: any }, payload: any) {
    state.user = payload
  }
}

export const actions: ActionTree<RootState, RootState> = {
  setUser({ commit }, payload) {
    commit('SET_USER', payload)
  }
}
