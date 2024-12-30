<script setup lang="ts">
import DataTable from '@src/tables/DataTable.vue'
import EditModalSlotData from '@src/pages/sa/role/EditSlotData.vue'
import CreateModalSlotData from '@src/pages/sa/role/CreateSlotData.vue'
import { ref, reactive } from 'vue'

const sendDataToTable = {
  // required
  urls: {
    'data_url': 'security-access/roles',
    'create_url': 'security-access/roles',
    'view_url': 'security-access/roles',
    'edit_url': 'security-access/roles',
    'status_url': 'security-access/roles',
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

const isVMOpen = ref(false)

const showViewModal = () => {
  isVMOpen.value = true
};

const closeViewModal = () => {
  isVMOpen.value = false
};

const emitDataForView = () => {
  showViewModal();
}

const userInformation = ref({});

const userDataForViewModal = (userData: object) => {
  userInformation.value = {
    ...userData,
    role_get_url: 'security-access/roles'
  }
}

const sendPropDataForVM = reactive({
  config: {
    title: 'User Information', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 52, /* modal height */
    width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

// for status
const dataForStatusChange = {
  statusColumnName: 'status',
  statusChangeFor: 'name',
}
</script>

<template>
  <a-button type="primary" style="width: 10vw;" @click="showCreateModal">Add</a-button>
  <DataTable :request-data="sendDataToTable" :edit-data="sendEditSlotToTable" :is-create-modal-open="isCMOpen"
    :create-data="sendCreateSlotToTable" :status-data="dataForStatusChange" @isCreateModalClose="isCMOpen = $event"
    @isViewModalOpen="emitDataForView" @dataForViewModal="userDataForViewModal" />
</template>
