<template>
  <div class="preferences-container">
    <div class="preferences-header">
      <PreferencesHeader />
    </div>
    <div class="settings-container">
      <Settings @openNameModal="isEditNameModalOpen = true" @openResetPasswordModal="isResetPasswordModalOpen = true" />
    </div>
  </div>
  <EditNameModal v-if="isEditNameModalOpen" @cancel="isEditNameModalOpen = false" />
  <ResetPasswordModal v-if="isResetPasswordModalOpen" @cancel="isResetPasswordModalOpen = false" />
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'

import PreferencesHeader from './preferences-header/PreferencesHeader.vue'
import Settings from './settings/Settings.vue'
import EditNameModal from './modals/EditNameModal.vue'
import ResetPasswordModal from './modals/ResetPasswordModal.vue'

const isEditNameModalOpen = ref(false)
const isResetPasswordModalOpen = ref(false)

const emit = defineEmits(['openNameModal', 'openResetPasswordModal'])

const showToast = (message: string, color: string) => {
  const toastElement = document.createElement('div')
  toastElement.className = `toast ${color}`
  toastElement.innerText = message
  document.body.appendChild(toastElement)
  
  setTimeout(() => {
    document.body.removeChild(toastElement)
  }, 3000)
}

const toggle2FA = () => {
  store.toggle2FA()
  showToast(toastMessage.value, 'success')
}

</script>

<style scoped>
.preferences-container {
  display: flex;
  flex-direction: column;
  padding: 16px;
  background-color: #f4f4f4;
  border-radius: 8px;
}

.preferences-header {
  display: flex;
  justify-content: space-between;
}

.settings-container {
  display: flex;
  flex-direction: column;
  margin-top: 16px;
}

.toast {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  background-color: #4CAF50;
  color: white;
  border-radius: 5px;
  font-size: 14px;
  opacity: 0;
  animation: fadeInOut 3s ease-in-out;
}

.toast.success {
  background-color: #4CAF50;
}

.toast.error {
  background-color: #F44336;
}

@keyframes fadeInOut {
  0% {
    opacity: 0;
  }
  20% {
    opacity: 1;
  }
  80% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
</style>
