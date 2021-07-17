const state = {
  user: null,
  userData: null,
  profileData: null,
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
  setProfileData(state, payload) {
    state.profileData = payload;
  },
  updateUserData(state, payload) {
    //
  },
  updateProfileData(state, payload) {
    //
  },
};

const getters = {
  getUser: (state) => state.user,
};

export default {
  state,
  mutations,
};
