<script lang="ts" setup>
const props = defineProps({
  modalConfigData: {
    type: Object || null || undefined
  }
});
const modalHeight = props.modalConfigData?.config.height;
const modalWidth = props.modalConfigData?.config.width;
const emit = defineEmits(['close'])
const handleCancel = () => {
  emit('close')
}
</script>

<template>
  <AModal :open="true" :closable="false" :bodyStyle="modalHeight ? { height: modalHeight + 'vh' } : {}"
    :width="modalWidth ? modalWidth + 'vw' : undefined">
    <template #title>
      <div :style="{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: props.modalConfigData?.config.titleBgColor || '#74e3e1',
        color: props.modalConfigData?.config.titleTextColor || '#323635',
        padding: '2px',
        borderRadius: '4px',
        margin: '-10px -15px 0px -15px'
      }">
        <!-- Centered Title Content -->
        <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center;">
          {{ props.modalConfigData?.config.title }}
        </div>
        <!-- Custom Close Button -->
        <button @click="handleCancel"
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
        <div v-if="props.modalConfigData?.config.footer">
          <AButton @click="handleCancel" :style="props.modalConfigData?.config.footerButtonBgColor
            ? { backgroundColor: props.modalConfigData.config.footerButtonBgColor }
            : {}">
            Close
          </AButton>
        </div>
      </div>
    </template>
  </AModal>
</template>
