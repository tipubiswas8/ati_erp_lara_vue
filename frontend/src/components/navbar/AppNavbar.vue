<!-- AppLayout.vue -->
<template>
  <header v-if="isShowHeader" class="app-layout-navbar">
    <!-- Left section -->
    <div class="left">
      <CustomTransition :show="isMobile" name="fade" :duration="500" @click="isSidebarMinimized = !isSidebarMinimized">
        <component :is="sidebarIcon" class="sidebar-toggle-icon" :style="{ color: collapseIconColor }" size="24px" />
      </CustomTransition>
      <RouterLink to="/" aria-label="Dashboard">
        <img src="/images/drug-logo.png" class="logo-style" alt="Side Logo" />
      </RouterLink>
    </div>

    <!-- Right section -->
    <div class="right">
      <div class="custom-icon">
        <RouterLink to="/settings">
          <SettingOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <ControlOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <LockOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <PoweroffOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <MessageOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <WechatOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <CustomerServiceOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <ZoomInOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <ShrinkOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/settings">
          <ArrowDownOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <ArrowUpOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <ArrowLeftOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <ArrowRightOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <GlobalOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <SwitcherOutlined class="icon-style" />
        </RouterLink>
        <RouterLink to="/">
          <FullscreenOutlined class="icon-style" />
        </RouterLink>
      </div>
    </div>


    <!-- AppNavbarActions -->
    <div class="custom-icon-profile user-profile">
      <RouterLink to="/settings">
        <NotificationOutlined class="icon-style" />
      </RouterLink>
      <RouterLink to="/settings">
        <img src="/images/messages.png" class="notification-style" alt="Notification" />
      </RouterLink>
      <AppNavbarActions :is-mobile="isMobile" />
    </div>
  </header>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue';
import { storeToRefs } from 'pinia';
import { useGlobalStore } from '../../stores/global-store';
import AppNavbarActions from './AppNavbarActions.vue';
import {
  SettingOutlined,
  ControlOutlined,
  LockOutlined,
  PoweroffOutlined,
  MessageOutlined,
  WechatOutlined,
  CustomerServiceOutlined,
  MenuOutlined,
  CloseOutlined,
  FullscreenOutlined,
  ZoomInOutlined,
  NotificationOutlined,
  SwitcherOutlined,
  GlobalOutlined,
  ArrowRightOutlined,
  ArrowLeftOutlined,
  ArrowUpOutlined,
  ArrowDownOutlined,
  ShrinkOutlined
} from '@ant-design/icons-vue';
import CustomTransition from './CustomTransition.vue';
import { useControlPanelSecond } from '../../stores/control-panel'

defineProps({
  isMobile: { type: Boolean, default: false },
})
// Access the store
const controlPanelSecond = useControlPanelSecond();
// Destructure the state and actions using storeToRefs
const { isShowHeader } = storeToRefs(controlPanelSecond);
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
const collapseIconColor = computed(() => getThemeColor('border'));
</script>

<style scoped>
.app-layout-navbar {
  width: 100%;
  height: 8vh;
  padding: 5px;
  display: flex;
  align-items: center;
  /* Center items vertically */
  justify-content: space-between;
  /* Space between left and right sections */
  z-index: 2;
  background-color: var(--background);
  color: var(--text);
}

.left {
  display: flex;
  align-items: left;
  order: 1;
}

.right {
  display: flex;
  align-items: center;
  margin-left: auto;
  order: 2;
}

.user-profile {
  display: flex;
  align-items: center;
  order: 3;
}

.logo-style {
  width: 200px;
  height: 8vh;
}

.notification-style {
  height: 4vh;
  width: 2vw;
}

.sidebar-toggle-icon {
  font-size: 24px;
  cursor: pointer;
}

.custom-icon-profile,
.custom-icon {
  font-size: 4vh;
}

.icon-style {
  padding: 5px;
}

/* Target all RouterLink-generated <a> elements */
a {
  color: inherit;
  /* Inherit color from parent or set your desired color */
  text-decoration: none;
  /* Remove underline */
}

/* Remove visited (blue) and active (red) styles */
a:visited,
a:active {
  color: inherit;
  text-decoration: none;
}

/* Override Vue Router active link styles */
.router-link-active,
.router-link-exact-active {
  color: inherit;
  /* Remove default active color */
  text-decoration: none;
  /* Ensure no underline */
}

/* Ensure no hover or focus color change */
a:hover,
a:focus {
  color: inherit;
  text-decoration: none;
}

/* Fade and scale transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

/* .fade-leave-active in Vue <2.1.8 */
.fade-enter,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

/* Add hover effects for elements inside the transition */
.slot-content {
  transition: transform 0.3s ease;
}

.slot-content:hover {
  transform: scale(1.05);
}

/* Extra Small Devices (Phones, Portrait Mode) */
@media (max-width: 575px) {

  /* Styles for phones in portrait mode */
  .app-layout-navbar {
    width: 100%;
    height: 40px;
    background-color: var(--background);
    color: var(--text);
  }

  .left {
    order: 1;
  }

  .user-profile {
    order: 2;
  }

  .right {
    display: none;
  }

  .custom-icon-profile {
    font-size: 24px;
  }

  .notification-style {
    height: 25px;
    width: 6vw;
  }

  .logo-style {
    width: 200px;
    height: 48px;
  }
}

/* Small Devices (Phones, Landscape Mode) */
@media (min-width: 576px) and (max-width: 767px) {

  /* Styles for phones in landscape mode */
  .app-layout-navbar {
    flex-wrap: wrap;
    width: 100%;
    height: 8vh;
    background-color: var(--background);
    color: var(--text);
  }

  .left {
    order: 1;
    height: 4vh;
  }

  .user-profile {
    display: flex;
    align-items: center;
    height: 4vh;
    order: 2;
  }

  .custom-icon {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-grow: 1;
    height: 4vh;
  }

  .custom-icon-profile {
    display: flex;
    align-items: center;
    justify-content: end;
    height: 4vh;
  }

  .right {
    order: 3;
    width: 100%;
    height: 4vh;
    display: flex;
    justify-content: space-between;
    /* Ensures proper spacing */
    align-items: center;
    /* Aligns icons vertically */
    margin-left: 15px;
    margin-right: 15px;
  }

  .icon-style {
    font-size: 4vh;
    /* Ensure consistent icon size */
    flex-shrink: 0;
    /* Prevent icons from shrinking */
  }

  .logo-style {
    height: 4vh;
  }

  .notification-style {
    height: 4vh;
    width: 30px;
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
