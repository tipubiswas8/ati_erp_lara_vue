<template>
   <SuccessNotification v-if="success" :message="successMessage.message" />
  <AForm :model="formState" v-bind="layout" name="nest-messages" :validate-messages="validateMessages" @finish="onFinish">
    <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
      <AInput v-model:value="formState.user.name" autocomplete="off" />
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.name }}</a-label>
    </AFormItem>

    <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
      <AInput v-model:value="formState.user.email" autocomplete="off"/>
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.email }}</a-label>
    </AFormItem>

    <AFormItem :name="['user', 'phone']" label="Phone" :rules="[{ validator: validatePhone }]">
      <AInput type="number" v-model:value="formState.user.phone" @keydown="blockNonNumericKeys"
      @input="castToNumber" autocomplete="off"/>
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.phone }}</a-label>
    </AFormItem>

    <AFormItem :name="['user', 'address']" label="Address">
      <AInput v-model:value="formState.user.address" autocomplete="off"/>
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.address }}</a-label>
    </AFormItem>

    <AFormItem :name="['user', 'password']" label="Password">
      <AInput v-model:value="formState.user.password" autocomplete="off"/>
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.password }}</a-label>
    </AFormItem>

    <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 10 }" style="margin-left: -10px;">
      <AButton type="primary" html-type="submit">Submit</AButton> 
    </AFormItem>
  </AForm>
</template>

<script lang="ts" setup>

import { ref, reactive } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessNotification from '../../../notifications/SuccessNotification.vue'

const sendToUserPage = defineEmits(['formData'])

const formState = reactive({
  user: {
    name: '' as string,
    email: '' as string,
    phone: '' as string | number,
    address: '' as string,
    password: '' as string,
  },
})
// For field-specific errors
const fieldErrors = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: ''
})

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const validateMessages = {
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
    number: '${label} is not a valid number!',
  }
};

// Function to block non-numeric keys like 'e', '+', and '-'
const blockNonNumericKeys = (event: KeyboardEvent) => {
  if (['e', 'E', '+', '-', '.'].includes(event.key)) {
    event.preventDefault();
  }
}

// Custom phone number validation
const validatePhone = async (_: any, value: string) => {
  if (!/^\d+$/.test(value)) {
    return Promise.reject(new Error('Phone number is not valid!'));
  } else {
    return Promise.resolve();
  }
}
// Convert input value to a number to avoid string issues
const castToNumber = (event: any) => {
  const value = event.target.value;
  if (value !== '' && !isNaN(value)) {
    formState.user.phone = parseInt(value);
  }
}


const props = defineProps({
  success_message: String,
  userData: Object,
})

const success = ref(false);

const router = useRouter()
const successMessage = reactive({
  message: props.success_message
})

const onFinish = async () => {
  try {
    const response = await axios.post(props.userData?.create_url, formState.user)
    if (response.status === 201) {
      success.value = true
      successMessage.message = response.data.message
      // handleCancel()
    }
  } catch (error) {
    if (error.response) {
      // console.error('Error status:', error.response.status) // Get the status code
      // console.error('Error data:', error.response.data) // Get the error response data
      if (error.response.status === 400) {
        // console.error('Validation error:', error.response.data.message)
        const errors = error.response.data.message || {}
        // fieldErrors.name = errors.name ? errors.name[0] : ''
        // fieldErrors.email = errors.email ? errors.email[0] : ''
        // fieldErrors.phone = errors.phone ? errors.phone[0] : ''
        // fieldErrors.address = errors.address ? errors.address[0] : ''
        // fieldErrors.password = errors.password ? errors.password[0] : ''
        // Trigger form validation after setting errors
        Object.keys(fieldErrors).forEach((key) => {
          fieldErrors[key] = errors[key] ? errors[key][0] : ''
        })
      }
    } else if (error.request) {
      console.error('No response received:', error.request)
    } else {
      console.error('Error:', error.message)
    }
  } finally {
    router.push({ name: 'users' })
  }
}
</script>
