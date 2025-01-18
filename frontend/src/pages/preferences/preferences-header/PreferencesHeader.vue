<template>
  <div class="custom-avatar-wrapper">
    <div class="custom-avatar">
      <span class="avatar-icon">😍</span>
    </div>
    <div class="user-info">
      <h2 class="user-name">{{ store.userName }}</h2>
      <div class="member-since">
        <p>Member since</p>
        <p>{{ store.memberSince }}</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { useUserStore } from '../../../stores/user-store'

const store = useUserStore()
</script>

<style scoped>
.custom-avatar-wrapper {
  display: flex;
  align-items: center;
  gap: 12px;
}

.custom-avatar {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 72px;
  height: 72px;
  background-color: #f39c12; /* Custom warning color */
  border-radius: 50%;
  font-size: 2rem; /* Equivalent to text-4xl */
}

.avatar-icon {
  color: white;
}

.user-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.user-name {
  font-size: 28px;
  font-weight: bold;
  line-height: 40px;
}

.member-since {
  display: flex;
  gap: 4px;
  font-size: 13px;
  line-height: 16px;
}
</style>
