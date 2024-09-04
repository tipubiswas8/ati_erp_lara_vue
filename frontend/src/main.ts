import { createApp } from 'vue'
import i18n from './i18n'
import { createVuestic } from 'vuestic-ui'
import { createGtm } from '@gtm-support/vue-gtm'
import { createPinia } from 'pinia'
import router from './router'
import vuesticGlobalConfig from './services/vuestic-ui/global-config'
import App from './App.vue'
import AntDesign from 'ant-design-vue'
import ElementPlus from 'element-plus'

const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.use(router)
app.use(AntDesign)
app.use(ElementPlus)
app.use(i18n)
app.use(createVuestic({ config: vuesticGlobalConfig }))

if (import.meta.env.VITE_APP_GTM_ENABLED) {
  app.use(
    createGtm({
      id: import.meta.env.VITE_APP_GTM_KEY,
      debug: false,
      vueRouter: router,
    }),
  )
}

if (import.meta.env.MODE === 'development') {
  app.config.performance = true
}

app.mount('#app')
