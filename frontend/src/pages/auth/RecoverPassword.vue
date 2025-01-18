<template>
  <form ref="passwordForm" @submit.prevent="submit" class="password-form">
    <!-- Heading -->
    <h1 class="password-form-heading">Forgot your password?</h1>

    <!-- Description -->
    <p class="password-form-description">
      If you've forgotten your password, don't worry. Simply enter your email address below, and we'll send you an email
      with a temporary password. Restoring access to your account has never been easier.
    </p>

    <!-- Email Input -->
    <div class="password-form-input">
      <label for="email" class="password-form-label">Enter your email</label>
      <input
        v-model="email"
        id="email"
        type="email"
        class="password-form-input-field"
        :required="true"
        placeholder="Your email address"
      />
      <span v-if="!email" class="password-form-error">Email field is required</span>
    </div>

    <!-- Send Password Button -->
    <div class="password-form-button-container">
      <button type="submit" class="password-form-button">Send password</button>
    </div>

    <!-- Go Back Button -->
    <div class="password-form-button-container">
      <RouterLink to="/login" class="password-form-button password-form-button-secondary">Go back</RouterLink>
    </div>
  </form>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const router = useRouter()

const submit = () => {
  if (email.value) {
    router.push({ name: 'recover-password-email' })
  }
}
</script>

<style scoped>
/* Form Container */
.password-form {
  text-align: center;
  max-width: 420px;
  margin: 0 auto;
}

/* Heading Styles */
.password-form-heading {
  font-weight: 600;
  font-size: 2.5rem; /* Equivalent to text-4xl */
  margin-bottom: 1rem; /* Equivalent to mb-4 */
}

/* Description Styles */
.password-form-description {
  font-size: 1rem; /* Equivalent to text-base */
  margin-bottom: 1rem; /* Equivalent to mb-4 */
  line-height: 1.25rem; /* Equivalent to leading-5 */
}

/* Input Container Styles */
.password-form-input {
  margin-bottom: 1rem; /* Equivalent to mb-4 */
  text-align: left;
}

/* Label Styles */
.password-form-label {
  font-weight: 500;
  font-size: 1rem;
  margin-bottom: 0.5rem;
}

/* Input Field Styles */
.password-form-input-field {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}

/* Error Message Styles */
.password-form-error {
  color: red;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

/* Button Container Styles */
.password-form-button-container {
  margin-top: 1rem; /* Equivalent to mt-4 */
  width: 100%;
}

/* Button Styles */
.password-form-button {
  width: 100%;
  padding: 0.75rem;
  background-color: var(--va-primary);
  color: #fff;
  text-align: center;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.password-form-button:hover {
  background-color: var(--va-primary-dark);
}

/* Secondary Button Styles */
.password-form-button-secondary {
  background-color: var(--va-secondary);
  margin-top: 0.5rem;
}

.password-form-button-secondary:hover {
  background-color: var(--va-secondary-dark);
}
</style>
