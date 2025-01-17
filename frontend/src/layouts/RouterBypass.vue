<template>
    <RouterView></RouterView>
    <!-- <div :style="{ border: getThemeColor('border') }" style="border: 10px solid;">
    </div>
    <p :style="{ backgroundColor: getThemeColor('background'), color: getThemeColor('text') }"
        style="text-align: center; font-size: 14px; margin-top: 20px;">
        © 2025 ATI Limited. All rights reserved222.
    </p> -->
</template>

<script lang="ts" setup>
import { inject } from 'vue'

// Define the type for the theme
type Theme = {
    setTheme: (theme: 'light' | 'dark') => void;
    getThemeColor: (colorKey: 'background' | 'border' | 'text') => string;
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
