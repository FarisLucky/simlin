import { createApp, h } from "vue";

import App from "./App.vue";
import router from "./router";
import vClickOutside from "click-outside-vue3";
import { registerScrollSpy } from "vue3-scroll-spy";

import i18n from "./i18n";

import BootstrapVueNext from "bootstrap-vue-next";
import vSelect from "vue-select";
import VueLazyload from "vue-lazyload";
import Vue3EasyDataTable from "vue3-easy-data-table";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-next/dist/bootstrap-vue-next.css";
import "vue3-easy-data-table/dist/style.css";

import store from "./state/store";

import "../src/design/app.scss";
import "vue-select/dist/vue-select.css";
import "flatpickr/dist/flatpickr.css";

vSelect.props.components.default = () => ({
  Deselect: {
    render: () => h("span", "❌"),
  },
  OpenIndicator: {
    render: () => h("span", "🔽"),
  },
});

createApp(App)
  .use(store)
  .use(router)
  .use(require("vue-chartist"))
  .use(BootstrapVueNext)
  .use(vClickOutside)
  .use(i18n)
  .use(registerScrollSpy)
  .use(VueLazyload, {
    preLoad: 1.3,
    // error: errorimage,
    // loading: loadimage,
    attempt: 1,
  })
  .component("v-select", vSelect)
  .component("EasyDataTable", Vue3EasyDataTable)
  .mount("#app");
