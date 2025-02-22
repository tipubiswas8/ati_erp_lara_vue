<template>
  <div>
    <p class="language-label">{{ t('language.language') }}</p>
    <div class="custom-select-wrapper">
      <select v-model="model" class="custom-select" @change="updateLanguage">
        <option v-for="option in options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

// Use vue-i18n for translations
const { t, locale } = useI18n()

// Define the language map
type LanguageMap = Record<string, string>
const languages: LanguageMap = {
  ar: 'العربية',
  en: 'English',
  bn: 'বাংলা',
  es: 'Español',
  br: 'Português',
  cn: '简体中文',
  ir: 'فارسی'
}

// Convert the language map into options for the <select>
const options = Object.entries(languages).map(([code, label]) => ({
  value: code,
  label,
}))

// Use a computed property to bind the selected language
const model = computed({
  get() {
    return locale.value
  },
  set(value: string) {
    if (languages[value]) {
      localStorage.setItem('selectedLanguage', value)
      locale.value = value
    }
  },
})

// Ensure the language persists on page reload
onMounted(() => {
  const savedLanguage = localStorage.getItem('selectedLanguage')
  if (savedLanguage && languages[savedLanguage]) {
    locale.value = savedLanguage
  } else {
    const defaultLocale = locale.value
    localStorage.setItem('selectedLanguage', defaultLocale)
  }
})

// Update the language when the selection changes
const updateLanguage = (event: Event) => {
  const selectedValue = (event.target as HTMLSelectElement).value
  model.value = selectedValue
}
</script>

<style scoped>
.language-label {
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: 10px;
  color: var(--text-color);
}

.custom-select-wrapper {
  display: inline-block;
  width: 20vw;
}

.custom-select {
  width: 100%;
  padding: 10px 12px;
  font-size: 1rem;
  color: var(--text-color, #333);
  background-color: var(--background-color, #fff);
  border: 1px solid var(--border-color, #ccc);
  border-radius: 5px;
  appearance: none;
  outline: none;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: border-color 0.3s, box-shadow 0.3s;
}

.custom-select:hover {
  border-color: var(--hover-border-color, #1a73e8);
}

.custom-select:focus {
  border-color: var(--focus-border-color, #1a73e8);
  box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
}

.custom-select option {
  color: var(--text-color, #333);
  background-color: var(--background-color, #fff);
}
</style>
