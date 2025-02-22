<template>
  <div class="flex flex-col p-4 bg-backgroundSecondary rounded-lg">
    <h3 class="h3 mb-6">General Settings</h3>
    <hr class="divider" />

    <div class="notification-item" v-if="(currentTheme == 'light' || currentTheme == 'dark')">
      <p class="text-regularLarge">Theme Dark/Light</p>
      <p>Dark</p>
      <input type="checkbox" :checked="(currentTheme == 'light')" class="switch" @click="toggleForSetTheme" />
      <p>Light</p>
    </div>
    <hr class="divider" />

    <div class="notification-item">
      <p class="text-regularLarge">Text Direction LTR/RTL</p>
      <label class="footer-input-and-label" for="left_to_right">Left to Right
        <input type="radio" id="left_to_right" name="text_direction" @click="setTextDirection('ltr')"
          :checked="currentTextDirection === 'ltr'" />
      </label>
      <label class="footer-input-and-label" for="right_to_left">Right to Left
        <input type="radio" id="right_to_left" name="text_direction" @click="setTextDirection('rtl')"
          :checked="currentTextDirection === 'rtl'" />
      </label>
    </div>
    <hr class="divider" />

    <div class="notification-item">
      <p class="text-regularLarge">Collaps</p>
      <input type="checkbox" class="switch" v-model="isSidebarMinimized" :disabled="!isShowSidebar" />
    </div>
    <hr class="divider" />

    <div class="notification-item">
      <p class="text-regularLarge">Show Header</p>
      <input type="checkbox" class="switch" :checked="isShowHeader" @click="isShowHeader = !isShowHeader" />
    </div>
    <hr class="divider" />

    <div class="notification-item">
      <p class="text-regularLarge">Show Sidebar</p>
      <input type="checkbox" class="switch" v-model="isShowSidebar" />
    </div>
    <hr class="divider" />

    <div v-if="isShowSidebar">
      <div class="notification-item">
        <p class="text-regularLarge">Sidebar Position</p>
        <label class="footer-input-and-label" for="sidebar_left">Left
          <input type="radio" id="sidebar_left" name="sidebar_position" @click="setSidebar('left')"
            :checked="sidebarCurrentPosition === 'left'" />
        </label>
        <label class="footer-input-and-label" for="sidebar_right">Right
          <input type="radio" id="sidebar_right" name="sidebar_position" @click="setSidebar('right')"
            :checked="sidebarCurrentPosition === 'right'" />
        </label>
      </div>
      <hr class="divider" />
    </div>

    <div v-for="(item, key) in controlItems" :key="key">
      <div class="notification-item">
        <p class="text-regularLarge">{{ item.name }}</p>
        <div class="tooltip-container">
          <!-- Checkbox with conditional disabling -->
          <input type="checkbox" class="switch" v-model="item.isEnabled"
            :disabled="key === 'footer' && (activeTheme === 'dark' || activeTheme === 'solarized')" />
          <!-- Tooltip for footer only -->
          <div v-if="key === 'footer' && (activeTheme === 'dark' || activeTheme === 'solarized')" class="tooltip">
            {{ item.tooltip }}
          </div>
        </div>
      </div>
      <hr class="divider" />
    </div>

    <div class="notification-item">
      <p class="text-regularLarge">Set Footer</p>
      <label class="footer-input-and-label" for="footer_one">Footer One
        <input type="radio" id="footer_one" name="footer" @click="setFooter(1)" :checked="selectedFooter === 1" />
      </label>
      <label class="footer-input-and-label" for="footer_two">Footer Two
        <input type="radio" id="footer_two" name="footer" @click="setFooter(2)" :checked="selectedFooter === 2" />
      </label>
      <label class="footer-input-and-label" for="footer_three">Footer Three
        <input type="radio" id="footer_three" name="footer" @click="setFooter(3)" :checked="selectedFooter === 3" />
      </label>
      <label class="footer-input-and-label" for="footer_four">Footer Four
        <input type="radio" id="footer_four" name="footer" @click="setFooter(4)" :checked="selectedFooter === 4" />
      </label>
    </div>
    <hr class="divider" />

  </div>
</template>


<script lang="ts" setup>
import { useControlPanelStore, useControlPanelSecond } from '../../stores/control-panel'
import { useGlobalStore } from '../../stores/global-store';
import { storeToRefs } from 'pinia';
import { inject, ref, watch } from 'vue';
import { selectedFooter, setFooter } from '@/stores/footer-store';
import { sidebarCurrentPosition, setSidebar, currentTextDirection, setTextDirection } from '@/stores/control-panel';

const { isSidebarMinimized } = storeToRefs(useGlobalStore());
const { controlItems } = useControlPanelStore();
// Access the store
const controlPanelSecond = useControlPanelSecond();
// Destructure the state and actions using storeToRefs
const { isShowHeader, isShowSidebar } = storeToRefs(controlPanelSecond);
// Define the type for the theme
type Theme = {
  setTheme: (theme: string) => void;
  currentTheme: import('vue').ComputedRef<'light' | 'dark' | 'blue' | 'solarized'>;
};
// Allowed themes
const allowedThemes = ['light', 'dark', 'blue', 'solarized'] as const;
type ThemeType = typeof allowedThemes[number];

// Inject the global theme
const theme = inject<Theme>('theme');
if (!theme) {
  throw new Error('Theme provider is not available!');
}

const { currentTheme } = theme;

// Validate and initialize the active theme
const localStorageTheme = localStorage.getItem('app-theme') as string;
const initialTheme: ThemeType = allowedThemes.includes(localStorageTheme as ThemeType)
  ? (localStorageTheme as ThemeType)
  : 'light';

const activeTheme = ref<ThemeType>(initialTheme);

const toggleForSetTheme = () => {
  if (currentTheme.value === 'light') {
    theme.setTheme('dark');
    activeTheme.value = 'dark'; // Update the active theme reference
    localStorage.setItem('app-theme', 'dark'); // Persist the theme in localStorage
  } else {
    theme.setTheme('light');
    activeTheme.value = 'light'; // Update the active theme reference
    localStorage.setItem('app-theme', 'light'); // Persist the theme in localStorage
  }
};
// Watch for theme changes and handle footer visibility
watch(
  currentTheme,
  (newTheme) => {
    activeTheme.value = newTheme;
    if (activeTheme.value === 'dark' || activeTheme.value === 'solarized') {
      controlItems.footer.isEnabled = false; // Disable footer toggle
      controlItems.footer.tooltip = 'Your theme does not support the footer';
    } else {
      controlItems.footer.isEnabled = true;
      controlItems.footer.tooltip = ''; // Clear tooltip for supported themes
    }
  },
  { immediate: true }
);

</script>

<style scoped>
/* Styling for the container */
.bg-backgroundSecondary {
  background-color: #f8f9fa;
  /* Example background color */
}

.h3 {
  font-size: 1.5rem;
  /* Example heading size */
  font-weight: bold;
}

.text-regularLarge {
  font-size: 1rem;
  /* Example text size */
  color: #333;
  /* Example text color */
  margin-right: auto;
  /* Ensures it aligns to the left */
}

.switch {
  appearance: none;
  width: 2rem;
  height: 1rem;
  background: #ccc;
  border-radius: 1rem;
  position: relative;
  outline: none;
  cursor: pointer;
  transition: background 0.3s ease-in-out;
}

.switch:checked {
  background: #4caf50;
  /* Example active color */
}

.switch:before {
  content: '';
  position: absolute;
  width: 1rem;
  height: 1rem;
  background: white;
  border-radius: 50%;
  top: 0;
  left: 0;
  transform: translate(0, 0);
  transition: transform 0.3s ease-in-out;
}

.switch:checked:before {
  transform: translate(100%, 0);
}

/* Divider */
.divider {
  border-top: 1px solid #e0e0e0;
}

/* Notification item container */
.notification-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  /* Ensures content is evenly distributed */
  overflow: hidden;
  margin-left: 5px;
}

/* Tooltip */
.tooltip-container {
  position: relative;
}

.tooltip {
  position: absolute;
  top: 0;
  right: 100px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.5rem;
  font-size: 0.85rem;
  border-radius: 5px;
  white-space: nowrap;
  z-index: 1000;
  opacity: 0;
  transform: translateY(10px);
  transition: all 0.3s ease-in-out;
  pointer-events: none;
}

.tooltip-container:hover .tooltip {
  opacity: 1;
  transform: translateY(0);
}

button:disabled,
input:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.footer-input-and-label {
  padding: 15px;
}
</style>
