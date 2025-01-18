<template>
    <router-view />
    <p
  style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; font-size: 14px; padding: 5px; margin: 0;"
  :style="{ backgroundColor: getThemeColor('background') || '#000', color: getThemeColor('accent') || '#fff' }"
>
  © 2025 ATI Limited. All rights reserved.
</p>

</template>

<script lang="ts" setup>
import { inject } from 'vue'

// Define the type for the theme
type Theme = {
  setTheme: (theme: 'light' | 'dark') => void;
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
</script>

<style>
/* Optional global styles */
body {
  height: 100%;
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
}
</style>
