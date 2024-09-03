<template>
  <AModal :open="true" :footer="null" title="Create Item" @cancel="handleCancel">
    <AForm
      :model="formState"
      v-bind="layout"
      name="nest-messages"
      :validate-messages="validateMessages"
      @finish="onFinish"
    >
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput v-model:value="formState.user.name" />
      </AFormItem>
      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput v-model:value="formState.user.email" />
      </AFormItem>
      <AFormItem :name="['user', 'phone']" label="Phone">
        <AInput v-model:value="formState.user.phone" />
      </AFormItem>
      <AFormItem :name="['user', 'address']" label="Address">
        <AInput v-model:value="formState.user.address" />
      </AFormItem>
      <AFormItem :name="['user', 'password']" label="Password">
        <AInput v-model:value="formState.user.password" />
      </AFormItem>
      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Submit</AButton>
      </AFormItem>
    </AForm>
  </AModal>
</template>

<script lang="ts" setup>
import { defineEmits, reactive } from 'vue'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'
import { useRouter } from 'vue-router'

const emit = defineEmits(['close'])

const handleCancel = () => {
  emit('close') // Emit event to close modal
}

const router = useRouter() // Fixed typo from 'roure' to 'route'
const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const validateMessages = {
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
  },
}

const authStore = useAuthStore()
authStore.loadToken()
const token = authStore.token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

const formState = reactive({
  user: {
    name: '',
    email: '',
    phone: '',
    address: '',
    password: '',
  },
})

const onFinish = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/user-register', formState.user)
    console.log(response.data)
    handleCancel()
  } catch (error) {
    console.error(error)
  } finally {
    router.push({ name: 'users' }) 
  }
}
</script>
