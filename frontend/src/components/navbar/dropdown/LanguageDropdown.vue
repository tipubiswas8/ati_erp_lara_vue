<template>
  <select v-model="selectedLanguage">
    <option v-for="(language, key) in languages" :key="key" :value="key">
      {{ language.label }}
    </option>
  </select>

  <div v-if="selectedLanguage">
    <img :src="languages[selectedLanguage].flag" :alt="selectedLanguage" class="flag-icon" />
    <span>{{ languages[selectedLanguage].label }}</span>
  </div>
</template>

<script setup>
import { ref } from "vue";

const selectedLanguage = ref("en");

const languages = {
  ar: { label: "العربية", flag: "/flags/saudi-arabia.png" },
  en: { label: "English", flag: "/flags/usa.png" },
  bn: { label: "বাংলা", flag: "/flags/bangladesh.png" },
  es: { label: "Español", flag: "/flags/spain.png" },
  br: { label: "Português", flag: "/flags/brazil.png" },
  cn: { label: "简体中文", flag: "/flags/china.png" },
  ir: { label: "فارسی", flag: "/flags/iran.png" }
};
</script>

<style scoped>
.flag-icon {
  width: 20px;
  height: 14px;
  margin-right: 5px;
  vertical-align: middle;
}
</style>
