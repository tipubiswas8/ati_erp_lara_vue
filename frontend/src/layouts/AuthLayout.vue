<template>
  <div v-if="isLgUp" class="layout lg-layout">
    <!-- Left Section -->
    <div class="left-section">
      <RouterLink to="/" aria-label="Visit homepage">
        <VuesticLogo :height="28" start="#FFF" />
      </RouterLink>
    </div>

    <!-- Main Content -->
    <div class="content-section">
      <main>
        <RouterView />
      </main>
    </div>
  </div>

  <div v-else class="layout sm-layout">
    <!-- Main Content -->
    <div class="content-wrapper">
      <div class="main-content">
        <div class="logo-and-content">
          <RouterLink class="logo-link" to="/" aria-label="Visit homepage">
            <img class="logo" src="/images/drug-logo.png" alt="ATI Logo" />
          </RouterLink>
          <main>
            <RouterView />
          </main>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Define custom breakpoints
const BREAKPOINTS = {
  lg: 1024, // Equivalent to 'lg' in Vuestic's breakpoints
}

// Reactive state for current screen size
const isLgUp = ref(false)

// Function to update the screen size state
const updateBreakpoint = () => {
  isLgUp.value = window.innerWidth >= BREAKPOINTS.lg
}

// Add event listeners on mount, and remove them on unmount
onMounted(() => {
  updateBreakpoint()
  window.addEventListener('resize', updateBreakpoint)
})

onUnmounted(() => {
  window.removeEventListener('resize', updateBreakpoint)
})
</script>

<style scoped>
/* Layout styles */
.layout {
  height: 100vh;
  display: flex;
}

/* Large screen layout */
.lg-layout {
  flex-direction: row;
}

.left-section {
  width: 35vw;
  background-color: var(--va-background-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
}

.content-section {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  max-width: 420px;
  margin: 0 auto;
}

/* Small screen layout */
.sm-layout {
  background-color: var(--va-background-secondary);
}

.content-wrapper {
  padding: 1rem;
}

.main-content {
  height: 100%;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  max-width: 420px;
  margin: 0 auto;
}

.logo-and-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.logo-link {
  padding: 1rem 0;
}

.logo {
  height: 35px;
  width: 200px;
}
</style>
