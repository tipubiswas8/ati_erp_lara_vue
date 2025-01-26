<template>
  <div>
    <p class="sidebar-label">Select Sidebar Item</p>
    <a-select v-model="sidebarItems" mode="multiple" placeholder="Select items" class="custom-select"
      @change="handleSelectionChange">
      <a-select-option v-for="item in items" :key="item.url" :value="item.name">
        <component :is="getAntdIcon(item.icon)" style="margin-right: 8px;" />
        {{ item.name }}
      </a-select-option>
    </a-select>
  </div>
  <div v-for="singleItem in savedItems" :key="singleItem.name">
    <span>{{ singleItem.name }}</span>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import * as AntdIcons from '@ant-design/icons-vue';

// Function to dynamically fetch icons from Ant Design
const getAntdIcon = (iconName: string) => {
  const icon = AntdIcons[iconName as keyof typeof AntdIcons];
  if (!icon) {
    console.error(`Icon "${iconName}" not found in Ant Design Icons.`);
  }
  return icon || null;
};

// i18n for translations
const { t } = useI18n();

// Reactive data
const items = ref([
  {
    name: t('sitebarItem.setting'),
    icon: 'SettingOutlined',
    url: 'dashboard',
  },
  {
    name: t('sitebarItem.global'),
    icon: 'GlobalOutlined',
    url: 'dashboard',
  },
  {
    name: t('sitebarItem.lock'),
    icon: 'LockOutlined',
    url: 'settings',
  },
  {
    name: t('sitebarItem.control'),
    icon: 'ControlOutlined',
    url: 'settings',
  },
  {
    name: t('sitebarItem.power'),
    icon: 'PoweroffOutlined',
    url: 'recover-password',
  },
  {
    name: t('sitebarItem.shrink'),
    icon: 'ShrinkOutlined',
    url: 'recover-password',
  },
  {
    name: t('sitebarItem.menu'),
    icon: 'MenuOutlined',
    url: 'recover-password',
  },
  {
    name: t('sitebarItem.customer'),
    icon: 'CustomerServiceOutlined',
    url: 'recover-password',
  },
]);

// Reactive array to track selected items
const sidebarItems = ref<string[]>([]);
// Sync selected items with local storage
const handleSelectionChange = (value: string[]) => {
  const previousSelected = JSON.parse(localStorage.getItem('sidebarItems') || '[]');

  // Items added to the selection
  const addedItems = value.filter(item => !previousSelected.includes(item));
  // Items removed from the selection
  const removedItems = previousSelected.filter(item => !value.includes(item));

  // Update local storage
  localStorage.setItem('sidebarItems', JSON.stringify(value));

  console.log('Added Items:', addedItems);
  console.log('Removed Items:', removedItems);

  // Update the `sidebarItems` state
  sidebarItems.value = value;
};

// Load initial items from local storage on mount
onMounted(() => {
  const storedItems = JSON.parse(localStorage.getItem('sidebarItems') || '[]');
  if (storedItems && Array.isArray(storedItems)) {
    sidebarItems.value = storedItems;
  }
});
// Computed property to fetch saved items from localStorage
const savedItems = computed(() => {
  const storedItems = localStorage.getItem('sidebarItems');
  return storedItems ? JSON.parse(storedItems) : [];
});
</script>

<style scoped>
.sidebar-label {
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: 10px;
  color: var(--text-color);
}

.custom-select-wrapper {
  display: inline-block;
  width: 20vw;
}

.custom-select {
  width: 30%;
  font-size: 1rem;
  color: var(--text-color, #333);
  background-color: var(--background-color, #fff);
  border: 1px solid var(--border-color, #ccc);
  border-radius: 5px;
  appearance: none;
  outline: none;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: border-color 0.3s, box-shadow 0.3s;
}

.custom-select:hover {
  border-color: var(--hover-border-color, #1a73e8);
}

.custom-select:focus {
  border-color: var(--focus-border-color, #1a73e8);
  box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
}

.custom-select option {
  color: var(--text-color, #333);
  background-color: var(--background-color, #fff);
}
</style>