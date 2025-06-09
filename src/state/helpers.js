import { mapState, mapActions } from "vuex";

// export const authComputed = {
//   ...mapState('auth', {
//     currentUser: (state) => state.currentUser,
//   }),
//   ...mapGetters('auth', ['loggedIn']),
// }

export const layoutComputed = {
  ...mapState("layout", {
    layoutType: (state) => state.layoutType,
    leftSidebarType: (state) => state.leftSidebarType,
    layoutWidth: (state) => state.layoutWidth,
    topbar: (state) => state.topbar,
    loader: (state) => state.loader,
  }),
};

// export const authMethods = mapActions('auth', ['logIn', 'logOut', 'register', 'resetPassword'])

export const layoutMethods = mapActions("layout", [
  "changeLayoutType",
  "changeLayoutWidth",
  "changeLeftSidebarType",
  "changeTopbar",
  "changeLoaderValue",
]);

export const authFackMethods = mapActions("authfack", [
  "login",
  "registeruser",
  "logout",
]);

export const notificationMethods = mapActions("notification", [
  "success",
  "error",
  "clear",
]);

export const todoComputed = {
  ...mapState("todo", {
    todos: (state) => state.todos,
  }),
};
export const todoMethods = mapActions("todo", ["fetchTodos"]);

export const authState = {
  ...mapState("auth", {
    token: (state) => state.token,
    data: (state) => state.data,
  }),
};

export const authMethods = mapActions("munit", ["login", "getLogin", "logout"]);

export const toastState = {
  ...mapState("toast", {
    visible: (state) => state.visible,
    type: (state) => state.type,
    title: (state) => state.title,
    msg: (state) => state.message,
  }),
};

export const toastMethods = mapActions("toast", [
  "toastSuccess",
  "toastError",
  "clear",
  "close",
]);

export const spinnerMethods = mapActions("spinner", ["show", "hide"]);

export const spinnerState = {
  ...mapState("spinner", {
    visible: (state) => state.visible,
  }),
};

/**
 * M UNIT
 */
export const mUnitState = {
  ...mapState("mUnit", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mUnitMethods = mapActions("mUnit", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);

/**
 * M ALAT
 */
export const mAlatState = {
  ...mapState("mAlat", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mAlatMethods = mapActions("mAlat", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);
/**
 * M BUNDLE
 */
export const mBundleState = {
  ...mapState("mBundle", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mBundleMethods = mapActions("mBundle", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);
/**
 * M Kategori
 */
export const mKategoriState = {
  ...mapState("mKategori", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mKategoriMethods = mapActions("mKategori", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);

/**
 * M ALAT Detail
 */
export const mAlatDetailState = {
  ...mapState("mAlatDetail", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mAlatDetailMethods = mapActions("mAlatDetail", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);

/**
 * M Linen Detail
 */
export const mLinenState = {
  ...mapState("mLinen", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mLinenMethods = mapActions("mLinen", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "reloadTable",
]);

/**
 * M Linen Unit Detail
 */
export const mLinenUnitState = {
  ...mapState("mLinenUnit", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const mLinenUnitMethods = mapActions("mLinenUnit", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterUnit",
  "onFilterSearch",
  "reloadTable",
]);

/**
 * Daftar
 */
export const daftarState = {
  ...mapState("daftar", {
    server: (state) => state.server,
    filter: (state) => state.filter,
  }),
};

export const daftarMethods = mapActions("daftar", [
  "onPageChange",
  "onPerPageChange",
  "resetFilter",
  "onFilterSearch",
  "onFilterJenis",
  "reloadTable",
]);
