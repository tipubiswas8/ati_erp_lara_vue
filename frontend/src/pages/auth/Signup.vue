<template>
  <div class="main-div" :style="{
    backgroundColor: getThemeColor('background') || '#000',
    color: getThemeColor('accent') || '#fff'
  }">
  <form ref="form" @submit.prevent="submit" class="signup-form">
    <!-- Heading -->
    <h1 class="signup-form-heading">Sign up</h1>

    <!-- Login Link -->
    <p class="signup-form-login-link">
      Have an account?
      <RouterLink :to="{ name: 'login' }" class="signup-form-login-link-text">Login</RouterLink>
    </p>

    <!-- Email Input -->
    <div class="signup-form-input">
      <label for="email" class="signup-form-label">Email</label>
      <input v-model="formData.email" id="email" type="email" class="signup-form-input-field"
        placeholder="Enter your email" :required="true" />
      <span v-if="!formData.email" class="signup-form-error">Email field is required</span>
      <span v-if="formData.email && !/.+@.+\..+/.test(formData.email)" class="signup-form-error">Email should be
        valid</span>
    </div>

    <!-- Password Input -->
    <div class="signup-form-input">
      <label for="password" class="signup-form-label">Password</label>
      <div class="signup-form-password-container">
        <input v-model="formData.password" id="password" type="password" class="signup-form-input-field"
          placeholder="Enter your password" :required="true" />
          <span v-if="!formData.password" class="signup-form-error">Password field is required</span>
        <span v-if="formData.password && formData.password.length < 8" class="signup-form-error">
          Password must be at least 8 characters long
        </span>
        <span v-if="formData.password && !/[A-Za-z]/.test(formData.password)" class="signup-form-error">
          Password must contain at least one letter
        </span>
        <span v-if="formData.password && !/\d/.test(formData.password)" class="signup-form-error">
          Password must contain at least one number
        </span>
        <span v-if="formData.password && !/[!@#$%^&*(),.?':{}|<>]/.test(formData.password)" class="signup-form-error">
          Password must contain at least one special character
        </span>
      </div>
    </div>

    <!-- Repeat Password Input -->
    <div class="signup-form-input">
      <label for="repeatPassword" class="signup-form-label">Repeat Password</label>
      <div class="signup-form-password-container">
        <input v-model="formData.repeatPassword" id="repeatPassword" type="password" class="signup-form-input-field"
          placeholder="Repeat your password" :required="true" />
          <span v-if="!formData.repeatPassword" class="signup-form-error">Repeat Password field is required</span>
        <span v-if="formData.repeatPassword && formData.repeatPassword !== formData.password" class="signup-form-error">
          Passwords don't match
        </span>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="signup-form-button-container">
      <button type="submit" class="signup-form-button" :style="{
    backgroundColor: getThemeColor('border') || '#000',
    color: getThemeColor('background') || '#fff'
  }">Create account</button>
    </div>
  </form>

  <!-- Toast Notifications -->
  <div v-if="toast.visible" :class="['toast', toast.type]">
    <span>{{ toast.message }}</span>
  </div>
</div>
</template>

<script lang="ts" setup>
import { reactive, ref, inject } from 'vue'
import { useRouter } from 'vue-router'

type Theme = {
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
};

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

const { getThemeColor } = theme;

// Reactive form data
const formData = reactive({
  email: '',
  password: '',
  repeatPassword: '',
})

// Toast state
const toast = ref({
  message: '',
  type: '',
  visible: false
})

// Router navigation
const { push } = useRouter()

// Form validation and submission logic
const submit = () => {
  // Validate form fields
  if (
    formData.email &&
    formData.password &&
    formData.repeatPassword &&
    formData.password === formData.repeatPassword
  ) {
    // Show success toast
    showToast('You\'ve successfully signed up', 'success')
    // Redirect to dashboard
    setTimeout(function(){
      push({ name: 'dashboard' })
    }, 2000)
  } else {
    // Show error toast
    showToast('Please fill in all fields correctly', 'error')
  }
}

// Custom toast function to display notifications
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
.main-div {
  padding: 10px;
  border: 2px solid;
  border-radius: 2%;
}

/* Form Styles */
.signup-form {
  width: 420px;
  margin: 0 auto;
  text-align: center;
}

/* Heading Styles */
.signup-form-heading {
  font-weight: 600;
  font-size: 2.5rem;
  /* Equivalent to text-4xl */
  margin-bottom: 1rem;
  /* Equivalent to mb-4 */
}

/* Login Link Styles */
.signup-form-login-link {
  font-size: 1rem;
  /* Equivalent to text-base */
  margin-bottom: 1rem;
}

.signup-form-login-link-text {
  font-weight: 600;
  color: var(--primary-color);
  text-decoration: none;
}

.signup-form-login-link-text:hover {
  text-decoration: underline;
}

/* Input Styles */
.signup-form-input {
  margin-bottom: 1rem;
  /* Equivalent to mb-4 */
  text-align: left;
  max-width: 94%;
}

.signup-form-label {
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.signup-form-input-field {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}

/* Error Message Styles */
.signup-form-error {
  color: red;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

/* Password Container */
.signup-form-password-container {
  position: relative;
}

/* Button Container */
.signup-form-button-container {
  margin-top: 1rem;
  /* Equivalent to mt-4 */
}

.signup-form-button {
  width: 100%;
  padding: 0.75rem;
  background-color: var(--primary-color);
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.signup-form-button:hover {
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
  z-index: 9999;
}

.toast.success {
  background-color: green;
}

.toast.error {
  background-color: red;
}
</style>
