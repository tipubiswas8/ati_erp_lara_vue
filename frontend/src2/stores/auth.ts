// stores/auth.js
import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
  }),
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('authToken', token);
    },
    clearToken() {
      this.token = null;
      localStorage.removeItem('authToken');
    },
    loadToken() {
      this.token = localStorage.getItem('authToken');
    }
  },
});