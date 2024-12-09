import Cookies from "js-cookie";

export const state = {
  data: Cookies.get("e-linen")?.data,
  token: Cookies.get("e-linen")?.token,
};

export const mutations = {
  setLogin(state, message) {
    const { user, token } = message;
    state.data = user;
    state.token = token;
  },
  setLogout(state) {
    state.data = null;
    state.token = null;
  },
  setLoginByCookies(state) {
    let userCookie = Cookies.get("e-linen");
    state.data =
      userCookie !== undefined ? JSON.parse(userCookie).data : undefined;
    state.token =
      userCookie !== undefined ? JSON.parse(userCookie).token : undefined;
  },
};

export const actions = {
  login({ commit }, message) {
    commit("setLogin", message);
  },
  logout({ commit }) {
    commit("setLogout");
  },
  getLogin({ commit, state }) {
    if (state.data === undefined) {
      commit("setLoginByCookies");
    }

    return state;
  },
};
