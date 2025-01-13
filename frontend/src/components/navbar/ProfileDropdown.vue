<template>
  <div class="profile-dropdown-wrapper">
    <VaDropdown v-model="isShown" :offset="[9, 0]" class="profile-dropdown" stick-to-edges>
      <template #anchor>
        <VaButton preset="secondary" color="textPrimary">
          <span class="profile-dropdown__anchor min-w-max">
            <slot />
            <img src="/images/user-1.png" style="height: 35px; width: 35px;" alt="User Logo" />
          </span>
        </VaButton>
      </template>
      <VaDropdownContent class="profile-dropdown__content md:w-60 px-0 py-4 w-full"
        :style="{ '--hover-color': hoverColor }">
        <VaList v-for="group in options" :key="group.name">
          <header v-if="group.name" class="uppercase text-[var(--va-secondary)] opacity-80 font-bold text-xs px-4">
            {{ t(`user.${group.name}`) }}
          </header>
          <VaListItem v-for="item in group.list" :key="item.name" class="menu-item px-4 text-base cursor-pointer h-8"
            v-bind="resolveLinkAttribute(item)">
            <component :is="getAntdIcon(item.icon)" class="pr-2" style="font-size: 18px; color: var(--va-primary);" />
            {{ t(`user.${item.name}`) }}
          </VaListItem>
          <VaListSeparator v-if="group.separator" class="mx-3 my-2" />
        </VaList>
      </VaDropdownContent>
    </VaDropdown>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useColors } from 'vuestic-ui'
import * as AntdIcons from '@ant-design/icons-vue'

const { colors, setHSLAColor } = useColors()
const hoverColor = computed(() => setHSLAColor(colors.focus, { a: 0.1 }))

const { t } = useI18n()

type ProfileListItem = {
  name: string
  to?: string
  href?: string
  icon: string
}

type ProfileOptions = {
  name: string
  separator: boolean
  list: ProfileListItem[]
}

withDefaults(
  defineProps<{
    options?: ProfileOptions[]
  }>(),
  {
    options: () => [
      {
        name: 'account',
        separator: true,
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
)

const isShown = ref(false)

// Dynamically resolve Ant Design Vue icons
const getAntdIcon = (iconName: string) => {
  return AntdIcons[iconName as keyof typeof AntdIcons] || null
}

const resolveLinkAttribute = (item: ProfileListItem) => {
  return item.to ? { to: { name: item.to } } : item.href ? { href: item.href, target: '_blank' } : {}
}
</script>

<style lang="scss">
.profile-dropdown {
  cursor: pointer;

  &__content {
    .menu-item:hover {
      background: var(--hover-color);
    }
  }

  &__anchor {
    display: inline-block;
  }
}
</style>
