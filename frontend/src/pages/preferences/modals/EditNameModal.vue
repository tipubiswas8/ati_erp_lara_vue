<template>
  <div class="custom-modal">
    <div class="modal-inner">
      <!-- Close Button -->
      <button class="close-button" @click="emits('cancel')">&times;</button>
      <h1 class="modal-title">Update User Information</h1>
      <form ref="form" @submit.prevent="submit">
        <div class="form-group">
          <label for="name" class="form-label">Name</label>
          <input v-model="Name" type="text" id="name" class="input" placeholder="Name" />
        </div>
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input v-model="Email" type="text" id="email" class="input" placeholder="Email" />
        </div>
        <div class="form-actions">
          <button type="button" class="button button-secondary" @click="emits('cancel')">Cancel</button>
          <button type="submit" class="button button-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useUserStore } from '../../../stores/user-store'

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

const store = useUserStore()

const Name = ref<string>(store.userName)
const Email = ref<string>(store.email)

const emits = defineEmits(['cancel'])

const submit = () => {
  if ((!Name.value || Name.value === store.userName) && (!Email.value || Email.value === store.email)) {
    emits('cancel')
    return
  }

  store.changeUserName(Name.value)
  store.changeUserEmail(Email.value)
  showToast("You've successfully changed your personal information", 'success')
  emits('cancel')
}
</script>

<style>
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
  max-width: 380px;
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

.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  font-weight: bold;
  margin-bottom: 8px;
}

.input {
  width: 95%;
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
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
  background-color: #4caf50;
  /* Success color by default */
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
