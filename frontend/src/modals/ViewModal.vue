<template>
  <AModal :open="true" :closable="false" :bodyStyle="modalHeight ? { height: modalHeight + 'vh' } : {}"
    :width="modalWidth ? modalWidth + 'vw' : undefined">
    <template #title>
      <div :style="{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: props.viewModalConfigData?.config.titleBgColor || '#74e3e1',
        color: props.viewModalConfigData?.config.titleTextColor || '#323635',
        padding: '2px',
        borderRadius: '4px',
        margin: '-10px -15px 0px -15px'
      }">
        <!-- Centered Title Content -->
        <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center;">
          {{ props.viewModalConfigData?.config.title }}
        </div>
        <!-- Custom Close Button -->
        <button @click="handleVMCancel"
          style="background: red;  padding: 0 4px 0 4px; color: white; font-size: 18px; cursor: pointer; margin-left: auto;">
          &times;
        </button>
      </div>
    </template>
    <slot></slot>
    <!-- Footer Slot -->
    <template #footer>
      <div :style="{ display: 'flex', justifyContent: 'right' }">
        <!-- Example custom footer content -->
        <div v-if="props.viewModalConfigData?.config.footer">
          <AButton @click="handleVMCancel" :style="props.viewModalConfigData?.config.footerButtonBgColor
            ? { backgroundColor: props.viewModalConfigData.config.footerButtonBgColor }
            : {}">
            Close
          </AButton>
        </div>
      </div>
    </template>
  </AModal>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  viewModalConfigData: Object
});

const emit = defineEmits(['closeVm']);

const modalHeight = props.viewModalConfigData?.config.height;
const modalWidth = props.viewModalConfigData?.config.width;

const handleVMCancel = () => {
  emit('closeVm')
}
</script>
