<template>
  <div class="preferences-container">
    <div class="preferences-row">
      <p class="label">Name</p>
      <div class="value">
        <div class="max-width">{{ store.userName }}</div>
      </div>
      <button class="edit-button" @click="emit('openNameModal')">Edit</button>
    </div>
    <hr class="divider" />
    <div class="preferences-row">
      <p class="label">Email</p>
      <div class="value">
        <div class="max-width">{{ store.email }}</div>
      </div>
    </div>
    <div class="preferences-row">
      <p class="label">Password</p>
      <div class="value">
        <div class="max-width">•••••••••••••</div>
      </div>
      <button class="reset-button" @click="emit('openResetPasswordModal')">Reset Password</button>
    </div>
    <div id="toast-container" class="toast-container"></div>
    <hr class="divider" />
    <div class="preferences-row">
      <p class="label">Two-factor authentication</p>
      <div class="value">
        <div class="max-width">{{ twoFA.content }}</div>
      </div>
      <button
        class="twofa-button"
        :class="twoFA.color"
        @click="toggle2FA"
      >
        {{ twoFA.button }}
      </button>
    </div>
    <hr class="divider" />
    <div class="preferences-row">
      <p class="label">Email subscriptions</p>
      <div class="value">
        <div class="max-width">
          <p>To manage what emails you get, visit the</p>
          <div class="notification-link">
            <router-link :to="{ name: 'settings' }">Notification settings</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import { useUserStore } from '../../../stores/user-store';

const store = useUserStore();

const emit = defineEmits(['openNameModal', 'openResetPasswordModal']);

const toastMessage = computed(() =>
  store.is2FAEnabled ? '2FA successfully enabled' : '2FA successfully disabled'
);

const twoFA = computed(() => ({
  color: store.is2FAEnabled ? 'danger' : 'primary',
  button: store.is2FAEnabled ? 'Disable 2FA' : 'Set up 2FA',
  content: store.is2FAEnabled
    ? 'Two-Factor Authentication (2FA) is now enabled for your account, adding an extra layer of security to your sign-ins.'
    : 'Add an extra layer of security to your account. To sign in, you’ll need to provide a code along with your username and password.',
}));

const toggle2FA = () => {
  store.toggle2FA();
  showToast(toastMessage.value, 'success');
};

const showToast = (message: string, type: string) => {
  const toastContainer = document.getElementById('toast-container');
  if (!toastContainer) return;

  const toastElement = document.createElement('div');
  toastElement.className = `toast ${type}`;
  toastElement.textContent = message;

  toastContainer.appendChild(toastElement);

  setTimeout(() => {
    toastElement.classList.add('fade-out');
    setTimeout(() => {
      toastElement.remove();
    }, 1000);
  }, 3000);
};
</script>

<style scoped>
.preferences-container {
  display: flex;
  flex-direction: column;
  padding: 16px;
  background-color: #f4f4f4;
  border-radius: 8px;
}

.toast-container {
  position: fixed;
  bottom: 100px;
  right: 20px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background-color: #4caf50;
  color: wheat;
}

.toast {
  padding: 12px 20px;
  border-left: 6px solid;
  border-radius: 4px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  background-color: #fff;
  color: #333;
  opacity: 1;
  transition: opacity 1s ease-in-out;
}

.toast.success {
  border-color: #4caf50;
}

.toast.error {
  border-color: #f44336;
}

.toast.fade-out {
  opacity: 0;
}

.preferences-row {
  display: flex;
  align-items: center;
  margin: 16px 0;
}

.label {
  font-weight: bold;
  width: 200px;
}

.value {
  flex: 1;
}

.max-width {
  max-width: 750px;
}

.edit-button,
.reset-button,
.twofa-button {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.edit-button:hover,
.reset-button:hover,
.twofa-button:hover {
  background-color: #0056b3;
}

.twofa-button.danger {
  background-color: #dc3545;
}

.notification-link a {
  color: #007bff;
}

.notification-link a:hover {
  text-decoration: underline;
}

.divider {
  border: none;
  border-top: 1px solid #e0e0e0;
  margin: 16px 0;
}
</style>
