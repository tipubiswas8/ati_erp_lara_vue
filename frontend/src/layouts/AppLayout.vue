<!-- This is app layout including header, sitebar and footer -->
<template>
  <div class="layout">
    <!-- Top Navigation/App Header -->
    <header>
      <AppNavbar :is-mobile="isMobile" />
    </header>

    <!-- App Sidebar -->
    <aside @click.self="isSidebarMinimized = true">
      <AppSidebar :mobile="isMobile" :tablet="isTablet" />
    </aside>

    <!-- Main Content -->
    <!-- For Tablet -->
    <div class="layout__content" :class="{ minimized: isSidebarMinimized, 'hide__sidebar': !isShowSidebar }"
      :style="computedStyles">
      <div class="layout__sidebar__wrapper">
        <div v-if="isFullScreenSidebar" class="layout__close__btn__wrapper">
          <button class="close__btn" @click="onCloseSidebarButtonClick">✖</button>
        </div>
      </div>
      <!-- Collapse icon and breadcrumb  -->
      <AppLayoutNavigation v-if="!isMobile" />
      <!-- main content border -->
      <div class="layout__main__border" :class="borderClasses" :style="{ borderColor: getThemeColor('primary') }">
        <!-- main content -->
        <main class="layout__main" :class="mainClasses">
          <article>
            <RouterView />
          </article>
        </main>
      </div>
    </div>
    <!-- Footer -->
    <div v-if="isShowFooter">
      <FooterContainer />
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
import { useControlPanelSecond, sidebarCurrentPosition, currentTextDirection } from '@/stores/control-panel'
import { selectedFooter } from '@/stores/footer-store'
import FooterContainer from '../components/footer/FooterContainer.vue'

const computedStyles = computed(() => {
  return {
    marginLeft: sidebarCurrentPosition.value === 'right' ? '0' : undefined,
    marginRight: sidebarCurrentPosition.value === 'right' ? '16vw' : undefined,
    direction: currentTextDirection.value === 'rtl' ? 'rtl' : undefined
  }
})

const isShowFooter = ref<boolean>(true);
const controlPanelSecond = useControlPanelSecond();
// Destructure the state and actions using storeToRefs
const { isShowSidebar } = storeToRefs(controlPanelSecond);

// Inject the theme from the parent component
type Theme = {
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
  currentTheme: import('vue').ComputedRef<'light' | 'dark' | 'blue' | 'solarized' | 'dracula' | 'pastel'>;
};

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

// Destructure the functions and properties from the theme
const { getThemeColor, currentTheme } = theme;
const GlobalStore = useGlobalStore()
const { isSidebarMinimized } = storeToRefs(GlobalStore)
const activeTheme = ref<string>(currentTheme.value);

watch(currentTheme, (newTheme) => {
  activeTheme.value = newTheme
});

// Make isSupportFooter reactive by using computed()
const isSupportFooter = computed(() => {
  return activeTheme.value !== 'dark' && activeTheme.value !== 'solarized';
});

const borderClasses = computed(() => ({
  is__show__border: !isSidebarMinimized.value && isMobile.value,
  layout__main__border__for__footer__one: selectedFooter.value === 1 && isSupportFooter.value,
}));

const mainClasses = computed(() => ({
  is__show__main__content: !isSidebarMinimized.value && isMobile.value,
  layout__main__for__footer__one: selectedFooter.value === 1 && isSupportFooter.value,
}));


// Custom Breakpoints
const breakpoints = {
  sm: 576,
  md: 768,
  lg: 992,
}

const windowWidth = ref(window.innerWidth)
const isMobile = computed(() => windowWidth.value < breakpoints.sm)
const isTablet = computed(() => windowWidth.value >= breakpoints.sm && windowWidth.value < breakpoints.md)

const onResize = () => {
  windowWidth.value = window.innerWidth
  isSidebarMinimized.value = windowWidth.value < breakpoints.md
}

onMounted(() => {
  window.addEventListener('resize', onResize)
  onResize()
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onResize)
})

onBeforeRouteUpdate(() => {
  // Collapse sidebar after route change for Mobile
  if (windowWidth.value < breakpoints.md) {
    isSidebarMinimized.value = true
  }
})

// for Tablet
const isFullScreenSidebar = computed(() => isTablet.value && !isSidebarMinimized.value)

const onCloseSidebarButtonClick = () => {
  isSidebarMinimized.value = true
}
</script>


<style scoped>
/* Base styles */
/* full application */
.layout {
  position: absolute;
  display: flex;
  flex-direction: column;
  height: 100%;
  width: 100%;
}

/* main content with border */
.layout__content {
  margin-left: 16vw;
  transition: margin-left 0.3s ease-in-out;
}

.hide__sidebar {
  margin-left: 0;
}

/* main content with border */
.layout__content.minimized {
  margin-left: 8vw;
}

/* main content border */
.layout__main__border {
  /* relative to layout */
  position: relative;
  /* header minimum height 4rem default height 8vh maximum height 8rem 
    breadcrumb minimum height 4rem default height 8vh maximum height 8rem
    total minimum top 8rem default top 16vh maximum top 16rem
  */
  top: clamp(8rem, 16vh, 16rem);
  border: 10px solid;
}

.layout__main__border__for__footer__one {
  /* footer one height */
  margin-bottom: clamp(2rem, 4vh, 4rem);
}

/* main content border */
/* hides the border when screen is mobile and show the sitebar */
.layout__main__border.is__show__border {
  display: none;
}

/* main content */
.layout__main {
  overflow: auto;
  padding: 0.5rem;
  /* header height 8vh and breadcrumb height 8vh and border 20px (top 10px + bottom 10px) total (8vh + 8vh + 10px) = (16vh + 20px) */
  top: clamp(8rem, 16vh, 16rem);
  height: calc(100vh - (clamp(8rem, 16vh, 16rem) + 20px));
}

.layout__main__for__footer__one {
  /* add footer one height 4vh */
  height: calc(100vh - (clamp(8rem, 16vh, 16rem) + 20px + clamp(2rem, 4vh, 4rem)));
}

/* main content */
/* hides the main content when screen is mobile and show the sitebar */
.layout__main.is__show__main__content {
  display: none;
}

/* for tablet */
.layout__sidebar__wrapper {
  position: relative;
  background-color: var(--secondary, green);
}

/* for tablet */
.layout__close__btn__wrapper {
  display: flex;
  justify-content: flex-end;
}

/* for tablet */
.close__btn {
  border: none;
  background-color: transparent;
  font-size: 1.5rem;
  cursor: pointer;
}


/* Responsive styles */
/* Extra Small Devices (Phones, Portrait Mode) */
/* Styles for phones in portrait mode */

@media (max-width: 576px) {

  .layout__content {
    margin-top: 50px;
    margin-left: 0;
  }

  .layout__content.minimized {
    margin-left: 0;
  }
}

/* Small Devices (Phones, Landscape Mode) */
/* Styles for phones in landscape mode */

@media (min-width: 576px) and (max-width: 767px) {
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
