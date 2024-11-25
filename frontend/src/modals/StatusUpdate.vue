<template>
  <ContextHolder />
</template>

<script lang="ts" setup>
import { NotificationPlacement, notification, Button } from 'ant-design-vue'
import { h, onMounted, defineEmits, defineProps } from 'vue'
import axios from 'axios'
import { reactive } from 'vue';

const emit = defineEmits<{
  (event: 'sendToParent', data: { statusUpdate: boolean; responseFeedback: boolean }): void;
}>();

// Define emitData as a reactive object
const emitData = reactive({
  statusUpdate: false,
  responseFeedback: false,
});

const openNotificationWithIcon = (
type: 'success' | 'info' | 'warning' | 'error', 
response: { responseStatus: boolean; responseUserName: string },
) => {
  const action = response.responseStatus === false ? 'deactivated' : 'activated';
  notification[type]({
    message: 'Response Notification',
    description: `${response.responseUserName} was ${action} successfully!`,
    placement: 'topRight',
    duration: 1
  });
  emit('sendToParent', { ...emitData });
};

const props = defineProps({
  statusData: Object,
})

const close = () => {
  emitData.statusUpdate = false;
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
                  emitData.responseFeedback = true;
                  const responseData = {
                    responseStatus: response.data.data.status,
                    responseUserName: response.data.data.user_name,
                  }
                  openNotificationWithIcon('success', { responseStatus: responseData.responseStatus, responseUserName: responseData.responseUserName });
                  setTimeout(() => {
                    emitData.statusUpdate = false;
                  }, 1000)
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
              emitData.statusUpdate = false;

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
