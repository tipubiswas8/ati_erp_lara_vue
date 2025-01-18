<template>
  <ContextHolder />
</template>

<script lang="ts" setup>
import { NotificationPlacement, notification, Button } from 'ant-design-vue'
import { h, onMounted } from 'vue'
import axios from 'axios'
import { reactive } from 'vue';

const emit = defineEmits<{
  (event: 'sendToParent', data: { statusUpdate: boolean; responseFeedback: any }): void;
}>();

// Define emitData as a reactive object
const emitData = reactive({
  statusUpdate: false,
  responseFeedback: '',
});

const openNotificationWithIcon = (
  type: 'success' | 'info' | 'warning' | 'error',
  response: { responseStatus: any; responseMessage: string },
) => {
  let action = response.responseStatus;
  let statusAfterUpdate = '' as string;
  // Correctly check if `action` matches any of the specified values
  if (action === 1 || action === '1' || action === 'Y' || action === 'Yes' || action === 'A' || action === 'Active' || action === "True" || action === 'true') {
    statusAfterUpdate = 'activate';
  } else {
    statusAfterUpdate = 'deactivate';
  }

  let desc = '';
  if (type === 'success') {
    desc = `${response.responseMessage} was ${statusAfterUpdate} successfully!`;
  } else if (type === 'error') {
    desc = response.responseMessage;
  }

  notification[type]({
    message: 'Response Notification',
    description: desc,
    placement: 'topRight',
    duration: 5
  });
  emit('sendToParent', { ...emitData });
};

const props = defineProps({
  statusData: Object,
  columnsName: {
    type: Object
  }
});

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

  const statusMap: Record<any, any> = {
    0: 1,
    1: 0,
    "Y": "N",
    "N": "Y",
    "Yes": "No",
    "No": "Yes",
    "A": "I",
    "I": "A",
    "Active": "Inactive",
    "Inactive": "Active",
    "True": "False",
    "False": "True",
    "true": "false",
    "false": "true",
  };

  const active_status = statusMap[userData.status] ?? userData.status;

  const activeStatuses = [1, "1", "Y", "Yes", "A", "Active", "True", "true"];

  const statusType = activeStatuses.includes(props.statusData?.status)
    ? "deactivate"
    : "activate";

  const dynamicName = props.statusData?.name_for_status_change

  notification.open({
    message: 'Confirmation',
    description: h('span', { innerHTML: `Are you sure you want to <b>${statusType} ${dynamicName}</b>?` }),
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
                  status: active_status,
                })
                if (response.status === 200) {
                  const columns_name = props.columnsName?.statusColumnName;
                  const change_for = props.columnsName?.statusChangeFor;
                  const responseData = {
                    responseStatus_2: response.data.data[columns_name] ?? response.data.data.status,
                    responseMessage: response.data.data[change_for] ?? response.data.data.name,
                  }
                  emitData.responseFeedback = responseData.responseStatus_2;
                  emitData.statusUpdate = true;
                  openNotificationWithIcon('success', { responseStatus: responseData.responseStatus_2, responseMessage: responseData.responseMessage });
                }
              } catch (error) {
                console.log(error);
                openNotificationWithIcon('error', {
                  responseStatus: false,
                  responseMessage: 'Failed to update the status. Please try again later.',
                });
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
