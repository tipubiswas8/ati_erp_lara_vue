<script lang="ts" setup>
import { useRoute } from 'vue-router'

// Access the route and retrieve the error message
const route = useRoute()
const errorMessage = route.query.message || ''
</script>

<template>
  <div class="error-page">
    <!-- Logo -->
    <RouterLink to="/" aria-label="Go to homepage">
      <img class="logo" src="/images/drug-logo.png" alt="ATI Logo" />
    </RouterLink>

    <!-- Main Content -->
    <div class="error-content">
      <!-- Placeholder Image -->
      <img class="placeholder-image" src="/images/placeholder-error.png" alt="Not Found Image" />
      
      <!-- Error Message -->
      <h1 class="error-message">{{ errorMessage }}</h1>

      <!-- Button -->
      <div class="button-container">
        <a href="/" class="button">Go to Homepage</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Container Styles */
.error-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  background-color: var(--va-background-secondary);
  min-height: 100vh;
  padding: 2rem 1rem;
}

/* Logo Styles */
.logo {
  height: 50px;
  width: 200px;
}

/* Error Content Styles */
.error-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
  padding: 0 1rem;
  margin: 2rem 0;
}

/* Placeholder Image */
.placeholder-image {
  width: 100%;
  max-width: 300px;
  height: auto;
  object-fit: contain;
}

/* Error Message */
.error-message {
  font-size: 30px;
  font-weight: bold;
  text-align: center;
}

/* Button Container */
.button-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

@media (min-width: 640px) {
  .button-container {
    flex-direction: row;
  }
}

/* Button Styles */
.button {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: var(--va-primary);
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  font-size: 1rem;
  text-align: center;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.button:hover {
  background-color: var(--va-primary-dark);
}
</style>
