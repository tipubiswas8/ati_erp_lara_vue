<!-- This is app layout including header, sitebar and footer -->
<template>
  <div class="layout">
    <!-- Top Navigation -->
    <header class="layout__navbar fixed">
      <AppNavbar :is-mobile="isMobile" />
    </header>

    <!-- Sidebar -->
    <aside class="layout__sidebar" :class="{ minimized: isSidebarMinimized, absolute: isMobile }"
      @click.self="isSidebarMinimized = true">
      <AppSidebar :mobile="isMobile" />
    </aside>

    <!-- Main Content -->
    <div class="layout__content" :class="{ minimized: isSidebarMinimized }">
      <div class="layout__sidebar-wrapper">
        <div v-if="isFullScreenSidebar" class="layout__close-btn-wrapper">
          <button class="close-btn" @click="onCloseSidebarButtonClick">✖</button>
        </div>
      </div>

      <div style="border: 10px solid;" :style="{ borderColor: getThemeColor('primary') }">
        <AppLayoutNavigation v-if="!isMobile" />
        <main class="layout__main">
          <article>
            <RouterView />
          </article>
        </main>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, onBeforeUnmount, ref, computed, inject, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { onBeforeRouteUpdate } from 'vue-router'
import { useGlobalStore } from '../stores/global-store'
import AppLayoutNavigation from '../components/app-layout-navigation/AppLayoutNavigation.vue'
import AppNavbar from '../components/navbar/AppNavbar.vue'
import AppSidebar from '../components/sidebar/AppSidebar.vue'

// Inject the theme from the parent component
type Theme = {
  setTheme: (theme: 'light' | 'dark') => void;
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary') => string;
  currentTheme: import('vue').ComputedRef<string>;
};

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

// Destructure the functions and properties from the theme
const { getThemeColor } = theme;
const GlobalStore = useGlobalStore()
const { isSidebarMinimized } = storeToRefs(GlobalStore)
const watchSidebarMinimized = ref(isSidebarMinimized)

watch(
  () => isSidebarMinimized,
  (newValue) => {
    watchSidebarMinimized.value = newValue.value
    console.log(watchSidebarMinimized.value)
  },
  { deep: true }
);
// Custom Breakpoints
const breakpoints = {
  sm: 576,
  md: 768,
  lg: 992,
}

const windowWidth = ref(window.innerWidth)
const isMobile = computed(() => windowWidth.value < breakpoints.sm)
const isTablet = computed(() => windowWidth.value >= breakpoints.sm && windowWidth.value < breakpoints.md)
const sidebarMinimizedWidth = ref<string | undefined>(undefined);

const onResize = () => {
  windowWidth.value = window.innerWidth
  isSidebarMinimized.value = windowWidth.value < breakpoints.md
  sidebarMinimizedWidth.value = isMobile.value ? '0' : '15vw'
}

onMounted(() => {
  window.addEventListener('resize', onResize)
  onResize()
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onResize)
})

onBeforeRouteUpdate(() => {
  if (windowWidth.value < breakpoints.md) {
    // Collapse sidebar after route change for Mobile
    isSidebarMinimized.value = true
  }
})

const isFullScreenSidebar = computed(() => isTablet.value && !isSidebarMinimized.value)
const onCloseSidebarButtonClick = () => {
  isSidebarMinimized.value = true
}
</script>


<style scoped>
/* Base styles */
.layout {
  display: flex;
  flex-direction: column;
  height: 92vh;
  margin-top: 8vh;
  padding-top: 5px;
}

.layout__navbar {
  position: relative;
  z-index: 2;
  width: 100%;
  background-color: #f8f9fa;
}

.layout__navbar.fixed {
  position: fixed;
  top: 0;
}

.layout__sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  background-color: #fff;
  width: 15vw;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.layout__sidebar.is_show_sidebar {
  display: none;
  /* This hides the sidebar */
}

.layout__sidebar.absolute {
  position: absolute;
}

.layout__sidebar.minimized {
  width: 5vw;
}

.layout__content {
  flex-grow: 1;
  margin-left: 15vw;
  transition: margin-left 0.3s ease;
}

.layout__content.minimized {
  margin-left: 4.5rem;
}

.layout__sidebar-wrapper {
  position: relative;
}

.layout__close-btn-wrapper {
  display: flex;
  justify-content: flex-end;
  padding: 1rem;
}

.close-btn {
  border: none;
  background-color: transparent;
  font-size: 1.5rem;
  cursor: pointer;
}

.layout__main {
  padding: 0.5rem;
  background-color: #f4f5f7;
  overflow-y: auto;
}

/* Responsive styles */
/* Extra Small Devices (Phones, Portrait Mode) */
/* Styles for phones in portrait mode */

@media (max-width: 576px) {
  .layout {
    margin-top: 5px;
  }

  .layout__content {
    margin-top: 40px;
    margin-left: 0;
  }

  .layout__content.minimized {
    margin-left: 0;
  }
}

/* Small Devices (Phones, Landscape Mode) */
/* Styles for phones in landscape mode */

@media (min-width: 576px) and (max-width: 767px) {
  .layout__sidebar {
    width: 100%;
    height: 100%;
  }

  .layout__content {
    margin-left: 0;
  }
}

/* Medium Devices (Tablets, Portrait Mode) */
@media (min-width: 768px) and (max-width: 991px) {
  /* Styles for tablets in portrait mode */
}

/* Large Devices (Tablets, Landscape Mode, Small Laptops) */
@media (min-width: 992px) and (max-width: 1199px) {
  /* Styles for tablets in landscape mode or small laptops */
}

/* Extra Large Devices (Desktops, Small Screens) */
@media (min-width: 1200px) and (max-width: 1399px) {
  /* Styles for desktops with small screens */
}

/* XX-Large Devices (Desktops, Large Screens) */
@media (min-width: 1400px) {
  /* Styles for desktops with large screens */
}
</style>
