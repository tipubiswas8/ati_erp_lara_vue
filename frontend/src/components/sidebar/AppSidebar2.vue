<!-- SidebarComponent.vue -->
<template>
  <div style="margin-top: 50px;" v-for="item in sidebarItems" :key="item.name">
    <router-link :to="{ name: item.url }">
      <component :is="getAntdIcon(item.icon)" style="font-size: 24px;" />
    </router-link>
  </div>
</template>

<script lang="ts" setup>
import { onMounted } from 'vue';
import * as AntdIcons from '@ant-design/icons-vue'

import { useSitebarItems } from '@/pages/settings/sitebar-item/items'; // Import the `useSitebarItems` hook
// Using the hook to get items
const { items } = useSitebarItems();
const sidebarItems = items;

onMounted(() => {
  const savedItems = localStorage.getItem('sidebarItems');
  if (savedItems) {
    sidebarItems.value = JSON.parse(savedItems);
  }
  console.log('Sidebar Items:', sidebarItems.value);
});
// Function to dynamically fetch icons from Ant Design
const getAntdIcon = (iconName: string) => {
  const icon = AntdIcons[iconName as keyof typeof AntdIcons];
  if (!icon) {
    console.error(`Icon ${iconName} not found in Ant Design Icons.`);
  }
  return icon || null;
};

// Reference to store the sidebar items

</script>

