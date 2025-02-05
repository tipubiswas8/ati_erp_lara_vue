
import { ref, watch } from 'vue';

// Reactive global state
export const selectedFooter = ref<number>(parseInt(localStorage.getItem('footer-no') || '1'));

// Function to update the footer
export const setFooter = (val: number) => {
  selectedFooter.value = val;
};

// Watch for changes and update localStorage
watch(selectedFooter, (newValue) => {
  localStorage.setItem('footer-no', newValue.toString());
});
