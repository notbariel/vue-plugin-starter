import axios from "axios";
window.axios = axios;

import { createApp, h } from "vue";
import App from "./App.vue";
import router from "./router";
import menuFix from "./utils/admin-menu-fix";

createApp(App).use(router).mount("#vue-admin-app");

// fix the admin menu for the slug "vue-app"
menuFix("vue-app");
