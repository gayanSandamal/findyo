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
    state.user = user;
  },
  setUserData(state, payload) {
    state.userData = payload;
  },
  setProfileData(state, payload) {
    state.profileData = payload;
  },
  updateUserData(state, payload) {
    if (Object.keys(payload).length > 0) {
    }
  },
  updateProfileData(state, payload) {
    if (Object.keys(payload).length > 0) {
    }
  },
  updateUser(state, payload) {
    state.user = payload;
  },
};

const getters = {
  getUser: (state) => state.user,
};

export default {
  state,
  mutations,
};
