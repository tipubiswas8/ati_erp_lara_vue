<template>
  <ProfileDropdown />
</template>

<script lang="ts" setup>
import ProfileDropdown from './ProfileDropdown.vue'
defineProps({
isMobile: { type: Boolean, default: false },
})
</script>