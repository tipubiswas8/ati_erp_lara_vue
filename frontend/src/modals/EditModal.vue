<template>
  <AModal :open="true" :footer="null" title="Update Item" @cancel="handleCancel">
    <AForm
      v-bind="layout"
      :model="formData"
      name="nest-messages"
      :validate-messages="validateMessages"
      @finish="onFinish"
    >
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
      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Update</AButton>
      </AFormItem>
    </AForm>
  </AModal>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits, reactive } from 'vue'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'
import { useRouter } from 'vue-router'

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const props = defineProps({
  editData: {
    type: Object,
  },
})

const formData = reactive({
  user: {
    id: props.editData?.id || '',
    name: props.editData?.name || '',
    email: props.editData?.email || '',
    phone: props.editData?.phone || '',
    address: props.editData?.address || '',
  },
})
const emit = defineEmits(['close'])

const handleCancel = () => {
  emit('close') // Emit event to close modal
}

const router = useRouter() // Fixed typo from 'roure' to 'route'

const authStore = useAuthStore()
authStore.loadToken()
const token = authStore.token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

const onFinish = async () => {
  try {
    const response = await axios.put('http://localhost:8000/api/user-update', formData.user)
    console.log(response.data)
    handleCancel()
  } catch (error) {
    console.error(error)
  } finally {
    router.push({ name: 'users' }) // Use router.push for navigation
  }
}
</script>
