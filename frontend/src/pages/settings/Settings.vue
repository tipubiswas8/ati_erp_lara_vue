<script lang="ts" setup>
import SitebarItem from './sitebar-item/SitebarItem.vue'
import LanguageSwitcher from './language-switcher/LanguageSwitcher.vue'
import ThemeSwitcher from './theme-switcher/ThemeSwitcher.vue'
import ControlPanel from './Control.vue'
import { useI18n } from 'vue-i18n'
import { inject } from 'vue'
const { t } = useI18n();

// Define the type for the theme object
interface Theme {
  getThemeColor: (colorKey: 'background' | 'border' | 'accent' | 'text' | 'primary' | 'secondary') => string;
}

// Inject the global theme and cast it to the Theme type
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

// Destructure functions and properties from the theme object
const { getThemeColor } = theme;
</script>

<template>
  <div>
    <h3>Sitebar Item</h3>
    <SitebarItem />
    <br>
    <h3>{{ t('appSetting.theme') }}</h3>
    <ThemeSwitcher />
    <h3>{{ t('appSetting.generalPreferences') }}</h3>
    <LanguageSwitcher />
    <ControlPanel />
  </div>
</template>
