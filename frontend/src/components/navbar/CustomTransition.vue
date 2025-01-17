<template>
  <div 
    v-if="show" 
    :class="transitionClasses"
    @transitionend="handleTransitionEnd"
    :style="style"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

defineProps({
  show: { type: Boolean, required: true },
  name: { type: String, default: 'fade' }, // Transition name
  duration: { type: Number, default: 300 }, // Duration in ms
  style: { type: Object, default: () => ({}) }, // Additional styles
});

const transitionClasses = computed(() => ({
  [`${props.name}-enter-active`]: props.show,
  [`${props.name}-leave-active`]: !props.show,
}));

const handleTransitionEnd = () => {
  if (!props.show) {
    transitionClasses.value = {};
  }
};
</script>

<style scoped>
/* Default transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease-in-out;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
