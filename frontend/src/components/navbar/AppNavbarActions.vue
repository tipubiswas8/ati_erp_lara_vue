<!-- This is notification and profile dropdown -->
<template>
  <NotificationDropdown />
  <ProfileDropdown />
</template>

<script lang="ts" setup>
import NotificationDropdown from './dropdown/NotificationDropdown.vue'
import ProfileDropdown from './dropdown/ProfileDropdown.vue'
defineProps({
isMobile: { type: Boolean, default: false },
})
</script>