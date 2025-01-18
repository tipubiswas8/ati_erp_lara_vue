<script lang="ts" setup>
import LanguageSwitcher from './language-switcher/LanguageSwitcher.vue'
import ThemeSwitcher from './theme-switcher/ThemeSwitcher.vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n();
</script>

<template>
  <h3>{{ t('appSetting.theme') }}</h3>
  <ThemeSwitcher />
  <h3>{{ t('appSetting.generalPreferences') }}</h3>
  <LanguageSwitcher />
</template>
