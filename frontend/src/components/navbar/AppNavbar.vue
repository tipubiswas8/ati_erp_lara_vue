<template>
  <VaNavbar class="app-layout-navbar py-2 px-0">
    <template #left>
      <div class="left">
        <Transition v-if="isMobile" name="icon-fade" mode="out-in">
          <component color="primary" :is="isSidebarMinimized ? MenuOutlined : CloseOutlined" size="24px"
            style="margin-top: 3px" @click="isSidebarMinimized = !isSidebarMinimized" />
        </Transition>
        <RouterLink to="/" aria-label="Visit home page">
          <img src="/images/drug-logo.png" style="height: 35px; width: 200px;" alt="ATI Logo" />
        </RouterLink>
      </div>
    </template>
    <template #right>
      <AppNavbarActions class="app-navbar__actions" :is-mobile="isMobile" :style="{ marginRight: '2vw' }" />
    </template>
  </VaNavbar>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useGlobalStore } from '../../stores/global-store'
import AppNavbarActions from './AppNavbarActions.vue'
import { MenuOutlined, CloseOutlined } from '@ant-design/icons-vue' // Import Ant Design icons

defineProps({
  isMobile: { type: Boolean, default: false },
})

const GlobalStore = useGlobalStore()

const { isSidebarMinimized } = storeToRefs(GlobalStore)
</script>

<style lang="scss" scoped>
.va-navbar {
  z-index: 2;

  @media screen and (max-width: 950px) {
    .left {
      width: 100%;
    }

    .app-navbar__actions {
      display: flex;
      justify-content: space-between;
    }
  }
}

.left {
  display: flex;
  align-items: center;
  margin-left: 1rem;

  &>* {
    margin-right: 1rem;
  }

  &>*:last-child {
    margin-right: 0;
  }
}

.icon-fade-enter-active,
.icon-fade-leave-active {
  transition: transform 0.5s ease;
}

.icon-fade-enter,
.icon-fade-leave-to {
  transform: scale(0.5);
}
</style>
