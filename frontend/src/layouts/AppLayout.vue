<template>
  <div class="layout">
    <!-- Top Navigation -->
    <header class="layout__navbar fixed">
      <AppNavbar :is-mobile="isMobile" />
    </header>

    <!-- Sidebar -->
    <aside class="layout__sidebar"
      :class="{ minimized: isSidebarMinimized, absolute: isMobile, overlay: isMobile && !isSidebarMinimized }"
      @click.self="isSidebarMinimized = true">
      <AppSidebar :minimized="isSidebarMinimized" :animated="!isMobile" :mobile="isMobile" />
    </aside>

    <!-- Main Content -->
    <div style="margin-top: 52px;" class="layout__content" :class="{ minimized: isSidebarMinimized }">
      <div class="layout__sidebar-wrapper">
        <div v-if="isFullScreenSidebar" class="layout__close-btn-wrapper">
          <button class="close-btn" @click="onCloseSidebarButtonClick">✖</button>
        </div>
      </div>
      <AppLayoutNavigation v-if="!isMobile" class="layout__navigation" />
      <main class="layout__main">
        <article>
          <RouterView />
        </article>
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { onBeforeRouteUpdate } from 'vue-router'

import { useGlobalStore } from '../stores/global-store'

import AppLayoutNavigation from '../components/app-layout-navigation/AppLayoutNavigation.vue'
import AppNavbar from '../components/navbar/AppNavbar.vue'
import AppSidebar from '../components/sidebar/AppSidebar.vue'

const GlobalStore = useGlobalStore()
const { isSidebarMinimized } = storeToRefs(GlobalStore)

// Custom Breakpoints
const breakpoints = {
  sm: 576,
  md: 768,
  lg: 992,
}

const windowWidth = ref(window.innerWidth)

const isMobile = computed(() => windowWidth.value < breakpoints.sm)
const isTablet = computed(() => windowWidth.value >= breakpoints.sm && windowWidth.value < breakpoints.md)

const sidebarWidth = ref('16rem')
const sidebarMinimizedWidth = ref(undefined)

const onResize = () => {
  windowWidth.value = window.innerWidth
  isSidebarMinimized.value = windowWidth.value < breakpoints.md
  sidebarMinimizedWidth.value = isMobile.value ? '0' : '4.5rem'
  sidebarWidth.value = isTablet.value ? '100%' : '16rem'
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
  height: 100vh;
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
  width: 16rem;
  transition: all 0.3s ease;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.layout__sidebar.absolute {
  position: absolute;
}

.layout__sidebar.minimized {
  width: 4.5rem;
}

.layout__sidebar.overlay {
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.5);
}

.layout__content {
  flex-grow: 1;
  margin-left: 16rem;
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
  padding: 1rem;
  background-color: #f4f5f7;
  overflow-y: auto;
}

/* Responsive styles */
@media (max-width: 768px) {
  .layout__sidebar {
    width: 100%;
    height: 100%;
  }

  .layout__content {
    margin-left: 0;
  }
}

@media (max-width: 576px) {
  .layout__sidebar {
    width: 0;
  }

  .layout__content {
    margin-left: 0;
  }
}
</style>
