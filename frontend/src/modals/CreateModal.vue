<script lang="ts" setup>
import { defineEmits, reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessNotification from '../notifications/SuccessNotification.vue'

const props = defineProps({
  success_message: String,
  userModalData: Object,
  userData: Object,
})

const modalHeight = props.userModalData?.config.height;
const modalWidth = props.userModalData?.config.width;



const emit = defineEmits(['close', 'userAdded'])
const success = ref(false);
const handleCancel = () => {
  emit('close')
}
const router = useRouter()

const successMessage = reactive({
  message: props.success_message
})

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


<template>
  <SuccessNotification v-if="success" :message="successMessage.message" />
  <AModal :open="true" :bodyStyle="{ height: modalHeight + 'vh' }" :width="modalWidth ? modalWidth + 'vw' : '30vw'"
    :closable="false">
    <template #title>
      <div :style="{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: props.userModalData?.config.titleBgColor || '#74e3e1',
        color: props.userModalData?.config.titleTextColor || '#323635',
        padding: '2px',
        borderRadius: '4px',
        margin: '-10px -15px 0px -15px'
      }">
        <!-- Centered Title Content -->
        <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center;">
          {{ props.userModalData?.config.title }}
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
        <div v-if="props.userModalData?.config.footer">
          <AButton @click="handleCancel" :style="props.userModalData?.config.footerButtonBgColor
            ? { backgroundColor: props.userModalData.config.footerButtonBgColor }
            : {}">
            Close
          </AButton>
        </div>
      </div>
    </template>
  </AModal>
</template>

