<script lang="ts" setup>
import LanguageSwitcher from './language-switcher/LanguageSwitcher.vue'
import ThemeSwitcher from './theme-switcher/ThemeSwitcher.vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n();
</script>

<template>
  <div class="flex flex-col space-y-6 md:space-y-4">
    <div class="flex flex-col p-4 space-y-4 bg-backgroundSecondary rounded-lg">
      <h3 class="h3">{{ t('appSetting.theme') }}</h3>
      <ThemeSwitcher />
    </div>
    <div class="flex flex-col p-4 space-y-4 bg-backgroundSecondary rounded-lg">
      <h3 class="h3">{{ t('appSetting.generalPreferences') }}</h3>
      <LanguageSwitcher />
    </div>
  </div>
</template>
