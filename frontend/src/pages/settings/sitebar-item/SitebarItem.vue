<template>
  <!-- Display Selected Items -->
  <div v-if="storedSidebarItems.length > 0">
    <p>Sidebar Item List:</p>
  <div style="display: inline;" v-for="(singleItem, index) in storedSidebarItems" :key="singleItem">
    <span> {{ (index + 1) + '. ' + getItemName(singleItem) }} &nbsp; </span>
  </div>
  </div>

  <p style="color: red">{{ maxLimitMessage }}</p>

  <div>
    <p class="sidebar-label">Select Sidebar Item</p>
    <a-select v-model:value="sidebarItems" mode="multiple" placeholder="Select items" class="custom-select"
      @change="handleSelectionChange">
      <a-select-option v-for="item in items" :key="item.id" :value="item.id"
        :disabled="sidebarItems.length >= 5 && !sidebarItems.includes(item.id)">
        <component :is="getAntdIcon(item.icon)" style="margin-right: 8px;" />
        {{ item.name }}
      </a-select-option>
    </a-select>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import * as AntdIcons from '@ant-design/icons-vue';
import { useSitebarItems } from './items'; // Import the `useSitebarItems` hook
// Using the hook to get items
const { items } = useSitebarItems();
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

// Function to get item name by ID
const getItemName = (id: string) => {
  const item = items.value.find((item) => item.id.toString() === id);
  return item ? t(item.name) : 'Unknown';
};


// Reactive array to track selected items
const sidebarItems = ref<string[]>([]);
const storedSidebarItems = ref<string[]>([]);
const maxLimitMessage = ref<string>('');

// Sync selected items with local storage
const handleSelectionChange = (value: string[]) => {
  if (value.length <= 5) {
    localStorage.setItem('sidebarItems', JSON.stringify(value));
    storedSidebarItems.value = value; // Update stored items
  }

  if (value.length == 5) {
    maxLimitMessage.value = "You can't select more than 5 items in the sidebar.";
    setTimeout(() => {
      maxLimitMessage.value = '';
    }, 3000);
  }
};

// Load saved sidebar items on mount
onMounted(() => {
  const savedItems = JSON.parse(localStorage.getItem('sidebarItems') || '[]');
  sidebarItems.value = savedItems;
  storedSidebarItems.value = savedItems; // Sync stored items
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