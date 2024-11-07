<script lang="ts" setup>
import { notification } from 'ant-design-vue';
import { onMounted } from 'vue';

const props = defineProps({
  message: String
});

const openNotification = () => {
  notification.open({
    message: props.message,
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