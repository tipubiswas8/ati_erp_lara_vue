<script lang="ts" setup>
import { notification } from 'ant-design-vue';
import { SmileOutlined } from '@ant-design/icons-vue';
import { onMounted, h } from 'vue';

const props = defineProps({
  message: String
});

const openNotification = () => {
  notification.open({
    message: props.message,
    icon: () => h(SmileOutlined, { style: 'color: #108ee9' }),
    onClick: () => {
      // console.log('Notification Clicked!');
    },
  });
};

// Automatically open the notification when the component is mounted
onMounted(() => {
  openNotification();
});
</script>