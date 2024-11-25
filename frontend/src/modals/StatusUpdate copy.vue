<template>
  <ContextHolder />
</template>

<script lang="ts" setup>
import { NotificationPlacement, notification, Button } from 'ant-design-vue'
import { h, onMounted, defineEmits, defineProps } from 'vue'
import axios from 'axios'

import { notification } from 'ant-design-vue';
const openNotificationWithIcon = (type: 'success' | 'info' | 'warning' | 'error') => {
  notification[type]({
    message: 'Notification Title',
    description:
      'This is the content of the notification. This is the content of the notification. This is the content of the notification.',
  });
};

// Call the function with a valid type
openNotificationWithIcon('success');


// for response notification
const [api, contextHolder] = notification.useNotification()
const openResponseNotification = (
  placement: NotificationPlacement,
  response: { responseStatus: boolean; responseUserName: string },
) => {
  const action = response.responseStatus === false ? 'deactivated' : 'activated'
  api.info({
    message: 'Response Notification',
    description: `${response.responseUserName} was ${action} successfully!`,
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
    url: props.statusData?.status_url,
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
                const response = await axios.patch(userData.url, {
                  id: userData.id,
                  status: userData.status,
                })
                if (response.status === 200) {
                  console.log(response)
                  const responseData = {
                    responseStatus: response.data.data.status,
                    responseUserName: response.data.data,
                  }
                  openResponseNotification('topRight', responseData);
                  setTimeout(() => {
                    emit('statusUpdate', false)
                  }, 5000)
                }
              } catch (error) {
                console.log(error)
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
