
import { createApp } from 'vue'
import axios, { isAxiosError } from 'axios'
import i18n from './i18n'
import { createVuestic } from 'vuestic-ui'
import { createGtm } from '@gtm-support/vue-gtm'
import { createPinia } from 'pinia'
import router from './router'
import vuesticGlobalConfig from './services/vuestic-ui/global-config'
import App from './App.vue'
import AntDesign from 'ant-design-vue'
import ElementPlus from 'element-plus'
import { useToast } from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'
import { useAuthStore } from './stores/auth'
import '@/assets/styles/css/global.css'
import '@/assets/script/js/global'
// import { onlyNumber } from '@/assets/script/js/validation/for-basic/common'
import { onlyNumber } from '@/assets/script/js/validation/for-and-design/common'
import useTheme from '../src/theme/ThemeProvider'

// Provide the theme globally
const app = createApp(App)
const theme = useTheme()
const pinia = createPinia()
app.use(pinia)
app.use(axios)
app.use(router)
app.use(AntDesign)
app.use(ElementPlus)
app.use(i18n)
app.use(createVuestic({ config: vuesticGlobalConfig }))
app.directive("only-number", onlyNumber);
app.provide('theme', theme)
// Define the validatePhone function globally

app.config.globalProperties.$validatePhone = async (value: any) => {
  if (!/^\d+$/.test(value)) {
    return Promise.reject(new Error('Phone number is not valid!'));
  }
  return Promise.resolve();
};

// Define the blockNonNumericKeys function globally
app.config.globalProperties.$blockNonNumericKeys = (event: any) => {
  if (['e', 'f', 'j', 'k', 'l'].includes(event.key)) {
    event.preventDefault();
  }
};

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

function showToast(message: string, type: 'error' | 'error' = 'error') {
  const $toast = useToast()
  $toast[type](message, { duration: 3000 })
}

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response) {
      if (isAxiosError(error)) {
        const messages = error.response.data.error_message || {}
        switch (error.response?.status) {
          case 400:
            Object.values(messages).forEach((messageArray: any) => {
              if (Array.isArray(messageArray)) {
                messageArray.forEach((msg: string) => showToast(msg))
              } else {
                showToast(messageArray)
              }
            })
            break
          case 401:
            showToast(error.response?.data.error_message)
            break
          case 404:
            router.push({
              path: '/404',
              query: { message: error.response?.data.error_message },
            })
            break
          case 500:
            showToast(error.response?.data.error_message)
            break
        }
      }
    } else {
      showToast(error.message)
    }
    return Promise.reject(error)
  }
)

const authStore = useAuthStore()
authStore.loadToken()
const token = authStore.token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

app.mount('#app')

