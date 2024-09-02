<template>
  <AModal :open="true" :footer="null" title="Update Item" @cancel="handleCancel">
    <AForm v-bind="layout" name="nest-messages" :validate-messages="validateMessages" @finish="onFinish">
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput />
      </AFormItem>
      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput />
      </AFormItem>
      <AFormItem :name="['user', 'phone']" label="Phone">
        <AInput />
      </AFormItem>
      <AFormItem :name="['user', 'address']" label="Address">
        <AInput />
      </AFormItem>
      <AFormItem :name="['user', 'password']" label="Password">
        <AInput />
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

const props = defineProps({
  editData: Object, // Receive the editData as a prop
})


const formState222 = reactive({
  user: {
    name: props.editData?.name || '',
    email: props.editData?.email || '',
    phone: props.editData?.phone || '',
    address: props.editData?.address || '',
    password: '', // Password is usually not prefilled
  },
})


console.log('eeeddd' + formState222.user.name)
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
  // try {
  //   const response = await axios.post('http://localhost:8000/api/register', formState.user)
  //   console.log(response.data)
  //   handleCancel()
  // } catch (error) {
  //   console.error(error)
  // } finally {
  //   router.push({ name: 'users' }) // Use router.push for navigation
  // }
}
</script>


