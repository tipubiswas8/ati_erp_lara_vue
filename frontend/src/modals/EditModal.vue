<template>
  <SuccessNotification v-if="successUpdate.success" :message="successUpdate.message" />
  <slot :parent-data="parentData" :data="sendToParent"></slot>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits, reactive } from 'vue'
import axios from 'axios'
import SuccessNotification from '../notifications/SuccessNotification.vue'

const parentData = "demo data";

const sendToParent = () => {
  // Logic to send data
};
const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}
const props = defineProps({
  editData: {
    type: Object,
  },
})
console.log('Sending data to parent...' + props.editData?.name);

const successUpdate = reactive({
  success: false,
  message: ''
})

const formData = reactive({
  user: {
    id: props.editData?.id || 0,
    name: props.editData?.name || '',
    email: props.editData?.email || '',
    phone: props.editData?.phone || '',
    address: props.editData?.address || '',
    status: props.editData?.status === 1,
  },
})

const emit = defineEmits(['close'])

const handleCancel = () => {
  emit('close') // Emit event to close modal
}

const onFinish = async () => {
  try {
    const response = await axios.put('http://localhost:8000/api/user-update', formData.user)
    if (response.status === 204) {
      successUpdate.success = true
      successUpdate.message = 'User Update Successfully!'
      await handleCancel()
    }
  } catch (error) {
    console.error(error)
  }
}
</script>
