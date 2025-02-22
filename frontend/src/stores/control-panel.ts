import { defineStore } from 'pinia'
import { ref, watch } from 'vue';

export const useControlPanelStore = defineStore('control', {
  state: () => {
    return {
      controlItems: {
        // header: {
        //   name: 'Show Header',
        //   isEnabled: true,
        // },
        // sidebar: {
        //   name: 'Show Sidebar',
        //   isEnabled: false,
        // },
        footer: {
          name: 'Show Footer',
          isEnabled: true,
          tooltip: 'This feature is disabled for the current theme',
        },
        postingAndCommenting: {
          name: 'Posting and commenting',
          isEnabled: true,
        },
        messaging: {
          name: 'Messaging',
          isEnabled: true,
        },
        groups: {
          name: 'Groups',
          isEnabled: false,
        },
        pages: {
          name: 'Pages',
          isEnabled: true,
        },
        attendingEvents: {
          name: 'Attending events',
          isEnabled: true,
        },
        newsAndReports: {
          name: 'News and reports',
          isEnabled: false,
        },
        updatingYourProfile: {
          name: 'Updating your profile',
          isEnabled: true,
        },
        verifications: {
          name: 'Verifications',
          isEnabled: true,
        },
      },
    }
  },

})

export const useControlPanelSecond = defineStore('control2', {
  state: () => {
    return {
      isShowHeader: true,
      isShowSidebar: true,
      isShowFooter: false,
    }
  },
  actions: {
    toggleHeder() {
      this.isShowHeader = !this.isShowHeader
    },
    toggleSidebar() {
      this.isShowSidebar = !this.isShowSidebar
    },
    toggleFooter() {
      this.isShowFooter = !this.isShowFooter
    },
  },

})


// Reactive global state
export const currentTextDirection = ref<string>(localStorage.getItem('text-direction') || 'ltr');

// Function to update the text direction
export const setTextDirection = (val: string) => {
  currentTextDirection.value = val;
};

// Watch for changes and update localStorage
watch(currentTextDirection, (newValue) => {
  localStorage.setItem('text-direction', newValue.toString());
});

// Reactive global state
export const sidebarCurrentPosition = ref<string>(localStorage.getItem('sidebar-position') || 'left');

// Function to update the sidebar position
export const setSidebar = (val: string) => {
  sidebarCurrentPosition.value = val;
};

// Watch for changes and update localStorage
watch(sidebarCurrentPosition, (newValue) => {
  localStorage.setItem('sidebar-position', newValue.toString());
});


