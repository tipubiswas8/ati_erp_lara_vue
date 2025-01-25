import { defineStore } from 'pinia'

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


