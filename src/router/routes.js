import store from "@/state/store";

export default [
  {
    path: "/login/:callback?",
    name: "Login",
    component: () => import("../views/account/login.vue"),
    meta: {
      authRequired: false,
      title: "Login",
      async beforeResolve(routeTo, routeFrom, next) {
        // If the user is already logged in

        /* GET ACTION VUEX STORE */
        let authStore = await store.dispatch("auth/getLogin");
        if (authStore.data !== undefined) {
          // Redirect to the home page instead
          next({ name: "Dashboard" });
        } else {
          // Continue to the login page
          next();
        }
      },
    },
  },
  {
    path: "/",
    name: "Dashboard",
    meta: {
      authRequired: true,
    },
    component: () => import("@/views/dashboards/default"),
  },
  {
    path: "/m-unit",
    name: "MUnit",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/m_unit/Index"),
    children: [
      {
        path: "",
        name: "MUnit",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/m_unit/List"),
      },
    ],
  },
  {
    path: "/m-linen",
    name: "MLinen",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/m_linen/Index"),
    children: [
      {
        path: "list",
        name: "MLinenTab",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/m_linen/LinenTab"),
      },
    ],
  },
  {
    path: "/m-alat",
    name: "MAlat",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/m_alat/Index"),
    children: [
      {
        path: "list",
        name: "MAlatTab",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/m_alat/AlatTab"),
      },
      {
        path: "show/:kode?",
        name: "MAlatShowKode",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/m_alat/AlatTab"),
      },
    ],
  },
  {
    path: "/m-alat-bundle",
    name: "MAlatBundle",
    meta: {
      authRequired: true,
    },
    component: () => import("@/views/m_bundle/Index"),
    children: [
      {
        path: "list",
        name: "MAlatBundleList",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/m_bundle/BundleList"),
      },
    ],
  },
  {
    path: "/daftar",
    name: "Daftar",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/daftar/Index"),
    children: [
      {
        path: "harian",
        name: "DaftarHarian",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/daftar/DaftarHarian"),
      },
      {
        path: "edit/:kode?/:jenis?",
        name: "DaftarEdit",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/daftar/DaftarEdit"),
      },
      {
        path: "edit-terima/:kode?/:jenis?",
        name: "DaftarEditTerima",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/daftar/DaftarEdit"),
      },
    ],
  },
  {
    path: "/history",
    name: "HDaftar",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/history/Index"),
    children: [
      {
        path: "daftar",
        name: "HDaftarList",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/history/DaftarHarian"),
      },
      {
        path: ":kode?/:jenis?",
        name: "HDaftarEdit",
        meta: {
          authRequired: true,
        },
        component: () => import("@/views/history/DaftarEdit"),
      },
    ],
  },
  {
    path: "/rekap/:jenis?",
    name: "Rekap",
    meta: {
      authRequired: false,
    },
    component: () => import("@/views/rekap/Index"),
  }
];
