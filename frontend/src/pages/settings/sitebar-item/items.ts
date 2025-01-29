import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

export const useSitebarItems = () => {
  const { t } = useI18n(); // <-- Directly in setup

  const items = ref([
    { id: '1', name: t('sitebarItem.setting'), icon: 'SettingOutlined', url: 'dashboard' },
    { id: '2', name: t('sitebarItem.global'), icon: 'GlobalOutlined', url: 'dashboard' },
    { id: '3', name: t('sitebarItem.lock'), icon: 'LockOutlined', url: 'settings' },
    { id: '4', name: t('sitebarItem.control'), icon: 'ControlOutlined', url: 'settings' },
    { id: '5', name: t('sitebarItem.power'), icon: 'PoweroffOutlined', url: 'recover-password' },
    { id: '6', name: t('sitebarItem.shrink'), icon: 'ShrinkOutlined', url: 'recover-password' },
    { id: '7', name: t('sitebarItem.menu'), icon: 'MenuOutlined', url: 'recover-password' },
    { id: '8', name: t('sitebarItem.customer'), icon: 'CustomerServiceOutlined', url: 'recover-password' },
  ]);

  return { items };
};
