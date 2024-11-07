<template>
  <SuccessNotification v-if="successUpdate.success" :message="successUpdate.message" />
  <AModal :open="true" :footer="null" title="Update Item" @cancel="handleCancel">
    <AForm v-bind="layout" :model="formData" name="nest-messages" :validate-messages="validateMessages"
      @finish="onFinish">
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput v-model:value="formData.user.name" />
      </AFormItem>
      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput v-model:value="formData.user.email" />
      </AFormItem>
      <AFormItem :name="['user', 'phone']" label="Phone">
        <AInput v-model:value="formData.user.phone" />
      </AFormItem>
      <AFormItem :name="['user', 'address']" label="Address">
        <AInput v-model:value="formData.user.address" />
      </AFormItem>
      <a-form-item :name="['user', 'status']" label="Active Status">
        <a-switch v-model:checked="formData.user.status" />
      </a-form-item>
      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Update</AButton>
      </AFormItem>
    </AForm>
  </AModal>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits, reactive } from 'vue'
import axios from 'axios'
import SuccessNotification from '../notifications/SuccessNotification.vue'

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const props = defineProps({
  editData: {
    type: Object,
  },
})

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
