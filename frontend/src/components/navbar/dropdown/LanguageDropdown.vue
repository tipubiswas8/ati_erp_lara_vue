<template>
  <div class="language-selector">
    <div class="custom-select" @click="toggleDropdown">
      <img :src="languages[selectedLanguage].flag" alt="Selected Language Flag" class="selected-flag" />
    </div>

    <div v-if="dropdownOpen" class="dropdown-list">
      <div v-for="(language, key) in languages" :key="key" class="dropdown-item" @click="selectLanguage(key)">
        <img :src="language.flag" alt="Flag" class="flag-icon" />
        {{ language.label }}
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import { useI18n } from 'vue-i18n'

// Initialize vue-i18n
const { locale } = useI18n()

// Language selection state
const selectedLanguage = ref('en')
const dropdownOpen = ref(false)

// Define the languages
const languages = {
  ar: { label: "العربية", flag: "/images/language/flag/saudi-arabia-flag.svg" },
  en: { label: "English", flag: "/images/language/flag/usa-flag.svg" },
  bn: { label: "বাংলা", flag: "/images/language/flag/bangladesh-flag.svg" },
  es: { label: "Español", flag: "/images/language/flag/spain-flag.svg" },
  br: { label: "Português", flag: "/images/language/flag/brazil-flag.svg" },
  cn: { label: "简体中文", flag: "/images/language/flag/china-flag.svg" },
  ir: { label: "فارسی", flag: "/images/language/flag/iran-flag.svg" }
}

// Toggle the dropdown visibility
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

// Select a language and update the app language
const selectLanguage = (key: string) => {
  selectedLanguage.value = key
  locale.value = key  // Update the app language
  dropdownOpen.value = false
  localStorage.setItem('selectedLanguage', key) // Save language to localStorage
}

// Ensure the language persists on page reload
onMounted(() => {
  const savedLanguage = localStorage.getItem('selectedLanguage')
  if (savedLanguage && languages[savedLanguage]) {
    selectedLanguage.value = savedLanguage
    locale.value = savedLanguage  // Set the language in vue-i18n
  } else {
    localStorage.setItem('selectedLanguage', selectedLanguage.value)
    locale.value = selectedLanguage.value  // Set default language
  }
})

</script>

<style scoped>
.language-selector {
  position: relative;
  width: 100%;
  max-width: 200px;
  /* Full width for mobile screens, max width for larger screens */
}

.custom-select {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  border: 1px solid #ccc;
  cursor: pointer;
  background-color: #fff;
  border-radius: 5px;
}

.selected-flag {
  width: 20px;
  height: 15px;
}

.dropdown-list {
  position: absolute;
  top: 40px;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  z-index: 10;
  max-height: 200px;
  overflow-y: auto;
  border-radius: 5px;
}

.dropdown-item {
  padding: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.dropdown-item:hover {
  background-color: #f1f1f1;
}

.flag-icon {
  width: 20px;
  height: 15px;
  margin-right: 10px;
}

@media (max-width: 600px) {
  .language-selector {
    max-width: 150px;
    /* Smaller width for mobile devices */
  }

  .selected-flag {
    width: 18px;
    height: 13px;
  }

  .dropdown-item {
    padding: 6px;
  }

  .flag-icon {
    width: 18px;
    height: 13px;
  }
}
</style>
