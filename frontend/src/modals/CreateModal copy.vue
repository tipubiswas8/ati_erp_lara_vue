<template>
  <SuccessNotification v-if="success" :message="successMessage.message" />
  <AModal :open="true" :footer="null" title="Create Item" @cancel="handleCancel">
    <AForm :model="formState" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
      @finish="onFinish">
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput v-model:value="formState.user.name" autocomplete="off" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.name }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput v-model:value="formState.user.email" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.email }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'phone']" label="Phone" :rules="[{ validator: validatePhone }]">
        <AInput type="number" v-model:value="formState.user.phone" @keydown="blockNonNumericKeys"
          @input="castToNumber" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.phone }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'address']" label="Address">
        <AInput v-model:value="formState.user.address" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.address }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'password']" label="Password">
        <AInput v-model:value="formState.user.password" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.password }}</a-label>
      </AFormItem>

      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Submit</AButton>
      </AFormItem>
    </AForm>
  </AModal>
</template>


<script lang="ts" setup>
import { defineEmits, reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessNotification from '../notifications/SuccessNotification.vue'

const props = defineProps({
  success_message: String
})

const emit = defineEmits(['close', 'userAdded'])
const success = ref(false);
const handleCancel = () => {
  emit('close')
}

const router = useRouter()
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



const formState = reactive({
  user: {
    name: '',
    email: '',
    phone: '',
    address: '',
    password: '',
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

const successMessage = reactive({
  message: props.success_message
})

// Custom phone number validation
const validatePhone = async (_: any, value: string) => {
  if (!/^\d+$/.test(value)) {
    return Promise.reject(new Error('Phone number is not valid!'));
  } else {
    return Promise.resolve();
  }
}

// Function to block non-numeric keys like 'e', '+', and '-'
const blockNonNumericKeys = (event: KeyboardEvent) => {
  if (['e', 'E', '+', '-', '.'].includes(event.key)) {
    event.preventDefault();
  }
}

// Convert input value to a number to avoid string issues
const castToNumber = (event: any) => {
  const value = event.target.value;
  if (value !== '' && !isNaN(value)) {
    formState.user.phone = parseInt(value);
  }
}
const onFinish = async () => {
  try {
    const response = await axios.post('/user-register', formState.user)
    if (response.status === 201) {
      emit('userAdded', response.data.user)
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

<style>
/* Hide arrows in input[type="number"] for all browsers */
input[type='number']::-webkit-outer-spin-button,
input[type='number']::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type='number'] {
  -moz-appearance: textfield;
  /* Hide arrows for Firefox */
}
</style>