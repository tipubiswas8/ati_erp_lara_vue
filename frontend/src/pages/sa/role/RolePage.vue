<script setup lang="ts">
import DataTable from '@/tables/BasicDataTable.vue'
import CreateModalSlotData from '@/pages/sa/role/CreateSlotData.vue'
import EditModalSlotData from '@/pages/sa/role/EditSlotData.vue'
import ViewModalSlotData from '@/pages/sa/role/ViewSlotData.vue'
import { ref, reactive } from 'vue'

// for data table
// ==============
const sendDataToTable = {
  // required
  urls: {
    'data_url': 'security-access/roles',
    'create_url': 'security-access/roles',
    'edit_url': 'security-access/roles',
    'status_url': 'security-access/role-status',
    'delete_url': 'security-access/roles',
    'org_get_url': 'hr/organizations',
  },
  custom_name: 'role_name',
  th_name: {
    '1': 'Sl',
    '2': 'Role Name',
    '3': 'Organization Name',
  },
  last_th_name: 'Action',
  // optional
  th_align: 'left',
  last_th_align: 'center',
  // required
  td_data: {
    '1': 'sl', // not change
    '2': 'role_name',
    '3': 'org_name',
  },
  // optional
  td_align: 'left',
  last_td_align: 'center',
};

// for create
// ================
// required
const isCMOpen = ref(false);
const showCreateModal = () => {
  isCMOpen.value = true;
};

const sendCreateSlotToTable = reactive({
  // required
  createComponent: CreateModalSlotData, // Pass the Vue file as a prop
  // optional
  config: {
    title: 'Add New Role', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

// for edit
const openEditModal = ref(false);
const closeEditModal = () => {
  openEditModal.value = false;
}
const sendEditSlotToTable = {
  editComponent: EditModalSlotData, // Pass the Vue file as a prop
  sendPropDataForEM: {
    config: {
      title: 'Update Role', /* modal title */
      titleBgColor: '#82c953', /* modal title */
      titleTextColor: 'white', /* title text color*/
      // height: 45, /* modal height */
      // width: 60, /* modal width*/
      footer: true, /* modal footer*/
      footerButtonBgColor: 'red', /* modal close button background color*/
    }
  },
  othersData: {
    org_get_url: 'hr/organizations',
  }
};

// for view modal
// ==============
const isVMOpen = ref(false);
const doViewModalOpen = () => {
  isVMOpen.value = true;
}

const sendViewSlotToTable = {
  viewComponent: ViewModalSlotData, // Pass the Vue file as a prop
  config: {
    title: 'Show Role', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
};

// for status
const dataForStatusChange = {
  statusColumnName: 'status',
  statusChangeFor: 'name',
}
</script>

<template>
  <a-button type="primary" style="width: 10vw;" @click="showCreateModal">Add</a-button>
  <DataTable :table-data-one="sendDataToTable" :status-data-one="dataForStatusChange"
    @isEditModalOpen="openEditModal = $event" :is-em-open="openEditModal" @isEditModalClose="closeEditModal"
    :edit-data-one="sendEditSlotToTable" :is-create-modal-open="isCMOpen" :create-data-two="sendCreateSlotToTable"
    @isCreateModalClose="isCMOpen = $event" :is-v-m-open="isVMOpen" @isViewModalOpen="doViewModalOpen"
    :view-data-one="sendViewSlotToTable" @isVMClose="(receivedEmit) => isVMOpen = receivedEmit" />
</template>
