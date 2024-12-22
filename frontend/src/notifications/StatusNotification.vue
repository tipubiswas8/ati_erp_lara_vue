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
  response: { responseStatus: boolean; responseMessage: string },
) => {
  const action = response.responseStatus === false ? 'deactivated' : 'activated';
  notification[type]({
    message: 'Response Notification',
    description: `${response.responseMessage} was ${action} successfully!`,
    placement: 'topRight',
    duration: 5
  });
  emit('sendToParent', { ...emitData });
};

const props = defineProps({
  statusData: Object,
})

const closeNoticication = () => {
  emitData.statusUpdate = false;
  emit('sendToParent', { ...emitData });
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
                    responseMessage: response.data.data.user_name,
                  }
                  openNotificationWithIcon('success', { responseStatus: responseData.responseStatus, responseMessage: responseData.responseMessage });
                  // setTimeout(() => {
                  //   emitData.statusUpdate = false;
                  // }, 4000);
                } else {
                  emitData.responseFeedback = false;
                  emitData.statusUpdate = false;
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
              emit('sendToParent', { ...emitData });
            },
          },
          { default: () => 'No' },
        ),
      ]),
    key,
    onClose: closeNoticication,
  })
}

onMounted(() => {
  openNotification()
})
</script>
