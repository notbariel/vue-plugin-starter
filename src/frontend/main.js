import axios from "axios";
window.axios = axios;

import { createApp, h } from "vue";
import App from "./App.vue";
import router from "./router";

createApp(App).use(router).mount("#vue-frontend-app");
