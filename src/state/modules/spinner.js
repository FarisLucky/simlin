export const state = {
  visible: false,
};

export const mutations = {
  show(state) {
    state.visible = true;
  },
  hide(state) {
    state.visible = false;
  },
};

export const actions = {
  show({ commit }) {
    commit("show");
  },
  hide({ commit }) {
    commit("hide");
  },
};
