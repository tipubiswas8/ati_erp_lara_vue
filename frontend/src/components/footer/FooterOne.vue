<template>
  <div v-if="isShowFooter" class="footer-one-style">
    <p style="text-align: center; font-size: 14px;">{{ copyrightText }}</p>
  </div>
  <!-- Custom Toasts -->
  <div class="toast-container">
    <div v-for="toast in toasts" :key="toast.id" class="toast">
      {{ toast.message }}
    </div>
  </div>
</template>

<script lang="ts" setup>
import { inject, ref, watch, computed, reactive } from 'vue';
import { useControlPanelStore } from '@/stores/control-panel';
import { copyrightText } from '@/stores/side-info-store';

// Pinia store setup
const { controlItems } = useControlPanelStore();
const isShowFooter = ref<boolean>(false);

// Toast system
const toasts = reactive<{ message: string; id: number }[]>([]);
let toastCounter = 0;

// Function to show toast
const showToast = (message: string) => {
  const id = toastCounter++;
  toasts.push({ message, id });

  // Automatically remove the toast after 3 seconds
  setTimeout(() => {
    const index = toasts.findIndex((toast) => toast.id === id);
    if (index !== -1) toasts.splice(index, 1);
  }, 3000);
};


// Define the type for the theme
type Theme = {
  getThemeColor: (colorKey: 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent') => string;
  currentTheme: import('vue').ComputedRef<'light' | 'dark' | 'blue' | 'solarized' | 'dracula' | 'pastel'>;
  getThemeFontFamily: () => string;
};

// Allowed themes
const allowedThemes = ['light', 'dark', 'blue', 'solarized', 'dracula', 'pastel'] as const;
type ThemeType = typeof allowedThemes[number];

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

const { getThemeColor, getThemeFontFamily, currentTheme } = theme;

// Computed property for the current font-family
const currentThemeFontFamily = computed(() => getThemeFontFamily());

// Validate and initialize the active theme
const localStorageTheme = localStorage.getItem('app-theme') as string;
const initialTheme: ThemeType = allowedThemes.includes(localStorageTheme as ThemeType)
  ? (localStorageTheme as ThemeType)
  : 'light';

const activeTheme = ref<ThemeType>(initialTheme);

// Watch for theme changes and handle footer visibility
watch(
  currentTheme,
  (newTheme) => {
    activeTheme.value = newTheme;
    localStorage.setItem('app-theme', newTheme);
    if (activeTheme.value === 'dark' || activeTheme.value === 'solarized') {
      // showToast('Your theme does not support the footer.');
      isShowFooter.value = false;
    } else {
      isShowFooter.value = true;
    }
  },
  { immediate: true }
);

// Watch for changes in `controlItems.footer.isEnabled`
watch(
  () => controlItems.footer.isEnabled,
  (newValue) => {
    if (activeTheme.value === 'dark' || activeTheme.value === 'solarized') {
      // showToast('Your theme does not support the footer.');
    } else {
      isShowFooter.value = newValue;
    }
  }
);
</script>

<style>
.footer-one-style {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 4vh;
  min-height: 30px;
  display: flex;
  align-items: center; /* Vertical centering */
  justify-content: center; /* Horizontal centering (optional) */
  background-color: aquamarine;
}

/* Toast Container */
.toast-container {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  z-index: 1000;
}

/* Toast Style */
.toast {
  background: #333;
  color: #fff;
  padding: 0.8rem 1.2rem;
  border-radius: 8px;
  font-size: 14px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.5s, fadeOut 0.5s 2.5s;
}

/* Toast Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
    transform: translateY(0);
  }

  to {
    opacity: 0;
    transform: translateY(10px);
  }
}
</style>