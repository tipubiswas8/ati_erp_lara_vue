<!-- This is collapse icon and breadcrumb  -->
<template>
  <div class="breadcrumb-style" :class="{ 'hide-header': !isShowHeader }" :style="{
    backgroundColor: getThemeColor('primary'),
    color: getThemeColor('text')
  }">
    <span :style="isShowSidebar ? {display: 'inline'} : {display: 'none'}">
      <!-- Sidebar Toggle Icon -->
      <MenuUnfoldOutlined v-if="isSidebarMinimized" :class="{ 'x-flip': !isSidebarMinimized }"
        class="expand-collapse-icon" :style="{
          backgroundColor: getThemeColor('primary'),
          color: getThemeColor('text')
        }" @click="toggleSidebar" />
      <MenuFoldOutlined v-else :class="{ 'x-flip': !isSidebarMinimized }" class="expand-collapse-icon" :style="{
        backgroundColor: getThemeColor('primary'),
        color: getThemeColor('text')
      }" @click="toggleSidebar" />
    </span>

    <!-- Breadcrumbs -->
    <span class="custom-breadcrumbs">
      <span class="breadcrumb-item" v-for="(item, index) in breadcrumbs" :key="item.label">
        <template v-if="index !== breadcrumbs.length - 1">
          <router-link :style="{
            backgroundColor: getThemeColor('primary'),
            color: getThemeColor('text')
          }" :to="item.to" class="breadcrumb-link">
            {{ item.label }}
          </router-link>
          <span :style="{
            backgroundColor: getThemeColor('primary'),
            color: getThemeColor('text')
          }" class="breadcrumb-separator">/</span>
        </template>
        <template v-else>
          <span :style="{
            backgroundColor: getThemeColor('primary'),
            color: getThemeColor('text')
          }" class="breadcrumb-current">{{ item.label }}</span>
        </template>
      </span>
    </span>
    <div class="menu-name">{{ lastDisplayName }}</div>
  </div>

</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { storeToRefs } from 'pinia';
import { useGlobalStore } from '../../stores/global-store';
import { MenuUnfoldOutlined, MenuFoldOutlined } from '@ant-design/icons-vue';
import navigationRoutes from '../sidebar/NavigationRoutes'; // Import NavigationRoutes
import { useControlPanelSecond } from '../../stores/control-panel'
import { inject } from 'vue'
// Access the store
const controlPanelSecond = useControlPanelSecond();
// Destructure the state and actions using storeToRefs
const { isShowHeader, isShowSidebar } = storeToRefs(controlPanelSecond);
// Define the type for the theme
type Theme = {
  setTheme: (theme: 'light' | 'dark' | 'blue' | 'solarized') => void;
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
  currentTheme: import('vue').ComputedRef<string>;
};

// Inject the global theme
const theme = inject<Theme>('theme')
if (!theme) {
  throw new Error('Theme provider is not available!')
}

// Destructure functions and properties from the theme
const { getThemeColor } = theme

// State and global store
const { isSidebarMinimized } = storeToRefs(useGlobalStore());

// Router and i18n
const router = useRouter();
const route = useRoute();
const { t } = useI18n();

// Recursive function to find route details
const findRouteDetails = (name: string, routes: any[]): any => {
  for (const route of routes) {
    if (route.name === name) {
      return route;
    }
    if (route.children) {
      const childResult = findRouteDetails(name, route.children);
      if (childResult) return childResult;
    }
    if (route.grandChildren) {
      const grandChildResult = findRouteDetails(name, route.grandChildren);
      if (grandChildResult) return grandChildResult;
    }
  }
  return null;
};

// Compute breadcrumbs dynamically
const breadcrumbs = computed(() => {
  const breadcrumbList = [{ label: t('dashboard.home'), to: { name: 'dashboard' } }];
  route.matched.forEach((matchedRoute) => {
    const routeDetails = findRouteDetails(matchedRoute.name as string, navigationRoutes);
    if (routeDetails) {
      breadcrumbList.push({
        label: t(routeDetails.displayName),
        to: { name: matchedRoute.name },
      });
    }
  });
  return breadcrumbList;
});


// Get the last part of the route path
const lastPartName = computed(() => {
  const parts = route.path.split('/').filter((part) => part); // Remove empty strings
  return parts.length ? parts[parts.length - 1] : 'N/A';
  // Return the last part or 'N/A' if empty
});

// Get the last display name from breadcrumbs
const lastDisplayName = computed(() => {
  return breadcrumbs.value.length ? breadcrumbs.value[breadcrumbs.value.length - 1].label : 'N/A';
});


// Toggle sidebar
const toggleSidebar = () => {
  isSidebarMinimized.value = !isSidebarMinimized.value;
};

// Navigate to breadcrumb
const navigateTo = (item: { label: string; to: any }) => {
  if (item.to) {
    router.push(item.to);
  }
};
</script>

<style scoped>
/* .x-flip {
  transform: scaleX(-1);
} */

.breadcrumb-style {
  position: sticky;
  width: 100%;
  height: 8vh;
  min-height: 60px;
  margin-top: calc(8vh + 10px);
  top: calc(8vh + 10px);
  z-index: 99;
}

.hide-header {
  position: sticky;
  width: 100%;
  height: 8vh;
  min-height: 60px;
  margin-top: 0;
  top: 0;
  z-index: 99;
}

.custom-breadcrumbs {
  display: inline;
  align-items: center;
  font-size: 14px;
  margin-left: 5px;
}

.breadcrumb-item {
  display: inline;
  align-items: center;
}

.breadcrumb-link {
  font-size: 20px;
  color: #706d6d;
  text-decoration: none;
  cursor: pointer;
}

.breadcrumb-link:hover {
  text-decoration: underline;
}

.breadcrumb-current {
  font-size: 20px;
  color: #706d6d;
}

.breadcrumb-separator {
  margin: 0 8px;
  color: #6c757d;
}

.expand-collapse-icon {
  font-size: 24px;
  padding-left: 10px;
  padding-top: 10px;
}

.menu-name {
  margin-left: 34px;
  padding: 5px;
  font-size: 16px;
  font-weight: bold;
}
</style>
