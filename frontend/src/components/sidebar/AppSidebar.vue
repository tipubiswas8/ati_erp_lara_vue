<!-- This is app sidebar -->
<template>

  <div style="margin-top: 50px;" v-for="item in items" :key="item.name">
    <router-link :to="{ name: item.to }">
      <component :is="getAntdIcon(item.name)" style="font-size: 24px;" />
    </router-link>
  </div>

</template>


<script lang="ts" setup>
import * as AntdIcons from '@ant-design/icons-vue'

const getAntdIcon = (iconName: string) => {
  const icon = AntdIcons[iconName as keyof typeof AntdIcons];
  if (!icon) {
    console.error(`Icon ${iconName} not found in Ant Design Icons.`);
  }
  return icon || null;
};

const items = [
  {
    name: 'SettingOutlined',
    to: 'dashboard'
  },
  {
    name: 'GlobalOutlined',
    to: 'dashboard'
  },
  {
    name: 'LockOutlined',
    to: 'settings'
  },
  {
    name: 'ControlOutlined',
    to: 'settings'
  },
  {
    name: 'PoweroffOutlined',
    to: 'recover-password'
  },

];


</script>

<style scoped></style>
