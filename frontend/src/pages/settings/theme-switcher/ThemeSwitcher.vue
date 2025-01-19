<template>
  <div :style="{ backgroundColor: getThemeColor('background'), color: getThemeColor('text') }">
    <!-- Display Current Theme -->
    <p>Current Theme: {{ currentTheme }}</p>

    <!-- Buttons for Theme Toggle -->
    <div>
      <button v-for="option in options" :key="option.value" :style="{
        backgroundColor: option.value === currentTheme ? getThemeColor('primary') : getThemeColor('border'),
        color: option.value === currentTheme ? getThemeColor('text') : getThemeColor('background'),
        border: `1px solid ${getThemeColor('border')}`,
        padding: '10px 20px',
        margin: '5px',
        cursor: 'pointer',
        borderRadius: '5px',
        transition: 'background-color 0.3s, color 0.3s',
      }" @click="changeTheme(option.value)">
        {{ option.label }}
      </button>
    </div>

    <!-- Dropdown for Theme Selection -->
    <div class="custom-select-wrapper">
      <label for="theme-select" class="custom-select-label">{{ t('theme.select') }}</label>
      <select id="theme-select" v-model="model" class="custom-select" @change="changeTheme($event)">
        <option v-for="option in options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>
    </div>
  </div>
</template>



<script lang="ts" setup>
import { inject, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

// Define the type for the theme object
interface Theme {
  setTheme: (theme: string) => void;
  getThemeColor: (colorKey: string) => string;
  currentTheme: string;
}

// Inject the global theme and cast it to the Theme type
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

// Destructure functions and properties from the theme object
const { setTheme, getThemeColor, currentTheme } = theme;

// i18n setup
const { t } = useI18n();

// Dropdown options
const options = [
  { label: t('buttonSelect.dark'), value: 'dark' },
  { label: t('buttonSelect.light'), value: 'light' },
  { label: t('buttonSelect.blue'), value: 'blue' },
  { label: t('buttonSelect.solarized'), value: 'solarized' },
];

// Bind `model` to the current theme for dropdown
const model = ref(currentTheme);

// Watch `currentTheme` to sync dropdown value when theme changes via button
watch(() => currentTheme, (newTheme) => {
  model.value = newTheme;
});

// Handle theme change for both button and dropdown
const changeTheme = (event: Event | string) => {
  let themeValue: string;

  if (typeof event === 'string') {
    // Button click passed the theme string directly
    themeValue = event;
  } else {
    // Dropdown change event, get the value from the select element
    const selectElement = event.target as HTMLSelectElement;
    themeValue = selectElement.value;
  }
  setTheme(themeValue);
};
</script>

<style scoped>
/* Wrapper for the custom select */
.custom-select-wrapper {
  margin: 20px 0;
}

/* Label styling */
.custom-select-label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
}

/* Custom select styling */
.custom-select {
  width: 100%;
  max-width: 300px;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: var(--background-color, #fff);
  color: var(--text-color, #000);
  appearance: none;
  cursor: pointer;
  transition: border-color 0.3s, background-color 0.3s, color 0.3s;
}

/* Hover and focus states */
.custom-select:hover,
.custom-select:focus {
  border-color: var(--primary-color, #007bff);
  outline: none;
}

/* Dropdown caret (arrow) */
.custom-select::after {
  content: '▼';
  position: absolute;
  right: 10px;
  pointer-events: none;
}
</style>
