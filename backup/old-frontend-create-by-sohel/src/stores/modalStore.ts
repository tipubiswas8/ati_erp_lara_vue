
import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
  state: () => ({
    open: false,
  }),
  actions: {
    show() {
      this.open = true;
    },
    hide() {
      this.open = false;
    },
  },
});
