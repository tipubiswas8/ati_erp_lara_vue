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
  position: relative;
  display: flex;
  align-items: center;
  /* Vertical centering */
  justify-content: center;
  /* Horizontal centering (optional) */
  height: clamp(2rem, 4vh, 4rem);
  background-color: cadetblue;
  
}
</style>
