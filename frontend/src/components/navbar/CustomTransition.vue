<template>
  <div v-if="show" :class="computedClasses" @transitionend="handleTransitionEnd" :style="style">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

// Define props for the component
const props = defineProps({
  show: { type: Boolean, required: false },
  name: { type: String, default: 'fade' }, // Transition name
  duration: { type: Number, default: 300 }, // Duration in ms
  style: { type: Object, default: () => ({ padding: '5px', marginTop: '8px' }) }, // Additional styles
});

// Computed property for transition classes
const computedClasses = computed(() => ({
  [`${props.name}-enter-active`]: props.show,
  [`${props.name}-leave-active`]: !props.show,
}));

// Handle transition end event
const handleTransitionEnd = () => {
  if (!props.show) {
    // You might need additional logic here, but resetting classes isn't required
  }
};
</script>

<style scoped>
.fade-enter,
.fade-leave-to

/* .fade-leave-active in Vue <2.1.8 */
  {
  opacity: 0;
  transform: scale(0.95);
}

/* Add hover effects for elements inside the transition */
.slot-content {
  transition: transform 0.3s ease;
}

.slot-content:hover {
  transform: scale(1.05);
}
</style>
