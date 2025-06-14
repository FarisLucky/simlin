export const state = {
  server: {
    page: 1,
    rowsPerPage: 10,
    sortBy: "created_at",
    sortType: "DESC",
  },
  filter: {
    search: "",
  },
};

export const mutations = {
  updateParams(state, newProps) {
    state.server = Object.assign({}, state.server, newProps);
  },
  updateFilter(state, newProps) {
    state.filter = Object.assign({}, state.filter, newProps);
  },
  reload(state) {
    state.reload++;
  },
};

export const actions = {
  onPageChange({ commit, dispatch }, params) {
    commit("updateParams", { page: params.currentPage });
    dispatch("reloadTable");
  },

  onPerPageChange({ commit, dispatch }, params) {
    commit("updateParams", { perPage: params.currentPerPage });
    dispatch("reloadTable");
  },

  resetFilter({ commit }) {
    commit("updateFilter", {
      search: "",
    });
  },

  onFilterSearch({ commit }, search) {
    console.log(search);
    commit("updateFilter", { search: search });
  },

  reloadTable({ commit }) {
    commit("reload");
  },
};
