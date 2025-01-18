<template>
  <SuccessNotification v-if="successUpdate.success" :message="successUpdate.message" />
  <AModal :open="true" :closable="false" :bodyStyle="modalHeight ? { height: modalHeight + 'vh' } : {}"
    :width="modalWidth ? modalWidth + 'vw' : undefined">
    <template #title>
      <div :style="{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: props.configData?.config.titleBgColor || '#74e3e1',
        color: props.configData?.config.titleTextColor || '#323635',
        padding: '2px',
        borderRadius: '4px',
        margin: '-10px -15px 0px -15px'
      }">
        <!-- Centered Title Content -->
        <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center;">
          {{ props.configData?.config.title }}
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
        <div v-if="props.configData?.config.footer">
          <AButton @click="handleCancel" :style="props.configData?.config.footerButtonBgColor
            ? { backgroundColor: props.configData.config.footerButtonBgColor }
            : {}">
            Close
          </AButton>
        </div>
      </div>
    </template>
  </AModal>
</template>

<script lang="ts" setup>
import { reactive, watch } from 'vue'
import SuccessNotification from '../notifications/SuccessNotification.vue'

const props = defineProps({
  configData: {
    type: Object,
  },
  notifyData: Object,
})

const emit = defineEmits(['close']);

const handleCancel = () => {
  emit('close')
}

const modalHeight = props.configData?.config.height;
const modalWidth = props.configData?.config.width;

const successUpdate = reactive({
  success: false,
  message: ''
})

watch(() => props.notifyData, (newValue) => {
  successUpdate.success = newValue?.success
  successUpdate.message = newValue?.message
});

</script>
