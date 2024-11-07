<script lang="ts" setup>
import NotFoundImage from '../../components/NotFoundImage.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const errorMessage = route.query.message || ''
</script>
<template>
  <div class="flex flex-col justify-between items-center bg-[var(--va-background-secondary)]">
    <RouterLink to="/">
      <img src="/images/drug-logo.png" style="height: 50px; width: 200px;" alt="ATI Logo" />
    </RouterLink>
    <div class="flex flex-col items-center gap-6 px-4 my-8">
      <NotFoundImage />
      <h1 style="font-size: 30px; font-weight: bolder" class="text-center">{{ errorMessage }}</h1>
      <div class="flex flex-col sm:flex-row gap-4">
        <VaButton to="/">Go to homepage</VaButton>
      </div>
    </div>
  </div>
</template>
