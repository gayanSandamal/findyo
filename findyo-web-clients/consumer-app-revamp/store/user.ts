export const state = () => ({
  user: {}
})

export const mutations = {
  setUser (state: any, payload: any) {
    state.user = payload
  }
}
