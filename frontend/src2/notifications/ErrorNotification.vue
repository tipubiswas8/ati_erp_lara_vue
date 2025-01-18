<script lang="ts" setup>
import { notification } from 'ant-design-vue';
import { FrownOutlined } from '@ant-design/icons-vue';
import { onMounted, h } from 'vue';

const props = defineProps({
  message: String,
  error: String
});

const openNotification = () => {
  notification.open({
    message: props.message,
    icon: () => h(FrownOutlined, { style: 'color: red' }),
    description: props.error,
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