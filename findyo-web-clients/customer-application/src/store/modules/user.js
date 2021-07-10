const state = {
  user: null,
};

const mutations = {
  logout(state) {
    state.user = null;
  },
  login(state, user) {
    state.user = user;
  },
};

const getters = {
  getUser: (state) => state.user,
};

export default {
  state,
  mutations,
};
