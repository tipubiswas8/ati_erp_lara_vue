<template>
  <div class="footer-two-style">
    <p style="text-align: center;">{{ copyrightText }}</p>
  </div>
</template>

<script lang="ts" setup>
import { copyrightText } from '@/stores/side-info-store';
</script>

<style scoped>
.footer-two-style {
  background-color: var(--background, black);
  color: var(--text, white);
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  min-height: auto;
}
</style>
