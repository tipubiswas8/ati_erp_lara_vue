<template>
  <div :style="{
    backgroundColor: getThemeColor('background') || '#000',
    color: getThemeColor('accent') || '#fff',
    fontFamily: currentThemeFontFamily
  }">
    <form ref="form" @submit.prevent="submit" class="login-form">
      <!-- Heading -->
      <h1 class="login-form-heading">Log in</h1>

      <!-- Sign-up Link -->
      <p class="login-form-signup-link">
        Don't have an account?
        <RouterLink :to="{ name: 'signup' }" class="login-form-signup-link-text">Sign up</RouterLink>
      </p>

      <!-- Email Input -->
      <div class="login-form-input">
        <label for="email" class="login-form-label">Email</label>
        <input v-model="formData.email" id="email" type="email" class="login-form-input-field"
          placeholder="Enter your email" :required="true" />
        <span v-if="!formData.email" class="login-form-error">Email field is required</span>
        <span v-if="formData.email && !/.+@.+\..+/.test(formData.email)" class="login-form-error">Email should be
          valid</span>
      </div>

      <!-- Password Input -->
      <div class="login-form-input">
        <label for="password" class="login-form-label">Password</label>
        <div class="login-form-password-container">
          <input v-model="formData.password" id="password" type="password" class="login-form-input-field"
            placeholder="Enter your password" :required="true" />
          <span v-if="!formData.password" class="login-form-error">Password field is required</span>
        </div>
      </div>

      <!-- Keep Me Signed In Option -->
      <div class="login-form-options">
        <label class="login-form-checkbox">
          <input v-model="formData.keepLoggedIn" type="checkbox" />
          Keep me signed in on this device
        </label>
        <RouterLink :to="{ name: 'recover-password' }" class="login-form-forgot-password">
          Forgot password?
        </RouterLink>
      </div>

      <!-- Submit Button -->
      <div class="login-form-button-container">
        <button type="submit" class="login-form-button" :style="{
          backgroundColor: getThemeColor('primary') || '#000',
          color: getThemeColor('text') || '#fff',
          fontFamily: currentThemeFontFamily
        }">Login</button>
      </div>
    </form>

    <!-- Toast Notifications -->
    <div v-if="toast.visible" :class="['toast', toast.type]">
      <span>{{ toast.message }}</span>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref, inject, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../../stores/auth'

// Define the type for the theme
type Theme = {
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
  getThemeFontFamily: () => string;
};

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

const { getThemeColor, getThemeFontFamily } = theme;
// Computed property for the current font-family
const currentThemeFontFamily = computed(() => getThemeFontFamily());
// Reactive form data
const formData = reactive({
  email: '',
  password: '',
  keepLoggedIn: false,
})

// Toast state
const toast = ref({
  message: '',
  type: '',
  visible: false
})

// Router navigation
const { push } = useRouter()

// Auth store
const authStore = useAuthStore()

// Clear previous token
authStore.clearToken()

// Submit function
const submit = async () => {
  if (formData.email && formData.password) {
    try {
      // API call for login
      const response = await axios.post('http://localhost:8000/api/login', formData)
      const token = response.data.token

      // Show success toast
      showToast(response.data.message, 'success')

      // Store the token
      authStore.setToken(token)

      // Redirect to dashboard
      push({ name: 'dashboard' })
    } catch (error) {
      console.error('Login failed:', error)

      // Show error toast
      if (error.response) {
        showToast(error.response.data.message, 'error')
      } else {
        showToast('An unknown error occurred', 'error')
      }
    }
  } else {
    // Validation failed
    showToast('Please fill in all fields', 'error')
  }
}

// Custom toast function
const showToast = (message: string, type: string) => {
  toast.value = {
    message,
    type,
    visible: true
  }

  // Hide toast after 3 seconds
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}
</script>

<style scoped>
/* Form Styles */
.login-form {
  max-width: 420px;
  margin: 0 auto;
  text-align: center;
  padding: 10px;
  border: 2px solid;
  border-radius: 2%;
}

/* Heading Styles */
.login-form-heading {
  font-weight: 600;
  font-size: 2.5rem;
  /* Equivalent to text-4xl */
  margin-bottom: 1rem;
  /* Equivalent to mb-4 */
}

/* Sign-Up Link Styles */
.login-form-signup-link {
  font-size: 1rem;
  /* Equivalent to text-base */
  margin-bottom: 1rem;
}

.login-form-signup-link-text {
  font-weight: 600;
  color: var(--primary-color);
  text-decoration: none;
}

.login-form-signup-link-text:hover {
  text-decoration: underline;
}

/* Input Styles */
.login-form-input {
  margin-bottom: 1rem;
  /* Equivalent to mb-4 */
  text-align: left;
  width: 94%;
}

.login-form-label {
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.login-form-input-field {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}

/* Error Message Styles */
.login-form-error {
  color: red;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

/* Checkbox and Forgot Password Styles */
.login-form-options {
  margin-top: 1rem;
  /* Equivalent to mt-4 */
}

.login-form-checkbox {
  font-size: 1rem;
}

.login-form-forgot-password {
  font-size: 1rem;
  color: var(--primary-color);
  text-decoration: none;
  margin-top: 1rem;
}

.login-form-forgot-password:hover {
  text-decoration: underline;
}

/* Button Styles */
.login-form-button-container {
  margin-top: 1rem;
}

.login-form-button {
  width: 100%;
  padding: 0.75rem;
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.login-form-button:hover {
  background-color: var(--primary-dark);
}

/* Toast Styles */
.toast {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 1rem;
  background-color: #333;
  color: #fff;
  border-radius: 5px;
  font-size: 1rem;
  z-index: 1000;
}

.toast.success {
  background-color: green;
}

.toast.error {
  background-color: red;
}
</style>
