<template>
  <div class="flex items-center justify-between">
    <p>{{ t('language.language') }}</p>
    <a-select v-model:value="model" style="width: 20vw" :options="options" :size="size"></a-select>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n();
type LanguageMap = Record<string, string>

const { locale } = useI18n()
const size = 'large'
const languages: LanguageMap = {
  en: 'English',
  bn: 'বাংলা',
  es: 'Spanish',
  br: 'Português',
  cn: 'Simplified Chinese',
  ir: 'Persian',
}

// Convert language codes into options for the select component
const options = Object.entries(languages).map(([code, label]) => ({
  value: code,  // Use the language code as the value
  label,        // Display the language label
}))

// Set up a computed property for model, based on the current locale
const model = computed({
  get() {
    return locale.value // Return the language code
  },
  set(value: string) {
    // Update locale and save the selected language to localStorage
    if (languages[value]) {
      localStorage.setItem('selectedLanguage', value)
      locale.value = value
    }
  },
})

// Persist the language selection on page load
onMounted(() => {
  const savedLanguageCode = localStorage.getItem('selectedLanguage')
  if (savedLanguageCode && languages[savedLanguageCode]) {
    locale.value = savedLanguageCode
  } else {
    const defaultLocale = locale.value
    localStorage.setItem('selectedLanguage', defaultLocale)
  }
})
</script>
