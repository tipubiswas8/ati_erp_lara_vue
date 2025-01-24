<template>
  <div class="profile-dropdown-wrapper">
    <!-- Dropdown Wrapper -->
    <div class="profile-dropdown" @mouseenter="isShown = true" @mouseleave="isShown = false">
      <!-- Anchor Button -->
      <button class="dropdown-anchor">
        <slot />
        <img src="/images/user-1.png" class="user-photo" alt="User Logo" />
      </button>

      <!-- Dropdown Content -->
      <div v-if="isShown" class="dropdown-content">
        <div v-for="group in options" :key="group.name" class="dropdown-group">
          <!-- Group Header -->
          <header v-if="group.name" class="dropdown-header">
            {{ t(`user.${group.name}`) }}
          </header>
          <!-- Group Items -->
          <div v-for="item in group.list" :key="item.name" class="dropdown-item" @click="handleNavigation(item)">
            <span class="dropdown-item-icon">
              <component :is="getAntdIcon(item.icon)" />
            </span>
            <span>{{ t(`user.${item.name}`) }}</span>
          </div>
          <!-- Separator -->
          <div v-if="group.separator" class="dropdown-separator"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import * as AntdIcons from '@ant-design/icons-vue';
import { useRouter } from 'vue-router';

const { t } = useI18n();
const router = useRouter();

type ProfileListItem = {
  name: string;
  to?: string;
  href?: string;
  icon: string;
};

type ProfileOptions = {
  name: string;
  separator: boolean;
  list: ProfileListItem[];
};

withDefaults(
  defineProps<{
    options?: ProfileOptions[];
  }>(),
  {
    options: () => [
      {
        name: 'account',
        separator: false,
        list: [
          {
            name: 'profile',
            to: 'preferences',
            icon: 'UserOutlined',
          },
        ],
      },
      {
        name: '',
        separator: true,
        list: [
          {
            name: 'changePassword',
            to: 'change-password',
            icon: 'KeyOutlined',
          },
        ],
      },
      {
        name: 'signOut',
        separator: false,
        list: [
          {
            name: 'logout',
            to: 'login',
            icon: 'LogoutOutlined',
          },
        ],
      },
    ],
  },
);

const isShown = ref(false);

// Dynamically resolve Ant Design Vue icons
const getAntdIcon = (iconName: string) => {
  return AntdIcons[iconName as keyof typeof AntdIcons] || null;
};

// Handle navigation on item click
const handleNavigation = (item: ProfileListItem) => {
  if (item.to) {
    router.push({ name: item.to }); // Navigate to named route
  } else if (item.href) {
    window.open(item.href, '_blank'); // Open external link in a new tab
  }
};
</script>

<style>
.profile-dropdown-wrapper {
  position: relative;
}

.profile-dropdown {
  display: inline-block;
  position: relative;
  cursor: pointer;
}

.dropdown-anchor {
  display: flex;
  align-items: center;
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 14px;
  color: #111827;
  /* Tailwind gray-900 */
}

.dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #ffffff;
  border: 1px solid #e5e7eb;
  /* Tailwind gray-200 */
  border-radius: 6px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  min-width: 240px;
  z-index: 1000;
  padding: 8px 0;
}

.dropdown-group {
  padding: 0;
}

.dropdown-header {
  text-transform: uppercase;
  font-weight: bold;
  font-size: 12px;
  color: #6b7280;
  /* Tailwind gray-500 */
  padding: 8px 16px;
  opacity: 0.8;
}

.dropdown-item {
  display: flex;
  align-items: center;
  padding: 8px 16px;
  font-size: 14px;
  color: #111827;
  /* Tailwind gray-900 */
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.dropdown-item:hover {
  background-color: #f3f4f6;
  /* Tailwind gray-100 */
}

.dropdown-item-icon {
  margin-right: 8px;
  font-size: 18px;
  color: #4f46e5;
  /* Tailwind indigo-600 */
}

.dropdown-separator {
  height: 1px;
  background: #e5e7eb;
  /* Tailwind gray-200 */
  margin: 8px 16px;
}

.user-photo {
  height: 6vh;
  width: 3vw;
}

/* Extra Small Devices (Phones, Portrait Mode) */
@media (max-width: 575px) {

  /* Styles for phones in portrait mode */
  .dropdown-anchor {
    padding-right: 16px;
  }

  .user-photo {
    height: 35px;
    width: 8vw;
  }
}

/* Small Devices (Phones, Landscape Mode) */
@media (min-width: 576px) and (max-width: 767px) {

  /* Styles for phones in landscape mode */
  .dropdown-anchor {
    padding-right: 16px;
  }

  .user-photo {
    height: 4vh;
    width: 30px;
  }
}
</style>
