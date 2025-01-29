<!-- This is app sidebar -->
<template>
  <div v-if="isShowSidebar" class="sidebar"
    :class="{ is__show__sidebar: isSidebarMinimized && props.mobile || !isSidebarMinimized && props.tablet }" :style="{
      width: isSidebarMinimized ? '8vw' : sidebarWidth,
      backgroundColor: getThemeColor('background'),
      color: getThemeColor('text')
    }">
    <!-- Top-Level Routes -->
    <div v-for="(route, index) in routes" :key="index" class="sidebar-item"
      :class="{ active: routeHasActiveChild(route) }">
      <!-- Main Route Item -->

      <div :style="isParentActive(index) ? {
        color: getThemeColor('primary'),
      } : {}">
        <!-- Router Link -->
        <router-link v-if="!route.children" :to="{ name: route.name }" class="sidebar-link"
          @click="setActiveParent(index)">
          <!-- Icon -->
          <span v-if="route.meta?.icon" :style="isParentActive(index) ? {
            color: getThemeColor('primary'),
          } : {
            backgroundColor: getThemeColor('background'),
            color: getThemeColor('text')
          }">
            <component :is="getAntdIcon(route.meta.icon)" />
          </span>

          <!-- Name -->
          <component :style="isParentActive(index) ? {
            color: getThemeColor('primary'),
          } : {
            backgroundColor: getThemeColor('background'),
            color: getThemeColor('text')
          }">
            <span v-if="!isSidebarMinimized">
              {{ t(route.displayName || 'Unnamed Route') }}
            </span>
          </component>
        </router-link>

        <!-- If Route has Children -->
        <div v-else @click="toggleCollapse(index)" :style="isParentActive(index) ? {
          color: getThemeColor('primary'),
        } : {}">
          <!-- Icon -->
          <span v-if="route.meta?.icon">
            <component :is="getAntdIcon(route.meta.icon)" />
          </span>

          <!-- Name -->
          <component v-if="!isSidebarMinimized">
            {{ t(route.displayName || 'Unnamed Route') }}
          </component>

          <!-- Arrow -->
          <span v-if="!isSidebarMinimized">
            <component :is="arrowDirection(isRouteCollapsed(index))" />
          </span>
        </div>
      </div>

      <!-- Child Routes -->
      <div v-if="route.children && !isRouteCollapsed(index)">
        <div v-for="(childRoute, childIndex) in route.children" :key="childIndex">
          <div :style="isChildActive(index, childIndex) ? {
            color: getThemeColor('secondary'),
          } : {}">
            <router-link v-if="!childRoute.grandChildren" :to="{ name: childRoute.name }" class="sidebar-link"
              @click="setActiveChild(index, childIndex)">
              <!-- Icon -->
              <span v-if="childRoute.meta?.icon" class="child" :style="isChildActive(index, childIndex) ? {
                color: getThemeColor('secondary'),
              } : {
                backgroundColor: getThemeColor('background'),
                color: getThemeColor('text')
              }">
                <component :is="getAntdIcon(childRoute.meta.icon)" />
              </span>

              <!-- Name -->
              <component :style="isChildActive(index, childIndex) ? {
                color: getThemeColor('secondary'),
              } : {
                backgroundColor: getThemeColor('background'),
                color: getThemeColor('text')
              }">
                <span v-if="!isSidebarMinimized">
                  {{ t(childRoute.displayName || 'Unnamed Route') }}
                </span>
              </component>
            </router-link>

            <!-- If Child has Grandchildren -->
            <div v-else @click="toggleCollapse2(index, childIndex)" :style="isChildActive(index, childIndex) ? {
              color: getThemeColor('secondary'),
            } : {}">
              <!-- Icon -->
              <span v-if="childRoute.meta?.icon" class="child">
                <component :is="getAntdIcon(childRoute.meta.icon)" />
              </span>

              <!-- Name -->
              <component v-if="!isSidebarMinimized">
                {{ t(childRoute.displayName || 'Unnamed Route') }}
              </component>

              <!-- Arrow -->
              <span v-if="!isSidebarMinimized">
                <component :is="arrowDirection(isRouteCollapsed2(index, childIndex))" />
              </span>
            </div>
          </div>

          <!-- Grandchild Routes -->
          <div v-if="childRoute.grandChildren && !isRouteCollapsed2(index, childIndex)">
            <div v-for="(grandChildRoute, grandChildIndex) in childRoute.grandChildren" :key="grandChildIndex" :style="isGrandchildActive(index, childIndex, grandChildIndex) ? {
              color: getThemeColor('accent'),
            } : {}">
              <router-link :to="{ name: grandChildRoute.name }" class="sidebar-link"
                @click="setActiveGrandchild(index, childIndex, grandChildIndex)">
                <!-- Icon -->
                <span v-if="grandChildRoute.meta?.icon" class="grand-child" :style="isGrandchildActive(index, childIndex, grandChildIndex) ? {
                  color: getThemeColor('accent'),
                } : {
                  backgroundColor: getThemeColor('background'),
                  color: getThemeColor('text')
                }">
                  <component :is="getAntdIcon(grandChildRoute.meta.icon)" />
                </span>

                <!-- Name -->
                <component :style="isGrandchildActive(index, childIndex, grandChildIndex) ? {
                  color: getThemeColor('accent'),
                } : {
                  backgroundColor: getThemeColor('background'),
                  color: getThemeColor('text')
                }">
                  <span v-if="!isSidebarMinimized">
                    {{ t(grandChildRoute.displayName || 'Unnamed Route') }}
                  </span>
                </component>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      :style="isSidebarMinimized ? { display: 'inline', padding: '10px' } : { display: 'flex', gap: '10px', padding: '10px', justifyContent: 'space-between' }">
      <div v-for="item in sidebarItems" :key="item.id">
        <router-link :to="{ name: item.url }">
          <component :is="getAntdIcon2(item.id)" style="font-size: 24px;" />
        </router-link>
      </div>
    </div>


  </div>
</template>

<script lang="ts" setup>
import { ref, computed, watch, inject, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import * as AntdIcons from '@ant-design/icons-vue'
import navigationRoutes from './NavigationRoutes';
import { storeToRefs } from 'pinia';
import { useGlobalStore } from '../../stores/global-store';
import { useControlPanelSecond } from '../../stores/control-panel'
import { useSitebarItems } from '@/pages/settings/sitebar-item/items'; // Import the `useSitebarItems` hook
// Using the hook to get items
const { items } = useSitebarItems();
const sidebarItems = ref<string[]>([]);

// Function to update sidebar items from localStorage
const updateSidebarItems = () => {
  const savedItems = localStorage.getItem('sidebarItems');
  if (savedItems) {
    try {
      const savedItemIds: string[] = JSON.parse(savedItems);
      sidebarItems.value = items.value.filter((item) => savedItemIds.includes(item.id.toString()));
    } catch (error) {
      console.error("Error parsing sidebarItems from localStorage:", error);
    }
  } else {
    sidebarItems.value = [];
  }
};

// 🔹 Listen for localStorage changes across pages/tabs
const handleStorageChange = (event: StorageEvent) => {
  if (event.key === 'sidebarItems') {
    updateSidebarItems();
  }
};

// 🔹 Also detect changes in the **same page** by watching localStorage manually
const observeLocalStorage = () => {
  setInterval(() => {
    updateSidebarItems();
  }, 500); // Check every 500ms for updates
};

onMounted(() => {
  updateSidebarItems();
  window.addEventListener('storage', handleStorageChange);
  observeLocalStorage(); // Ensure real-time update within the same tab
});

onUnmounted(() => {
  window.removeEventListener('storage', handleStorageChange);
});

const getAntdIcon2 = (id: string) => {
  const item = sidebarItems.value.find((item) => item.id.toString() === id);
  return item && (AntdIcons as Record<string, any>)[item.icon]
    ? (AntdIcons as Record<string, any>)[item.icon]
    : AntdIcons.SettingOutlined;
};
// Access the store
// Destructure the state and actions using storeToRefs
const { isShowSidebar } = storeToRefs(useControlPanelSecond());
// Sidebar State
const { isSidebarMinimized } = storeToRefs(useGlobalStore());

const activeParent = ref<number | null>(null)
const activeChild = ref<{ parentIndex: number; childIndex: number } | null>(null)
const activeGrandchild = ref<{ parentIndex: number; childIndex: number; grandchildIndex: number } | null>(null)

const setActiveParent = (index: number) => {
  activeParent.value = index
  activeChild.value = null
  activeGrandchild.value = null
}
const isParentActive = (index: number) => activeParent.value === index

const setActiveChild = (parentIndex: number, childIndex: number) => {
  activeParent.value = parentIndex
  activeChild.value = { parentIndex, childIndex }
  activeGrandchild.value = null
}
const isChildActive = (parentIndex: number, childIndex: number) =>
  activeChild.value?.parentIndex === parentIndex && activeChild.value?.childIndex === childIndex

const setActiveGrandchild = (parentIndex: number, childIndex: number, grandchildIndex: number) => {
  activeParent.value = parentIndex
  activeChild.value = { parentIndex, childIndex }
  activeGrandchild.value = { parentIndex, childIndex, grandchildIndex }
}

const isGrandchildActive = (parentIndex: number, childIndex: number, grandchildIndex: number) =>
  activeGrandchild.value?.parentIndex === parentIndex &&
  activeGrandchild.value?.childIndex === childIndex &&
  activeGrandchild.value?.grandchildIndex === grandchildIndex

// Define the type for the theme
type Theme = {
  setTheme: (theme: 'light' | 'dark') => void;
  getThemeColor: (colorKey: 'background' | 'border' | 'accent' | 'text' | 'primary' | 'secondary') => string;
  currentTheme: import('vue').ComputedRef<string>;
};

// Inject the global theme
const theme = inject<Theme>('theme')
if (!theme) {
  throw new Error('Theme provider is not available!')
}

// Destructure functions and properties from the theme
const { getThemeColor } = theme

const routes = navigationRoutes;

// Props and Emits
const props = defineProps({
  visible: { type: Boolean, default: true },
  mobile: { type: Boolean, default: false },
  tablet: { type: Boolean, default: false },
})
const emit = defineEmits(['update:visible'])

// Utilities
const route = useRoute()
const { t } = useI18n()

const parameterOne = ref<Record<string | number, boolean>>({})
const parameterTwo = ref<Record<string, Record<number, boolean>>>({})

const writableVisible = computed({
  get: () => props.visible,
  set: (v: boolean) => emit('update:visible', v),
})

const sidebarWidth = computed(() => (props.mobile ? '100vw' : '16vw'))

const getAntdIcon = (iconName: string) => {
  const icon = AntdIcons[iconName as keyof typeof AntdIcons];
  if (!icon) {
    console.error(`Icon ${iconName} not found in Ant Design Icons.`);
  }
  return icon || null;
};


// Helper Functions
const routeHasActiveChild = (section: any) => section.children?.some(({ name }: any) => route.name === name)

// Recursive Helper to Expand Ancestors
const expandAncestors = (routes: any[], activeName: string) => {
  routes.forEach((route, index) => {
    if (route.children || route.grandChildren) {
      const isActiveParent = route.children?.some(
        (child: any) => child.name === activeName || child.grandChildren?.some((grand: any) => grand.name === activeName)
      )
      if (isActiveParent) {
        parameterOne.value[index] = true // Keep parent expanded
        if (route.children) {
          expandAncestors(route.children, activeName) // Recursively expand ancestors
        }
      }
    }
  })
}

const expandAncestors2 = (routes: any[], activeName: string, parentIndex: number) => {
  routes.forEach((childRoute, childIndex) => {
    if (childRoute.grandChildren) {
      const isActiveGrandchild = childRoute.grandChildren?.some(
        (grandchild: any) => grandchild.name === activeName
      );
      if (isActiveGrandchild) {
        if (!parameterTwo.value[parentIndex]) parameterTwo.value[parentIndex] = {};
        parameterTwo.value[parentIndex][childIndex] = true; // Keep the child expanded
      }
    }
  });
};

// Arrow Direction
const arrowDirection = (state: boolean) => (state ? AntdIcons.UpOutlined : AntdIcons.DownOutlined)

// Handle Collapse and Watch Route Changes
const isRouteCollapsed = (index: string | number) => !parameterOne.value[index]
const isRouteCollapsed2 = (parentIndex: string | number, childIndex: string | number) => !parameterTwo.value[parentIndex]?.[childIndex]
const toggleCollapse = (index: string | number) => {
  parameterOne.value[index] = !parameterOne.value[index]
}
const toggleCollapse2 = (parentIndex: number, childIndex: number) => {
  if (!parameterTwo.value[parentIndex]) parameterTwo.value[parentIndex] = {};
  Object.keys(parameterTwo.value[parentIndex]).forEach((key) => {
    if (parseInt(key) !== childIndex) {
      parameterTwo.value[parentIndex][key] = false;
    }
  });
  parameterTwo.value[parentIndex][childIndex] = !parameterTwo.value[parentIndex][childIndex];
};

// Watch for route changes to expand ancestors dynamically
watch(
  () => route.name,
  (newRouteName) => {
    parameterOne.value = {} // Reset all collapsible states
    expandAncestors(routes, newRouteName || '')
    routes.forEach((route, parentIndex) => {
      if (route.children) {
        expandAncestors2(route.children, newRouteName || '', parentIndex);
      }
    })
  },
  { immediate: true }
)
</script>

<style scoped>
/* General Sidebar Styles */
/* app sidebar */
.sidebar {
  position: fixed;
  display: flex;
  flex-direction: column;
  height: 100vh;
  margin-top: 8vh;
  overflow: auto;
  transition: width 0.3s ease-in-out;
}

/* app sidebar show or not */
/* hides the sidebar when screen is mobile and show the main content */
.sidebar.is__show__sidebar {
  display: none;
}

.sidebar-item {
  margin-left: 5px;
  padding: 8px 12px;
  cursor: pointer;
  overflow: auto;
  transition: background-color 0.2s, color 0.2s;
}

.sidebar-link {
  text-decoration: none;
  color: inherit;
  width: 100%;
  display: flex;
  align-items: center;
}

/* Child and Grandchild Styles */
.child {
  margin-left: 20px;
  padding: 8px 4px;
  overflow: auto;
  transition: background-color 0.2s, color 0.2s;
}

.grand-child {
  margin-left: 40px;
  padding: 8px 4px;
  overflow: auto;
  transition: background-color 0.2s, color 0.2s;
}

.sidebar-item-active-parent {
  background-color: lightblue !important;
  margin: 1.5px;
  padding: 1.5px;
  /* For override theme background color */
}

.sidebar-item-active-child {
  background-color: lightgreen !important;
  padding: 1.5px;
  /* For override theme background color */
}

.sidebar-item-active-grandchild {
  background-color: lightcoral !important;
  padding: 1.5px;
  /* For override theme background color */
}

/* Responsive Design */

@media (max-width: 576px) {
  .sidebar {
    width: 100vw;
    margin-top: 50px;
  }

  /* .sidebar-item {
    padding: 10px;
  }

  .child,
  .grand-child {
    margin-left: 12px;
    padding: 6px 10px;
  } */

}


@media (min-width: 576px) and (max-width: 767px) {
  /* .sidebar {
    margin-top: 10vh;
  } 

  .sidebar-item {
    padding: 10px 16px;
  }

  .child,
  .grand-child {
    margin-left: 16px;
    padding: 6px 12px;
  } */
}
</style>
