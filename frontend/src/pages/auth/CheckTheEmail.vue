<template>
  <div :style="{
    backgroundColor: getThemeColor('background') || '#000',
    color: getThemeColor('accent') || '#fff'
  }" class="email-check-container">
    <!-- Heading -->
    <h1 class="email-check-heading">Check the email</h1>

    <!-- Description -->
    <p class="email-check-description">
      Password reset instructions have been sent to your email. Check your inbox, including the spam folder if needed.
      For assistance, <span class="email-check-link">contact support</span>.
    </p>

    <!-- Button -->
    <div class="email-check-button-container">
      <RouterLink to="/login" class="email-check-button" :style="{
    backgroundColor: getThemeColor('secondary') || '#000',
    color: getThemeColor('text') || '#fff'
  }" >Back to login</RouterLink>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { inject } from 'vue'
// Define the type for the theme
type Theme = {
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
};

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

const { getThemeColor } = theme;
</script>

<style scoped>
/* Container Styles */
.email-check-container {
  text-align: center;
  padding: 10px;
  border: 2px solid;
  border-radius: 2%;
}

/* Heading Styles */
.email-check-heading {
  font-weight: 600;
  font-size: 2.5rem; /* Equivalent to text-4xl */
  margin-bottom: 1rem; /* Equivalent to mb-4 */
}

/* Description Styles */
.email-check-description {
  font-size: 1rem; /* Equivalent to text-base */
  margin-bottom: 1rem; /* Equivalent to mb-4 */
  line-height: 1.25rem; /* Equivalent to leading-5 */
}

/* Link Styles */
.email-check-link {
  color: var(--va-primary);
  text-decoration: underline;
  cursor: pointer;
}

/* Button Container Styles */
.email-check-button-container {
  display: flex;
  justify-content: center;
  margin-top: 1rem; /* Equivalent to mt-4 */
}

/* Button Styles */
.email-check-button {
  display: inline-block;
  width: 100%; /* Equivalent to w-full */
  padding: 0.75rem 1rem;
  background-color: var(--va-primary);
  color: #fff;
  text-decoration: none;
  text-align: center;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.email-check-button:hover {
  background-color: var(--va-primary-dark);
}
</style>
