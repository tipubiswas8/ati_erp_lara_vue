<!-- AppLayout.vue -->
<template>
  <header class="app-layout-navbar py-2 px-0">
    <div class="navbar-container">
      <!-- Left section -->
      <div class="left">
        <CustomTransition :show="isMobile" name="icon-fade" @click="isSidebarMinimized = !isSidebarMinimized">
          <component 
            :is="sidebarIcon" 
            class="sidebar-toggle-icon"
            :style="{ color: collapseIconColor }"
            size="24px" 
            style="margin-top: 3px"
          />
        </CustomTransition>
        <RouterLink to="/" aria-label="Visit home page">
          <img src="/images/drug-logo.png" style="height: 35px; width: 200px;" alt="ATI Logo" />
        </RouterLink>
      </div>

      <!-- Right section -->
      <div class="right">
        <AppNavbarActions class="app-navbar__actions" :is-mobile="isMobile" :style="{ marginRight: '1vw' }" />
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue';
import { storeToRefs } from 'pinia';
import { useGlobalStore } from '../../stores/global-store';
import AppNavbarActions from './AppNavbarActions.vue';
import { MenuOutlined, CloseOutlined } from '@ant-design/icons-vue';
import CustomTransition from './CustomTransition.vue';

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
const { isSidebarMinimized } = storeToRefs(useGlobalStore());

// Sidebar icon logic
const sidebarIcon = computed(() => isSidebarMinimized.value ? MenuOutlined : CloseOutlined);
const collapseIconColor = computed(() => getThemeColor('primary'));
</script>

<style scoped>
.app-layout-navbar {
  z-index: 2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 1rem;
  background-color: var(--background);
  color: var(--text);
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  width: 100%;
}

.left {
  display: flex;
  align-items: center;

  &>* {
    margin-right: 1rem;
  }

  &>*:last-child {
    margin-right: 0;
  }
}

.right {
  display: flex;
  align-items: center;
}

.icon-fade-enter-active,
.icon-fade-leave-active {
  transition: transform 0.5s ease;
}

.icon-fade-enter,
.icon-fade-leave-to {
  transform: scale(0.5);
}

.sidebar-toggle-icon {
  font-size: 24px;
  cursor: pointer;
}

@media screen and (max-width: 950px) {
  .left {
    width: 100%;
  }

  .right {
    display: none;
  }
}
</style>
