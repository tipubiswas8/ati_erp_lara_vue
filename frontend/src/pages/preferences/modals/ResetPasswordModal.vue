<template>
  <div class="custom-modal">
    <div class="modal-inner">
      <!-- Close Button -->
      <button class="close-button" @click="emits('cancel')">&times;</button>
      <h1 class="modal-title">Reset password</h1>
      <form ref="form" class="form space-y-6" @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-group">
            <label for="oldPassword" class="form-label">Old password</label>
            <input
              v-model="oldPassword"
              type="password"
              id="oldPassword"
              class="input"
              placeholder="Old password"
              required
            />
          </div>
          <div class="hidden md:block"></div>
          <div class="form-group">
            <label for="newPassword" class="form-label">New password</label>
            <input
              v-model="newPassword"
              type="password"
              id="newPassword"
              class="input"
              placeholder="New password"
              required
            />
          </div>
          <div class="form-group">
            <label for="repeatNewPassword" class="form-label">Repeat new password</label>
            <input
              v-model="repeatNewPassword"
              type="password"
              id="repeatNewPassword"
              class="input"
              placeholder="Repeat new password"
              required
            />
          </div>
        </div>

        <div class="flex flex-col space-y-2">
          <div class="flex space-x-2 items-center">
            <div style="display: inline;">
              <span :class="newPassword?.length >= 8 ? 'check-icon' : 'close-icon'" />
            </div>
            <p style="display: inline;">Must be at least 8 characters long</p>
          </div>
          <div class="flex space-x-2 items-center">
            <div style="display: inline;">
              <span :class="new Set(newPassword).size >= 6 ? 'check-icon' : 'close-icon'" />
            </div>
            <p style="display: inline;">Must contain at least 6 unique characters</p>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="button button-secondary" @click="emits('cancel')">Cancel</button>
          <button type="submit" class="button button-primary">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const oldPassword = ref<string>()
const newPassword = ref<string>()
const repeatNewPassword = ref<string>()
const emits = defineEmits(['cancel'])

const submit = () => {
  if (validate()) {
    showToast("You've successfully changed your password", 'success')
    emits('cancel')
  }
}

const validate = () => {
  return (
    oldPassword.value &&
    newPassword.value &&
    repeatNewPassword.value &&
    newPassword.value === repeatNewPassword.value &&
    newPassword.value !== oldPassword.value
  )
}

// Custom toast function
const showToast = (message: string, color: string) => {
  const toast = document.createElement('div')
  toast.classList.add('custom-toast', color)
  toast.textContent = message
  document.body.appendChild(toast)
  setTimeout(() => {
    toast.remove()
  }, 3000)
}
</script>

<style scoped>
.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-inner {
  position: relative; /* To position the close button relative to the modal */
  background-color: white;
  padding: 20px;
  max-width: 530px;
  width: 100%;
  border-radius: 8px;
}

.close-button {
  position: absolute;
  top: 10px;
  left: 10px;
  background-color: transparent;
  border: none;
  font-size: 20px;
  font-weight: bold;
  cursor: pointer;
  color: #333;
}

.close-button:hover {
  color: #007bff;
}

.modal-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 16px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: bold;
  margin-bottom: 8px;
}

.input {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.check-icon,
.close-icon {
  width: 15px;
  height: 15px;
  margin-right: 10px;
  border-radius: 50%;
  display: inline-block;
  background-color: #ccc;
}

.check-icon {
  background-color: #4caf50;
}

.close-icon {
  background-color: #f44336;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.button {
  padding: 10px 20px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  font-weight: bold;
}

.button-secondary {
  background-color: #e0e0e0;
  color: #333;
}

.button-primary {
  background-color: #007bff;
  color: white;
}

.custom-toast {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #4caf50; /* Success color by default */
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  font-size: 14px;
}

.custom-toast.success {
  background-color: #4caf50;
}

.custom-toast.error {
  background-color: #f44336;
}

.custom-toast.warning {
  background-color: #ff9800;
}
</style>
