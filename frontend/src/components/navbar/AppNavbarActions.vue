<template>
  <NotificationDropdown class="app-navbar-actions__item" />
  <ProfileDropdown />
</template>

<script lang="ts" setup>
import ProfileDropdown from './ProfileDropdown.vue'
import NotificationDropdown from './NotificationDropdown.vue'
defineProps({
isMobile: { type: Boolean, default: false },
})
</script>