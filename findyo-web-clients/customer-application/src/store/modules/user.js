const state = {
  user: null,
  userData: null,
};

const mutations = {
  logout(state) {
    state.user = null;
  },
  login(state, user) {
    state.user = user;
  },
  setUserData(state, payload) {
    state.userData = payload;
  },
};

const getters = {
  getUser: (state) => state.user,
};

export default {
  state,
  mutations,
};
