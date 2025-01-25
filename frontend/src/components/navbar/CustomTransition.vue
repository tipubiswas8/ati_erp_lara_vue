<!-- This is menu bar and close menu bar for mobile view -->
<template>
  <div v-if="show"  @transitionend="handleTransitionEnd" :style="style">
    <slot />
  </div>
</template>

<script setup lang="ts">
// Define props for the component
const props = defineProps({
  show: { type: Boolean, required: true },
  name: { type: String, default: 'fade' }, // Transition name
  duration: { type: Number, default: 300 }, // Duration in ms
  style: { type: Object, default: () => ({ padding: '5px', marginTop: '8px' }) }, // Additional styles
});

// Handle transition end event
const handleTransitionEnd = () => {
  if (!props.show) {
    // You might need additional logic here, but resetting classes isn't required
  }
};
</script>
