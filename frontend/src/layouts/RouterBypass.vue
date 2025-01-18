<template>
    <div :style="{
        border: showBorder ? `8px solid ${getThemeColor('primary')}` : 'none',
        padding: '8px'
    }">
        <RouterView />
    </div>
</template>

<script lang="ts" setup>
import { inject, ref, watch, computed } from 'vue';
import { useRoute } from 'vue-router'; // Import the useRoute hook
import navigationRoutes from '../components/sidebar/NavigationRoutes';

// Access the current route using useRoute
const route = useRoute(); // This will give you the current route object

// Create a computed property to check if the current route has children or grandchildren
const showBorder = computed(() => {
  // Find the current route in the static navigationRoutes array based on the current route name
  const currentRoute = navigationRoutes.find((routeItem) =>  route.name); // Use route.name

  console.log('Current route:', currentRoute); // Log the current route name
  
  // If route has grandchildren, show the border
  if (currentRoute?.grandChildren && currentRoute?.grandChildren.length > 0) {
      return true;
    }

    // If route has children (but no grandchildren), show the border
    if (currentRoute?.children && currentRoute?.children.length > 0) {
        return true;
    }
    
    // If no children or grandchildren, don't show a border
    return false;
});

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

// Create a computed property to check if a route has children or grandchildren


</script>
