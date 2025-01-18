<script lang="ts" setup>
import { inject, ref, watch, computed } from 'vue';

// Define the type for the theme
type Theme = {
  setTheme: (theme: 'light' | 'dark' | 'blue' | 'solarized') => void;
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
  currentTheme: import('vue').ComputedRef<'light' | 'dark' | 'blue' | 'solarized'>;
  getThemeFontFamily: () => string;
};

// Allowed themes
const allowedThemes = ['light', 'dark', 'blue', 'solarized'] as const;

// Create a type from `allowedThemes`
type ThemeType = typeof allowedThemes[number];

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

// Destructure functions and properties from the theme
const { getThemeColor, getThemeFontFamily, currentTheme } = theme;
// Computed property for the current font-family
const currentThemeFontFamily = computed(() => getThemeFontFamily());

// Validate and initialize the active theme
const localStorageTheme = localStorage.getItem('app-theme') as string;
const initialTheme: ThemeType = allowedThemes.includes(localStorageTheme as ThemeType)
  ? (localStorageTheme as ThemeType)
  : 'light';

const activeTheme = ref<ThemeType>(initialTheme);

// Sync `activeTheme` with `currentTheme`
watch(
  currentTheme,
  (newTheme) => {
    activeTheme.value = newTheme;
    localStorage.setItem('app-theme', newTheme); // Save the active theme to localStorage
  },
  { immediate: true }
);
</script>

<template>
  <router-view />
  <div
    v-if="activeTheme === 'dark' || activeTheme === 'solarized'"
    style="position: fixed; bottom: 0; left: 0; width: 100%; margin: 0;"
    :style="{ backgroundColor: getThemeColor('background') || '#000', color: getThemeColor('accent') || '#fff', fontFamily: currentThemeFontFamily  }"
  >
    <p style="text-align: center; font-size: 14px;"> © 2025 ATI Limited. All rights reserved.</p>
  </div>
</template>

<style>
/* Optional global styles */
body {
  height: 100%;
  margin: 0;
  padding: 0;
  font-family: var(--font-family, 'Arial, sans-serif');
}
</style>
