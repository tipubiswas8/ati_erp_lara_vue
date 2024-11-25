<template>
  <AModal :open="true" :bodyStyle="{ height: modalHeight + 'vh' }" :width="modalWidth ? modalWidth + 'vw' : '30vw'"
    :closable="false">
    <template #title>
      <div :style="{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: props.userViewModalData?.config.titleBgColor || '#74e3e1',
        color: props.userViewModalData?.config.titleTextColor || '#323635',
        padding: '2px',
        borderRadius: '4px',
        margin: '-10px -15px 0px -15px'
      }">
        <!-- Centered Title Content -->
        <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center;">
          {{ props.userViewModalData?.config.title }}
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
        <div v-if="props.userViewModalData?.config.footer">
          <AButton @click="handleVMCancel" :style="props.userViewModalData?.config.footerButtonBgColor
            ? { backgroundColor: props.userViewModalData.config.footerButtonBgColor }
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
  userViewModalData: Object
});

const emit = defineEmits(['closeVm']);

const modalHeight = props.userViewModalData?.config.height;
const modalWidth = props.userViewModalData?.config.width;

const handleVMCancel = () => {
  emit('closeVm')
}
</script>
