const state = {
  user: undefined,
  userData: undefined,
  profileData: undefined,
};

const mutations = {
  logout(state) {
    state.user = undefined;
    state.userData = undefined;
    state.profileData = undefined;
  },
  login(state, user) {
    state.user = { ...state.user, ...user };
  },
  setUserData(state, payload) {
    state.userData = { ...state.userData, ...payload };
  },
  setProfileData(state, payload) {
    state.profileData = { ...state.profileData, ...payload };
  },
  updateUser(state, payload) {
    state.user = { ...state.user, ...payload };
  },
};

const getters = {
  getUser: (state) => state.user,
};

export default {
  state,
  mutations,
};
