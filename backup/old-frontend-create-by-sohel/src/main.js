import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import AntDesign from "ant-design-vue";
import ElementPlus from "element-plus";
import { useAuthStore } from './stores/auth';

library.add(fas, far, fab)

const app = createApp(App)
app.component("font-awesome-icon", FontAwesomeIcon)
app.use(createPinia())
app.use(router)
app.use(AntDesign)
app.use(ElementPlus)
const authStore = useAuthStore();
authStore.loadToken();
app.mount('#app')