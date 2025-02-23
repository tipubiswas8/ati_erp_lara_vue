<template>
  <div class="notification-dropdown">
    <div class="notification-dropdown__anchor">
      <button class="btn-secondary text-primary" @click="  = !displayDropdown">
        <span class="badge-text">2+</span>
        <i class="notification-dropdown__icon">🔔</i>
      </button>
    </div>
    <div v-if="displayDropdown" class="dropdown-content">
      <section>
        <ul class="list">
          <template v-for="(item, index) in notificationsWithRelativeTime" :key="item.id">
            <li class="list-item">
              <div class="icon-section">
                <component :is="getAntdIcon(item.icon) || MessageOutlined" />
              </div>
              <div class="text-section">
                {{ item.message }}
              </div>
              <div class="timestamp-section">
                {{ item.updateTimestamp }}
              </div>
            </li>
            <hr v-if="item.separator && index !== notificationsWithRelativeTime.length - 1" class="separator" />
          </template>
        </ul>

        <button class="btn-primary w-full" @click="displayAllNotifications = !displayAllNotifications">
          {{ displayAllNotifications ? t('notifications.less') : t('notifications.all') }}
        </button>
      </section>
    </div>
  </div>
</template>


<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import * as AntdIcons from '@ant-design/icons-vue'
import { MessageOutlined } from '@ant-design/icons-vue'

const getAntdIcon = (iconName: string) => {
  const IconComponent = AntdIcons[iconName as keyof typeof AntdIcons];
  if (!IconComponent) {
    console.error(`Icon "${iconName}" not found in Ant Design Icons.`);
    return null;
  }
  return IconComponent;
};


const { t, locale } = useI18n()

const baseNumberOfVisibleNotifications = 4
const rtf = new Intl.RelativeTimeFormat(locale.value, { style: 'short' })
const displayAllNotifications = ref(false)
const displayDropdown = ref(false)


interface INotification {
  message: string
  icon: string
  id: number
  separator?: boolean
  updateTimestamp: Date
}

const makeDateFromNow = (timeFromNow: number) => {
  const date = new Date()
  date.setTime(date.getTime() + timeFromNow)
  return date
}

const notifications: INotification[] = [
  {
    message: '4 pending requests',
    icon: 'UserAddOutlined',
    id: 1,
    separator: true,
    updateTimestamp: makeDateFromNow(-3 * 60 * 1000),
  },
  {
    message: '3 new reports',
    icon: 'ControlOutlined',
    id: 2,
    separator: true,
    updateTimestamp: makeDateFromNow(-12 * 60 * 60 * 1000),
  },
  {
    message: 'Whoops! Your trial period has expired.',
    icon: 'LockOutlined',
    id: 3,
    separator: true,
    updateTimestamp: makeDateFromNow(-2 * 24 * 60 * 60 * 1000),
  },
  {
    message: 'It looks like your timezone is set incorrectly, please change it to avoid issues with Memory.',
    icon: 'icon-schedule',
    id: 4,
    updateTimestamp: makeDateFromNow(-2 * 7 * 24 * 60 * 60 * 1000),
  },
  {
    message: '2 new team members added',
    icon: 'icon-group',
    id: 5,
    separator: false,
    updateTimestamp: makeDateFromNow(-3 * 60 * 1000),
  },
  {
    message: 'Monthly budget exceeded by 10%',
    icon: 'icon-trending',
    id: 6,
    separator: true,
    updateTimestamp: makeDateFromNow(-3 * 24 * 60 * 60 * 1000),
  },
  {
    message: '7 tasks are approaching their deadlines',
    icon: 'icon-alarm',
    id: 7,
    separator: false,
    updateTimestamp: makeDateFromNow(-5 * 60 * 60 * 1000),
  },
  {
    message: 'New software update available',
    icon: 'icon-system',
    id: 8,
    separator: true,
    updateTimestamp: makeDateFromNow(-1 * 24 * 60 * 60 * 1000),
  },
].sort((a, b) => new Date(b.updateTimestamp).getTime() - new Date(a.updateTimestamp).getTime())

const TIME_NAMES = {
  second: 1000,
  minute: 1000 * 60,
  hour: 1000 * 60 * 60,
  day: 1000 * 60 * 60 * 24,
  week: 1000 * 60 * 60 * 24 * 7,
  month: 1000 * 60 * 60 * 24 * 30,
  year: 1000 * 60 * 60 * 24 * 365,
}

const getTimeName = (differenceTime: number) => {
  return Object.keys(TIME_NAMES).reduce(
    (acc, key) => (TIME_NAMES[key as keyof typeof TIME_NAMES] < differenceTime ? key : acc),
    'month',
  ) as keyof typeof TIME_NAMES
}

const notificationsWithRelativeTime = computed(() => {
  const list = displayAllNotifications.value ? notifications : notifications.slice(0, baseNumberOfVisibleNotifications)

  return list.map((item, index) => {
    const timeDifference = Math.round(new Date().getTime() - new Date(item.updateTimestamp).getTime())
    const timeName = getTimeName(timeDifference)

    let separator = false

    const nextItem = list[index + 1]
    if (nextItem) {
      const nextItemDifference = Math.round(new Date().getTime() - new Date(nextItem.updateTimestamp).getTime())
      const nextItemTimeName = getTimeName(nextItemDifference)

      if (timeName !== nextItemTimeName) {
        separator = true
      }
    }

    return {
      ...item,
      updateTimestamp: rtf.format(-1 * Math.round(timeDifference / TIME_NAMES[timeName]), timeName),
      separator,
    }
  })
})

// Close dropdown when clicking outside
const handleOutsideClick = (event: MouseEvent) => {
  const dropdown = document.querySelector('.notification-dropdown');
  if (dropdown && !dropdown.contains(event.target as Node)) {
    displayDropdown.value = false;
  }
};

onMounted(() => {
  document.addEventListener('mousedown', handleOutsideClick);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleOutsideClick);
});


</script>

<style scoped>
.notification-dropdown {
  cursor: pointer;
}

.notification-dropdown__icon {
  display: flex;
  align-items: center;
  margin-top: -1vh;
  font-size: 3.5vh;
  height: 4.5vh;
}

.notification-dropdown__anchor {
  display: flex;
}

.badge-text {
  position: absolute;
  display: flex;
  align-items: center;
  background-color: red;
  color: white;
  margin-top: -1vh;
  font-size: 1.5vh;
  border-radius: 50%;
  margin-left: 6px;
  padding: 0.5vh 1vh;
  z-index: 2;
}

.dropdown-content {
  background-color: var(--background, 'black');
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 2;
  width: 10vw;
  height: 40vh;
  padding: 10px;
  overflow-y: auto;
  font-family: var(--font-family, 'Arial, sans-serif');
  font-size: 14px;
}

.list {
  list-style: none;
  padding: 0;
}

.list-item {
  display: flex;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #f0f0f0;
}

.icon-section {
  margin-right: 10px;
  color: var(--accent, 'white');
}

.text-section {
  flex-grow: 1;
}

.timestamp-section {
  margin-left: 10px;
  color: #888;
}

.separator {
  border: none;
  border-bottom: 1px solid #ccc;
  margin: 8px 0;
}

.btn-secondary {
  background-color: transparent;
  color: #333;
  border: none;
  cursor: pointer;
  margin: 0px;
  padding: 0px;
}

.btn-primary {
  background-color: #007bff;
  color: white;
  border: none;
  text-align: center;
  cursor: pointer;
}

.w-full {
  width: 100%;
}

.list-item:hover {
  background-color: var(--secondary, 'green');
  transform: scale(1.05);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Extra Small Devices (Phones, Portrait Mode) */
@media (max-width: 575px) {

  /* Styles for phones in portrait mode */
  .notification-dropdown__icon {
    margin-top: -10px;
    font-size: 24px;
    height: 14px;
  }

  .badge-text {
    margin-top: -18px;
    font-size: 12px;
    margin-left: 10px;
    padding: 2px 8px;
  }

}

/* Small Devices (Phones, Landscape Mode) */
@media (min-width: 576px) and (max-width: 767px) {

  /* Styles for phones in landscape mode */
  .notification-dropdown__icon {
    margin-top: -1.5vh;
    font-size: 2.5vh;
    height: 3.5vh;
  }

  .badge-text {
    margin-top: -1.5vh;
    padding: 0.3vh 0.6vh;
  }
}

/* Medium Devices (Tablets, Portrait Mode) */
@media (min-width: 768px) and (max-width: 991px) {
  /* Styles for tablets in portrait mode */
}

/* Large Devices (Tablets, Landscape Mode, Small Laptops) */
@media (min-width: 992px) and (max-width: 1199px) {
  /* Styles for tablets in landscape mode or small laptops */
}

/* Extra Large Devices (Desktops, Small Screens) */
@media (min-width: 1200px) and (max-width: 1399px) {
  /* Styles for desktops with small screens */
}

/* XX-Large Devices (Desktops, Large Screens) */
@media (min-width: 1400px) {
  /* Styles for desktops with large screens */
}
</style>
