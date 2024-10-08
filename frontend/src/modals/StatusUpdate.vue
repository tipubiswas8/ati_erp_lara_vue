<template>
  <contextHolder />
</template>

<script lang="ts" setup>
import { NotificationPlacement, notification, Button } from 'ant-design-vue'
import { h, onMounted, defineEmits, defineProps } from 'vue'
import axios from 'axios'

// for response notification
const [api, contextHolder] = notification.useNotification()
const openResponseNotification = (
  placement: NotificationPlacement,
  response: { responseStatus: boolean; responseUserName: string },
) => {
  const action = response.responseStatus === false ? 'deactivated' : 'activated'
  api.info({
    message: 'Response Notification',
    description: `${response.responseUserName} was ${action} successfully`,
    placement,
  })
}

// Define emits
const emit = defineEmits(['statusUpdate'])
const props = defineProps({
  statusData: Object,
})

const close = () => {
  emit('statusUpdate', false) // Emit event to parent with 'false' value
}

const openNotification = () => {
  const key = '1'

  const userData = {
    id: props.statusData?.id,
    status: props.statusData?.status,
  }

  const statusType = props.statusData?.status === 1 ? 'deactivate' : 'activate'
  const userName = props.statusData?.name

  notification.open({
    message: 'Confirmation',
    description: h('span', { innerHTML: `Are you sure you want to <b>${statusType} ${userName}</b>?` }),
    duration: 0,
    placement: 'top',
    top: '80px',
    type: 'warning',
    btn: () =>
      h('div', [
        h(
          Button,
          {
            type: 'primary',
            size: 'small',
            style: { marginRight: '8px' },
            onClick: async () => {
              notification.close(key)
              try {
                const response = await axios.patch('http://localhost:8000/api/user-status', {
                  id: userData.id,
                  status: userData.status,
                })

                if (response.data.status === 200 || response.data.status === 300) {
                  const responseData = {
                    responseStatus: response.data.success.status,
                    responseUserName: response.data.success.name,
                  }
                  openResponseNotification('topRight', responseData)
                  setTimeout(() => {
                    emit('statusUpdate', false)
                  }, 5000)
                } else {
                  if (response.data.status === 500) {
                    console.log(response.data)
                    const responseData = {
                      responseStatus: response.data.message,
                      responseUserName: response.data.error,
                    }
                    openResponseNotification('topRight', responseData)
                    setTimeout(() => {
                      emit('statusUpdate', false)
                    }, 5000)
                  }
                }
              } catch (error) {
                console.error(error)
                // openResponseNotification('topRight', { responseStatus: 0, responseUserName: "Unknown" });
                // setTimeout(() => {
                //   emit('statusUpdate', false);
                // }, 5000);
              }
            },
          },
          { default: () => 'Yes' },
        ),
        h(
          Button,
          {
            type: 'default',
            size: 'small',
            onClick: () => {
              notification.close(key)
              emit('statusUpdate', false) // Emit event to parent with 'false' value
            },
          },
          { default: () => 'No' },
        ),
      ]),
    key,
    onClose: close,
  })
}

onMounted(() => {
  openNotification()
})
</script>
